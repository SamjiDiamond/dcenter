<?php

namespace Tests\Feature\Api;

use App\Notifications\UserNotification;

class NotificationTest extends ApiTestCase
{
    public function testIndexReturnsNotificationsAndUnreadCount()
    {
        $user = $this->makeUser();
        $user->notify(new UserNotification('First notification'));
        $user->notify(new UserNotification('Second notification'));

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/notifications')
            ->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonPath('unread_count', 2)
            ->assertJsonCount(2, 'data.data');
    }

    public function testMarkAsReadDecrementsUnreadCount()
    {
        $user = $this->makeUser();
        $user->notify(new UserNotification('First notification'));
        $user->notify(new UserNotification('Second notification'));

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $id = $user->notifications()->first()->id;

        $this->postJson('/api/notifications/' . $id . '/read')
            ->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonPath('unread_count', 1);
    }

    public function testMarkAllAsRead()
    {
        $user = $this->makeUser();
        $user->notify(new UserNotification('First notification'));
        $user->notify(new UserNotification('Second notification'));

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->postJson('/api/notifications/read-all')
            ->assertStatus(200)
            ->assertJsonPath('status', 1)
            ->assertJsonPath('unread_count', 0);
    }

    public function testUnreadCountEndpoint()
    {
        $user = $this->makeUser();
        $user->notify(new UserNotification('First notification'));

        \Laravel\Sanctum\Sanctum::actingAs($user);

        $this->getJson('/api/notifications/unread-count')
            ->assertStatus(200)
            ->assertJsonPath('unread_count', 1);
    }
}
