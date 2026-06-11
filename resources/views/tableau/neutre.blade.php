<!DOCTYPE html>
<html lang="fr" data-theme="day">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Le Glorieux')</title>

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
            --neon-glow: 0 0 30px rgba(59, 130, 246, 0.5);
            --night-brightness: 1; /* contrôlé par JS */
        }

        /* ===== THÈME JOUR ===== */
        [data-theme="day"] body { background: linear-gradient(135deg, #f0f2ff 0%, #e0e7ff 25%, #dbedff 50%, #f0f9ff 75%, #f8fafc 100%); }
        [data-theme="day"] .content { background: linear-gradient(135deg, #f0f2ff 0%, #e0e7ff 25%, #dbedff 50%, #f0f9ff 75%, #f8fafc 100%); }
        [data-theme="day"] .content::before { opacity: 0.06; filter: contrast(200%) brightness(85%) sepia(0.3); }
        [data-theme="day"] .brightness-panel { display: none !important; }

        /* ===== THÈME NUIT ===== */
        [data-theme="night"] body { background: linear-gradient(135deg, #0f0c29 0%, #1a1a2e 25%, #16213e 50%, #0f3460 75%, #0a0a0a 100%); }
        [data-theme="night"] .content {
            background: linear-gradient(135deg, #0f0c29 0%, #1a1a2e 25%, #16213e 50%, #0f3460 75%, #0a0a0a 100%) !important;
            color: rgba(255,255,255,0.9);
            filter: brightness(var(--night-brightness));
        }
        [data-theme="night"] body {
            filter: brightness(var(--night-brightness));
        }
        [data-theme="night"] .header-main {
            filter: none !important; /* le header reste toujours visible */
        }
        [data-theme="night"] .content::before { opacity: 0.04; filter: contrast(200%) brightness(40%) sepia(0.5) invert(1); }
        [data-theme="night"] .card { background: rgba(255,255,255,0.07) !important; color: rgba(255,255,255,0.9); }
        [data-theme="night"] .card-header { background: rgba(99,102,241,0.3) !important; }
        [data-theme="night"] .form-control,
        [data-theme="night"] .form-select { background: rgba(255,255,255,0.1) !important; color: white !important; border-color: rgba(255,255,255,0.15) !important; }
        [data-theme="night"] .table { --bs-table-bg: transparent; color: rgba(255,255,255,0.85); border-color: rgba(255,255,255,0.1); }
        [data-theme="night"] h1,[data-theme="night"] h2,[data-theme="night"] h3,
        [data-theme="night"] h4,[data-theme="night"] h5,[data-theme="night"] h6,
        [data-theme="night"] label,[data-theme="night"] p { color: rgba(255,255,255,0.9) !important; }
        [data-theme="night"] .text-muted { color: rgba(255,255,255,0.5) !important; }
        [data-theme="night"] .text-dark { color: rgba(255,255,255,0.85) !important; }

        /* ===== BOUTON JOUR/NUIT ===== */
        .theme-toggle {
            width: 60px; height: 30px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 30px;
            cursor: pointer;
            position: relative;
            transition: all 0.4s ease;
            display: flex; align-items: center;
            padding: 3px;
            backdrop-filter: blur(10px);
        }
        .theme-toggle-thumb {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            box-shadow: 0 0 10px rgba(251,191,36,0.6);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
        }
        [data-theme="night"] .theme-toggle-thumb {
            transform: translateX(30px);
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            box-shadow: 0 0 10px rgba(99,102,241,0.6);
        }
        [data-theme="night"] .theme-toggle { background: rgba(99,102,241,0.2); border-color: rgba(99,102,241,0.4); }

        /* ===== PANNEAU LUMINOSITÉ ===== */
        .brightness-panel {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 30px;
            padding: 6px 14px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .brightness-panel span {
            font-size: 14px;
            color: rgba(255,255,255,0.8) !important;
            white-space: nowrap;
        }
        .brightness-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 90px; height: 4px;
            border-radius: 10px;
            background: rgba(255,255,255,0.3);
            outline: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .brightness-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 16px; height: 16px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            box-shadow: 0 0 8px rgba(251,191,36,0.6);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .brightness-slider::-webkit-slider-thumb:hover {
            transform: scale(1.2);
            box-shadow: 0 0 15px rgba(251,191,36,0.8);
        }
        .brightness-slider::-moz-range-thumb {
            width: 16px; height: 16px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            cursor: pointer;
        }

        * { box-sizing: border-box; }
        html, body {
            height: 100%; margin: 0;
            overflow-x: hidden; overflow-y: auto;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            transition: background 0.5s ease;
        }

        .dropdown-menu { z-index: 9999 !important; }
        .dropdown { position: relative; }
        .header-main { isolation: isolate; }
        .dropdown:hover .dropdown-menu { display: block; }

        .header-main {
            position: fixed !important;
            top: 0; left: 0; right: 0; height: 80px;
            z-index: 1050;
            background: rgba(17, 24, 39, 0.95) !important;
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);
            margin: 0 !important; padding: 0 !important;
        }

        .glass-icon, .header-btn, .glass-effect {
            background: rgba(255,255,255,0.08) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.95) !important;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            border-radius: 20px; text-decoration: none;
            display: inline-flex; align-items: center;
            min-height: 52px; font-weight: 500;
        }
        .glass-icon:hover, .header-btn:hover, .glass-effect:hover {
            background: rgba(255,255,255,0.18) !important;
            color: white !important;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 25px rgba(99,102,241,0.3);
        }

        .marquee-glass {
            background: rgba(0,0,0,0.4) !important;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.98) !important;
            padding: 12px 20px; border-radius: 30px;
            max-width: 70%; overflow: hidden; white-space: nowrap;
            flex-shrink: 1; box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }

        .user-avatar {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.9) !important;
        }

        .shadow-3d {
            background: rgba(17,24,39,0.98) !important;
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 35px 80px rgba(0,0,0,0.5);
            border-radius: 20px; margin-top: 8px;
        }

        .dropdown-item:hover { background: rgba(99,102,241,0.15) !important; color: white !important; }

        .sidebar {
            position: fixed; top: 80px; left: 0;
            width: 280px; height: calc(100vh - 80px);
            background: var(--sidebar-primary); color: white;
            padding: 40px 25px; overflow: hidden;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: var(--neon-glow), 5px 0 40px rgba(59,130,246,0.4);
        }
        .sidebar::before {
            content: ''; position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle at 20% 80%, rgba(120,119,198,0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: rotate 20s linear infinite; pointer-events: none;
        }
        @keyframes rotate { 100% { transform: rotate(1turn); } }
        .sidebar::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: var(--primary-glow); border-radius: 0 0 20px 20px;
            animation: shimmer 3s ease-in-out infinite;
        }
        @keyframes shimmer { 0%, 100% { opacity: 0.7; } 50% { opacity: 1; } }

        .hero_bg_box { text-align: center; margin-bottom: 3rem; }
        .hero_bg_box img {
            width: 70px; height: 55px; object-fit: cover; border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 0 4px rgba(255,255,255,0.3);
            animation: logo3DFloat 4s ease-in-out infinite;
        }
        @keyframes logo3DFloat {
            0%, 100% { transform: translateY(0); }
            33% { transform: translateY(-8px) rotateX(5deg) rotateY(-5deg); }
            66% { transform: translateY(-4px) rotateX(-3deg) rotateY(3deg); }
        }

        .sidebar h6 {
            font-size: 1.4rem; font-weight: 800; margin-bottom: 3rem; letter-spacing: 2px;
            background: linear-gradient(45deg, #fff, rgba(255,255,255,0.8));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.97); text-decoration: none;
            padding: 16px 25px; border-radius: 25px;
            display: flex; align-items: center; gap: 16px;
            margin-bottom: 12px; font-weight: 700; font-size: 1rem;
            position: relative; overflow: hidden;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            background: rgba(255,255,255,0.1); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .sidebar .nav-link:hover {
            transform: translateX(12px) scale(1.05);
            background: rgba(255,255,255,0.3);
            box-shadow: var(--neon-glow), 0 20px 40px rgba(0,0,0,0.4);
            color: white;
        }

        .content {
            position: fixed; top: 80px; left: 280px; right: 0; bottom: 0;
            padding: 45px; overflow-y: auto; transition: background 0.5s ease, filter 0.3s ease;
        }
        .content::before {
            content: ""; position: fixed; inset: 0;
            background-image: url("{{ asset('images/logo-glorieux.png') }}");
            background-repeat: no-repeat; background-position: center; background-size: 680px;
            pointer-events: none; z-index: 0;
            animation: watermark3D 25s ease-in-out infinite;
            transition: opacity 0.5s ease, filter 0.5s ease;
        }
        @keyframes watermark3D {
            0%, 100% { transform: scale(1); }
            33% { transform: scale(1.03) rotateX(2deg) rotateY(-1deg); }
            66% { transform: scale(1.02) rotateX(-1deg) rotateY(1deg); }
        }
        .content > * { position: relative; z-index: 2; }

        .card {
            background: rgba(255,255,255,0.6); backdrop-filter: blur(40px) saturate(180%);
            box-shadow: 0 35px 80px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.8);
            border: none; border-radius: 32px;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); position: relative; overflow: hidden;
        }
        .card:hover { transform: translateY(-12px); box-shadow: 0 50px 100px rgba(0,0,0,0.25), 0 0 60px rgba(99,102,241,0.3); }
        .card-header { background: var(--primary-glow); border-radius: 32px 32px 0 0 !important; color: white; font-weight: 800; }

        .form-control, .form-select {
            border-radius: 20px; border: 2px solid rgba(0,0,0,0.08);
            padding: 16px 24px; font-weight: 500;
            background: rgba(255,255,255,0.9); transition: all 0.4s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: transparent;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.2); transform: translateY(-3px);
        }
        .btn-primary {
            background: var(--primary-glow); border: none; border-radius: 20px;
            padding: 16px 40px; font-weight: 700; transition: all 0.4s ease;
        }
        .btn-primary:hover { transform: translateY(-5px) scale(1.05); box-shadow: 0 25px 50px rgba(99,102,241,0.4); }

        @media (max-width: 992px) { .sidebar { transform: translateX(-100%); } .content { left: 0; padding: 25px; } }
        .content::-webkit-scrollbar { width: 10px; }
        .content::-webkit-scrollbar-track { background: rgba(0,0,0,0.03); border-radius: 20px; }
        .content::-webkit-scrollbar-thumb { background: var(--primary-glow); border-radius: 20px; }
        @keyframes floatUp { to { transform: translateY(-100vh) scale(0); opacity: 0; } }
        .particle { animation: floatUp 6s linear infinite; }
    </style>
</head>

<body>
    <header class="header-main">
        <div class="container-fluid d-flex justify-content-between align-items-center h-100 px-5">
            <div class="d-flex align-items-center gap-4 flex-grow-1">
                <div class="glass-icon p-3 rounded-4 shadow-lg">
                    <i class="fas fa-map-marker-alt fs-3 text-light"></i>
                </div>
                <marquee scrollamount="2" behavior="scroll" direction="left" class="marquee-glass">
                    <strong class="text-light fw-bold">
                        🌟 COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE 1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR ✨
                    </strong>
                </marquee>
                <a class="header-btn glass-effect text-light" href="" title="">
                    <i class="fas fa-phone fs-8"></i>
                    <span class="d-none d-xl-inline ms-2 fw-semibold"> +229 01 97 52 16 37 </span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">

                {{-- SLIDER LUMINOSITÉ (visible seulement en mode nuit) --}}
                <div class="brightness-panel" id="brightnessPanel">
                    <span>🌑</span>
                    <input type="range" class="brightness-slider" id="brightnessSlider"
                           min="0.2" max="1" step="0.05" value="1" title="Luminosité">
                    <span>🌕</span>
                </div>

                {{-- BOUTON JOUR/NUIT --}}
                <button class="theme-toggle" id="themeToggle" title="Changer le thème">
                    <div class="theme-toggle-thumb" id="themeThumb">
                        <span id="themeIcon">☀️</span>
                    </div>
                </button>

                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="#">
                            🔔
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    @endauth
                    <li class="nav-item dropdown">...</li>
                </ul>

                @auth
                <div class="dropdown">
                    <button class="header-btn glass-effect d-flex align-items-center gap-3 p-3 text-light border-0 bg-transparent"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <i class="bi bi-person-circle fs-2 text-white"></i>
                        </div>
                        <div class="d-none d-lg-block text-start">
                            <div class="fw-bold small text-white">
                                {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                            </div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow p-0 border-0 rounded-4 overflow-hidden">
                        <li class="px-2 pt-2">
                            <a href="{{ route('profil.show', Auth::user()->id) }}"
                               class="dropdown-item rounded-3 d-flex align-items-center gap-3 p-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3">
                                    <i class="bi bi-person-lines-fill fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Mon compte</div>
                                    <div class="small text-muted">Gérer profil</div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-2 pb-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="dropdown-item text-danger rounded-3 d-flex align-items-center gap-3 p-3 w-100 border-0 bg-transparent">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded-3">
                                        <i class="bi bi-box-arrow-right fs-4 text-danger"></i>
                                    </div>
                                    <div class="text-start">
                                        <div class="fw-bold">Déconnexion</div>
                                        <div class="small text-muted">Quitter session</div>
                                    </div>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
    </header>

    @auth
        @php $user = Auth::user(); @endphp
        @if ($user->role === 'censeur') @include('components.sidebar.sidebar-censeur')
        @elseif ($user->role === 'admin') @include('components.sidebar.sidebar-admin')
        @elseif ($user->role === 'parent') @include('components.sidebar.sidebar-parent')
        @elseif ($user->role === 'secretaire') @include('components.sidebar.sidebar-secretaire')
        @elseif ($user->role === 'directeur') @include('components.sidebar.sidebar-directeur')
        @elseif ($user->role === 'comptable') @include('components.sidebar.sidebar-comptable')
        @elseif ($user->role === 'surveillant') @include('components.sidebar.sidebar-surveillant')
        @elseif ($user->role === 'enseignant') @include('components.sidebar.sidebar-enseignant')
        @endif
    @endauth

    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

    <script>
        // ===== THÈME JOUR/NUIT =====
        const html = document.documentElement;
        const btn = document.getElementById('themeToggle');
        const icon = document.getElementById('themeIcon');
        const slider = document.getElementById('brightnessSlider');
        const panel = document.getElementById('brightnessPanel');

        // Restaurer thème et luminosité
        const savedTheme = localStorage.getItem('theme') || 'day';
        const savedBrightness = localStorage.getItem('brightness') || '1';

        html.setAttribute('data-theme', savedTheme);
        icon.textContent = savedTheme === 'night' ? '🌙' : '☀️';
        slider.value = savedBrightness;
        html.style.setProperty('--night-brightness', savedBrightness);

        // Bascule jour/nuit
        btn.addEventListener('click', function () {
            const current = html.getAttribute('data-theme');
            const next = current === 'day' ? 'night' : 'day';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            icon.textContent = next === 'night' ? '🌙' : '☀️';
        });

        // Slider luminosité
        slider.addEventListener('input', function () {
            const val = this.value;
            html.style.setProperty('--night-brightness', val);
            localStorage.setItem('brightness', val);
        });

        // ===== PARTICLES =====
        $(document).ready(function () {
            function createParticle() {
                const particle = $('<div class="particle"></div>').css({
                    position: 'absolute', width: '4px', height: '4px',
                    background: 'rgba(255,255,255,0.7)', borderRadius: '50%',
                    pointerEvents: 'none',
                    left: Math.random() * 280,
                    top: Math.random() * 100 + 'vh',
                });
                $('.sidebar').append(particle);
                setTimeout(() => particle.remove(), 6000);
            }
            setInterval(createParticle, 800);

            setTimeout(function () {
                $('.alert').each(function () {
                    $(this).fadeOut(800, function () { $(this).alert('close'); });
                });
            }, 5000);

            $('#classe_id').on('change', function () {
                let classeId = $(this).val();
                let $select = $('#matiere_id');
                $select.empty().append('<option value="">Chargement...</option>').prop('disabled', true);
                if (classeId) {
                    $.get(`/classes/${classeId}/matieres`)
                        .done(function (data) {
                            $select.empty().append('<option value="">-- Sélectionnez une matière --</option>');
                            $.each(data.matieres || [], function (key, matiere) {
                                $select.append(`<option value="${matiere.id}">${matiere.nom}</option>`);
                            });
                            $select.prop('disabled', false);
                        })
                        .fail(function () {
                            $select.empty().append('<option value="">Erreur chargement</option>').prop('disabled', false);
                        });
                } else { $select.prop('disabled', false); }
            });
        });

        // ===== SESSION CHECK =====
        async function isSessionActive() {
            try {
                const res = await fetch('/check-session', { credentials: 'same-origin' });
                const data = await res.json();
                return data.active === true;
            } catch (e) { return false; }
        }

        document.addEventListener('click', async function (e) {
            const link = e.target.closest('a');
            if (!link) return;
            if (!link.href || link.href.startsWith('#') || link.target === '_blank') return;
            if (!link.href.startsWith(window.location.origin)) return;
            e.preventDefault();
            const active = await isSessionActive();
            window.location.href = active ? link.href : '/';
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>