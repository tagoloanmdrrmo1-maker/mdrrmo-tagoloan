<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted');
    }
}