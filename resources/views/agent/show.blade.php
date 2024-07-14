{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tableau de bord de l'agent</h1>

        @foreach($incidents as $incident)
            <div class="incident">
                <h2>Incident #{{ $incident->id }}</h2>
                <p>Type: {{ $incident->type }}</p>
                <p>Statut: {{ $incident->status }}</p>
                
                @if(!$incident->agent_id)
                    <form action="{{ route('agent.assign.incidents', $incident) }}" method="POST">
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
    </div>
@endsection --}}

@extends('layouts.app')

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
                    <p>Statut: {{ $incident->status }}</p>

                    @if(!$incident->agent_id)
                        <form action="{{ route('agent.update.status', $incident) }}" method="POST">
                            @csrf
                            <button type="submit">Prendre en charge</button>
                        </form>
                    @elseif($incident->agent_id == Auth::id())
                        <form action="{{ route('agent.assign.incidents', $incident) }}" method="POST">
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
@endsection
