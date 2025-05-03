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
        $notifications = Notification::where('user_id', auth()->user()->id)->get();
        return NotificationResource::collection($notifications);
    }

    public function sendNotification(Request $request)
    {
        $message = $request->input('message');
        $userId = $request->input('user_id'); // Specify the user ID

        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $response = Http::post('http://localhost:3000/emit', [
            'event' => 'notification',
            'data' => ['message' => $message],
            'userId' => $userId,
        ]);

        if ($response->successful()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Failed to send notification'], 500);
    }

    public function markAsRead($id)
    {
        Notification::find($id)->update([
            'seen' => true
        ]);
    }

    public function markAllAsRead()
    {
        Notification::update([
            'seen' => true
        ]);
    }

    public function getAuthUserId()
    {
        return auth()->user()->id;
    }
}
