<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationsController extends Controller
{
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
}
