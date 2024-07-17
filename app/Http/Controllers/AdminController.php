<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\AdminNotification;

class AdminController extends Controller
{
    public function index()
    {
        $agents = User::where('role', 'agent')->get();
        return view('admin.dashboard', compact('agents'));
        //return view('admin.dashboard');
    }

    public function createAgent()
    {
        return view('admin.create-agent');
    }

    public function storeAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'agent_type' => 'required|in:agent_eau,agent_electricite,agent_pompier',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agent',
            'agent_type' => $request->agent_type,

        ]);

        // $validatedData = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|string|min:8',
        //     'agent_type' => 'required|in:water_leak,power_outage,firefighter',
        // ]);

        // $user = User::create([
        //     'name' => $validatedData['name'],
        //     'email' => $validatedData['email'],
        //     'password' => bcrypt($validatedData['password']),
        //     'role' => 'agent',
        //     'agent_type' => $validatedData['agent_type'],
        // ]);

        return redirect()->route('admin.dashboard')->with('success', 'Agent créé avec succès.');
    }

    // Afficher le formulaire d'édition d'un agent
    public function editAgent($id)
    {
        $agent = User::findOrFail($id);
        return view('admin.edit', compact('agent'));
    }

    // Mettre à jour un agent
    public function updateAgent(Request $request, $id)
    {
        $agent = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $agent->id,
            'agent_type' => 'required|string|in:agent_eau,agent_electricite,agent_pompier',
        ]);

        $agent->name = $request->name;
        $agent->email = $request->email;
        $agent->agent_type = $request->agent_type;

        if ($request->password) {
            $request->validate([
                'password' => 'nullable|string|min:8|confirmed',
            ]);
            $agent->password = Hash::make($request->password);
        }

        $agent->save();

        return redirect()->route('admin.dashboard')->with('success', 'Agent mis à jour avec succès.');
    }

    // Supprimer un agent
    public function deleteAgent($id)
    {
        $agent = User::findOrFail($id);
        $agent->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Agent supprimé avec succès.');
    }

    public function notifyUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'link' => 'nullable|url',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->notify(new AdminNotification($request->message, $request->link));

        return back()->with('success', 'Notification envoyée avec succès.');
    }
    public function notify(Request $request){
        $users=User::all();
        return view('notifications.create', ['users' => $users]);
        // return view('notifications.create');
    }
    
}

