@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Historique des incidents</h1>

    @auth
        @if(auth()->user()->role == 'user' || auth()->user()->role == 'agent')
            <form method="GET" action="{{ route('historique.index') }}" class="row g-3 mb-4 align-items-end">
                <div class="col-md-4">
                    <label for="type" class="form-label fw-bold">Filtrer par type d'incident</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    @if(auth()->user()->role == 'user')
                        <label for="filter" class="form-label fw-bold">Filtrer par</label>
                        <select name="filter" id="filter" class="form-select">
                            <option value="">Tous les incidents</option>
                            <option value="mine" {{ request('filter') == 'mine' ? 'selected' : '' }}>Mes incidents</option>
                        </select>
                    @elseif(auth()->user()->role == 'agent')
                        <label for="filter" class="form-label fw-bold">Filtrer par</label>
                        <select name="filter" id="filter" class="form-select">
                            <option value="">Tous les incidents</option>
                            <option value="mine" {{ request('filter') == 'mine' ? 'selected' : '' }}>Incidents que j'ai pris en charge</option>
                            <option value="type" {{ request('filter') == 'type' ? 'selected' : '' }}>Incidents de mon type</option>
                        </select>
                    @endif
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        @endif

        @if(auth()->user()->role == 'admin')
            <form method="GET" action="{{ route('historique.index') }}" class="row g-3 mb-4 align-items-end">
                <div class="col-md-3">
                    <label for="type" class="form-label fw-bold">Filtrer par type d'incident</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label fw-bold">Filtrer par statut</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="agent" class="form-label fw-bold">Filtrer par agent</label>
                    <select name="agent" id="agent" class="form-select">
                        <option value="">Tous les agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        @endif
    @endauth

    @if($incidents->isEmpty())
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>Aucun incident trouvé.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Type</th>
                        <th scope="col">Description</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidents as $index => $incident)
                        <tr>
                            <th scope="row">{{ $loop->iteration + ($incidents->currentPage() - 1) * $incidents->perPage() }}</th>
                            <td>{{ strtoupper($incident->type) }}</td>
                            <td>{{ \Illuminate\Support\Str::words($incident->description, 10) }}</td>
                            <td>{{ ucfirst($incident->status) }}</td>
                            <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('incidents.show', $incident) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir plus
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $incidents->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        transition: all 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('tbody tr').hover(
            function() { $(this).addClass('table-active'); },
            function() { $(this).removeClass('table-active'); }
        );
    });
</script>
@endpush
