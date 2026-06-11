<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Complexe Le Glorieux')</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.6.2/css/bootstrap.min.css">

    @stack('styles')
    <!--<style>
    body {
        background-color: #f2f2f2; /* ou #f8f9fa pour un gris Bootstrap */
    }

    .card, .table {
        background-color: #ffffff;
    }
</style>-->
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
            <div class="header_top">
                <div class="container-fluid">
                    <div class="contact_link-container d-flex flex-column flex-md-row justify-content-between">
                        <a href="#" class="text-white">
                            <i class="fa fa-map-marker"></i>
                            <marquee behavior="scroll" direction="left" width="100%">
                                <span>
                                    COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR
                                </span>
                            </marquee>
                        </a>
                        <a class="nav-link text-white" href="https://wa.me/+2290197189324" target="_blank">
                            <a class="nav-link text-white" href="https://wa.me/+2290197521637" target="_blank">
                            <i class="fa fa-phone"></i>
                            Tel: +229 0197189324 / +229 0197521637
                        </a>
                         </a>
                        <a class="nav-link text-white" href="mailto:complexeleglorieux@gmail.com" target="_blank">
                            <i class="fa fa-envelope"></i>
                            complexeleglorieux@gmail.com
                        </a>
                    </div>
                </div>
            </div>

            <div class="header_bottom bg-dark">
                <div class="container-fluid">
                    <nav class="navbar navbar-expand-lg navbar-dark">
                        <a class="navbar-brand" href="#">Le Glorieux</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarNav">
                             <li class="nav-item submenu dropdown">
                    <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                      aria-expanded="false"> Les IMPORTATIONS</a>
                    <ul class="dropdown-menu">
                      <li class="nav-item">
                        <a class="nav-link" href="/eleves/import">Importation des élèves</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="/parens/import">Importation des parents</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="/notes/import">Importations des notes</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="/parens/create">Créer un parent</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="/enseignants/import">Importation des enseignants</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="/enseignants/index">Enseignants</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="/journee">Journée</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="/journee/create">Symposium</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="/journee/show">Atelier</a>
                        </li>
                    </ul>
                  </li>
                  <li class="nav-item submenu dropdown">
                    <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                      aria-expanded="false"> Les Notes</a>
                    <ul class="dropdown-menu">
                      <li class="nav-item">
                        <a class="nav-link" href="/notes/import">Importation des notes</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="/notes">Les notes</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="/notes/create">Créer une nte des notes</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href=""></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href=""></a>
                      </li>
                    </ul>
                  </li>
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('eleves.index') }}">Élèves</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('annees.index') }}">Années</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('classes.index') }}">Classes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('inscriptions.index') }}">Inscriptions</a>
                                </li>
                                 <li class="nav-item">
                                    <a class="nav-link" href="{{ route('parens.index') }}">Parents</a>
                                </li>

                                 
                                <!-- Ajoute ici d'autres liens si nécessaire -->
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
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
