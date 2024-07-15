@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Signaler un Incident</h1>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="incident-form" action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Type d'incident :</label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="fuite d'eau">Fuite d'eau</option>
                                    <option value="panne électrique">Panne électrique</option>
                                    <option value="demande de pompiers">Demande de pompiers</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="numero" class="form-label">Numéro :</label>
                                <input type="text" name="numero" id="numero" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ville" class="form-label">Ville :</label>
                                <input type="text" name="ville" id="ville" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="secteur" class="form-label">Secteur :</label>
                                <input type="text" name="secteur" id="secteur" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description :</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="localisation" class="form-label">Localisation :</label>
                            <div class="input-group">
                                <input type="text" name="localisation" id="localisation" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary" id="localisation-btn" onclick="requestLocationPermission()">Obtenir la localisation</button>
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>

                        <div class="mb-3">
                            <label for="preuves" class="form-label">Preuves (images) :</label>
                            <input type="file" name="preuves[]" id="preuves" class="form-control" multiple>
                        </div>

                        <div class="mb-3">
                            @if(Auth::check())
                                <button type="button" class="btn btn-primary" onclick="submitForm('simple')">Signaler</button>
                            @else
                                <button type="button" class="btn btn-primary" onclick="submitForm('simple')">Signaler</button>
                                <button type="button" class="btn btn-secondary" onclick="submitForm('authenticated')">Signaler et Suivi</button>
                            @endif
                        </div>
                    </form>

                    <div id="map" class="mb-4" style="height: 400px;"></div>
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

    function submitForm(action) {
        var form = document.getElementById('incident-form');
        if (action === 'authenticated' && !@json(Auth::check())) {
            window.location.href = "{{ route('login') }}";
        } else {
            form.action = "{{ route('incidents.store') }}?action=" + action;
            form.submit();
        }
    }
</script>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    #map {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

@endpush