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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
@livewireStyles



    @stack('styles')
    <style>
  .text-bleu {
    color: blue !important;
  }
</style>
</head>
<body class="margin-top">
<div class="hero_area">
    <!-- Image de fond -->
   <!-- <div class="hero_bg_box">
        <div class="img-box">
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="fond">
        </div>
    </div>-->

    <!-- En-tête -->
    <header class="header_section">
        <!-- Haut de la page avec infos de contact -->
        <div class="header_top bg-dark text-white py-2">
            <div class="container-fluid">
                <div class="contact_link-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <a href="#" class="text-white">
                        <i class="fa fa-map-marker"></i>
                        <marquee behavior="scroll" direction="left" width="90%">
                           <h2 style="color: blue;">
    COMPLEXE LE GLORIEUX – Cotonou Akpakpa-AYELAWADJE, 
    1ère rue après ZOOM SERVICE en venant de SACRÉ-CŒUR
</h2>

                        </marquee>
                    </a>
                    <a class="nav-link text-white" href="#" target="_blank">
                        <i class="fa fa-phone"></i>
                        <span>Tels : +229 0197189324 / +229 0197521637</span>
                    </a>
                    <a class="nav-link text-white" href="https://mail.google.com/mail/?view=cm&fs=1&to=complexeleglorieux@gmail.com" target="_blank">
                        <i class="fa fa-envelope"></i>
                        complexeleglorieux@gmail.com
                    </a>
                    @auth
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
            <!--<img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-user.png') }}" 
                 alt="Photo de profil" class="rounded-circle" width="30" height="30">-->
            <span class="ms-2">{{ Auth::user()->name }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end bg-info text-white" aria-labelledby="userDropdown">
            <a href="{{ route('profil.show', Auth::user()->id) }}" class="dropdown-item text-dark">
                <i class="bi bi-person-lines-fill"></i> Mon compte
            </a>
            <div class="dropdown-divider bg-light"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-dark">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </div>
    </li>
@endauth
                </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('#classe_id').on('change', function () {
        var classeId = $(this).val();

        if (classeId) {
            $.ajax({
                url: `/classes/${classeId}/matieres`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#matiere_id').empty().append('<option value="">-- Sélectionnez une matière --</option>');
                    $.each(data.matieres, function (key, matiere) {
                        $('#matiere_id').append('<option value="' + matiere.id + '">' + matiere.nom + '</option>');
                    });
                }
            });
        } else {
            $('#matiere_id').empty().append('<option value="">-- Sélectionnez une matière --</option>');
        }
    });
});
</script>

<script>
$(document).ready(function () {
    $('#classe_id').on('change', function () {
        var classeId = $(this).val();

        if (classeId) {
            $.ajax({
               url: `/classes/${classeId}/matieres`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#eleve_id').empty().append('<option value="">-- Sélectionnez un élève --</option>');
                    $.each(data.eleves, function (key, eleve) {
                        $('#eleve_id').append('<option value="' + eleve.id + '">' + eleve.prenom + ' ' + eleve.nom + '</option>');
                    });
                }
            });
        } else {
            $('#eleve_id').empty().append('<option value="">-- Sélectionnez un élève --</option>');
        }
    });
});
</script>


<script>
    setTimeout(function () {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 4000); // 4 secondes
</script>

@stack('scripts')
@yield('scripts')
@livewireScripts

</body>
</html>
