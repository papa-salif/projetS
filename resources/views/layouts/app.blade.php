{{-- <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), url("/images/incident-report.svg");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: 700;
            color: #3490dc !important;
        }
        .nav-link {
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #3490dc !important;
            transform: translateY(-2px);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
        }
        .dropdown-item {
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #3490dc;
        }
        .btn-custom {
            background-color: #3490dc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #2779bd;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(50,50,93,.11), 0 1px 3px rgba(0,0,0,.08);
        }
        main {
            min-height: calc(100vh - 60px);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm animate__animated animate__fadeIn">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-home"></i> {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('incidents.create') }}"><i class="fas fa-exclamation-triangle"></i> Signaler un incident</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> {{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> {{ __('Register') }}</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('historique.visiteur') }}"><i class="fas fa-history"></i> Historique</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->role == 'admin')
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}</a>
                                    @elseif(Auth::user()->role == 'agent')
                                        <a class="dropdown-item" href="{{ route('agent.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}</a>
                                    @endif

                                    @if(auth()->user()->hasRole('user'))
                                        <a class="dropdown-item" href="{{ route('historique.user') }}"><i class="fas fa-history"></i> Mon historique</a>
                                    @elseif(auth()->user()->hasRole('agent'))
                                        <a class="dropdown-item" href="{{ route('historique.agent') }}"><i class="fas fa-clipboard-list"></i> Historique des incidents</a>
                                    @elseif(auth()->user()->hasRole('admin'))
                                        <a class="dropdown-item" href="{{ route('historique.admin') }}"><i class="fas fa-database"></i> Historique global</a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="bg-light text-center text-lg-start mt-auto">
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2023 Copyright:
                <a class="text-dark" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{ asset('js/rating.js') }}"></script>
</body>
</html> --}}

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-image: linear-gradient(rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.8)), url("/images/incident-report.svg");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: 700;
            color: #3490dc !important;
        }
        .nav-link {
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #3490dc !important;
            transform: translateY(-2px);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,.1);
        }
        .dropdown-item {
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #3490dc;
        }
        .btn-custom {
            background-color: #3490dc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #2779bd;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(50,50,93,.11), 0 1px 3px rgba(0,0,0,.08);
        }
        main {
            min-height: calc(100vh - 60px);
        }
        .dropdown-menu {
            z-index: 1030;
        }
    </style>
</head>
<body>
    
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm animate__animated animate__fadeIn">
            <div class="container">
                <style>
                    .dropdown-menu {
                        z-index: 2000 !important;
                    }
                </style>
                
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-home"></i> {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
        
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('incidents.create') }}"><i class="fas fa-exclamation-triangle"></i> Signaler un incident</a>
                        </li>
                    </ul>
        
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> {{ __('Login') }}</a>
                                </li>
                            @endif
        
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> {{ __('Register') }}</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('historique.index') }}"><i class="fas fa-history"></i> Historique</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="notificationDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-bell"></i>
                                    {{-- <span class="badge badge-danger">{{ Auth::user()->unreadNotifications->count() }}</span> --}}
                                </a>
        
                                <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="notificationDropdown">
                                    {{-- @forelse(Auth::user()->unreadNotifications as $notification)
                                        <a class="dropdown-item" href="#">
                                            {{ $notification->data['message'] }}
                                        </a>
                                    @empty
                                        <a class="dropdown-item text-muted" href="#">Aucune nouvelle notification</a>
                                    @endforelse
                                    @if(Auth::user()->unreadNotifications->count())
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('notifications.mark-as-read') }}" class="dropdown-item text-center">Marquer tout comme lu</a>
                                    @endif --}}
                                </div>
                            </li>
        
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </a>
        
                                <div class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->role == 'admin')
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}</a>
                                    @elseif(Auth::user()->role == 'agent')
                                        <a class="dropdown-item" href="{{ route('agent.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}</a>
                                    @endif
        
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-cog"></i> Modifier le profil</a>
                                    {{-- <a class="dropdown-item" href="{{ route('notifications') }}"><i class="fas fa-bell"></i> Notifications</a> --}}
        
                                    {{-- @if(auth()->user()->hasRole('user'))
                                        <a class="dropdown-item" href="{{ route('historique.user') }}"><i class="fas fa-history"></i> Mon historique</a>
                                    @elseif(auth()->user()->hasRole('agent'))
                                        <a class="dropdown-item" href="{{ route('historique.agent') }}"><i class="fas fa-clipboard-list"></i> Historique des incidents</a>
                                    @elseif(auth()->user()->hasRole('admin'))
                                        <a class="dropdown-item" href="{{ route('historique.admin') }}"><i class="fas fa-database"></i> Historique global</a>
                                    @endif --}}
                                    
                                    <a class="dropdown-item" href="{{ route('historique.index') }}"><i class="fas fa-history"></i> Historique</a>
                                    
        
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>
        
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="bg-light text-center text-lg-start mt-auto">
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                © 2023 Copyright:
                <a class="text-dark" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{ asset('js/rating.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
</body>
</html>
