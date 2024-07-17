@extends('layouts.app') <!-- Assurez-vous d'avoir un layout de base -->

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Envoyer une notification</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notify-user') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="user_id" class="form-label">Utilisateur</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Sélectionnez un utilisateur</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="link" class="form-label">Lien (optionnel)</label>
                            <input type="url" name="link" id="link" class="form-control">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Envoyer la notification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        font-weight: bold;
    }
    .form-label {
        font-weight: 500;
    }
    .btn-primary {
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endpush