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
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --sidebar-glow: rgba(255,255,255,0.1);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* HEADER FIXE - GLASSMORPHISM */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            z-index: 1030;
            background: rgba(52, 58, 64, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }

        /* SIDEBAR FIXE - ULTRA MODERNE */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 260px;
            height: calc(100vh - 70px);
            background: var(--primary-gradient);
            color: white;
            padding: 30px 20px;
            overflow-y: auto;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 25px rgba(102, 126, 234, 0.3);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
            border-radius: 0 0 10px 10px;
        }

        .sidebar h6 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
            text-shadow: 0 4px 12px rgba(0,0,0,0.4);
            letter-spacing: 1px;
            position: relative;
        }

        .sidebar h6::after {
            content: '✨';
            position: absolute;
            top: -10px;
            right: -20px;
            font-size: 1.5rem;
            animation: sparkle 2s infinite;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.95);
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--sidebar-glow);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.25);
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            border-color: rgba(255,255,255,0.3);
            color: white;
        }

        .sidebar .nav-link:hover::before {
            left: 0;
        }

        /* LOGO SIDEBAR - ANIMÉ */
        .hero_bg_box {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .hero_bg_box img {
            width: 65px;
            height: 50px;
            max-height: 50px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.4);
            border: 4px solid rgba(255,255,255,0.3);
            transition: all 0.4s ease;
            animation: logoFloat 3s ease-in-out infinite;
        }

        .hero_bg_box:hover img {
            transform: scale(1.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        /* CONTENU PRINCIPAL - DAZZLING */
        .content {
            position: fixed;
            top: 70px;
            left: 260px;
            right: 0;
            bottom: 0;
            padding: 35px;
            overflow-y: auto;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-attachment: fixed;
        }

        /* Filigrane amélioré */
        .content::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url("{{ asset('images/logo-glorieux.png') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 600px;
            opacity: 0.08;
            filter: contrast(150%) brightness(80%) sepia(0.2);
            pointer-events: none;
            z-index: 0;
            animation: watermarkFloat 20s ease-in-out infinite;
        }

        @keyframes watermarkFloat {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.05) rotate(0.5deg); }
        }

        .content > * {
            position: relative;
            z-index: 2;
        }

        /* CARDS GLASSMORPHISM ULTIME */
        .card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(25px);
            box-shadow: 
                0 25px 45px rgba(0,0,0,0.15),
                0 0 0 1px rgba(255,255,255,0.2),
                inset 0 1px 0 rgba(255,255,255,0.5);
            border: none;
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 35px 60px rgba(0,0,0,0.2),
                0 0 0 1px rgba(255,255,255,0.3);
        }

        .card-header {
            background: var(--primary-gradient);
            border-radius: 24px 24px 0 0 !important;
            color: white;
            font-weight: 700;
        }

        /* FORMES & INPUTS */
        .form-control, .form-select {
            border-radius: 16px;
            border: 2px solid rgba(0,0,0,0.1);
            padding: 12px 20px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.8);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 16px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        /* ALERTES ANIMÉES */
        .alert {
            border: none;
            border-radius: 16px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* MARQUEE GLAM */
        marquee {
            max-width: 700px;
            font-size: 0.95rem;
            background: rgba(255,255,255,0.1);
            padding: 8px 16px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
        }

        /* DROPDOWN FANCY */
        .dropdown-menu {
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            border: none;
            padding: 12px;
            backdrop-filter: blur(15px);
            background: rgba(255,255,255,0.95);
        }

        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .sidebar { width: 240px; }
            .content { left: 240px; padding: 25px; }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .content {
                left: 0;
                padding: 20px;
            }
        }

        /* SCROLLBAR CUSTOM */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .content::-webkit-scrollbar {
            width: 8px;
        }

        .content::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
        }

        .content::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    {{-- HEADER FIXE --}}
    <header class="text-white">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100 px-4 px-md-5">
            <div class="d-flex align-items-center gap-4 flex-grow-1">
                <div class="bg-primary bg-opacity-25 p-2 rounded-3">
                    <i class="fas fa-map-marker-alt fs-4"></i>
                </div>
                <marquee behavior="scroll" direction="left" scrollamount="5">
                    <strong class="fw-bold fs-6">
                        🌟 COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 
                        1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR ✨
                    </strong>
                </marquee>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a class="btn btn-sm btn-outline-light border-0 p-2 rounded-4 text-white text-decoration-none d-flex align-items-center gap-2 shadow-sm" href="tel:+2290197189324">
                    <i class="fas fa-phone fs-5"></i>
                    <small class="d-none d-md-inline">Tél: +229 01 97 18 932</small>
                </a>
                <a class="btn btn-sm btn-outline-light border-0 p-2 rounded-4 text-white text-decoration-none d-flex align-items-center gap-2 shadow-sm" href="mailto:complexeleglorieux@gmail.com">
                    <i class="fas fa-envelope fs-5"></i>
                    <small class="d-none d-md-inline">Email</small>
                </a>

                @auth
                <div class="dropdown">
                    <a class="btn btn-sm p-2 rounded-4 text-white text-decoration-none d-flex align-items-center gap-2 shadow-sm" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <div class="bg-light bg-opacity-20 p-2 rounded-3">
                            <i class="bi bi-person-circle fs-4"></i>
                        </div>
                        <span class="d-none d-lg-inline fw-semibold">{{ Str::limit(Auth::user()->name, 12) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                        <li>
                            <a href="{{ route('profil.show', Auth::user()->id) }}" class="dropdown-item rounded-3 py-2">
                                <i class="bi bi-person-lines-fill me-3 text-primary"></i>Mon compte
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger rounded-3 py-2 w-100 text-start">
                                    <i class="bi bi-box-arrow-right me-3"></i>Déconnexion
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
        <div class="hero_bg_box">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Le Glorieux">
        </div>
        
        <h6>📚 CPEG LE GLORIEUX</h6>

        <ul class="nav nav-pills flex-column gap-2">
            {{-- Menu prêt à décommenter --}}
            {{-- 
            <li><a href="/bords/show" class="nav-link"><i class="bi bi-columns-gap"></i>📊 Classes</a></li>
            <li><a href="{{ route('eleves.index') }}" class="nav-link"><i class="bi bi-people"></i>👨‍🎓 Élèves</a></li>
            <li><a href="{{ route('notes.index') }}" class="nav-link"><i class="bi bi-journal-text"></i>📘 Notes</a></li>
            <li><a href="{{ route('paiements.index') }}" class="nav-link"><i class="bi bi-credit-card"></i>💰 Paiements</a></li>
            <li><a href="{{ route('paiements.historique') }}" class="nav-link"><i class="bi bi-graph-up"></i>📈 Historique</a></li>
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
            
            $select.empty().append('<option value="">-- Sélectionnez une matière --</option>').prop('disabled', true);
            
            if (classeId) {
                $.get(`/classes/${classeId}/matieres`)
                    .done(function(data) {
                        $.each(data.matieres || [], function(key, matiere) {
                            $select.append(`<option value="${matiere.id}">${matiere.nom}</option>`);
                        });
                        $select.prop('disabled', false);
                    })
                    .fail(function() {
                        console.error('Erreur chargement matières');
                        $select.prop('disabled', false);
                    });
            }
        });

        // Eleves par classe
        $(document).on('change', '#classe_id.eleve-select', function() {
            let classeId = $(this).val();
            let $select = $('#eleve_id');
            
            $select.empty().append('<option value="">-- Sélectionnez un élève --</option>').prop('disabled', true);
            
            if (classeId) {
                $.get('/eleves-par-classe/' + classeId)
                    .done(function(data) {
                        $.each(data.eleves || [], function(key, eleve) {
                            $select.append(`<option value="${eleve.id}">${eleve.prenom} ${eleve.nom}</option>`);
                        });
                        $select.prop('disabled', false);
                    })
                    .fail(function() {
                        console.error('Erreur chargement élèves');
                        $select.prop('disabled', false);
                    });
            }
        });

        // Auto-dismiss alerts avec animation
        setTimeout(function() {
            $('.alert').each(function() {
                let alertEl = this;
                setTimeout(() => {
                    let alert = bootstrap.Alert.getOrCreateInstance(alertEl);
                    if (alert) alert.close();
                }, 100);
            });
        }, 5000);
    });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
