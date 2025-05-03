<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationsController extends Controller
{
    public function getNotifications()
    {
        $notifications = Notification::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return NotificationResource::collection($notifications);
    }

    public function sendNotification(Request $request)
    {
        if (!$request->user_id) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $response = Http::post('http://localhost:3000/emit', [
            'event' => 'notification',
            'data' => ['message' => $request->message],
            'userId' => $request->user_id,
        ]);

        if ($response->successful()) {
            Notification::create([
                'message' => $request->message,
                'user_id' => $request->user_id
            ]);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Failed to send notification'], 500);
    }

    public function markAsRead($id)
    {
        Notification::find($id)->update([
            'read' => true
        ]);

        return $this->getNotifications();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->user()->id)->update([
            'read' => true
        ]);
        return $this->getNotifications();
    }

    public function getAuthUserId()
    {
        return auth()->user()->id;
    }
}
