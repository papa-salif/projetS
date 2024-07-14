@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails de l'incident</h1>

        {{-- <p>Description de l'incident : {{ $incident->description }}</p>
        <p>Statut de l'incident : {{ $incident->status }}</p> --}}

        {{-- @if($incident->status == 'En cours')
            <form action="{{ route('user.send.message') }}" method="POST">
                @csrf
                <input type="hidden" name="incident_id" value="{{ $incident->id }}">
                <textarea name="message" placeholder="Entrez votre message de suivi"></textarea>
                <button type="submit">Envoyer un message de suivi</button>
            </form>
        @endif --}}
    </div>
@endsection