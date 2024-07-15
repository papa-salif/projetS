@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Notifications</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($notifications->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            Aucune notification non lue.
                        </div>
                    @else
                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item">
                                    {{ $notification->data['message'] }}
                                    <span class="badge badge-primary">{{ $notification->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <form action="{{ route('notifications.mark-as-read') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary">Marquer toutes comme lues</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
