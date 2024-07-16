@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Détails de l'incident #{{ $incident->id }}
                    </h1>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="text-primary"><i class="fas fa-info-circle me-2"></i>Informations de l'incident</h4>
                            <p><strong>Type:</strong> <span class="badge bg-secondary">{{ $incident->type }}</span></p>
                            <p><strong>Description:</strong> {{ $incident->description }}</p>
                            <p><strong>Statut:</strong> <span class="badge bg-{{ $incident->status == 'pending' ? 'warning' : ($incident->status == 'in_progress' ? 'info' : 'success') }}">{{ $incident->status }}</span></p>
                            <p><strong>Localisation:</strong> <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-map-marker-alt me-1"></i>Voir sur Google Maps
                            </a></p>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-primary"><i class="fas fa-images me-2"></i>Preuves</h4>
                            <div class="row">
                                @if($incident->preuves)
                                    @foreach($incident->preuves as $preuve)
                                        <div class="col-md-6 mb-3">
                                            <img src="{{ asset('storage/' . $preuve) }}" alt="Preuve" class="img-fluid rounded shadow-sm">
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted"><i class="fas fa-exclamation-circle me-2"></i>Aucune preuve disponible</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour à l'historique</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .img-fluid {
        max-height: 200px;
        object-fit: cover;
    }
</style>
@endpush
