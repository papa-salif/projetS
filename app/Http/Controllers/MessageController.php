<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function store(Request $request, Incident $incident)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = new Message();
        $message->incident_id = $incident->id;
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->save();


        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return back()->with('success', 'Message envoyé avec succès.');
    }

    public function index(Incident $incident)
    {
        $messages = Message::where('incident_id', $incident->id)->get();
        return view('messages.index', compact('messages', 'incident'));
    }
}
