<?php

namespace Tests\Feature\Api;

class AccountDeletionTest extends ApiTestCase
{
    public function testDeleteRequestRequiresCorrectPassword()
    {
        $user = $this->makeUser();
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/account/delete-request', ['password' => 'wrong'])
            ->assertStatus(200)
            ->assertJsonPath('status', 0);

        $this->assertNull($user->fresh()->deletion_scheduled_for);
    }

    public function testDeleteRequestSchedulesDeletionInSevenDays()
    {
        $user = $this->makeUser();
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/account/delete-request', ['password' => 'password123'])
            ->assertStatus(200)
            ->assertJsonPath('status', 1);

        $fresh = $user->fresh();
        $this->assertNotNull($fresh->deletion_requested_at);
        $this->assertNotNull($fresh->deletion_scheduled_for);
        $this->assertTrue($fresh->deletion_scheduled_for->gt(now()->addDays(6)));
        $this->assertTrue($fresh->deletion_scheduled_for->lte(now()->addDays(8)));
    }

    public function testCancelClearsScheduledDeletion()
    {
        $user = $this->makeUser();
        $user->forceFill([
            'deletion_requested_at'  => now(),
            'deletion_scheduled_for' => now()->addDays(7),
        ])->save();
        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->deleteJson('/api/account/delete-request')
            ->assertStatus(200)
            ->assertJsonPath('status', 1);

        $fresh = $user->fresh();
        $this->assertNull($fresh->deletion_requested_at);
        $this->assertNull($fresh->deletion_scheduled_for);
    }
}
