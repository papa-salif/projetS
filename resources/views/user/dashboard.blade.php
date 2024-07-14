@extends('layouts.app')

@section('content')
    <h1>user dashboard</h1>
    {{-- @foreach($incidents as $incident)
    <p><strong>Titre</strong>: {{ $incident->titre }}</p>
    <p><strong>Titre</strong>:{{ $incident->description }}</p>
    @endforeach --}}


    <div class="card-body">
        <h2>Incident en cours</h2>
        <table class="table">
            <thead>
                <tr>
                <th>Ordre</th>
            <th>Type</th>
            <th>Description</th>
            {{-- <th>Photo</th> --}}
            <th>Localisation</th>
            {{-- <th>Statut</th> --}}
            <th>Date de Création</th>
            <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incidents as $key => $incident)
                @if ($incident->status == 'pending' || $incident->status == 'in_progress')
                    <tr>
                    <td>{{ $key + 1 }}</td>
                <td>{{ $incident->type }}</td>
                <td>{{ $incident->description }}</td>
                {{-- <td>
                     @if ($incident->photo)
                        <div class="easyzoom easyzoom--overlay">
                            <a href="{{ asset('storage/' . $incident->photo) }}">
                                <img src="{{ asset('storage/' . $incident->photo) }}" alt="Photo du signalement" style="width: 100px; height: auto;">
                            </a>
                         </div>
                    @endif
                </td> --}}
             
            
                <td>
                    <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" target="_blank">
                        Voir la localisation sur Google Maps
                    </a>
                </td>
                {{-- <td>{{ $incident->statut }}</td> --}}
                <td>{{ $incident->created_at }}</td>
                <td>
                    <a href="{{ route('incidents.update', $incident->id) }}" class="btn btn-warning">Suivi</a>
                    <form action="{{ route('incidents.destroy', $incident->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?')">Supprimer</button>
                    </form>
                </td>
                    </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="card-body">
        <h2>Historique</h2>
        <table class="table">
            <thead>
                <tr>
                <th>Ordre</th>
            <th>Type</th>
            <th>Description</th>
            {{-- <th>Photo</th> --}}
            {{-- <th>Localisation</th> --}}
            {{-- <th>Statut</th> --}}
            <th>Date de Création</th>
            {{-- <th>Actions</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($incidents as $key => $incident)
                @if ($incident->status == 'resolved')
                    <tr>
                    <td>{{ $key + 1 }}</td>
                <td>{{ $incident->type }}</td>
                <td>{{ $incident->description }}</td>
                {{-- <td>
                     @if ($incident->photo)
                        <div class="easyzoom easyzoom--overlay">
                            <a href="{{ asset('storage/' . $incident->photo) }}">
                                <img src="{{ asset('storage/' . $incident->photo) }}" alt="Photo du signalement" style="width: 100px; height: auto;">
                            </a>
                         </div>
                    @endif
                </td> --}}
             
            
                {{-- <td>
                    <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" target="_blank">
                        Voir la localisation sur Google Maps
                    </a>
                </td> --}}
                {{-- <td>{{ $incident->statut }}</td> --}}
                <td>{{ $incident->created_at }}</td>
                {{-- <td>
                    <a href="{{ route('incidents.update', $incident->id) }}" class="btn btn-warning">Modifier</a>
                    <form action="{{ route('incidents.destroy', $incident->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?')">Supprimer</button>
                    </form>
                </td> --}}
                    </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>




    <button type="submit" onclick="window.location.href='{{ route('incidents.create') }}'">Signaler</button>
    <!-- Votre contenu spécifique du tableau de bord ici -->
@endsection