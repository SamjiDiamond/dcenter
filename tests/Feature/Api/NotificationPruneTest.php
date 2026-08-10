<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationPruneTest extends ApiTestCase
{
    private function seedNotifications($user, int $count, string $prefix = 'Notification')
    {
        $now = now();

        foreach (range(1, $count) as $i) {
            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => UserNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode(['text' => $prefix . ' ' . $i]),
                'read_at'         => null,
                'created_at'      => $now->copy()->addSeconds($i),
                'updated_at'      => $now->copy()->addSeconds($i),
            ]);
        }
    }

    public function testPruneKeepsOnlyTheNewestPerUser()
    {
        $user = $this->makeUser();
        $this->seedNotifications($user, 250);

        $this->artisan('notifications:prune')->assertExitCode(0);

        $this->assertSame(200, $user->notifications()->count());

        // Newest are kept, oldest are gone (keeps #51–#250 of 250).
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'data'          => json_encode(['text' => 'Notification 250']),
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $user->id,
            'data'          => json_encode(['text' => 'Notification 50']),
        ]);
    }

    public function testPruneRespectsCustomKeepCount()
    {
        $user = $this->makeUser();
        $this->seedNotifications($user, 250);

        $this->artisan('notifications:prune', ['--keep' => 100])->assertExitCode(0);

        $this->assertSame(100, $user->notifications()->count());

        // Boundary check: keeps #151–#250 of 250.
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'data'          => json_encode(['text' => 'Notification 151']),
        ]);
        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $user->id,
            'data'          => json_encode(['text' => 'Notification 150']),
        ]);
    }

    public function testPruneIsScopedPerUser()
    {
        $userA = $this->makeUser();
        $userB = $this->makeUser();

        $this->seedNotifications($userA, 250, 'UserA');
        $this->seedNotifications($userB, 20, 'UserB');

        $this->artisan('notifications:prune')->assertExitCode(0);

        // User A was over the cap and got trimmed; user B was under and untouched.
        $this->assertSame(200, $userA->notifications()->count());
        $this->assertSame(20, $userB->notifications()->count());
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $userB->id,
            'data'          => json_encode(['text' => 'UserB 1']),
        ]);
    }

    public function testPruneWithNoNotificationsSucceeds()
    {
        $user = $this->makeUser(); // a user with zero notifications

        $this->artisan('notifications:prune')->assertExitCode(0);

        // The command leaves users that are under the cap untouched. (The table
        // is shared with the live dev DB, so only scope assertions to this user.)
        $this->assertSame(0, $user->notifications()->count());
    }
}
