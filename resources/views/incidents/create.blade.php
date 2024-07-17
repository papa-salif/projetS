@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Signaler un Incident</h1>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/incident-report.svg') }}" alt="Incident Report" class="img-fluid animated-svg" style="max-width: 200px;">
                    </div>

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
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="type" class="form-label">Type d'incident :</label>
                                <select name="type" id="type" class="form-select animate__animated animate__fadeIn" required>
                                    <option value="fuite d'eau">Fuite d'eau</option>
                                    <option value="panne électrique">Panne électrique</option>
                                    <option value="demande de pompiers">Demande de pompiers</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="numero" class="form-label">Numéro :</label>
                                <input type="text" name="numero" id="numero" class="form-control animate__animated animate__fadeIn" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="ville" class="form-label">Ville :</label>
                                <input type="text" name="ville" id="ville" class="form-control animate__animated animate__fadeIn" required>
                            </div>
                            <div class="col-md-6">
                                <label for="secteur" class="form-label">Secteur :</label>
                                <input type="text" name="secteur" id="secteur" class="form-control animate__animated animate__fadeIn" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description :</label>
                            <textarea name="description" id="description" class="form-control animate__animated animate__fadeIn" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="localisation" class="form-label">Localisation :</label>
                            <div class="input-group">
                                <input type="text" name="localisation" id="localisation" class="form-control animate__animated animate__fadeIn" required>
                                <button type="button" class="btn btn-outline-secondary animate__animated animate__fadeIn" id="localisation-btn" onclick="requestLocationPermission()">Obtenir la localisation</button>
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>

                        <div class="mb-3">
                            <label for="preuves" class="form-label">Preuves (images) :</label>
                            <input type="file" name="preuves[]" id="preuves" class="form-control animate__animated animate__fadeIn" multiple>
                        </div>

                        <div class="mb-3">
                            @if(Auth::check())
                                <button type="button" class="btn btn-primary animate__animated animate__fadeIn" onclick="submitForm('simple')">Signaler</button>
                            @else
                                <button type="button" class="btn btn-primary animate__animated animate__fadeIn" onclick="submitForm('simple')">Signaler</button>
                                <button type="button" class="btn btn-secondary animate__animated animate__fadeIn" onclick="submitForm('authenticated')">Signaler et Suivi</button>
                            @endif
                        </div>
                    </form>

                    <div id="map" class="mb-4 animate__animated animate__fadeIn" style="height: 400px;"></div>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<style>
    #map {
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .animated-svg {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .form-control, .form-select, .btn {
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus, .btn:hover {
        transform: scale(1.02);
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Le reste du script JavaScript reste inchangé
    // ...
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
</script>
@endpush
