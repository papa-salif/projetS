<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\IncidentStatusChanged;
use App\Models\Message;
use App\Events\IncidentCompleted;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::where('user_id', Auth::id())->get();
        return view('incidents.index', compact('incidents'));
    }

    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
    {
        if ($request->query('action') == 'authenticated' && !Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'numero' => 'required|string',
            'ville'=> 'required|string',
            'secteur'=> 'required|numeric',
            'preuves' => 'nullable|array',
            'preuves.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $incident = new Incident();
        $incident->user_id = Auth::id();
        $incident->type = $request->type;
        $incident->description = $request->description;
        $incident->localisation = $request->localisation;
        $incident->latitude = $request->latitude;
        $incident->numero = $request->numero;
        $incident->ville = $request->ville;
        $incident->secteur = $request->secteur;
        $incident->longitude = $request->longitude;

        if ($request->hasFile('preuves')) {
            $files = [];
            foreach ($request->file('preuves') as $file) {
                $path = $file->store('preuves', 'public');
                $files[] = $path;
            }
            $incident->preuves = $files;
        }

        $incident->save();

        if ($request->query('action') == 'authenticated' || Auth::check()) {
            return redirect()->route('incidents.show', $incident->id);
        }

        return redirect()->route('home')->with('success', 'Incident signalé avec succès.');
    }

    public function show($id)
    {
        $incident = Incident::findOrFail($id);
        $messages = $incident->messages; // Suppose que vous avez une relation "messages" dans votre modèle d'incident

        return view('incidents.show', compact('incident', 'messages'));
    }
    public function edit(Incident $incident)
    {
        return view('incidents.edit', compact('incident'));
    }

    public function update(Request $request, Incident $incident)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'numero' => 'required|string',
            'ville'=> 'required|string',
            'secteur'=> 'required|numeric',
            'preuves' => 'nullable|array',
            'preuves.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $incident->update($request->except('preuves'));

        if ($request->hasFile('preuves')) {
            $files = [];
            foreach ($request->file('preuves') as $file) {
                $path = $file->store('preuves', 'public');
                $files[] = $path;
            }
            $incident->preuves = array_merge($incident->preuves ?? [], $files);
            $incident->save();
        }

        return redirect()->route('incidents.show', $incident->id)->with('success', 'Incident mis à jour avec succès.');
    }

    
    //c'est le models rating et incident
    public function rate(Request $request, Incident $incident)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $incident->rating = $request->rating;
        $incident->save();

        return back()->with('success', 'Merci pour votre évaluation !');
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();
        return back()->with('success','Incident supprime avec succes');
        //return redirect()->route('incidents.index')->with('success', 'Incident supprimé avec succès.');
    }


    //pour les notifications
    public function updateStatus($newStatus, Incident $incident)
    {
        // $incident->status = $request->status;
        // $incident->save();

        // $incident->user->notify(new IncidentStatusChanged($incident));

        // return back()->with('success', 'Statut mis à jour avec succès.');
        $oldStatus = $incident->status;
    $incident->status = $newStatus;
    $incident->save();

    event(new IncidentCompleted($incident, $oldStatus, $newStatus));

    }

    // app/Http/Controllers/IncidentController.php




}
