<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markNotificationAsRead($id){
      auth()->user()->unreadNotifications()->find($id)?->markAsRead();
      return auth()->user()->unreadNotifications()->count();
    }
}
