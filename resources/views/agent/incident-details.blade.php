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
                            <p><strong>Statut:</strong> <span id="status" class="badge bg-{{ $incident->status == 'pending' ? 'warning' : ($incident->status == 'in_progress' ? 'info' : 'success') }}">{{ $incident->status }}</span></p>
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
                                    <p class="text-muted"><i class="fas fa-exclamation-circle me-2"></i>Il n'y a pas de preuve</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h4 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Informations de l'utilisateur</h4>
                    @if($incident->reportedBy)
                        <div class="card mb-4">
                            <div class="card-body">
                                <p><strong>Nom:</strong> {{ $incident->reportedBy->name }}</p>
                                <p><strong>Email:</strong> {{ $incident->reportedBy->email }}</p>
                                <p><strong>Numero:</strong> {{ $incident->numero}}</p>
                                <p><strong>Ville:</strong> {{ $incident->ville}}</p>
                                <p><strong>Secteur:</strong> {{ $incident->secteur}}</p>
                            </div>
                        </div>

                        <h4 class="text-primary mb-3"><i class="fas fa-comments me-2"></i>Chatbox</h4>
                        <div class="card mb-4">
                            <div id="chatbox" class="">
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($messages as $message)
                                        <div class="mb-2">
                                            <strong>{{ $message->user->name }}:</strong>
                                            <p class="mb-0">{{ $message->message }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <form action="{{ route('agent.send.message', $incident) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="message">Message</label>
                                    <textarea name="message" id="message" rows="3" class="form-control" placeholder="Tapez votre message ici..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer
                                </button>
                            </form>
                        @else
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="alert alert-info"><i class="fas fa-info-circle me-2"></i>C'est un visiteur !</p>
                                <p><strong>Numero:</strong> {{ $incident->numero}}</p>
                                <p><strong>Ville:</strong> {{ $incident->ville}}</p>
                                <p><strong>Secteur:</strong> {{ $incident->secteur}}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <form action="{{ route('agent.update.status', $incident->id) }}" method="POST">
                        @csrf
                        <button id="bouton-terminer" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle me-2"></i>Terminer la prise en charge
                        </button>
                    </form>
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