{{-- <!DOCTYPE html>
<html>
<head>
    <title>Signaler un Incident</title>
    <script>
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
                        navigator.geolocation.getCurrentPosition(function (position) {
                            getLocation();
                        }, function (error) {
                            console.error("Error Code = " + error.code + " - " + error.message);
                        });
                    } else {
                        console.error("Geolocation permission denied.");
                    }
                });
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    getLocation();
                }, function (error) {
                    console.error("Error Code = " + error.code + " - " + error.message);
                });
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        }

        function getLocation() {
            navigator.geolocation.getCurrentPosition(function (position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('localisation').value = `Latitude: ${lat}, Longitude: ${lng}`;

                var incidentLocation = { lat: lat, lng: lng };
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: incidentLocation
                });
                var marker = new google.maps.Marker({
                    position: incidentLocation,
                    map: map
                });
            }, function (error) {
                console.error("Error Code = " + error.code + " - " + error.message);
            });
        }
    </script>
</head>
<body>
    <h1>Signaler un Incident</h1>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="incident-form" action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="type">Type d'incident :</label>
            <select name="type" id="type" required>
                <option value="fuite d'eau">Fuite d'eau</option>
                <option value="panne électrique">Panne électrique</option>
                <option value="demande de pompiers">Demande de pompiers</option>
            </select>
        </div>

        <div>
            <label for="description">Description :</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <div>
            <label for="localisation">Localisation :</label>
            <input type="text" name="localisation" id="localisation" required >
            <button type="button" id="localisation-btn" onclick="requestLocationPermission()">Obtenir la localisation</button>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
        </div>

        <div>
            <label for="preuves">Preuves (images) :</label>
            <input type="file" name="preuves[]" id="preuves" multiple>
        </div>

        <button type="button" onclick="submitForm('simple')">Signaler</button>
        <button type="button" onclick="submitForm('authenticated')">Signaler et Suivi</button>
    </form>

    <div id="map" style="height: 400px; width: 100%;"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
    <script>
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
</body>
</html> --}}

@extends('layouts.app')

@section('content')
<!--geolocation -->

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
</script>

    <h1>Signaler un Incident</h1>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="incident-form" action="{{ route('incidents.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="type">Type d'incident :</label>
            <select name="type" id="type" required>
                <option value="fuite d'eau">Fuite d'eau</option>
                <option value="panne électrique">Panne électrique</option>
                <option value="demande de pompiers">Demande de pompiers</option>
            </select>
        </div>

        <div>
            <label for="description">Description :</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <div>
            <label for="localisation">Localisation :</label>
            <input type="text" name="localisation" id="localisation" required >
            <button type="button" id="localisation-btn" onclick="requestLocationPermission()">Obtenir la localisation</button>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
        </div>

        <div>
            <label for="preuves">Preuves (images) :</label>
            <input type="file" name="preuves[]" id="preuves" multiple>
        </div>

        @if(Auth::check())
            <button type="button" onclick="submitForm('simple')">Signaler</button>
        @else
            <button type="button" onclick="submitForm('simple')">Signaler</button>
            <button type="button" onclick="submitForm('authenticated')">Signaler et Suivi</button>
        @endif

        {{-- <button type="button" onclick="submitForm('simple')">Signaler</button>
        <button type="button" onclick="submitForm('authenticated')">Signaler et Suivi</button> --}}
    </form>

    <div id="map" style="height: 300px; width: 50%;"></div>

    {{-- <script >  mapImage.src = "https://maps.google.com/?q=" + latitude + "," + longitude; </script> --}}
    <script>
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