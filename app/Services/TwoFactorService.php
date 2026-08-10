<?php

namespace App\Services;

use App\Mail\TwoFactorCodeMail;
use App\Models\TwoFactorCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TwoFactorService
{
    /**
     * Generate, persist (hashed) and email a 6-digit code for the user.
     * Any previously pending codes are invalidated.
     *
     * @return array{code: string, expires_at: \Illuminate\Support\Carbon}
     */
    public static function sendCode(User $user)
    {
        TwoFactorCode::where('user_id', $user->id)->delete();

        $code = (string) random_int(100000, 999999);

        TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user)->send(new TwoFactorCodeMail($user, $code));

        return [
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ];
    }

    /**
     * Verify a submitted code against the user's latest pending code.
     * Marks the code as used when it matches.
     */
    public static function verify(User $user, $code)
    {
        $record = TwoFactorCode::where('user_id', $user->id)
            ->active()
            ->latest()
            ->first();

        if (! $record || ! Hash::check((string) $code, $record->code)) {
            return false;
        }

        $record->update(['used_at' => now()]);

        return true;
    }
}
