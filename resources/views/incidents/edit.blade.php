@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Modifier un Incident
                    </h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('incidents.update', $incident) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type d'incident :</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="fuite d'eau" {{ $incident->type == 'fuite d\'eau' ? 'selected' : '' }}>Fuite d'eau</option>
                                <option value="panne électrique" {{ $incident->type == 'panne électrique' ? 'selected' : '' }}>Panne électrique</option>
                                <option value="demande de pompiers" {{ $incident->type == 'demande de pompiers' ? 'selected' : '' }}>Demande de pompiers</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description :</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required>{{ $incident->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="numero" class="form-label">Numéro :</label>
                            <input type="text" name="numero" id="numero" class="form-control" value="{{ $incident->numero ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="ville" class="form-label">Ville :</label>
                            <input type="text" name="ville" id="ville" class="form-control" value="{{ $incident->ville ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="secteur" class="form-label">Secteur :</label>
                            <input type="text" name="secteur" id="secteur" class="form-control" value="{{ $incident->secteur ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="localisation" class="form-label">Localisation :</label>
                            <div class="input-group">
                                <input type="text" name="localisation" id="localisation" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" id="localisation-btn" onclick="requestLocationPermission()">
                                    <i class="fas fa-map-marker-alt me-1"></i>Obtenir la localisation
                                </button>
                            </div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ $incident->latitude }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ $incident->longitude }}">
                        </div>

                        <div class="mb-3">
                            <label for="preuves" class="form-label">Preuves (images) :</label>
                            <input type="file" name="preuves[]" id="preuves" class="form-control" accept="image/*" multiple>
                        </div>

                        <div id="map" style="height: 300px; width: 100%;" class="mb-3"></div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var map;

    function requestLocationPermission() {
        if (navigator.permissions) {
            navigator.permissions.query({ name: 'geolocation' }).then(function (permissionStatus) {
                if (permissionStatus.state === 'granted') {
                    getLocation();
                } else if (permissionStatus.state === 'prompt') {
                    permissionStatus.onchange = function () {
                        if (permissionStatus.state === 'granted') {
                            getLocation();
                        }
                    };
                    getCurrentPosition();
                } else {
                    console.error("Geolocation permission denied.");
                }
            });
        } else {
            getCurrentPosition();
        }
    }

    function getCurrentPosition() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                getLocation(position.coords.latitude, position.coords.longitude);
            }, function (error) {
                console.error("Error Code = " + error.code + " - " + error.message);
            });
        } else {
            console.error("Geolocation is not supported by this browser.");
        }
    }

    function getLocation(latitude, longitude) {
        if (latitude && longitude) {
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
            document.getElementById('localisation').value = `Latitude: ${latitude}, Longitude: ${longitude}`;

            var incidentLocation = [latitude, longitude];
            if (!map) {
                map = L.map('map').setView(incidentLocation, 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
                }).addTo(map);
            } else {
                map.setView(incidentLocation);
            }
            L.marker(incidentLocation).addTo(map);
        } else {
            console.error("Unable to get location.");
        }
    }

    // Initialize map with incident's location
    document.addEventListener('DOMContentLoaded', function() {
        var lat = {{ $incident->latitude }};
        var lng = {{ $incident->longitude }};
        getLocation(lat, lng);
    });
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endpush