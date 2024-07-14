{{-- <!DOCTYPE html>
<html>
<head>
    <title>Détails de l'Incident</title>
</head>
<body>
    <h1>Détails de l'Incident</h1>

    <div>
        <strong>Type :</strong> {{ $incident->type }}
    </div>
    <div>
        <strong>Description :</strong> {{ $incident->description }}
    </div>
    <div>
        <strong>Localisation :</strong> {{ $incident->localisation }}
    </div>
    <div>
        <strong>Status :</strong> {{ $incident->status }}
    </div>
    <div>
        <strong>Preuves :</strong>
        @if($incident->preuves)
            @foreach($incident->preuves as $preuve)
                <a href="{{ asset('storage/' . $preuve) }}" target="_blank">Voir Preuve</a><br>
            @endforeach
        @else
            Pas de preuves
        @endif
    </div>
    <div>
        <a href="{{ route('incidents.edit', $incident) }}">Modifier</a>
        <form action="{{ route('incidents.destroy', $incident) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Supprimer</button>
        </form>
    </div>
    <div class="message">
        <form method="POST" action="{{ route('message.store') }}">
            <textarea placeholder="ecrire"></textarea>
            <button type="submit">envoye</button>
        </form>
    </div>
    
</body>
</html> --}}

<!-- resources/views/incidents/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails de l'incident</h1>
        <h2>Incident #{{ $incident->id }}</h2>
        <p>Type: {{ $incident->type }}</p>
        <p>Description: {{ $incident->description }}</p>
        <p>Statut: {{ $incident->status }}</p>
        <p>Localisation: <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" target="_blank">
            Voir la localisation sur Google Maps
        </a></p>

        @if($incident->preuves)
            <h3>Preuves</h3>
            <ul>
                {{-- @foreach($incident->preuves as $preuve)
                    <li><a href="{{ asset('storage/' . $preuve->name) }}" target="_blank">Voir Preuve</a></li>
                @endforeach --}}
                
                    <style >
                        img{
                        height: 30%;
                         width: 50%;
                         padding-bottom: 5px;

                        }
                        
                    </style> 
                    @foreach($incident->preuves as $preuve)
                        <img src="{{ asset('storage/' . $preuve) }}" alt="Preuve">
                    @endforeach
                
            </ul>
        @else
            <p>Aucune preuve disponible</p>
        @endif

        <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-primary">Modifier</a>

        <h3>Chatbox</h3>
        <div id="chatbox">
            @foreach($messages as $message)
                <p><strong>{{ $message->user->name }}:</strong> {{ $message->message }}</p>
            @endforeach
        </div>

        <form action="{{ route('message.store', $incident) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>
    
@endsection
