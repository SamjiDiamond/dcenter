<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuditService
{
    /**
     * Write an audit trail entry for the current request/actor.
     *
     * @param  string  $action       e.g. "two_factor.enabled"
     * @param  string  $description  human-readable summary
     * @param  string  $severity     info|warning|critical
     * @param  string|null  $subjectType
     * @param  int|string|null  $subjectId
     * @param  \App\Models\User|null  $user  explicit actor (used for login audits)
     * @return void
     */
    public static function log($action, $description = '', $severity = 'info', $subjectType = null, $subjectId = null, $user = null)
    {
        try {
            $user = $user ?: auth()->user();

            AuditTrail::create([
                'company_id'   => $user->company_id ?? null,
                'admin_id'     => $user->id ?? null,
                'role'         => $user ? self::roleNameFor($user->id) : null,
                'action'       => $action,
                // "type" is NOT NULL in this schema; fall back to the action slug.
                'type'         => $subjectType ?: $action,
                'description'  => $description,
                'subject_type' => $subjectType,
                'subject_id'   => $subjectId,
                'severity'     => in_array($severity, ['info', 'warning', 'critical'], true) ? $severity : 'info',
                'ip_address'   => request()->ip(),
                'user_agent'   => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Audit logging must never break the main request flow.
        }
    }

    /**
     * Resolve a user's role name via the assigned_roles table (the Bouncer
     * roles() relationship is not usable in this schema).
     */
    public static function roleNameFor($userId)
    {
        return DB::table('assigned_roles')
            ->join('roles', 'roles.id', '=', 'assigned_roles.role_id')
            ->where('assigned_roles.entity_type', User::class)
            ->where('assigned_roles.entity_id', $userId)
            ->value('roles.name');
    }
}
