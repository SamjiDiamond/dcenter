<?php

namespace Tests\Feature\Api;

use App\Models\TwoFactorCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TwoFactorTest extends ApiTestCase
{
    public function testEnableSendsCodeButDoesNotEnableYet()
    {
        $user = $this->makeUser();
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/two-factor/enable')
            ->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonPath('message', 'Verification code sent to your email.');

        $this->assertDatabaseHas('two_factor_codes', ['user_id' => $user->id, 'used_at' => null]);
        $this->assertFalse((bool) $user->fresh()->email_2fa_enabled);
    }

    public function testConfirmWithWrongCodeFails()
    {
        $user = $this->makeUser();
        TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/two-factor/confirm', ['code' => '000000'])
            ->assertStatus(200)
            ->assertJsonPath('status', 0);

        $this->assertFalse((bool) $user->fresh()->email_2fa_enabled);
    }

    public function testConfirmWithCorrectCodeEnablesTwoFactor()
    {
        $user = $this->makeUser();
        TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/two-factor/confirm', ['code' => '123456'])
            ->assertStatus(200)
            ->assertJsonPath('status', 1);

        $this->assertTrue((bool) $user->fresh()->email_2fa_enabled);
    }

    public function testDisableRequiresCorrectPassword()
    {
        $user = $this->makeUser(['email_2fa_enabled' => true]);
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->deleteJson('/api/two-factor/disable', ['password' => 'wrong-password'])
            ->assertStatus(200)
            ->assertJsonPath('status', 0);

        $this->deleteJson('/api/two-factor/disable', ['password' => 'password123'])
            ->assertStatus(200)
            ->assertJsonPath('status', 1);

        $this->assertFalse((bool) $user->fresh()->email_2fa_enabled);
    }

    public function testVerifyLoginIssuesTokenWhenCodeMatches()
    {
        $user = $this->makeUser(['email_2fa_enabled' => true]);
        TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->postJson('/api/two-factor/verify-login', [
            'username'    => $user->email,
            'code'        => '123456',
            'device_name' => 'test-device',
        ])
            ->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonStructure(['token']);
    }

    public function testVerifyLoginRejectsBadCode()
    {
        $user = $this->makeUser(['email_2fa_enabled' => true]);
        TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->postJson('/api/two-factor/verify-login', [
            'username'    => $user->email,
            'code'        => '999999',
            'device_name' => 'test-device',
        ])
            ->assertStatus(200)
            ->assertJsonPath('status', 0);
    }

    public function testVerifyLoginRejectsDisabledUserEvenWithValidCode()
    {
        $user = $this->makeUser(['email_2fa_enabled' => true, 'status' => 'disable']);
        TwoFactorCode::create([
            'user_id'    => $user->id,
            'code'       => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->postJson('/api/two-factor/verify-login', [
            'username'    => $user->email,
            'code'        => '123456',
            'device_name' => 'test-device',
        ])
            ->assertStatus(200)
            ->assertJsonPath('status', 0)
            ->assertJsonPath('message', 'User disable, kindly contact support');
    }
}
