<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Le Glorieux')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @livewireStyles

    <style>
        body {
            min-height: 100vh;
            display: flex;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 230px;
            background-color: #0d6efd;
            color: white;
            flex-shrink: 0;
            min-height: 100vh;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.15);
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            position: relative;
            background-color: #f8f9fa;
        }

        /* Filigrane */
        .content::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url("{{ asset('images/logo-glorieux.png') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 520px;
            opacity: 0.22;
            filter: contrast(130%) brightness(75%);
            pointer-events: none;
            z-index: 0;
        }

        .content > * {
            position: relative;
            z-index: 1;
        }

        .card {
            background-color: rgba(255,255,255,0.95);
            box-shadow: 0 8px 18px rgba(0,0,0,0.18);
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- HEADER --}}
<header class="header_section w-100">
    <div class="header_top bg-dark text-white py-2">
        <div class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <i class="fa fa-map-marker"></i>
                <marquee behavior="scroll" direction="left" width="90%">
                    <strong style="color: #0d6efd;">
                        COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 1ère rue après ZOOM SERVICE
                    </strong>
                </marquee>
            </div>

            <div class="d-flex align-items-center gap-4">
                <span><i class="fa fa-phone"></i> +229 0197189324 / +229 0197521637</span>

                @auth
                <ul class="nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white"
                           href="#"
                           id="userDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            {{ Auth::user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a href="{{ route('profil.show', Auth::user()->id) }}" class="dropdown-item">
                                    <i class="bi bi-person-lines-fill"></i> Mon compte
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endauth
            </div>

        </div>
    </div>
</header>

{{-- SIDEBAR --}}
<div class="sidebar d-flex flex-column p-3">
    <h5 class="text-center mb-4">📚 LE CENSORAT – CPEG LE GLORIEUX</h5>

    <ul class="nav nav-pills flex-column gap-1">
        <li>
            <a href="/bords/show" class="nav-link text-white">
                <i class="bi bi-speedometer2 me-2"></i> Classes
            </a>
        </li>
        <li>
            <a href="{{ route('eleves.index') }}" class="nav-link text-white">
                <i class="bi bi-people me-2"></i> Élèves
            </a>
        </li>
        <li>
            <a href="{{ route('notes.index') }}" class="nav-link text-white">
                <i class="bi bi-book me-2"></i> Notes
            </a>
        </li>
        <li>
            <a href="{{ route('paiements.index') }}" class="nav-link text-white">
                <i class="bi bi-wallet2 me-2"></i> Historique des paiements 
            </a>
        </li>
        <li>
            <a href="{{ route('paiements.historique') }}" class="nav-link text-white">
                <i class="bi bi-bar-chart-line me-2"></i> Paiements par | jour | Mois | Année
            </a>
        </li>
    </ul>
</div>

{{-- CONTENU --}}
<div class="content">
    @yield('content')
</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@livewireScripts
@stack('scripts')
@yield('scripts')

</body>
</html>
