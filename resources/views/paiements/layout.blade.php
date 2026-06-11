<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'GESPA')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    {{-- Styles spécifiques --}}
    @stack('styles')
</head>

<body class="bg-light">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                🎓 GESPA
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paiements.index') }}">
                            💳 Paiements
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown">
                            📊 États
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('paiements.soldesParEleve') }}">
                                    💰 Soldes par élève
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('paiements.historique') }}">
                                    📄 Historique
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    {{-- CONTENU --}}
    <main class="py-4">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="text-center text-muted py-3 small">
        © {{ date('Y') }} GESPA — Gestion scolaire
    </footer>

    {{-- jQuery (UNE SEULE FOIS) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Scripts spécifiques --}}
    @yield('scripts')

</body>
</html>