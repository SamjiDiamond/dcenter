<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InviteTeamMemberRequest;
use App\Models\User;
use App\Services\AuditService;
use App\Services\TeamMemberService;

class TeamMemberController extends Controller
{
    protected $teamMembers;

    public function __construct(TeamMemberService $teamMembers)
    {
        $this->teamMembers = $teamMembers;
    }

    /**
     * List the people on the authenticated user's account.
     */
    public function index()
    {
        $members = User::where('company_id', auth()->user()->company_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($member) {
                return [
                    'id'         => $member->id,
                    'first_name' => $member->first_name,
                    'last_name'  => $member->last_name,
                    'email'      => $member->email,
                    'phoneno'    => $member->phoneno,
                    'role'       => AuditService::roleNameFor($member->id),
                    'created_at' => $member->created_at,
                ];
            });

        return response()->json([
            'status'  => 1,
            'message' => 'Team members fetched successfully',
            'data'    => $members,
            'roles'   => TeamMemberService::ROLES,
        ]);
    }

    /**
     * Invite a person by email with a role (admin|finance|viewer).
     * Only users with the admin role may manage the team.
     */
    public function invite(InviteTeamMemberRequest $request)
    {
        if (! $this->teamMembers->canManage(auth()->id())) {
            return response()->json(['status' => 0, 'message' => 'Only admins can manage team members.']);
        }

        $result = $this->teamMembers->invite($request->email, $request->role, auth()->user()->company_id, [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'phoneno'    => $request->phoneno,
        ]);

        if (! $result['success']) {
            return response()->json(['status' => 0, 'message' => $result['message']]);
        }

        return response()->json(['status' => 1, 'message' => $result['message']]);
    }

    /**
     * Remove a person from the account.
     */
    public function remove($id)
    {
        if (! $this->teamMembers->canManage(auth()->id())) {
            return response()->json(['status' => 0, 'message' => 'Only admins can manage team members.']);
        }

        $result = $this->teamMembers->remove(User::find($id), auth()->id(), auth()->user()->company_id);

        return response()->json([
            'status'  => $result['success'] ? 1 : 0,
            'message' => $result['message'],
        ]);
    }
}
