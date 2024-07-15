@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
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

                    <!-- Ajoutez d'autres fonctionnalités administratives ici -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
