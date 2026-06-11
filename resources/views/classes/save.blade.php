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
                </div>
            </div>
        </div>

        <!-- Barre de navigation -->
        <div class="header_bottom">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container bg-dark">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="importDropdown" role="button" data-toggle="dropdown">
                                    Les IMPORTATIONS
                                </a>
                                     <div class="dropdown-menu bg-info text-bleu">
                                    <a class="dropdown-item" href="/eleves/import">Importation des élèves</a>
                                    <a class="dropdown-item" href="/parens/import">Importation des parents</a>
                                    <a class="dropdown-item" href="/notes/import">Importation des notes</a>
                                    <a class="dropdown-item" href="/matieres/create">Créer une Matière</a>
                                    <a class="dropdown-item" href="/matieres">Les Matières</a>
                                    <a class="dropdown-item" href="/trimestres/create">Créer un Trismestre</a>
                                    <a class="dropdown-item" href="/enseignants/import">Importation des Enseignants</a>
                                    <a class="dropdown-item" href="/enseignants">Enseignants</a>
                                    <a class="dropdown-item" href="/passage">Passage des élèves</a>
                                    <a class="dropdown-item" href="/journee/show">Atelier</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les Notes
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                    <a class="dropdown-item" href="/notes/import">Importation des notes</a>
                                    <a class="dropdown-item" href="/notes">Les notes</a>
                                    <a class="dropdown-item" href="/notes/create">Créer une note</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les Frais
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                    <a class="dropdown-item" href="/frais/create">créer les frais</a>
                                    <a class="dropdown-item" href="/frais">Les frais</a>
                                    <a class="dropdown-item" href="/paiements/create">Créer un paiement</a>
                                      <a class="dropdown-item" href="/paiements">Les paiements</a>
                                      <a class="dropdown-item" href="/paysco-dropdown">Créer Les Versements</a>
                                      <a class="dropdown-item" href="/payscos">Les Versements</a>
                                </div>
                            </li>
                             <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les BULLETINS
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                    <a class="dropdown-item" href="/bulletins/create">Créer les bulletins</a>
                                    <a class="dropdown-item" href="/bulletins">Les Bulletins</a>
                                    <a class="dropdown-item" href="/"></a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les CONDUITES
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                      <a class="dropdown-item" href="/conduites/import">Importation des Conduites</a>
                                    <a class="dropdown-item" href="/conduites/create">Créer les CONDUITES</a>
                                    <a class="dropdown-item" href="/conduites">Les Conduites</a>
                                    <a class="dropdown-item" href="/"></a>
                                </div>
                            </li>
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('eleves.index') }}">Élèves</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('annees.index') }}">Années</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('classes.index') }}">Classes</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('inscriptions.index') }}">Inscriptions</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('parens.index') }}">Parents</a></li>
                           <!-- <li class="nav-item"><a class="nav-link text-white" href="/register">Enregistrement</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/service">Nos Offres</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/login">&#128273; Connexion</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/files">Banque d'épreuves</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/contacts/create">Contactez-nous</a></li>-->

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
                url: '/eleves-par-classe/' + classeId,
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
</body>
</html>
