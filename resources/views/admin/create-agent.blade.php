{{-- <!DOCTYPE html>
<html>
<head>
    <title>Créer un Agent</title>
</head>
<body>
    <h1>Créer un Agent</h1>

    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.store-agent') }}" method="POST">
        @csrf
        <div>
            <label for="name">Nom :</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirmer le mot de passe :</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <div>
            <label for="agent_type">Rôle :</label>
            <select name="agent_type" id="agent_type" required>
                <option value="agent_eau">Agent Eau</option>
                <option value="agent_electricite">Agent Électricité</option>
                <option value="agent_pompier">Agent Pompier</option>
            </select>
        </div>

        <button type="submit">Créer l'Agent</button>
    </form>
</body>
</html> --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer un Agent</h1>

        @if($errors->any())
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.store-agent') }}" method="POST">
            @csrf
            <div>
                <label for="name">Nom :</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            <div>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            </div>

            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div>
                <label for="password_confirmation">Confirmer le mot de passe :</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <div>
                <label for="agent_type">Rôle :</label>
                <select name="agent_type" id="agent_type" required>
                    <option value="agent_eau">Agent Eau</option>
                    <option value="agent_electricite">Agent Électricité</option>
                    <option value="agent_pompier">Agent Pompier</option>
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Créer l'Agent</button>
        </form>
    </div>
@endsection