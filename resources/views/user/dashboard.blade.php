@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Tableau de bord de l'utilisateur</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Incidents en cours</h2>
                @if ($incidents->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        Aucun incident trouvé.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">N°</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Localisation</th>
                                    <th scope="col">Date de Création</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incidents as $index => $incident)
                                    @if ($incident->status == 'pending' || $incident->status == 'in_progress')
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ strtoupper($incident->type) }}</td>
                                            <td>{{ $incident->description }}</td>
                                            <td>
                                                <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" target="_blank" class="btn btn-info btn-sm">
                                                    Voir sur Google Maps
                                                </a>
                                            </td>
                                            <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('incidents.update', $incident->id) }}" class="btn btn-warning btn-sm">Suivi</a>
                                                <form action="{{ route('incidents.destroy', $incident->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce signalement ?')">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('incidents.create') }}'">Signaler un incident</button>
    </div>
@endsection
