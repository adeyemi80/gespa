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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @livewireStyles

    <style>
        :root {
            --primary-glow: linear-gradient(135deg, #6366f1 0%, #8b5cf6 25%, #ec4899 50%, #f43f5e 75%, #ef4444 100%);
            --sidebar-primary: linear-gradient(145deg, #1e3a8a 0%, #3b82f6 50%, #1d4ed8 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-glow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --neon-glow: 0 0 30px rgba(59, 130, 246, 0.5);
        }

        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(ellipse at top, #667eea05, transparent), 
                        radial-gradient(ellipse at bottom, #764ba205, transparent);
        }

        /* HEADER FIXE - TOUTES PROPRIÉTÉS CONSERVÉES */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            z-index: 1050;
            background: var(--glass-bg);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: var(--shadow-glow), inset 0 1px 0 rgba(255,255,255,0.4);
        }

        /* SIDEBAR ACTIF - TOUTES PROPRIÉTÉS MAINTENUES */
        .sidebar {
            position: fixed;
            top: 80px;  /* ✅ Juste sous le header 80px */
            left: 0;    /* ✅ Toujours collé à gauche */
            width: 280px; /* ✅ Largeur fixe 280px */
            height: calc(100vh - 80px); /* ✅ Hauteur complète */
            background: var(--sidebar-primary);
            color: white;
            padding: 40px 25px;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: var(--neon-glow), 5px 0 40px rgba(59, 130, 246, 0.4);
            z-index: 1040; /* ✅ Au-dessus du contenu */
        }

        /* EFFETS PARTICULES - INCHANGÉS */
        .sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120,119,198,0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120,219,255,0.2) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate { 100% { transform: rotate(1turn); } }

        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--primary-glow);
            border-radius: 0 0 20px 20px;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        /* LOGO 3D - IDENTIQUE */
        .hero_bg_box {
            text-align: center;
            margin-bottom: Votre layout actuel garde déjà parfaitement les propriétés du sidebar actif ! Voici la version optimisée qui maintient **toutes** les caractéristiques visuelles (280px fixe, effets 3D, particles, glows, animations) tout en ajoutant un système de toggle responsive :

```html
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @livewireStyles

    <style>
        :root {
            --primary-glow: linear-gradient(135deg, #6366f1 0%, #8b5cf6 25%, #ec4899 50%, #f43f5e 75%, #ef4444 100%);
            --sidebar-primary: linear-gradient(145deg, #1e3a8a 0%, #3b82f6 50%, #1d4ed8 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-glow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --neon-glow: 0 0 30px rgba(59, 130, 246, 0.5);
        }

        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(ellipse at top, #667eea05, transparent), 
                        radial-gradient(ellipse at bottom, #764ba205, transparent);
        }

        /* HEADER NEOMORPHISM + GLASS */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            z-index: 1050;
            background: var(--glass-bg);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: var(--shadow-glow), inset 0 1px 0 rgba(255,255,255,0.4);
        }

        /* SIDEBAR 3D + PARTICLES - TOUJOURS ACTIF */
        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            width: 280px; /* FIXE - JAMAIS MODIFIÉ */
            height: calc(100vh - 80px);
            background: var(--sidebar-primary);
            color: white;
            padding: 40px 25px;
            overflow: hidden;
            transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1); /* UNIQUEMENT transform */
            box-shadow: var(--neon-glow), 5px 0 40px rgba(59, 130, 246, 0.4);
            z-index: 1040;
            position: relative;
        }

        /* ÉTAT FERMÉ MOBILE UNIQUEMENT */
        body.sidebar-closed .sidebar {
            transform: translateX(-100%); /* SEUL changement possible */
        }

        /* TOUS LES EFFETS VISUELS RESTENT IDENTIQUES */
        .sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120,119,198,0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120,219,255,0.2) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate { 100% { transform: rotate(1turn); } }

        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--primary-glow);
            border-radius: 0 0 20px 20px;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        /* LOGO 3D FLOATING */
        .hero_bg_box {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            perspective: 1000px;
        }

        .hero_bg_box img {
            width: 70px;
            height: 55px;
            max-height: 55px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 
                0 20px 40px rgba(0,0,0,0.4),
                0 0 0 4px rgba(255,255,255,0.3),
                inset 0 1px 0 rgba(255,255,255,0.6);
            transform-style: preserve-3d;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            animation: logo3DFloat 4s ease-in-out infinite;
        }

        .hero_bg_box:hover img {
            transform: rotateY(10deg) rotateX(10deg) scale(1.15);
            box-shadow: 0 30px 60px rgba(0,0,0,0.5), 0 0 40px rgba(59,130,246,0.6);
        }

        @keyframes logo3DFloat {
            0%, 100% { transform: translateY(0) rotateX(0deg) rotateY(0deg); filter: brightness(1); }
            33% { transform: translateY(-8px) rotateX(5deg) rotateY(-5deg); filter: brightness(1.1); }
            66% { transform: translateY(-4px) rotateX(-3deg) rotateY(3deg); filter: brightness(1.05); }
        }

        /* NAVIGATION MAGIQUE */
        .sidebar h6 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 3rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.5);
            letter-spacing: 2px;
            background: linear-gradient(45deg, #fff, rgba(255,255,255,0.8));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            animation: titleGlow 2s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            from { filter: drop-shadow(0 0 10px rgba(255,255,255,0.5)); }
            to { filter: drop-shadow(0 0 20px rgba(255,255,255,0.8)); }
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.97);
            text-decoration: none;
            padding: 16px 25px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
            font-weight: 700;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.6s;
        }

        .sidebar .nav-link:hover {
            transform: translateX(12px) scale(1.05);
            background: rgba(255,255,255,0.3);
            box-shadow: var(--neon-glow), 0 20px 40px rgba(0,0,0,0.4);
            border-color: rgba(255,255,255,0.5);
            color: white;
        }

        .sidebar .nav-link:hover::before { left: 100%; }

        /* CONTENT COSMIC - S'ADAPTE AU SIDEBAR ACTIF */
        .content {
            position: fixed;
            top: 80px;
            left: 280px; /* CORRESPOND EXACTEMENT AU SIDEBAR */
            right: 0;
            bottom: 0;
            padding: 45px;
            overflow-y: auto;
            background: 
                linear-gradient(135deg, #f0f2ff 0%, #e0e7ff 25%, #dbedff 50%, #f0f9ff 75%, #f8fafc 100%),
                radial-gradient(circle at 20% 80%, rgba(99,102,241,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139,92,246,0.08) 0%, transparent 50%);
            background-attachment: fixed;
            transition: left 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        /* MOBILE : CONTENT PLEIN ÉCRAN */
        @media (max-width: 992px) {
            body.sidebar-closed .content { left: 0; }
        }

        /* WATERMARK 3D */
        .content::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url("{{ asset('images/logo-glorieux.png') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 680px;
            opacity: 0.06;
            filter: contrast(200%) brightness(85%) sepia(0.3) drop-shadow(0 0 20px rgba(99,102,241,0.3));
            pointer-events: none;
            z-index: 0;
            animation: watermark3D 25s ease-in-out infinite;
            transform-style: preserve-3d;
        }

        @keyframes watermark3D {
            0%, 100% { transform: scale(1) rotateX(0deg) rotateY(0deg) translateZ(0); }
            33% { transform: scale(1.03) rotateX(2deg) rotateY(-1deg) translateZ(10px); }
            66% { transform: scale(1.02) rotateX(-1deg) rotateY(1deg) translateZ(5px); }
        }

        .content > * { position: relative; z-index: 2; }

        /* BOUTON TOGGLE MOBILE */
        .sidebar-toggle {
            position: fixed;
            top: 100px;
            left: 15px;
            z-index: 1060;
            background: var(--sidebar-primary);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            box-shadow: var(--neon-glow), 0 10px 30px rgba(59,130,246,0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            display: none;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1) rotate(180deg);
            box-shadow: 0 0 40px rgba(59,130,246,0.7);
        }

        /* CARDS NÉOMORPHISM ULTIME */
        .card {
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            box-shadow: 
                0 35px 80px rgba(0,0,0,0.15),
                0 0 0 1px rgba(255,255,255,0.2),
                inset 0 1px 0 rgba(255,255,255,0.8),
                inset 0 -1px 0 rgba(0,0,0,0.05);
            border: none;
            border-radius: 32px;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }

        /* FORM ELEMENTS PREMIUM + BTN */
        .form-control, .form-select, .form-control:focus, .form-select:focus {
            border-radius: 20px;
            border: 2px solid rgba(0,0,0,0.08);
            padding: 16px 24px;
            font-weight: 500;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(20px);
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .form-control:focus, .form-select:focus {
            border-color: transparent;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.2), 0 20px 40px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,1);
            transform: translateY(-3px);
            background: rgba(255,255,255,1);
        }

        .btn-primary {
            background: var(--primary-glow);
            border: none;
            border-radius: 20px;
            padding: 16px 40px;
            font-weight: 700;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        /* SCROLLBAR COSMIC */
        .sidebar::-webkit-scrollbar { width: 8px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); border-radius: 20px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.4); border-radius: 20px; box-shadow: var(--neon-glow); }
        .content::-webkit-scrollbar { width: 10px; }
        .content::-webkit-scrollbar-track { background: rgba(0,0,0,0.03); border-radius: 20px; }
        .content::-webkit-scrollbar-thumb { background: var(--primary-glow); border-radius: 20px; }

        /* RESPONSIVE - SEULEMENT MOBILE */
        @media (max-width: 992px) {
            .sidebar-toggle { display: block; }
            body.sidebar-closed .content { left: 0; padding: 25px; }
        }
    </style>
</head>

<body class="sidebar-open"> {{-- Classe par défaut: sidebar actif --}}
    {{-- HEADER --}}
    <header class="bg-dark text-light">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100 px-5">
            <!-- Contenu header inchangé -->
            <div class="d-flex align-items-center gap-4 flex-grow-1">
                <div class="glass-icon p-3 rounded-4 shadow-lg">
                    <i class="fas fa-map-marker-alt fs-3 text-light"></i>
                </div>
                <marquee behavior="scroll" direction="left" scrollamount="6" class="marquee-glass">
                    <strong class="text-light fw-bold">
                        🌟 COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE  1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR ✨
                    </strong>
                </marquee>
            </div>
            <!-- Droite header inchangée -->
            <div class="d-flex align-items-center gap-4">
                <!-- Auth dropdown inchangé -->
                @auth
                <!-- ... votre dropdown existant ... -->
                @endauth
            </div>
        </div>
    </header>

    {{-- BOUTON TOGGLE MOBILE --}}
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    {{-- SIDEBAR - TOUJOURS LES MÊMES PROPRIÉTÉS --}}
    <div class="sidebar">
        <div class="hero_bg_box">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Le Glorieux">
        </div>
        <h6>📚 CPEG LE GLORIEUX</h6>
        <ul class="nav nav-pills flex-column gap-3">
            <!-- Vos liens existants -->
        </ul>
    </div>

    {{-- CONTENT --}}
    <div class="content">
        @yield('content')
    </div>

    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
    $(document).ready(function() {
        // Toggle sidebar mobile UNIQUEMENT
        $('#sidebarToggle').on('click', function() {
            $('body').toggleClass('sidebar-closed');
            const icon = $(this).find('i');
            icon.removeClass('bi-list bi-x-lg');
            icon.addClass($('body').hasClass('sidebar-closed') ? 'bi-list' : 'bi-x-lg');
        });

        // Particles sidebar (TOUJOURS ACTIVES)
        function createParticle() {
            const particle = $('<div class="particle"></div>').css({
                position: 'absolute',
                width: '4px',
                height: '4px',
                background: 'rgba(255,255,255,0.7)',
                borderRadius: '50%',
                pointerEvents: 'none',
                left: Math.random() * 280,
                top: Math.random() * 100 + 'vh',
                animation: 'floatUp 6s linear infinite'
            });
            $('.sidebar').append(particle);
            setTimeout(() => particle.remove(), 6000);
        }
        setInterval(createParticle, 800);

        // Fermer sidebar en cliquant dehors (mobile)
        $(document).on('click', function(e) {
            if ($(window).width() < 992 && !$(e.target).closest('.sidebar, #sidebarToggle').length) {
                $('body').addClass('sidebar-closed');
            }
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert').each(function() {
                $(this).fadeOut(800, function() { $(this).alert('close'); });
            });
        }, 5000);
    });

    // Resize handler
    $(window).resize(function() {
        if ($(window).width() >= 992) {
            $('body').removeClass('sidebar-closed');
        }
    });
    </script>

    <style>
    /* Styles header inchangés */
    .glass-icon, .header-btn, .glass-effect {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s ease;
    }
    @keyframes floatUp { to { transform: translateY(-100vh) scale(0); opacity: 0; } }
    .particle { animation: floatUp 6s linear infinite; }
    </style>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
