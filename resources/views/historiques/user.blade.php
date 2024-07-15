@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0 text-center">
                        <i class="fas fa-history me-2"></i>Mon historique d'incidents
                    </h1>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('historique.user') }}" class="row g-3 mb-4 align-items-end">
                        <div class="col-md-8">
                            <label for="type" class="form-label fw-bold">Filtrer par type d'incident</label>
                            <select name="type" id="type" class="form-select form-select-lg">
                                <option value="">Tous les types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-filter me-2"></i>Filtrer
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
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidents as $index => $incident)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration + ($incidents->currentPage() - 1) * $incidents->perPage() }}</th>
                                            <td>
                                                <span class="badge bg-{{ $incident->type === 'urgent' ? 'danger' : ($incident->type === 'important' ? 'warning' : 'info') }} text-white">
                                                    {{ strtoupper($incident->type) }}
                                                </span>
                                            </td>
                                            <td>{{ $incident->description }}</td>
                                            <td>
                                                <i class="far fa-calendar-alt me-2"></i>
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
</style>
@endpush

@push('scripts')
<script>
    // Add any JavaScript enhancements here
    $(document).ready(function() {
        // Example: Add a hover effect to table rows
        $('tbody tr').hover(
            function() { $(this).addClass('table-active'); },
            function() { $(this).removeClass('table-active'); }
        );
    });
</script>
@endpush