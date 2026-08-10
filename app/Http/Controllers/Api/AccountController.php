<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AccountDeletionMail;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    /**
     * Schedule the account for deletion in 7 days (requires the password).
     */
    public function deleteRequest(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 0, 'message' => 'Incorrect password.']);
        }

        if ($user->deletion_scheduled_for) {
            return response()->json([
                'status'                => 0,
                'message'               => 'Your account is already scheduled for deletion.',
                'deletion_scheduled_for' => $user->deletion_scheduled_for,
            ]);
        }

        $user->forceFill([
            'deletion_requested_at'  => now(),
            'deletion_scheduled_for' => now()->addDays(7),
        ])->save();

        // Revoke existing sessions so a scheduled account can't keep using the API.
        $user->tokens()->delete();

        AuditService::log(
            'account.delete_requested',
            'Account deletion requested, scheduled for ' . $user->deletion_scheduled_for->toDateString(),
            'critical'
        );

        Mail::to($user)->send(new AccountDeletionMail($user));

        return response()->json([
            'status'                 => 1,
            'message'                => 'Your account is scheduled for deletion on ' . $user->deletion_scheduled_for->toDateString() . '. You can cancel this anytime within the next 7 days.',
            'deletion_scheduled_for' => $user->deletion_scheduled_for,
        ]);
    }

    /**
     * Cancel a pending account deletion.
     */
    public function cancelDeleteRequest(Request $request)
    {
        $user = $request->user();

        if (! $user->deletion_scheduled_for) {
            return response()->json(['status' => 0, 'message' => 'Your account is not scheduled for deletion.']);
        }

        $user->forceFill([
            'deletion_requested_at'  => null,
            'deletion_scheduled_for' => null,
        ])->save();

        AuditService::log('account.delete_cancelled', 'Account deletion cancelled', 'info');

        return response()->json(['status' => 1, 'message' => 'Account deletion cancelled successfully.']);
    }
}
