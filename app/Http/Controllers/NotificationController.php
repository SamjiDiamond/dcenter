<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markNotificationAsRead($id)
    {
        optional(auth()->user()->unreadNotifications()->find($id))->markAsRead();

        return auth()->user()->unreadNotificationsCount();
    }

    /**
     * Full notification history page (paginated).
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Only the 100 most recent notifications are ever browsable — the bell
        // badge caps at '99+', so anything older is unreachable anyway.
        $recentIds = $user->notifications()->latest()->limit(100)->pluck('id');

        $query = $user->notifications()->whereIn('id', $recentIds)->latest();

        // Optional "unread only" filter (?unread=1).
        if ($request->boolean('unread')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(20)->withQueryString();

        return view('notifications_index', compact('notifications'));
    }

    /**
     * Mark every notification as read (web).
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->each->markAsRead();

        if ($request->ajax()) {
            return response()->json(['status' => 1, 'message' => 'All notifications marked as read.']);
        }

        return redirect()->back()->withToast('All notifications marked as read.');
    }

    /**
     * Return the unread notification count as JSON (for live polling).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countJson()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * Render the notification list partial (for live polling refresh).
     *
     * @return \Illuminate\View\View
     */
    public function feed()
    {
        return view('partials.notifications-feed');
    }
}
