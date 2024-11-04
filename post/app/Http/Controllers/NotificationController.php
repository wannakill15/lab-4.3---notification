<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index() {
    $notification = Notification::where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->take(20)
        ->get();
        
        return response()->json($notification);
    }

    public function unread() {
        $count = Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->count();

        return response()->json(['count' => $count]);
    }

    public function markAsRead($id) 
    {
        $notification = Notification::findOrFail($id);

        if($notification->user_id !== Auth::id()) 
        {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Marked as Read']);
    }


    public function markAllAsRead()
    {
        $notification = Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}

