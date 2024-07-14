<!-- resources/views/incidents/evaluate.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Évaluation de l'incident</h1>
        <form action="{{ route('incidents.evaluate', $incident->id) }}" method="POST">
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
            {{-- <div class="form-group">
                <label for="comments">Commentaires</label>
                <textarea name="comments" id="comments" rows="3" class="form-control"></textarea>
            </div> --}}
            <button type="submit" class="btn btn-primary">Soumettre</button>
        </form>
    </div>
@endsection
