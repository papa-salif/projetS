@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Modifier un Agent</h1>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.update-agent', $agent->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom :</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $agent->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email :</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $agent->email) }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mot de passe (laisser vide pour ne pas changer) :</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe :</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="agent_type" class="form-label">Rôle :</label>
                            <select name="agent_type" id="agent_type" class="form-select" required>
                                <option value="agent_eau" {{ $agent->agent_type == 'agent_eau' ? 'selected' : '' }}>Agent Eau</option>
                                <option value="agent_electricite" {{ $agent->agent_type == 'agent_electricite' ? 'selected' : '' }}>Agent Électricité</option>
                                <option value="agent_pompier" {{ $agent->agent_type == 'agent_pompier' ? 'selected' : '' }}>Agent Pompier</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Mettre à jour l'Agent</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
