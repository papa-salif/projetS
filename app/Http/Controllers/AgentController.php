<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use app\Models\User;
use App\Models\Message;

class AgentController extends Controller
{
    
    public function dashboard()
    {
        $agent = Auth::user();
        $incidentType = $this->getIncidentTypeForAgent($agent->agent_type);

        $assignedIncidents = Incident::where('agent_id', $agent->id)
            ->where('status', '!=', 'resolved')
            ->get();
        
        $unassignedIncidents = Incident::whereNull('agent_id')
            ->where('status', 'pending');
        
        if ($incidentType) {
            $unassignedIncidents = $unassignedIncidents->where('type', $incidentType);
        }
        $unassignedIncidents = $unassignedIncidents->get();

        $incidents = $assignedIncidents->concat($unassignedIncidents);

        return view('agent.dashboard', compact('incidents'));
    }

    // public function assignIncident(Request $request, Incident $incidents)
    // {
    //     $agent = Auth::user();
        
    //     if ($incidents->type !== $this->getIncidentTypeForAgent($agent->agent_type)) {
    //         return back()->with('error', 'Vous n\'êtes pas autorisé à prendre en charge ce type d\'incident.');
    //     }

    //     if ($incidents->agent_id) {
    //         return back()->with('error', 'Cet incident est déjà assigné à un agent.');
    //     }

    //     $incidents->agent_id = $agent->id;
    //     $incidents->status = 'in_progress';
    //     $incidents->save();

    //     // Notifier l'utilisateur
    //     //$incidents->user->notify(new IncidentAssigned($incident));

    //     return redirect()->route('agent.incident.details', $incidents->id)->with('success', 'Incident assigné avec succès.');
    // }

    public function assignIncident(Request $request, Incident $incident)
    {
        $agent = Auth::user();

        // Vérifiez si l'incident est déjà assigné
        if ($incident->agent_id) {
            return response()->json(['error' => 'Cet incident est déjà assigné à un agent.'], 403);
        }

        // Assigner l'agent à l'incident et mettre à jour le statut
        $incident->agent_id = $agent->id;
        $incident->status = 'in_progress';
        $incident->save();

        // Notifier l'utilisateur que l'incident a été pris en charge
       // $incident->user->notify(new IncidentStatusChanged($incident));

        return response()->json(['success' => 'Incident assigné avec succès.', 'incident' => $incident]);
    }

public function showIncidentDetails($id)
{
    $incident = Incident::with('user')->findOrFail($id);
    $messages = Message::where('incident_id', $id)->get();

    return view('agent.incident-details', compact('incident', 'messages'));
}


public function updateStatus(Request $request, Incident $incident)
{
    $agent = Auth::user();

    if ($incident->agent_id !== $agent->id) {
        return response()->json(['error' => 'Vous n\'êtes pas autorisé à mettre à jour cet incident.'], 403);
    }

    $incident->status = $request->status;
    $incident->status = 'resolved';
    $incident->save();

    if ($request->status == 'resolved') {
        return response()->json(['success' => 'Statut mis à jour avec succès.', 'redirect' => route('incidents.evaluate', $incident->id)]);
    }

    //return response()->json(['success' => 'Statut mis à jour avec succès.']);
    return redirect()->route('agent.dashboard');
}

//return redirect()->route('agent.dashboard');

    public function show()
    {
        
        return view('agent.show');
    }
    // public function showIncidentDetails(Incident $incidents)
    // {
    //     $user = User::find($incidents->user_id);
    //     $messages = Message::where('incident_id', $incidents->id)->orderBy('created_at', 'asc')->get();

    //     return view('agent.incident-details', compact('incident', 'user', 'messages'));
    // }


    public function sendMessage(Request $request, Incident $incident)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $message = new Message();
    $message->user_id = Auth::id();
    $message->message = $request->message;
    $message->incident_id = $incident->id;

   // dd($message);
    $message->save();

    return redirect()->route('agent.incident.details', $incident->id)->with('success', 'Message envoyé avec succès.');
}






    private function getIncidentTypeForAgent($agentType)
{
    $typeMapping = [
        'agent_electricite' => 'panne électrique',
        'agent_pompier' => 'demande de pompiers',
        'agent_eau' => 'Fuite d\'eau',
        // Ajoutez d'autres correspondances si nécessaire
    ];

    return $typeMapping[$agentType] ?? null;
}
}
//agent_eau', 'agent_electricite', 'agent_pompier
//Fuite d'eau
//panne électrique
//demande de pompiers