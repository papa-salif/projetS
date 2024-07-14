@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-4 mb-4">Mon historique d'incidents</h1>

        <form method="GET" action="{{ route('historique.user') }}" class="row g-3 mb-4 align-items-end">
            <div class="col-md-6 col-lg-4">
                <label for="type" class="form-label">Filtrer par type d'incident</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Tous</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
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
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $index => $incident)
                            <tr>
                                <!-- Numérotation continue en tenant compte de la pagination -->
                                <th scope="row">{{ $loop->iteration + ($incidents->currentPage() - 1) * $incidents->perPage() }}</th>
                                <td>
                                    <span >{{ strtoupper($incident->type) }}</span>
                                </td>
                                <td>{{ $incident->description }}</td>
                                <td>{{ $incident->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
           <div class="w-10%">{{ $incidents->links() }}</div> 
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
