<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $notifications = $user->notifications()->latest()->paginate(20)->withQueryString();

        // Mark as read
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        return response()->json(['count' => $user->unreadNotifications()->count()]);
    }
}
