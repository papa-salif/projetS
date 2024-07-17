{{-- <!-- resources/views/ratings/evaluate.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Évaluation de l'incident #{{ $incident->id }}</h1>
        <form action="{{ route('incidents.submitEvaluation', $incident->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="rating">Évaluation</label>
                <select name="rating" id="rating" class="form-control">
                    <option value="1">1 - Très insatisfait</option>
                    <option value="2">2 - Insatisfait</option>
                    <option value="3">3 - Neutre</option>
                    <option value="4">4 - Satisfait</option>
                    <option value="5">5 - Très satisfait</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comments">Commentaires</label>
                <textarea name="comments" id="comments" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Soumettre</button>
        </form>
    </div>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Détails de l'incident #{{ $incident->id }}
                    </h1>
                </div>
                <div class="card-body">
                    <p><strong>Type:</strong> <span class="badge bg-secondary">{{ $incident->type }}</span></p>
                    <p><strong>Description:</strong> {{ $incident->description }}</p>
                    <p><strong>Statut:</strong> <span class="badge bg-{{ $incident->status == 'pending' ? 'warning' : ($incident->status == 'in_progress' ? 'info' : 'success') }}">{{ $incident->status }}</span></p>
                    <p><strong>Localisation:</strong> <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-map-marker-alt me-1"></i>Voir sur Google Maps
                    </a></p>

                    <h4 class="text-primary mt-4"><i class="fas fa-star me-2"></i>Noter cet incident</h4>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('incidents.submitEvaluation', $incident->id) }}" method="POST">
                        @csrf
                        <div class="rating">
                            <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                            <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                            <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                            <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                            <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                        </div>
                        <div class="form-group">
                            <label for="comments">Commentaires</label>
                            <textarea name="comments" id="comments" rows="3" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Soumettre</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .rating {
        direction: rtl;
        unicode-bidi: bidi-override;
        display: inline-flex;
    }

    .rating input[type="radio"] {
        display: none;
    }

    .rating label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
    }

    .rating input[type="radio"]:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #f5c518;
    }
</style>
@endpush
@endsection
