@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Historique des incidents résolus</h1>

        <form method="GET" action="{{ route('historique.agent') }}" class="mb-4">
            <div class="form-group">
                <label for="type">Filtrer par type d'incident</label>
                <select name="type" id="type" class="form-control">
                    <option value="">Tous</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
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
                        <th>Description</th>
                        <th>Signalé par</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidents as $incident)
                        <tr>
                            <td>{{ $incident->description }}</td>
                            @if($incident->user_id )
                            <td>{{ $incident->reportedBy->name }}</td>
                            @else
                            <td>visiteur</td>
                            @endif
                            <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $incidents->links() }}
        @endif
    </div>
@endsection
