<!DOCTYPE html>
<html>
<head>
    <title>Messages pour l'Incident {{ $incident->id }}</title>
</head>
<body>
    <h1>Messages pour l'Incident {{ $incident->id }}</h1>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <div>
        @foreach($messages as $message)
            <div>
                <strong>{{ $message->user->name }}:</strong> {{ $message->message }}
            </div>
        @endforeach
    </div>

    <form action="{{ route('messages.store', $incident) }}" method="POST">
        @csrf
        <div>
            <textarea name="message" required></textarea>
        </div>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
