<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteTeamMemberRequest;
use App\Models\User;
use App\Services\AuditService;
use App\Services\TeamMemberService;
use Illuminate\Support\Facades\DB;

class TeamMemberController extends Controller
{
    protected $teamMembers;

    public function __construct(TeamMemberService $teamMembers)
    {
        $this->middleware('auth');
        $this->teamMembers = $teamMembers;
    }

    /**
     * Dedicated, scalable team page: search by name/email, filter by role,
     * and server-side pagination so the list never clusters as the team grows.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;

        $query = User::where('company_id', $companyId)->orderByDesc('created_at');

        $search = trim((string) request('search'));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $role = request('role');
        if (in_array($role, TeamMemberService::ROLES, true)) {
            $query->whereIn('id', function ($q) use ($role) {
                $q->select('entity_id')
                    ->from('assigned_roles')
                    ->join('roles', 'roles.id', '=', 'assigned_roles.role_id')
                    ->where('assigned_roles.entity_type', User::class)
                    ->where('roles.name', $role);
            });
        }

        $members = $query->paginate(10)->withQueryString();

        // Resolve every visible member's role name in one query (no N+1 lookups).
        // assigned_roles is polymorphic, so scope to users to avoid entity-id collisions.
        $roleMap = DB::table('assigned_roles')
            ->join('roles', 'roles.id', '=', 'assigned_roles.role_id')
            ->where('assigned_roles.entity_type', User::class)
            ->whereIn('entity_id', $members->pluck('id'))
            ->get(['assigned_roles.entity_id', 'roles.name'])
            ->pluck('name', 'entity_id');

        $members->getCollection()->each(function ($member) use ($roleMap) {
            $member->role_name = $roleMap->get($member->id);
            $member->is_self = $member->id === auth()->id();
        });

        // Headcount per role for the filter pills.
        $roleCounts = DB::table('assigned_roles')
            ->join('roles', 'roles.id', '=', 'assigned_roles.role_id')
            ->join('users', 'users.id', '=', 'assigned_roles.entity_id')
            ->where('assigned_roles.entity_type', User::class)
            ->where('users.company_id', $companyId)
            ->groupBy('roles.name')
            ->get(['roles.name', DB::raw('count(distinct users.id) as total')])
            ->pluck('total', 'name');

        $totalMembers = User::where('company_id', $companyId)->count();
        $isAdmin = AuditService::roleNameFor(auth()->id()) === 'admin';

        return view('settings.team', compact('members', 'roleCounts', 'totalMembers', 'isAdmin'));
    }

    /**
     * Invite someone to the team by email with a role.
     */
    public function inviteMember(InviteTeamMemberRequest $request)
    {
        if (! $this->teamMembers->canManage(auth()->id())) {
            return redirect()->back(302, [], route('team.index'))->withToast('Only admins can manage team members.', 'danger');
        }

        $result = $this->teamMembers->invite($request->email, $request->role, auth()->user()->company_id, [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'phoneno'    => $request->phoneno,
        ]);

        if (! $result['success']) {
            return redirect()->back(302, [], route('team.index'))->withToast($result['message'] . '.', 'danger');
        }

        return redirect()->back(302, [], route('team.index'))->withToast($result['message'] . '.');
    }

    /**
     * Remove a member from the team (their account stays, it just loses access).
     */
    public function removeMember(User $user)
    {
        if (! $this->teamMembers->canManage(auth()->id())) {
            return redirect()->back(302, [], route('team.index'))->withToast('Only admins can manage team members.', 'danger');
        }

        $result = $this->teamMembers->remove($user, auth()->id(), auth()->user()->company_id);

        if (! $result['success']) {
            return redirect()->back(302, [], route('team.index'))->withToast($result['message'] . '.', 'danger');
        }

        return redirect()->back(302, [], route('team.index'))->withToast($result['message'] . '.');
    }
}
