<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Le Glorieux')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        }
        .bg-gradient {
            background: linear-gradient(90deg, #0d6efd, #20c997);
            color: white;
        }
        .text-bleu { color: blue !important; }
        <style>
    body {
        min-height: 100vh;
        display: flex;
        background-color: #f8f9fa;
    }

    .content {
        flex-grow: 1;
        padding: 20px;
        position: relative;
        overflow: hidden;
        background-color: #f8f9fa;
    }

    /* 🎨 FILIGRANE COULEUR RÉELLE, FONCÉE */
    .content::before {
        content: "";
        position: fixed;
        inset: 0;

        background-image: url("{{ asset('images/logo-glorieux.png') }}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 520px;

        /* 🔥 réglages clés */
        opacity: 0.22;            /* visibilité forte */
        filter: contrast(130%)    /* couleurs plus marquées */
                brightness(75%);  /* assombrit sans dénaturer */

        pointer-events: none;
        z-index: 0;
    }

    /* Tout le contenu au-dessus */
    .content > * {
        position: relative;
        z-index: 1;
    }

    /* Cartes lisibles malgré le fond */
    .card {
        background-color: rgba(255, 255, 255, 0.95);
        box-shadow: 0 8px 18px rgba(0,0,0,0.18);
    }
</style>

    </style>

    @stack('styles')
</head>

<body class="margin-top">

{{-- HEADER avec contact --}}
<header class="header_section">
    <div class="header_top bg-dark text-white py-2">
        <div class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center">
            <a href="#" class="text-white">
                <i class="fa fa-map-marker"></i>
                <marquee behavior="scroll" direction="left" width="90%">
                    <h2 style="color: blue;">
                        COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 1ère rue après ZOOM SERVICE
                    </h2>
                </marquee>
            </a>
            <a class="nav-link text-white" href="#" target="_blank">
                <i class="fa fa-phone"></i> +229 0197189324 / +229 0197521637
            </a>
           <!-- <a class="nav-link text-white" href="mailto:complexeleglorieux@gmail.com" target="_blank">
                <i class="fa fa-envelope"></i> complexeleglorieux@gmail.com
            </a>-->
        </div>
    </div>
</header>

{{-- SIDEBAR --}}
<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-center mb-4">📚 ENSEIGNANT-CPEG LE GLORIEUX</h4>
    <ul class="nav nav-pills flex-column gap-1">
        <li><a href="/bords/show" class="nav-link text-white"><i class="bi bi-speedometer2 me-2"></i>Classes</a></li>
        <li><a href="{{ route('eleves.index') }}" class="nav-link text-white"><i class="bi bi-people me-2"></i> Élèves</a></li>
        <li><a href="{{ route('notes.index') }}" class="nav-link text-white"><i class="bi bi-book me-2"></i> Notes</a></li>
        <li><a href="{{ route('paiements.index') }}" class="nav-link text-white"><i class="bi bi-wallet2 me-2"></i> Paiements</a></li>
        <li><a href="{{ route('paiements.historique') }}" class="nav-link text-white"><i class="bi bi-bar-chart-line me-2"></i>Historiques des paiements</a></li>
    </ul>

    {{-- Sidebar ou header --}}
<div class="sidebar d-flex flex-column p-3">
    @auth
        <hr class="text-white">
        <div class="dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('profil.show', Auth::user()->id) }}" class="dropdown-item">Mon compte</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item">Déconnexion</button>
                    </form>
                </li>
            </ul>
        </div>
    @endauth
</div>

</div>

{{-- CONTENU --}}
<div class="content">
    @yield('content')
</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts

{{-- AJAX classe → élèves --}}
<script>
$(document).ready(function () {
    $('#classe_id').on('change', function () {
        let classeId = $(this).val();
        let eleveSelect = $('#eleve_id');

        eleveSelect.html('<option>Chargement...</option>');

        if (!classeId) {
            eleveSelect.html('<option value="">-- Sélectionnez un élève --</option>');
            return;
        }

        $.ajax({
            url: '/eleves-par-classe/' + classeId,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                eleveSelect.html('<option value="">-- Sélectionnez un élève --</option>');
                $.each(data.eleves, function (i, eleve) {
                    eleveSelect.append('<option value="' + eleve.id + '">' + eleve.prenom + ' ' + eleve.nom + '</option>');
                });
            }
        });
    });
});
</script>

{{-- Auto-close alerts --}}
<script>
setTimeout(() => {
    $('.alert').fadeOut('slow', function() { $(this).remove(); });
}, 4000);
</script>

@stack('scripts')
@yield('scripts')

</body>
</html>
