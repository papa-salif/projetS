@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Historique des incidents</h1>

        <form method="GET" action="{{ route('historique.admin') }}" class="row g-3 mb-4 align-items-end">
            <div class="col-md-4">
                <label for="type" class="form-label">Filtrer par type d'incident</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Tous</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label">Filtrer par statut</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Tous</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="agent" class="form-label">Filtrer par agent</label>
                <select name="agent" id="agent" class="form-select">
                    <option value="">Tous</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
        </form>

        @if($incidents->isEmpty())
            <div class="alert alert-warning" role="alert">
                Aucun incident trouvé.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Date</th>
                            <th scope="col">Signalé par</th>
                            {{-- <th scope="col">Agent</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $index => $incident)
                            <tr>
                                <th scope="row">{{ $loop->iteration + ($incidents->currentPage() - 1) * $incidents->perPage() }}</th>
                                <td>{{ strtoupper($incident->type) }}</td>
                                <td>{{ $incident->description }}</td>
                                <td>{{ $incident->status }}</td>
                                <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                                @if($incident->user_id)
                                    <td>{{ $incident->reportedBy->name }}</td>
                                @else
                                    <td>visiteur</td>
                                @endif                                
                                {{-- <td>{{ $incident->agent ? $incident->agent->name : 'Non assigné' }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $incidents->links() }}
        @endif
    </div>
@endsection
