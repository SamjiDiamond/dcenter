<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\TwoFactorCode;
use App\Models\User;
use App\Services\AuditService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TwoFactorController extends Controller
{
    /**
     * Generate and email a verification code (does not enable 2FA yet).
     */
    public function enable(Request $request)
    {
        $user = $request->user();

        if ($user->email_2fa_enabled) {
            return response()->json(['status' => 0, 'message' => 'Two-factor authentication is already enabled.']);
        }

        $result = TwoFactorService::sendCode($user);

        AuditService::log('two_factor.enable_requested', 'Two-factor authentication enable requested, code emailed', 'info');

        return response()->json([
            'status'          => 1,
            'message'         => 'Verification code sent to your email.',
            'code_expires_at' => $result['expires_at']->toISOString(),
        ]);
    }

    /**
     * Verify the emailed code and turn 2FA on.
     */
    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $user = $request->user();

        if (! TwoFactorService::verify($user, $request->code)) {
            return response()->json(['status' => 0, 'message' => 'Invalid or expired code.']);
        }

        $user->forceFill(['email_2fa_enabled' => true])->save();

        AuditService::log('two_factor.enabled', 'Two-factor authentication enabled (email OTP)', 'warning');

        return response()->json(['status' => 1, 'message' => 'Two-factor authentication enabled successfully.']);
    }

    /**
     * Send a fresh code (throttled to one per 60 seconds).
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        $last = TwoFactorCode::where('user_id', $user->id)->latest()->first();

        if ($last && $last->created_at->gt(now()->subSeconds(60))) {
            return response()->json(['status' => 0, 'message' => 'Please wait at least 60 seconds before requesting a new code.']);
        }

        $result = TwoFactorService::sendCode($user);

        return response()->json([
            'status'          => 1,
            'message'         => 'A new verification code has been sent to your email.',
            'code_expires_at' => $result['expires_at']->toISOString(),
        ]);
    }

    /**
     * Disable email OTP (requires the account password).
     */
    public function disable(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 0, 'message' => 'Incorrect password.']);
        }

        TwoFactorCode::where('user_id', $user->id)->delete();
        $user->forceFill(['email_2fa_enabled' => false])->save();

        AuditService::log('two_factor.disabled', 'Two-factor authentication disabled', 'warning');

        return response()->json(['status' => 1, 'message' => 'Two-factor authentication disabled successfully.']);
    }

    /**
     * Complete a login that required 2FA by verifying the emailed code.
     */
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'username'    => 'required',
            'code'        => 'required|digits:6',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->username)
            ->orWhere('phoneno', $request->username)
            ->first();

        if (! $user) {
            return response()->json(['status' => 0, 'message' => 'Incorrect Login Details', 'error' => '']);
        }

        // Mirror the login guard: disabled accounts may not authenticate even
        // with a valid code (a user can be disabled after the code was emailed).
        if ($user->status !== 'active') {
            return response()->json(['status' => 0, 'message' => 'User ' . $user->status . ', kindly contact support']);
        }

        if (! TwoFactorService::verify($user, $request->code)) {
            return response()->json(['status' => 0, 'message' => 'Invalid or expired code.']);
        }

        // Mirror the login guards: scheduled-deletion and invalid-company accounts may not authenticate.
        if ($user->deletion_scheduled_for) {
            return response()->json(['status' => 0, 'message' => 'Your account is scheduled for deletion and can no longer be used. Kindly contact support.']);
        }

        $company = Company::find($user->company_id);
        if (! $company) {
            return response()->json(['status' => 0, 'message' => 'Invalid Company ID. Kindly contact support']);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        AuditService::log('auth.login', 'User logged in (2FA verified)', 'info', null, null, $user);

        return response()->json([
            'status'       => 1,
            'message'      => 'User authenticated successfully',
            'token'        => $token,
            'wallet'       => $user->wallet,
            'first_name'   => $user->first_name,
            'last_name'    => $user->last_name,
            'company'      => $company ? $company->name : null,
            'profile_path' => 'https://ui-avatars.com/api/?name=' . substr($user->first_name, 0, 2) . '&color=7F9CF5&background=EBF4FF',
        ]);
    }
}
