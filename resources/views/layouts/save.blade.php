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

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @stack('styles')
</head>
<body>
<div class="hero_area">
    <!-- Image de fond -->
    <div class="hero_bg_box">
        <div class="img-box">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="fond">
        </div>
    </div>

    <!-- En-tête -->
    <header class="header_section">
        <!-- Haut de la page avec infos de contact -->
        <div class="header_top bg-dark text-white py-2">
            <div class="container-fluid">
                <div class="contact_link-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <a href="#" class="text-white">
                        <i class="fa fa-map-marker"></i>
                        <marquee behavior="scroll" direction="left" width="100%">
                            <span>
                                COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR
                            </span>
                        </marquee>
                    </a>
                    <a class="nav-link text-white" href="https://wa.me/+2290197189324/+2290197521637" target="_blank">
                        <i class="fa fa-phone"></i>
                        Tels : +229 0197189324 / +229 0197521637
                    </a>
                    <a class="nav-link text-white" href="mailto:complexeleglorieux@gmail.com" target="_blank">
                        <i class="fa fa-envelope"></i>
                        complexeleglorieux@gmail.com
                    </a>
                </div>
            </div>
        </div>

        <!-- Barre de navigation -->
        <div class="header_bottom">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container bg-dark">
                    <a class="navbar-brand text-white" href="#">Le Glorieux</a>
                    <button class="navbar-toggler text-white" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form></li>
                        <ul class="navbar-nav mr-auto">
                           <li class="nav-item"><a class="nav-link text-white" href="/register">Enregistrement</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/service">Nos Offres</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/login">&#128273; Connexion</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/files">Banque d'épreuves</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/contacts/create">Contactez-nous</a></li>
                            <li class="nav-item"><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">&#9212; DECONNEXION</a>

                
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="container py-4">
        @yield('content')
    </main>
</div>

<!-- JS scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

@stack('scripts')
</body>
</html>
