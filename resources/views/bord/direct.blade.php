<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Le Glorieux')</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @livewireStyles

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* ⛔ plus de scroll global */
        }

        /* HEADER FIXE */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 1030;
        }

        /* SIDEBAR FIXE */
        .sidebar {
            position: fixed;
            top: 60px; /* sous le header */
            left: 0;
            width: 230px;
            height: calc(100vh - 60px);
            background-color: #0d6efd;
            color: white;
            padding: 15px;
            overflow-y: auto;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: rgba(255,255,255,0.15);
        }

        /* CONTENU SCROLLABLE */
        .content {
            position: fixed;
            top: 60px;
            left: 230px;
            right: 0;
            bottom: 0;
            padding: 20px;
            overflow-y: auto; /* ✅ scroll ici */
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
</head>

<body>

{{-- HEADER FIXE --}}
<header class="bg-dark text-white">
    <div class="container-fluid d-flex justify-content-between align-items-center h-100 px-3">

        <div class="d-flex align-items-center gap-2">
            <i class="fa fa-map-marker"></i>
            <marquee behavior="scroll" direction="left" width="400">
                <strong class="text-primary">
                    COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE
                </strong>
            </marquee>
        </div>

        <div class="d-flex align-items-center gap-4">
            <span><i class="fa fa-phone"></i>  +229 0197189324 / +229 0197521637</span>
 <a class="nav-link text-white" href="https://mail.google.com/mail/?view=cm&fs=1&to=complexeleglorieux@gmail.com" target="_blank">
                        <i class="fa fa-envelope"></i>
                        complexeleglorieux@gmail.com
                    </a>
            @auth
            <div class="dropdown">
                <a class="text-white dropdown-toggle"
                   href="#"
                   data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
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
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>
</header>

{{-- SIDEBAR FIXE --}}
<div class="sidebar">
    <h6 class="text-center mb-4">📚 DIRECTION – CPEG LE GLORIEUX</h6>

    <ul class="nav nav-pills flex-column gap-1">
        <li><a href="" class="nav-link text-white">📊 Classes</a></li>
        <li><a href="{{ route('eleves.index') }}" class="nav-link text-white">👨‍🎓 Élèves</a></li>
        <li><a href="{{ route('notes.index') }}" class="nav-link text-white">📘 Notes</a></li>
        <li><a href="{{ route('paiements.index') }}" class="nav-link text-white">💰 Paiements</a></li>
        <li><a href="{{ route('paiements.historique') }}" class="nav-link text-white">📈 Historique</a></li>
         <li><a href="{{ route('register') }}" class="nav-link text-white"> Enregistrement des utilisateurs</a></li>
    </ul>
</div>

{{-- CONTENU SCROLLABLE --}}
<div class="content">
    @yield('content')
</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
@stack('scripts')
</body>
</html>
