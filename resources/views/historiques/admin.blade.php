@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Historique des incidents</h1>

        <form method="GET" action="{{ route('historique.admin') }}" class="mb-4">
            <div class="form-group">
                <label for="type">Filtrer par type d'incident</label>
                <select name="type" id="type" class="form-control">
                    <option value="">Tous</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Filtrer par statut</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Tous</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>

        @if($incidents->isEmpty())
            <p>Aucun incident trouvé.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Signalé par</th>
                        <th>Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidents as $incident)
                        <tr>
                            <td>{{ $incident->type }}</td>
                            <td>{{ $incident->description }}</td>
                            <td>{{ $incident->status }}</td>
                            <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $incident->reportedBy->name }}</td>
                            <td>{{ $incident->agent ? $incident->agent->name : 'Non assigné' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $incidents->links() }}
        @endif
    </div>
@endsection
