<?php

namespace Tests\Feature\Api;

use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserAccountTest extends ApiTestCase
{
    public function testUserDetailsIncludeSettingsAndSecurityFlags()
    {
        Settings::query()->delete();
        $settings = new Settings();
        $settings->id = 1;
        $settings->funding_fee = 80.00;
        $settings->save();

        $user = $this->makeUser();
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.two_factor_enabled', false)
            ->assertJsonPath('settings.funding_fee', '80.00');
    }

    public function testUserDetailsExposeDeletionSchedule()
    {
        $user = $this->makeUser();
        $user->forceFill(['deletion_scheduled_for' => now()->addDays(7)])->save();

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/user')
            ->assertStatus(200)
            ->assertJsonPath('data.deletion_scheduled_for', $user->deletion_scheduled_for->toISOString());
    }

    public function testRemovePhotoClearsProfilePhotoPath()
    {
        Storage::fake('public');

        $user = $this->makeUser(['profile_photo_path' => 'user/image/abc.jpg']);
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/remove-photo')
            ->assertStatus(200)
            ->assertJsonPath('status', 1);

        $this->assertNull($user->fresh()->profile_photo_path);
    }

    public function testUpdateUserWhitelistsFieldsAndIgnoresGhostColumns()
    {
        $user = $this->makeUser();
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/updateprofile', [
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'phoneno'    => '08011112222',
            'email'      => 'jane' . uniqid() . '@example.com',
            // Columns that do not exist on users — must be ignored, not crash.
            'address'    => '1 Main St',
            'gender'     => 'female',
        ])->assertStatus(200)
            ->assertJsonPath('status', 1);

        $fresh = $user->fresh();
        $this->assertSame('Jane', $fresh->first_name);
        $this->assertSame('08011112222', $fresh->phoneno);
    }

    public function testUpdateUserCannotEscalatePrivilegesViaMassAssignment()
    {
        $user = $this->makeUser();
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/updateprofile', [
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'phoneno'    => '08011112222',
            'email'      => $user->email,
            'gender'     => 'female',
            'status'     => 'disable',
        ])->assertStatus(200)
            ->assertJsonPath('status', 1);

        // The raw 'status' from the payload must not be persisted.
        $this->assertSame('active', $user->fresh()->status);
    }
}
