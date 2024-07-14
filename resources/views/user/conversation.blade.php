@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Conversation avec l'agent</h1>

        <div class="conversation">
            @foreach($messages as $message)
                <div class="message">
                    <p>{{ $message->content }}</p>
                    <p class="sender">Envoyé par: {{ $message->sender }}</p>
                </div>
            @endforeach
        </div>

        <form action="{{ route('user.send.message') }}" method="POST">
            @csrf
            <textarea name="message" placeholder="Entrez votre message"></textarea>
            <button type="submit">Envoyer</button>

            <a href="{{ route('user.conversation') }}">Commencer la conversation avec l'agent</a>
        </form>
    </div>
@endsection