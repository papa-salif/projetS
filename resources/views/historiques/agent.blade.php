@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Historique des incidents résolus</h1>

        <form method="GET" action="{{ route('historique.agent') }}" class="row g-3 mb-4 align-items-end">
            <div class="col-md-6 col-lg-4">
                <label for="filter" class="form-label">Filtrer par</label>
                <select name="filter" id="filter" class="form-select">
                    <option value="agent" {{ request('filter') == 'agent' ? 'selected' : '' }}>Moi</option>
                    <option value="type" {{ request('filter') == 'type' ? 'selected' : '' }}>Tous</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </div>
        </form>

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
                            <th scope="col">Description</th>
                            <th scope="col">Signalé par</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $index => $incident)
                            <tr>
                                <!-- Numérotation continue en tenant compte de la pagination -->
                                <th scope="row">{{ $loop->iteration + ($incidents->currentPage() - 1) * $incidents->perPage() }}</th>
                                <td>{{ $incident->description }}</td>
                                @if($incident->user_id)
                                    <td>{{ $incident->reportedBy->name }}</td>
                                @else
                                    <td>visiteur</td>
                                @endif
                                <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $incidents->links() }}
        @endif
    </div>
@endsection

@section('styles')
    <style>
        .type-badge {
            color: #fff;
        }

        .type-badge.panne-electrique {
            background-color: #f39c12; /* Jaune/orange */
        }

        .type-badge.fuite-deau {
            background-color: #3498db; /* Bleu */
        }

        .type-badge.demande-de-pompiers {
            background-color: #e74c3c; /* Rouge */
        }

        /* Ajoutez d'autres types ici */
    </style>
@endsection
