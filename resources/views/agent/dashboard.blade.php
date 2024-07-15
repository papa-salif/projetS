@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord de l'agent
                    </h1>
                </div>
                <div class="card-body">
                    @if($incidents->isEmpty())
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>Aucun incident à afficher.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Statut</th>
                                        <th>Preuves</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidents as $incident)
                                        <tr>
                                            <td>{{ $incident->id }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $incident->type }}</span>
                                            </td>
                                            <td>{{ Str::limit($incident->description, 50) }}</td>
                                            <td>
                                                <span id="status-{{ $incident->id }}" class="badge bg-{{ $incident->status == 'pending' ? 'warning' : ($incident->status == 'in_progress' ? 'info' : 'success') }}">
                                                    {{ $incident->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($incident->preuves)
                                                    @foreach($incident->preuves as $preuve)
                                                        <a href="{{ asset('storage/' . $preuve) }}" target="_blank" class="btn btn-sm btn-outline-secondary me-1">
                                                            <i class="fas fa-file-alt"></i>
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Aucune preuve</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$incident->agent_id && $incident->status == 'pending')
                                                    <button class="btn btn-primary btn-sm" onclick="assignIncident({{ $incident->id }})">
                                                        <i class="fas fa-hand-pointer me-1"></i>Prendre en charge
                                                    </button>
                                                @elseif($incident->agent_id == Auth::id())
                                                    <a href="{{ route('agent.incident.details', $incident->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye me-1"></i>Voir Détails
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
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

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
    }
</style>
@endpush

@push('scripts')
 
@endpush