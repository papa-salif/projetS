<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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
}

