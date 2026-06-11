<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Le Glorieux')</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">

    {{-- Bootstrap & Font --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet">

    {{-- Styles custom --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @stack('styles')
</head>
<body>

<div class="hero_area">

    {{-- 🎨 Image de fond --}}
    <div class="hero_bg_box position-relative">
      <!--  <img src="{{ asset('images/hero-bg.jpg') }}" alt="fond" class="img-fluid w-100">-->
    </div>

    {{-- 🔝 Top Header Infos --}}
    <div class="bg-dark text-white py-2">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <i class="fa fa-map-marker"></i>
                <marquee behavior="scroll" direction="left" scrollamount="5">
                    COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR
                </marquee>
            </div>
            <div>
                <i class="fa fa-phone"></i> +229 0197189324 / +229 0197521637
            </div>
            <div>
                <i class="fa fa-envelope"></i> <a href="mailto:complexeleglorieux@gmail.com" class="text-white text-decoration-none">complexeleglorieux@gmail.com</a>
            </div>
        </div>
    </div>

    {{-- 🚀 Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🏫 Le Glorieux</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    {{-- Importations --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">📥 Importations</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/eleves/import">Élèves</a></li>
                            <li><a class="dropdown-item" href="/parens/import">Parents</a></li>
                            <li><a class="dropdown-item" href="/notes/import">Notes</a></li>
                            <li><a class="dropdown-item" href="/enseignants/import">Enseignants</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/matieres/create">Créer Matière</a></li>
                            <li><a class="dropdown-item" href="/trimestres/create">Créer Trimestre</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/journee">Journée</a></li>
                            <li><a class="dropdown-item" href="/journee/create">Symposium</a></li>
                            <li><a class="dropdown-item" href="/journee/show">Atelier</a></li>
                        </ul>
                    </li>

                    {{-- Notes --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">📝 Notes</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/notes/import">Importation</a></li>
                            <li><a class="dropdown-item" href="/notes">Consulter</a></li>
                            <li><a class="dropdown-item" href="/notes/create">Créer</a></li>
                        </ul>
                    </li>

                    {{-- Frais --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">💳 Frais</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/frais/create">Créer</a></li>
                            <li><a class="dropdown-item" href="/frais">Liste</a></li>
                            <li><a class="dropdown-item" href="/paiement/create">Paiement</a></li>
                        </ul>
                    </li>

                    {{-- Autres --}}
                    <li class="nav-item"><a class="nav-link" href="{{ route('eleves.index') }}">👨‍🎓 Élèves</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('annees.index') }}">📆 Années</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('classes.index') }}">📚 Classes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('inscriptions.index') }}">📝 Inscriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('parens.index') }}">👪 Parents</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- 📄 Contenu principal --}}
    <main class="container py-5">
        @yield('content')
    </main>
</div>

{{-- 📜 Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    // Chargement dynamique des matières selon la classe
    $('#classe').on('change', function () {
        var classeId = $(this).val();
        var matiereSelect = $('#matiere');

        matiereSelect.html('<option>Chargement...</option>');
        if (classeId) {
            $.get('/classes/' + classeId + '/matieres', function (data) {
                matiereSelect.empty().append('<option value="">-- Sélectionner une matière --</option>');
                $.each(data, function (index, matiere) {
                    matiereSelect.append(`<option value="${matiere.id}">${matiere.nom}</option>`);
                });
            }).fail(function () {
                matiereSelect.html('<option>Aucune matière trouvée</option>');
            });
        } else {
            matiereSelect.html('<option>-- Sélectionner une matière --</option>');
        }
    });
</script>

@stack('scripts')
</body>
</html>
