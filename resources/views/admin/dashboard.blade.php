{{-- <!DOCTYPE html>
<html>
<head>
    <title>Tableau de Bord de l'Admin</title>
</head>
<body>
    <h1>Tableau de Bord de l'Admin</h1>

    <div>
        <a href="{{ route('admin.create-agent') }}">Créer un nouvel agent</a>
    </div>

    <!-- Ajoutez d'autres fonctionnalités administratives ici -->
</body>
</html> --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tableau de Bord de l'Admin</h1>

        <div>
            <a class="btn btn-primary" href="{{ route('admin.create-agent') }}">Créer un nouvel agent</a>
        </div>

        <!-- Ajoutez d'autres fonctionnalités administratives ici -->
    </div>
@endsection