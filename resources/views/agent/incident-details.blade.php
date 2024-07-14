{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails de l'Incident #{{ $incident->id }}</h1>

        <div class="incident">
            <p><strong>Type:</strong> {{ $incident->type }}</p>
            <p><strong>Description:</strong> {{ $incident->description }}</p>
            <p><strong>Statut:</strong> {{ $incident->status }}</p>
            <p><strong>Localisation:</strong> {{ $incident->localisation }}</p>
            
            @if($incident->preuves)
                <p><strong>Preuves:</strong></p>
                <ul>
                    @foreach($incident->preuves as $preuve)
                        <li><a href="{{ asset('storage/' . $preuve) }}" target="_blank">Voir Preuve</a></li>
                    @endforeach
                </ul>
            @else
                <p>Aucune preuve disponible</p>
            @endif
        </div>

        <h2>Informations de l'utilisateur</h2>
        <div class="user-info">
            <p><strong>Nom:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <h2>Communication avec l'utilisateur</h2>
        <form action="{{ route('agent.sendMessage', $incident->id) }}" method="POST">
            @csrf
            <div>
                <label for="message">Message:</label>
                <textarea name="message" id="message" required></textarea>
            </div>
            <button type="submit">Envoyer le message</button>
        </form>

        <h2>Terminer l'incident</h2>
        <form action="{{ route('agent.update.status', $incident->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="resolved">
            <button type="submit">Terminer l'incident</button>
        </form>

        <h2>Messages</h2>
        <div class="messages">
            @foreach($incident->messages as $message)
                <div class="message">
                    <p><strong>{{ $message->user->name }}:</strong> {{ $message->content }}</p>
                    <p><small>{{ $message->created_at }}</small></p>
                </div>
            @endforeach
        </div>
    </div>
@endsection --}}


<!-- resources/views/agent/incident-details.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails de l'incident</h1>
        <h2>Incident #{{ $incident->id }}</h2>
        <p>Type: {{ $incident->type }}</p>
        <p>Description: {{ $incident->description }}</p>
        <div class='image'>
            <style >
                img{
                height: 30%;
                 width: 50%;
                 padding-bottom: 5px;

                }
            </style> 
            @if($incident->preuves)
            @foreach($incident->preuves as $preuve)
            
                <img src="{{ asset('storage/' . $preuve) }}" alt="Preuve">
            @endforeach
            @else
            <p>il n'y a pas de preuve</p>
            @endif
        </div>
        {{-- <p>Statut: <span id="status">{{ $incident->status }}</span></p> --}}
        <p>Localisation: <a href="https://maps.google.com/?q={{ $incident->latitude }},{{ $incident->longitude }}" target="_blank">
            Voir la localisation sur Google Maps
        </a></p>


        
        <h3>Informations de l'utilisateur</h3>
        @if($incident->reportedBy)
        <p><strong>Nom:</strong> {{ $incident->reportedBy->name }}</p>
        <p><strong>Email:</strong> {{ $incident->reportedBy->email }}</p>
       
        {{-- <p>Nom: {{ $incident->user->name }}</p>
        <p>Email: {{ $incident->user->email }}</p> --}}

        <h3>Chatbox</h3>
        <div id="chatbox">
            @foreach($messages as $message)
                <p><strong>{{ $message->user->name }}:</strong> {{ $message->message }}</p>
            @endforeach
        </div>

        <form action="{{ route('agent.send.message', $incident) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
        @else
        <p>c'est visiteur!!!!</p>
        @endif
        <form action="{{ route('agent.update.status', $incident->id) }}" method="POST">
            @csrf
        <button id="bouton-terminer" class="btn btn-success mt-3" >Terminer la prise en charge</button>
        </form>
        
    </div>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
    var boutonTerminer = document.getElementById('bouton-terminer');

    boutonTerminer.addEventListener('click', function() {
        // Appel AJAX pour terminer l'incident
        // ...

        // Vérifier la réponse JSON
        if (response.success) {
            // Rediriger l'agent vers la page de l'utilisateur qui a signalé l'incident
            var evaluateUserId = "{{ Session::get('evaluate_user_id') }}";
            var evaluateUrl = "{{ route('incidents.evaluate') }}?user_id=" + evaluateUserId;
            window.location.href = evaluateUrl;
                }
            });
        });
    </script> --}}
@endsection
