@extends('layouts.app')

@section('content')
<div class="container-fluid py-5 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h1 class="text-center mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Historique des incidents
                    </h1>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('historique.admin') }}" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="type" class="form-label fw-bold">Type d'incident</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Tous les types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label fw-bold">Statut</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="agent" class="form-label fw-bold">Agent</label>
                            <select name="agent" id="agent" class="form-select">
                                <option value="">Tous les agents</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-filter me-2"></i>Appliquer les filtres
                            </button>
                        </div>
                    </form>

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
                                        <th scope="col">Signalé par</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidents as $index => $incident)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration + ($incidents->currentPage() - 1) * $incidents->perPage() }}</th>
                                            <td><span class="badge bg-secondary">{{ strtoupper($incident->type) }}</span></td>
                                            <td>{{ $incident->description }}</td>
                                            <td>
                                                <span class="badge bg-{{ $incident->status == 'résolu' ? 'success' : ($incident->status == 'en cours' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($incident->status) }}
                                                </span>
                                            </td>
                                            <td><i class="far fa-calendar-alt me-1"></i>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                @if($incident->user_id)
                                                    <i class="fas fa-user me-1"></i>{{ $incident->reportedBy->name }}
                                                @else
                                                    <i class="fas fa-user-secret me-1"></i>Visiteur
                                                @endif
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
            </div>
        </div>
    </div>
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
    .badge {
        font-size: 0.9em;
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