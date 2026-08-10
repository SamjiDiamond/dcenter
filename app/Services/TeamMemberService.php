<?php

namespace App\Services;

use App\Mail\TeamInviteMail;
use App\Models\User;
use App\Notifications\TeamMemberNotification;
use Bouncer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeamMemberService
{
    public const ROLES = ['admin', 'finance', 'viewer'];

    /**
     * Abilities granted to each team role the moment the role exists (idempotent).
     * 'admin' mirrors the ceo role and can do everything; 'finance' covers the
     * money-posting actions; 'viewer' is read-only.
     */
    protected const DEFAULT_ABILITIES = [
        'admin'   => '*',
        'finance' => ['fund_wallet', 'charge_customer', 'post_airtime_transaction', 'recharge_card', 'reversal'],
        'viewer'  => [],
    ];

    /**
     * Get (or create) the Bouncer role for a company. Newly created roles get
     * their default abilities so @can guards work out of the box. Pre-existing
     * roles are never re-granted here (deliberate ability edits are respected);
     * TeamRolesSeeder is the explicit backfill for roles created before the
     * defaults existed.
     */
    public function resolveRole($roleName, $companyId)
    {
        $role = Bouncer::role()->firstOrCreate([
            'name'       => $roleName,
            'company_id' => $companyId,
        ]);

        if ($role->wasRecentlyCreated) {
            $this->ensureDefaultAbilities($role);
        }

        return $role;
    }

    /**
     * Grant the role's default abilities. Safe to call repeatedly (idempotent).
     */
    public function ensureDefaultAbilities($role): void
    {
        $abilities = self::DEFAULT_ABILITIES[$role->name] ?? [];

        if ($abilities === '*') {
            $role->allow()->everything();

            return;
        }

        foreach ($abilities as $ability) {
            $role->allow($ability);
        }
    }

    /**
     * Whether the user may manage the team (admin role).
     */
    public function canManage($userId): bool
    {
        return AuditService::roleNameFor($userId) === 'admin';
    }

    /**
     * Invite a person to the team by email with a role.
     *
     * @return array{success: bool, message: string, user: ?User, new_account: bool}
     */
    public function invite($email, $roleName, $companyId, array $details = []): array
    {
        if (! in_array($roleName, self::ROLES, true)) {
            return ['success' => false, 'message' => 'Invalid role', 'user' => null, 'new_account' => false];
        }

        $role = $this->resolveRole($roleName, $companyId);

        $user = User::where('email', $email)->first();
        $newAccount = false;

        if (! $user) {
            $tempPassword = Str::random(10);

            $user = User::create([
                'first_name'   => $details['first_name'] ?? '' ?: 'New',
                'last_name'    => $details['last_name'] ?? '' ?: 'Member',
                'email'        => $email,
                'password'     => Hash::make($tempPassword),
                'phoneno'      => $details['phoneno'] ?? '' ?: '0' . random_int(1000000000, 9999999999),
                'company_id'   => $companyId,
                'account_type' => 'admin',
            ]);

            $newAccount = true;

            Mail::to($user)->send(new TeamInviteMail($user, $tempPassword));
        } else {
            // Refuse to move an account that already belongs to another company.
            if ((int) $user->company_id !== 0 && (int) $user->company_id !== (int) $companyId) {
                return ['success' => false, 'message' => 'This email already belongs to another account', 'user' => null, 'new_account' => false];
            }

            $user->forceFill(['company_id' => $companyId])->save();
        }

        $user->assign($role->id);

        $user->notify(new TeamMemberNotification($roleName));

        AuditService::log('team.invite', 'Invited ' . $user->email . ' to the team as ' . $roleName, 'warning', 'User', $user->id);

        return [
            'success'     => true,
            'message'     => $newAccount ? 'Team member invited successfully' : 'Team member added to your account successfully',
            'user'        => $user,
            'new_account' => $newAccount,
        ];
    }

    /**
     * Remove a member from the team (their account stays, it just loses access).
     *
     * @return array{success: bool, message: string}
     */
    public function remove(?User $user, $actorId, $actorCompanyId): array
    {
        if (! $user || (int) $user->company_id !== (int) $actorCompanyId) {
            return ['success' => false, 'message' => 'Team member not found'];
        }

        if ((int) $user->id === (int) $actorId) {
            return ['success' => false, 'message' => 'You cannot remove yourself from the team'];
        }

        if (AuditService::roleNameFor($user->id) === 'admin' && $this->adminCount($actorCompanyId) <= 1) {
            return ['success' => false, 'message' => 'You cannot remove the last admin on the account'];
        }

        DB::transaction(function () use ($user) {
            DB::table('assigned_roles')->where('entity_id', $user->id)->delete();
            // company_id is NOT NULL in this schema; 0 is the "detached" sentinel.
            $user->forceFill(['company_id' => 0])->save();
        });

        AuditService::log('team.remove', 'Removed ' . $user->email . ' from the team', 'warning', 'User', $user->id);

        return ['success' => true, 'message' => 'Team member removed successfully'];
    }

    /**
     * Number of admins on a company account.
     */
    public function adminCount($companyId): int
    {
        return DB::table('assigned_roles')
            ->join('roles', 'roles.id', '=', 'assigned_roles.role_id')
            ->join('users', 'users.id', '=', 'assigned_roles.entity_id')
            ->where('assigned_roles.entity_type', User::class)
            ->where('users.company_id', $companyId)
            ->where('roles.name', 'admin')
            ->count();
    }
}
