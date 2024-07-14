<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HistoriqueController extends Controller
{
    // Historique pour les visiteurs : affiche tous les incidents résolus
    public function visiteurHistorique()
    {
        $incidents = Incident::where('status', 'resolved')->latest()->get();
        return view('historiques.visiteur', compact('incidents'));
    }

    // Historique pour les utilisateurs authentifiés : filtre par type
    public function userHistorique(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir l\'historique.');
        }

        $incidentsQuery = $user->incident()->where('status', 'resolved')->latest();

        if ($request->has('type')) {
            $incidentsQuery->where('type', $request->type);
        }

        $incidents = $incidentsQuery->paginate(10);
        $types = Incident::distinct('type')->pluck('type');

        return view('historiques.user', compact('incidents', 'types'));
    }

    // Historique pour les agents : filtre par incidents résolus par l'agent ou par type d'agent
    public function agentHistorique(Request $request)
    {
        $agent = Auth::user();

        if (!$agent) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir l\'historique.');
        }

        $incidentsQuery = Incident::where('status', 'resolved')
            ->where(function ($query) use ($agent) {
                $query->where('agent_id', $agent->id)
                      ->orWhere('type', $agent->agent_type);
            })->latest();

        if ($request->has('type')) {
            $incidentsQuery->where('type', $request->type);
        }

        $incidents = $incidentsQuery->paginate(10);
        $types = Incident::distinct('type')->pluck('type');

        return view('historiques.agent', compact('incidents', 'types'));
    }

    // Historique pour les administrateurs : filtre par statut et type
    public function adminHistorique(Request $request)
    {
        $admin = Auth::user();

        if (!$admin) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir l\'historique.');
        }

        $incidentsQuery = Incident::latest();

        if ($request->has('type')) {
            $incidentsQuery->where('type', $request->type);
        }

        if ($request->has('status')) {
            $incidentsQuery->where('status', $request->status);
        }

        $incidents = $incidentsQuery->paginate(10);
        $types = Incident::distinct('type')->pluck('type');
        $statuses = Incident::distinct('status')->pluck('status');

        return view('historiques.admin', compact('incidents', 'types', 'statuses'));
    }
}
