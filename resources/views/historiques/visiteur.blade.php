@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Historique des incidents résolus</h1>

        @if($incidents->isEmpty())
            <div class="alert alert-warning" role="alert">
                Aucun incident trouvé.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $index => $incident)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ strtoupper($incident->type) }}</td>
                                <td>{{ $incident->description }}</td>
                                <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
