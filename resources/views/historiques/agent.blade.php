@extends('layouts.app')

@section('content')
<div class="container-fluid py-5 bg-light">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h1 class="text-center mb-0">
                        <i class="fas fa-history me-2"></i>Historique des incidents résolus
                    </h1>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('historique.agent') }}" class="row g-3 mb-4 align-items-end">
                        <div class="col-md-8">
                            <label for="filter" class="form-label fw-bold">Filtrer par</label>
                            <select name="filter" id="filter" class="form-select form-select-lg">
                                <option value="agent" {{ request('filter') == 'agent' ? 'selected' : '' }}>Mes incidents résolus</option>
                                <option value="type" {{ request('filter') == 'type' ? 'selected' : '' }}>Tous les incidents</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-filter me-2"></i>Appliquer le filtre
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
                                        <th scope="col">N°</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Signalé par</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidents as $index => $incident)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration + ($incidents->currentPage() - 1) * $incidents->perPage() }}</th>
                                            <td>
                                                {{-- <span class="badge bg-secondary me-2">{{ ucfirst($incident->type) }}</span> --}}
                                                {{ $incident->description }}
                                            </td>
                                            <td>
                                                @if($incident->user_id)
                                                    <i class="fas fa-user me-1"></i>{{ $incident->reportedBy->name }}
                                                @else
                                                    <i class="fas fa-user-secret me-1"></i>Visiteur
                                                @endif
                                            </td>
                                            <td>
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $incident->created_at->format('d-m-Y H:i') }}
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
    .badge.panne-electrique {
        background-color: #f39c12;
    }
    .badge.fuite-deau {
        background-color: #3498db;
    }
    .badge.demande-de-pompiers {
        background-color: #e74c3c;
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