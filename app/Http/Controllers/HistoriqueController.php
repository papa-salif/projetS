<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Incident;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;

// class HistoriqueController extends Controller
// {
//     // Historique pour les visiteurs : affiche tous les incidents résolus
//     public function visiteurHistorique()
//     {
//         $incidents = Incident::latest()->get();
//         return view('historiques.visiteur', compact('incidents'));
//     }

//     // Historique pour les utilisateurs authentifiés : filtre par type
//     public function userHistorique(Request $request)
//     {
//         $user = Auth::user();

//         if (!$user) {
//             return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir l\'historique.');
//         }

//         $incidentsQuery = $user->incident()->where('status', 'resolved')->latest();

//         if ($request->has('type')) {
//             $incidentsQuery->where('type', $request->type);
//         }

//         $incidents = $incidentsQuery->paginate(10);
//         $types = Incident::distinct('type')->pluck('type');

//         return view('historiques.user', compact('incidents', 'types'));
//     }

//     // Historique pour les agents : filtre par incidents résolus par l'agent ou par type d'agent
//     public function agentHistorique(Request $request)
//     {
//         $agent = Auth::user();

//         if (!$agent) {
//             return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir l\'historique.');
//         }

//         // Gestion des filtres
//         $incidentsQuery = Incident::where('status', 'resolved');

//         if ($request->filter == 'agent') {
//             $incidentsQuery->where('agent_id', $agent->id);
//         } else if ($request->filter == 'type') {
//             $incidentsQuery->where('type', $agent->agent_type);
//         }

//         $incidents = $incidentsQuery->paginate(10);
//         $types = Incident::distinct('type')->pluck('type');

//         return view('historiques.agent', compact('incidents', 'types'));
//     }

//     // Historique pour les administrateurs : filtre par statut et type
//     public function adminHistorique(Request $request)
//     {
//         $admin = Auth::user();

//         if (!$admin) {
//             return redirect()->route('login')->with('error', 'Vous devez être connecté pour voir l\'historique.');
//         }

//         $incidentsQuery = Incident::latest();

//         if ($request->has('type') && $request->type != '') {
//             $incidentsQuery->where('type', $request->type);
//         }

//         if ($request->has('status') && $request->status != '') {
//             $incidentsQuery->where('status', $request->status);
//         }

//         if ($request->has('agent') && $request->agent != '') {
//             $incidentsQuery->where('agent_id', $request->agent);
//         }

//         $incidents = $incidentsQuery->paginate(10);
//         $types = Incident::distinct('type')->pluck('type');
//         $statuses = Incident::distinct('status')->pluck('status');
//         $agents = User::where('role', 'agent')->get();

//         return view('historiques.admin', compact('incidents', 'types', 'statuses', 'agents'));
//     }
//     public function show(Incident $incident)
// {
//     return view('historiques.show', compact('incident'));
// }
// }

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HistoriqueController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $incidentsQuery = Incident::latest();

        // Filtre pour les visiteurs : incidents résolus
        if (!$user) {
            $incidentsQuery->whereNotIn('status', ['in_progress']);
        } else {
            // Filtre pour les utilisateurs authentifiés
            if ($user->role == 'user') {
                if ($request->has('type') && $request->type != '') {
                    $incidentsQuery->where('type', $request->type);
                }
                if ($request->has('filter') && $request->filter == 'mine') {
                    $incidentsQuery->where('user_id', $user->id);
                }
            }

            // Filtre pour les agents
            if ($user->role == 'agent') {
                if ($request->has('type') && $request->type != '') {
                    $incidentsQuery->where('type', $request->type);
                }
                if ($request->has('filter')) {
                    if ($request->filter == 'mine') {
                        $incidentsQuery->where('agent_id', $user->id);
                    } else if ($request->filter == 'type') {
                        $incidentsQuery->where('type', $user->agent_type);
                    }
                }
            }

            // Filtre pour les administrateurs
            if ($user->role == 'admin') {
                if ($request->has('type') && $request->type != '') {
                    $incidentsQuery->where('type', $request->type);
                }
                if ($request->has('status') && $request->status != '') {
                    $incidentsQuery->where('status', $request->status);
                }
                if ($request->has('agent') && $request->agent != '') {
                    $incidentsQuery->where('agent_id', $request->agent);
                }
            }
        }

        $incidents = $incidentsQuery->paginate(10);
        $types = Incident::distinct('type')->pluck('type');
        $statuses = Incident::distinct('status')->pluck('status');
        $agents = User::where('role', 'agent')->get();

        return view('historiques.index', compact('incidents', 'types', 'statuses', 'agents', 'user'));
    }

    public function show(Incident $incident)
    {
        $incident = Incident::findOrFail($incident->id);

        return view('historiques.show', compact('incident'));
    }
}
