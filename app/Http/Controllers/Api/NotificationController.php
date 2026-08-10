<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Paginated list of the authenticated user's notifications.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Mirror the web page: only the 100 most recent notifications are reachable.
        $recentIds = $user->notifications()->latest()->limit(100)->pluck('id');

        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);

        $notifications = $user->notifications()
            ->whereIn('id', $recentIds)
            ->latest()
            ->paginate($perPage);

        $data = $notifications->through(function ($notification) {
            return [
                'id'               => $notification->id,
                'type'             => $notification->type,
                'data'             => $notification->data,
                'read_at'          => $notification->read_at,
                'created_at'       => $notification->created_at,
                'created_at_human' => $notification->created_at ? $notification->created_at->diffForHumans() : null,
            ];
        });

        return response()->json([
            'status'       => 1,
            'message'      => 'Notifications fetched successfully',
            'data'         => $data,
            'unread_count' => $request->user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $request->user()->unreadNotifications()->find($id)?->markAsRead();

        return response()->json([
            'status'       => 1,
            'message'      => 'Notification marked as read',
            'unread_count' => $request->user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->each->markAsRead();

        return response()->json([
            'status'       => 1,
            'message'      => 'All notifications marked as read',
            'unread_count' => 0,
        ]);
    }

    /**
     * Lightweight unread count for the bell icon.
     */
    public function unreadCount(Request $request)
    {
        return response()->json([
            'status'       => 1,
            'message'      => 'Unread notification count fetched successfully',
            'unread_count' => $request->user()->unreadNotificationsCount(),
        ]);
    }
}
