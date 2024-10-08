@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                @if(Auth::check() && Auth::user()->role == 'user' )
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Détails de l'incident #{{ $incident->id }}
                    </h1>
                    <a href="{{ route('incidents.evaluate', ['incident' => $incident->id]) }}" class="btn btn-ms h2">
                        <i class="fas fa-sticky-note me-2"></i>Noter
                    </a>
                </div>
                @else
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Détails de l'incident #{{ $incident->id }}
                    </h1>
            
                </div>
                @endif
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
                    @if(Auth::check() && Auth::user()->role == 'user' && $incident->status !== "resolved" && Auth::user()->id == $incident->user_id)
                    <div class="mb-4">
                        <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                    </div>
                    <h4 class="text-primary mb-3"><i class="fas fa-comments me-2"></i>Chatbox</h4>
                    <div class="card mb-4">
                                                        <label for="message">Message</label>

                        <div id="chatbox" class="">
                            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                @foreach($messages as $message)
                                    <div class="mb-2">
                                        {{-- <strong>{{ $message->user->name }}:</strong> --}}
                                        <p class="mb-0"><strong>{{ $message->user->name }}:  </strong>{{ $message->message }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <form action="{{ route('message.store', $incident) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                {{-- <label for="message">Message</label> --}}
                                <textarea name="message" id="message" rows="3" class="form-control" placeholder="Tapez votre message ici..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer
                            </button>
                        </form>
                        <div>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour au tableau de board</a>                        </div>
                    </div>
                    @else
                    <br>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour à l'historique</a>
                    @endif
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
