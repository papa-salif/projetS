@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Historique des incidents résolus</h1>

        @if($incidents->isEmpty())
            <div class="alert alert-warning" role="alert">
                Aucun incident trouvé.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">N°</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $index => $incident)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <span class="text-uppercase">{{ $incident->type }}</span>
                                </td>
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const badges = document.querySelectorAll('.type-badge');
            badges.forEach(badge => {
                const type = badge.innerText.trim().toLowerCase().replace(/\s+/g, '-');
                badge.classList.add(type);
            });
        });
    </script>
@endsection
