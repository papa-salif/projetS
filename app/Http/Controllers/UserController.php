<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Incident;

class UserController extends Controller
{
    public function showconversation()
    {
        $messages = Message::orderBy('created_at', 'asc')->get();

        return view('user.show', compact('messages'));
    }

    // public function sendMessage(Request $request)
    // {
    //     $user = Auth::user();

    //     $message = new Message();
    //     $message->content = $request->message;
    //     $message->sender = $user->name;
    //     $message->save();

    //     return back()->with('success', 'Message envoyé avec succès.');
    // }

    public function store(Request $request, Incident $incident)
{
    //$user = Auth::user();

    $message = new Message();
    $message->message = $request->message;
    $message->user_id = Auth::id();
    $message->incident_id = $incident->id;
    //dd($message);
    $message->save();

    return back($incident->id)->with('success', 'Message de suivi envoyé avec succès.');
}

public function index(){

    //$incidents = $user->incidents;

    $userId = auth()->user()->id;
    $incidents = Incident::where('user_id', $userId)->orderBy('created_at', 'asc')->get();
        
    return view('user.dashboard', compact('incidents'));
}

}