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
                                    <a class="dropdown-item" href="/notes/import-livewire">Importation des notes</a>
                                    <a class="dropdown-item" href="/matieres/create">Créer une Matière</a>
                                    <a class="dropdown-item" href="/matieres">Les Matières</a>
                                    <a class="dropdown-item" href="/trimestres/create">Créer un Trismestre</a>
                                    <a class="dropdown-item" href="/enseignants/import">Importation des Enseignants</a>
                                    <a class="dropdown-item" href="/enseignants">Enseignants</a>
                                    <a class="dropdown-item" href="/passages">Passage des élèves</a>
                                    <a class="dropdown-item" href="/trimestres">Les Trimestres</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les Notes
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                    <a class="dropdown-item" href="/notes/import-livewire">Importation des notes</a>
                                    <a class="dropdown-item" href="/notes/import2">Importation d'une Note</a>
                                    <a class="dropdown-item" href="/notes">Les notes</a>
                                       <a class="dropdown-item" href="/fiches-notes">FICHE DE notes</a>
                                    <a class="dropdown-item" href="/notes/create">Créer une note</a>
                                     <a class="dropdown-item" href="/matieres/import">Importation des matières</a>
                                      <a class="dropdown-item" href="/matieres">Matières</a>
                                       <a class="dropdown-item" href="/transactions">Les Transactions </a>
                                       <a class="dropdown-item" href="/categories">Les Categories </a>
                                        <a class="dropdown-item" href="/comptes">Les Comptes </a>
                                         <a class="dropdown-item" href="/budgets">Les Budgets </a>
                                          <a class="dropdown-item" href="/rapports">Les Rapports </a>
                                           <a class="dropdown-item" href="/rapports/global">Les Rapports globaux </a>
                                        <a class="dropdown-item" href="/inscription-frais">Les Frais pour chaque élève</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les Finances
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                      <a class="dropdown-item" href="/finances">Les Finances</a>
                                    <a class="dropdown-item" href="/frais/create">créer les frais</a>
                                    <a class="dropdown-item" href="/frais">Les frais</a>
                                       <a class="dropdown-item" href="/operations">Les Opérations</a>
                                         <a class="dropdown-item" href="/operations/rapport">Les Rapports des Opérations </a>
                                    <a class="dropdown-item" href="/recettes">Les Recettes</a>
                                     <a class="dropdown-item" href="/depenses">Les Dépenses</a>
                                      <a class="dropdown-item" href="/depenses/create">Créer Les Dépenses</a>
                                    <a class="dropdown-item" href="/paiements/create">Créer un paiement</a>
                                    <a class="dropdown-item" href="/paiements/create-up">Paiement de plusieurs Frais</a>
                                      <a class="dropdown-item" href="/paiements">Les paiements</a>
                                      <a class="dropdown-item" href="/paiements/historique">Historique des paiements</a>
                                     
                                </div>
                            </li>
                             <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Le cycle
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                    <a class="dropdown-item" href="/cycles">Les cycles</a>
                                    <a class="dropdown-item" href="/cycles/create">Créer un cycle</a>
                                   <a class="dropdown-item" href="/examens-blancs">Les Examens Blancs</a>
                                   <a class="dropdown-item" href="/examens-blancs/create"> Créer Les Examens Blancs</a>
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
                                    <a class="dropdown-item" href="/tests">Les Epreuves</a>
                                     <a class="dropdown-item" href="/tests/import">Importation des Epreuves</a>
                                     <a class="dropdown-item" href="/tests/create">Ajouter une Epreuve</a>
                                      <a class="dropdown-item" href="/tests/multiple/create"> Multipe Ajout d'Epreuves</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les ELEVES
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                    <a class="dropdown-item" href="/eleves">Les Elèvess</a>
                                     <a class="dropdown-item" href="/eleves/photos">Importation des PHOTOS</a>
                                    <a class="dropdown-item" href="/passages">Le Passage des élèves</a>
                                    <a class="dropdown-item" href="/bulletins.manager">Les Bulletins</a>
                                    <a class="dropdown-item" href="/moyennes">Les Moyennes</a>
                                    <a class="dropdown-item" href="/bulletins/classe">Les Bulletins par Classe</a>
                                    <a class="dropdown-item" href="/users">Les Utilisateurs</a>
                                </div>
                            </li>
                            <li class="nav-item"><a class="nav-link text-white" href="{{ route('annees.index') }}">Années</a></li>
                           <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les CLASSES
                                </a>
                             <div class="dropdown-menu bg-info text-white">
                                 <a class="dropdown-item" href="/classes">Les Classes</a>
                                  <a class="dropdown-item" href="/dashboard-statistiques">Les STATISTIQUES</a>
                                   <a class="dropdown-item" href="/classement-par-classe">Le Classement par Classe</a>
                                      <a class="dropdown-item" href="/td">GESTION DES TD</a>
                                   <a class="dropdown-item" href="/td/export-pdf/{classe_id}">GESTION DES TD EXPORTATION</a>
                                    <a class="dropdown-item" href="/articles">Les Articles</a>
                                     <a class="dropdown-item" href="/articles/create">Créer les articles</a>
                                      <a class="dropdown-item" href="/types"> les types</a>
                                       <a class="dropdown-item" href="/types/create">Créer les types</a>
                                </div>
                        </li>
                            <!--<li class="nav-item"><a class="nav-link text-white" href="{{ route('inscriptions.index') }}">Inscriptions</a></li>-->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="noteDropdown" role="button" data-toggle="dropdown">
                                    Les PARENTS
                                </a>
                                <div class="dropdown-menu bg-info text-white">
                                    <a class="dropdown-item" href="/parens">Les Parents</a>
                                    <a class="dropdown-item" href="/parens/dashboard">Les messages aux parents</a>
                                    <a class="dropdown-item" href="/"></a>
                                </div>
                            </li>
                            <li class="nav-item">
              <a class="nav-link text-white" href="/register">Enregistrement</a>
            </li>
                           <!-- <li class="nav-item"><a class="nav-link text-white" href="/register">Enregistrement</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/service">Nos Offres</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/login">&#128273; Connexion</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/files">Banque d'épreuves</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="/bulletins/classe/3/trimestre/1">Contactez-nous</a></li>-->
                                    

                        </ul>
                    </div>
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
@livewireScripts

</body>
</html>
