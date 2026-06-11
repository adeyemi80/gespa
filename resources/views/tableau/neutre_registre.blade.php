<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Le Glorieux')</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @livewireStyles

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        /* HEADER FIXE */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            z-index: 1030;
            background-color: #343a40 !important;
        }

        /* SIDEBAR FIXE */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 230px;
            height: calc(100vh - 60px);
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
            padding: 20px 15px;
            overflow-y: auto;
            transition: width 0.3s ease;
        }

        .sidebar h6 {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .sidebar .nav-link {
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        /* Logo sidebar - HAUTEUR RÉDUITE */
.hero_bg_box {
    text-align: center;
    margin-bottom: 2rem;
}

.hero_bg_box img {
    width: 60px;      /* Réduit aussi légèrement */
    height: 50px;     /* ← NOUVELLE HAUTEUR COMPACTE */
    max-height: 50px; /* Limitation stricte */
    object-fit: cover; /* Conserve les proportions sans déformation */
    border-radius: 50%;
    box-shadow: 0 6px 16px rgba(0,0,0,0.25);
}


        /* CONTENU SCROLLABLE */
        .content {
            position: fixed;
            top: 60px;
            left: 230px;
            right: 0;
            bottom: 0;
            padding: 25px;
            overflow-y: auto;
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
            background-color: rgba(255,255,255,0.97);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: none;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        /* Logo sidebar */
        .hero_bg_box {
            text-align: center;
            margin-bottom: 2rem;
        }

        .hero_bg_box img {
            width: 80px;
            height: auto;
            border-radius: 50%;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .content {
                left: 0;
                padding: 20px;
            }
            /* Ajouter un bouton menu pour mobile si besoin */
        }

        /* Marquee amélioré */
        marquee {
            max-width: 600px;
        }

        /* Amélioration dropdown */
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>
    {{-- HEADER FIXE --}}
    <header class="text-white">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100 px-3 px-md-4">
            <div class="d-flex align-items-center gap-3 flex-grow-1">
                <i class="fas fa-map-marker-alt fs-5"></i>
                <marquee behavior="scroll" direction="left" scrollamount="4">
                    <strong class="text-primary fw-bold">
                        COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 
                        1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR
                    </strong>
                </marquee>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a class="nav-link text-white text-decoration-none d-flex align-items-center gap-2" href="tel:+2290197189324">
                    <i class="fas fa-phone fs-5"></i>
                    <small>+229 0197189324 / +229 0197521637</small>
                </a>
                <a class="nav-link text-white text-decoration-none d-flex align-items-center gap-2" href="mailto:complexeleglorieux@gmail.com">
                    <i class="fas fa-envelope fs-5"></i>
                    <small>complexeleglorieux@gmail.com</small>
                </a>

                @auth
                <div class="dropdown">
                    <a class="text-white dropdown-toggle d-flex align-items-center gap-2 text-decoration-none" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-4"></i>
                        <span class="d-none d-md-inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('profil.show', Auth::user()->id) }}" class="dropdown-item">
                                <i class="bi bi-person-lines-fill me-2"></i> Mon compte
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
        <!-- Barre de navigation -->
  <div class="header_bottom">
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg custom_nav-container bg-dark">
         <a class="navbar-brand" href="#">Le Glorieux</a>
        <div class="img-box">
          <!-- <img src="images/lg.png" alt=""> -->
        </div>
        <button class="navbar-toggler text-white" type="button" data-toggle="collapse"
          data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link text-white" href="/show/register">ENREGISTREMENT DES UTILISATEURS</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
    </header>

    {{-- SIDEBAR FIXE --}}
    <div class="sidebar">
        <div class="hero_bg_box">
            <div class="img-box">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Le Glorieux">
            </div>
        </div>
        
        <h6 class="text-center mb-4">📚  CPEG LE GLORIEUX</h6>

        <ul class="nav nav-pills flex-column gap-1">
            {{-- Décommentez selon vos besoins --}}
            {{-- 
            <li><a href="/bords/show" class="nav-link"><i class="bi bi-columns-gap me-2"></i>📊 Classes</a></li>
            <li><a href="{{ route('eleves.index') }}" class="nav-link"><i class="bi bi-people me-2"></i>👨‍🎓 Élèves</a></li>
            <li><a href="{{ route('notes.index') }}" class="nav-link"><i class="bi bi-journal-text me-2"></i>📘 Notes</a></li>
            <li><a href="{{ route('paiements.index') }}" class="nav-link"><i class="bi bi-credit-card me-2"></i>💰 Paiements</a></li>
            <li><a href="{{ route('paiements.historique') }}" class="nav-link"><i class="bi bi-graph-up me-2"></i>📈 Historique</a></li>
            --}}
        </ul>
    </div>

    {{-- CONTENU SCROLLABLE --}}
    <div class="content">
        @yield('content')
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts

    {{-- Scripts AJAX optimisés --}}
    <script>
    $(document).ready(function() {
        // Matieres par classe
        $('#classe_id').on('change', function() {
            let classeId = $(this).val();
            let $select = $('#matiere_id');
            
            $select.empty().append('<option value="">-- Sélectionnez une matière --</option>');
            
            if (classeId) {
               $.get(`/classes/${classeId}/matieres`)
                    .done(function(data) {
                        $.each(data.matieres || [], function(key, matiere) {
                            $select.append(`<option value="${matiere.id}">${matiere.nom}</option>`);
                        });
                    })
                    .fail(function() {
                        console.error('Erreur chargement matières');
                    });
            }
        });

        // Eleves par classe
        $(document).on('change', '#classe_id.eleve-select', function() {
            let classeId = $(this).val();
            let $select = $('#eleve_id');
            
            $select.empty().append('<option value="">-- Sélectionnez un élève --</option>');
            
            if (classeId) {
                $.get('/eleves-par-classe/' + classeId)
                    .done(function(data) {
                        $.each(data.eleves || [], function(key, eleve) {
                            $select.append(`<option value="${eleve.id}">${eleve.prenom} ${eleve.nom}</option>`);
                        });
                    })
                    .fail(function() {
                        console.error('Erreur chargement élèves');
                    });
            }
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert').each(function() {
                let alert = bootstrap.Alert.getOrCreateInstance(this);
                alert.close();
            });
        }, 5000);
    });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
