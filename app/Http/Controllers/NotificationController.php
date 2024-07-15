<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications;
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return redirect()->route('notifications')->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
