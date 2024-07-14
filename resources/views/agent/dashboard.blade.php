{{-- <!DOCTYPE html>
<html>
<head>
    <title>Tableau de Bord de l'Agent</title>
</head>
<body>
    <h1>Tableau de Bord de l'Agent</h1>

    @if($incidents->isEmpty())
        <p>Aucun incident assigné pour le moment.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Type d'incident</th>
                    <th>Description</th>
                    <th>Localisation</th>
                    <th>Preuves</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incidents as $incident)
                    <tr>
                        <td>{{ $incident->type }}</td>
                        <td>{{ $incident->description }}</td>
                        <td>{{ $incident->localisation }}</td>
                        <td>
                            @if($incident->preuves)
                                @foreach($incident->preuves as $preuve)
                                    <a href="{{ asset('storage/' . $preuve) }}" target="_blank">Voir Preuve</a><br>
                                @endforeach
                            @else
                                Pas de preuves
                            @endif
                        </td>
                        <td>{{ $incident->status }}</td>
                        <td>
                            <form action="{{ route('agent.updateStatus', $incident) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" {{ $incident->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="in_progress" {{ $incident->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="resolved" {{ $incident->status == 'resolved' ? 'selected' : '' }}>Résolu</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html> --}}


{{-- @extends('layouts.app')

@section('content')
    <h1>Tableau de bord de l'agent</h1>

    @foreach($incidents as $incident)
        <div class="incident">
           <!-- <h2>Incident #{{ $incident->id }}</h2> -->
            <p>Type: {{ $incident->type }}</p>
            <p>Description:{{ $incident->description }}</p>
            <p>preuve : {{ $incident-> }}</p>
            <p>Statut: {{ $incident->status }}</p>
            
            @if(!$incident->agent_id)
                <form action="{{ route('agent.assign.incident', $incident) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="window.location.href='{{ route('agent.update.status', ['incident' => $incident]) }}'">Prendre en charge</button>
                </form>
            @elseif($incident->agent_id == Auth::id())
                <form action="{{ route('agent.update.status', $incident) }}" method="POST">
                    @csrf
                    <select name="status">
                        <option value="en cours" {{ $incident->status == 'en cours' ? 'selected' : '' }}>En cours</option>
                        <option value="résolu" {{ $incident->status == 'résolu' ? 'selected' : '' }}>Résolu</option>
                    </select>
                    <button type="submit">Mettre à jour le statut</button>
                </form>
            @endif
        </div>
    @endforeach
@endsection --}}


{{-- pour gere d'autre chose --}}
{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tableau de bord de l'agent</h1>

        @if($incidents->isEmpty())
            <p>Aucun incident à afficher.</p>
        @else
            @foreach($incidents as $incident)
                <div class="incident">
                    <h2>Incident #{{ $incident->id }}</h2>
                    <p>Type: {{ $incident->type }}</p>
                    <p>Description: {{ $incident->description }}</p>
                    <p>Statut: {{ $incident->status }}</p>
                    
                    @if($incident->preuves)
                        <p>Preuves :</p>
                        <ul>
                            @foreach($incident->preuves as $preuve)
                                <li><a href="{{ asset('storage/' . $preuve) }}" target="_blank">Voir Preuve</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>Aucune preuve disponible</p>
                    @endif

                    @if(!$incident->agent_id)
                        <form action="{{ route('agent.assign.incident', $incident) }}" method="POST">
                            @csrf
                            <button type="submit">Prendre en charge</button>
                        </form>
                    @elseif($incident->agent_id == Auth::id())
                        <form action="{{ route('agent.update.status', $incident) }}" method="POST">
                            @csrf
                            <select name="status">
                                <option value="en cours" {{ $incident->status == 'en cours' ? 'selected' : '' }}>En cours</option>
                                <option value="résolu" {{ $incident->status == 'résolu' ? 'selected' : '' }}>Résolu</option>
                            </select>
                            <button type="submit">Mettre à jour le statut</button>
                        </form>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
@endsection --}}

<!-- resources/views/agent/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tableau de bord de l'agent</h1>

        @if($incidents->isEmpty())
            <p>Aucun incident à afficher.</p>
        @else
            @foreach($incidents as $incident)
                <div class="incident" id="incident-{{ $incident->id }}">
                    <h2>Incident #{{ $incident->id }}</h2>
                    <p>Type: {{ $incident->type }}</p>
                    <p>Description: {{ $incident->description }}</p>
                    <p>Statut: <span id="status-{{ $incident->id }}">{{ $incident->status }}</span></p>
                    
                    @if($incident->preuves)
                        <p>Preuves :</p>
                        <ul>
                            @foreach($incident->preuves as $preuve)
                                <li><a href="{{ asset('storage/' . $preuve) }}" target="_blank">Voir Preuve</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>Aucune preuve disponible</p>
                    @endif

                    @if(!$incident->agent_id && $incident->status == 'pending')
                        <button class="btn btn-primary" onclick="assignIncident({{ $incident->id }})">Prendre en charge</button>
                    @elseif($incident->agent_id == Auth::id())
                        <a href="{{ route('agent.incident.details', $incident->id) }}"><button class="btn btn-primary">Voir Détails</button></a>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <script>
        function assignIncident(incidentId) {
            fetch(`/agent/incidents/${incidentId}/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`status-${incidentId}`).textContent = 'in_progress';
                    location.href = `/agent/incidents/${incidentId}/details`;
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
