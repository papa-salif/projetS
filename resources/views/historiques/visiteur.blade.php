@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Historique des incidents résolus</h1>

        @if($incidents->isEmpty())
            <p>Aucun incident trouvé.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidents as $incident)
                        <tr>
                            <td>{{ $incident->type }}</td>
                            <td>{{ $incident->description }}</td>
                            <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
