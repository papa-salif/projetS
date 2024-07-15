@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Tableau de Bord de l'Admin</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <a class="btn btn-primary" href="{{ route('admin.create-agent') }}">Créer un nouvel agent</a>
                    </div>

                    <h2 class="h4 mb-4">Liste des Agents</h2>
                    @if($agents->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Aucun agent trouvé.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Rôle</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($agents as $index => $agent)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $agent->name }}</td>
                                            <td>{{ $agent->email }}</td>
                                            <td>{{ $agent->agent_type }}</td>
                                            <td>
                                                <!-- Ajouter les actions de modification et de suppression -->
                                                <a href="{{ route('admin.edit-agent', $agent->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                                                <form action="{{ route('admin.delete-agent', $agent->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Ajoutez d'autres fonctionnalités administratives ici -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
