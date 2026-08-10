<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UploadPhotoRequest;
use App\Mail\AccountDeletionMail;
use App\Models\TwoFactorCode;
use App\Models\User;
use App\Services\AuditService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AccountSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the account settings page (profile photo, security, team link).
     */
    public function index()
    {
        $user = auth()->user();

        $twoFactorEnabled = (bool) ($user->email_2fa_enabled || ! is_null($user->two_factor_secret));
        $pendingCode = TwoFactorCode::where('user_id', $user->id)->active()->latest()->first();

        // Team management lives on its own scalable page; the tab here just links out.
        $memberCount = User::where('company_id', $user->company_id)->count();

        return view('settings.account', compact('user', 'twoFactorEnabled', 'pendingCode', 'memberCount'));
    }

    /* ------------------------------------------------------------------ */
    /* Two-factor authentication (email OTP)                              */
    /* ------------------------------------------------------------------ */

    public function enableTwoFactor(Request $request)
    {
        $user = auth()->user();

        if ($user->email_2fa_enabled) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 0,
                    'message' => 'Two-factor authentication is already enabled.',
                    'toast'   => ['message' => 'Two-factor authentication is already enabled.', 'type' => 'danger'],
                ]);
            }

            return redirect()->route('account.settings.index')->withToast('Two-factor authentication is already enabled.', 'danger');
        }

        $result = TwoFactorService::sendCode($user);
        AuditService::log('two_factor.enable_requested', 'Two-factor authentication enable requested, code emailed', 'info');

        if ($request->ajax()) {
            return response()->json([
                'status'     => 1,
                'message'    => 'A verification code has been sent to your email.',
                'expires_at' => $result['expires_at']->format('g:i A'),
                'toast'      => ['message' => 'A verification code has been sent to your email.', 'type' => 'success'],
            ]);
        }

        return redirect()->route('account.settings.index')->withToast('A verification code has been sent to your email.');
    }

    public function confirmTwoFactor(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $user = auth()->user();

        if (! TwoFactorService::verify($user, $request->code)) {
            return redirect()->route('account.settings.index')->withToast('Invalid or expired code.', 'danger');
        }

        $user->forceFill(['email_2fa_enabled' => true])->save();

        AuditService::log('two_factor.enabled', 'Two-factor authentication enabled (email OTP)', 'warning');

        return redirect()->route('account.settings.index')->withToast('Two-factor authentication enabled successfully.');
    }

    public function resendTwoFactor(Request $request)
    {
        $user = auth()->user();

        $last = TwoFactorCode::where('user_id', $user->id)->active()->latest()->first();

        if ($last && $last->created_at->gt(now()->subSeconds(60))) {
            $message = 'Please wait at least 60 seconds before requesting a new code.';

            if ($request->ajax()) {
                return response()->json([
                    'status'    => 0,
                    'message'   => $message,
                    'throttled' => true,
                    'toast'     => ['message' => $message, 'type' => 'danger'],
                ]);
            }

            return redirect()->route('account.settings.index')->withToast($message, 'danger');
        }

        $result = TwoFactorService::sendCode($user);

        if ($request->ajax()) {
            return response()->json([
                'status'     => 1,
                'message'    => 'A new verification code has been sent to your email.',
                'expires_at' => $result['expires_at']->format('g:i A'),
                'toast'      => ['message' => 'A new verification code has been sent to your email.', 'type' => 'success'],
            ]);
        }

        return redirect()->route('account.settings.index')->withToast('A new verification code has been sent to your email.');
    }

    public function disableTwoFactor(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = auth()->user();

        if (! Hash::check($request->password, $user->password)) {
            return redirect()->route('account.settings.index')->withToast('Incorrect password.', 'danger');
        }

        TwoFactorCode::where('user_id', $user->id)->delete();
        $user->forceFill(['email_2fa_enabled' => false])->save();

        AuditService::log('two_factor.disabled', 'Two-factor authentication disabled', 'warning');

        return redirect()->route('account.settings.index')->withToast('Two-factor authentication disabled successfully.');
    }

    /* ------------------------------------------------------------------ */
    /* Profile photo                                                       */
    /* ------------------------------------------------------------------ */

    public function uploadPhoto(UploadPhotoRequest $request)
    {
        $user = auth()->user();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('users', $fileName, 'public');

            // Remove the previous photo file (if any) to avoid orphaned files.
            if ($user->profile_photo_path) {
                $oldFile = basename($user->profile_photo_path);

                if (Storage::disk('public')->exists('users/' . $oldFile)) {
                    Storage::disk('public')->delete('users/' . $oldFile);
                }
            }

            $user->forceFill(['profile_photo_path' => 'user/image/' . $fileName])->save();

            AuditService::log('profile.photo_uploaded', 'Profile photo uploaded', 'info', 'User', $user->id);

            return redirect()->route('account.settings.index')->withToast('Profile photo uploaded successfully.');
        }

        return redirect()->route('account.settings.index')->withToast('No image was uploaded.', 'danger');
    }

    public function removePhoto()
    {
        $user = auth()->user();

        if ($user->profile_photo_path) {
            $file = basename($user->profile_photo_path);

            if (Storage::disk('public')->exists('users/' . $file)) {
                Storage::disk('public')->delete('users/' . $file);
            }

            $user->forceFill(['profile_photo_path' => null])->save();

            AuditService::log('profile.photo_removed', 'Profile photo removed', 'info', 'User', $user->id);
        }

        return redirect()->route('account.settings.index')->withToast('Profile photo removed successfully.');
    }

    /* ------------------------------------------------------------------ */
    /* Account deletion (scheduled)                                        */
    /* ------------------------------------------------------------------ */

    public function deleteRequest(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = auth()->user();

        if (! Hash::check($request->password, $user->password)) {
            return redirect()->route('account.settings.index')->withToast('Incorrect password.', 'danger');
        }

        if ($user->deletion_scheduled_for) {
            return redirect()->route('account.settings.index')->withToast('Your account is already scheduled for deletion.', 'danger');
        }

        $user->forceFill([
            'deletion_requested_at'  => now(),
            'deletion_scheduled_for' => now()->addDays(7),
        ])->save();

        // Revoke API tokens; the web session stays so the user can cancel.
        $user->tokens()->delete();

        AuditService::log(
            'account.delete_requested',
            'Account deletion requested, scheduled for ' . $user->deletion_scheduled_for->toDateString(),
            'critical'
        );

        Mail::to($user)->send(new AccountDeletionMail($user));

        return redirect()->route('account.settings.index')->withToast('Your account is scheduled for deletion on ' . $user->deletion_scheduled_for->toDateString() . '. You can cancel this anytime within the next 7 days.');
    }

    public function cancelDeleteRequest()
    {
        $user = auth()->user();

        if (! $user->deletion_scheduled_for) {
            return redirect()->route('account.settings.index')->withToast('Your account is not scheduled for deletion.', 'danger');
        }

        $user->forceFill([
            'deletion_requested_at'  => null,
            'deletion_scheduled_for' => null,
        ])->save();

        AuditService::log('account.delete_cancelled', 'Account deletion cancelled', 'info');

        return redirect()->route('account.settings.index')->withToast('Account deletion cancelled successfully.');
    }

    /* ------------------------------------------------------------------ */
    /* Profile information                                                 */
    /* ------------------------------------------------------------------ */

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        $validated = $request->validated();

        // Note: the email is deliberately kept verified on change. Every admin route
        // sits behind the 'verified' middleware and this app has no verification.verify
        // route, so invalidating the verification would lock the user out with no
        // way to re-verify.
        $user->forceFill([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'phoneno'    => $validated['phoneno'],
        ])->save();

        AuditService::log('profile.updated', 'Profile information updated', 'info', 'User', $user->id);

        return redirect()->route('account.settings.index')->withToast('Profile information updated successfully.');
    }

}
