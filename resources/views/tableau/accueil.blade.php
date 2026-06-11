@extends('tableau.neutre')
@section('title', 'le glorieux')
@section('content')
 @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @foreach(auth()->user()->unreadNotifications as $notification)

    <div class="alert alert-info">
        {{ $notification->data['message'] }}
        <br>
        {{ $notification->data['nom'] }}
    </div>

@endforeach
<div class="container mt-4">
 <marquee class="marquee-content" scrollamount="1" behavior="scroll" direction="up" width="1100" height="800">
   {{-- <h2>Bienvenue {{ auth()->user()->name }} 👋</h2>--}}
    @if(auth()->user()?->role == 'directeur')
        <h3 class="text-success">Espace Directeur</h3>
       <div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

       <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">

    <h3 class="mb-0">🎓 Rôle du Directeur du CPEG LE GLORIEUX</h3>

    <a href="{{ route('roles.pdf', 'directeur') }}" class="btn btn-light btn-sm">
        📄 Télécharger PDF
    </a>
</div>
        <div class="card-body">
            <p>
    Le directeur est le responsable principal de l’établissement. Il assure la gestion pédagogique,
administrative, financière et organisationnelle.
</p>
            {{-- 1. Pilotage pédagogique --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold">🎓 1. Pilotage pédagogique</h5>
                <ul>
                    <li>Met en œuvre les programmes officiels</li>
                    <li>Coordonne les activités pédagogiques</li>
                    <li>Encadre et accompagne les enseignants</li>
                    <li>Organise les conseils de classe et réunions pédagogiques</li>
                </ul>
            </div>
            {{-- 2. Gestion administrative --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold">🏫 2. Gestion administrative</h5>
                <ul>
                    <li>Gère les inscriptions et les dossiers des élèves</li>
                    <li>Supervise les emplois du temps</li>
                    <li>Veille au respect des règlements scolaires</li>
                    <li>Rédige des rapports et correspondances officielles</li>
                </ul>
            </div>
            {{-- 3. Gestion financière --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold">💰 3. Gestion financière et matérielle</h5>
                <ul>
                    <li>Prépare et exécute le budget de l’établissement</li>
                    <li>Gère les ressources matérielles (salles, équipements, fournitures)</li>
                    <li>Veille à la maintenance des infrastructures</li>
                </ul>
            </div>

            {{-- 4. Gestion du personnel --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold">👥 4. Gestion du personnel</h5>
                <ul>
                    <li>Encadre les enseignants et le personnel administratif</li>
                    <li>Organise le travail de chacun</li>
                    <li>Évalue les performances</li>
                    <li>Favorise un bon climat de travail</li>
                </ul>
            </div>

            {{-- 5. Relations --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold">🤝 5. Relations avec les partenaires</h5>
                <ul>
                    <li>Les parents d’élèves</li>
                    <li>Les autorités éducatives (ministère, inspection)</li>
                    <li>Les partenaires locaux et institutions</li>
                </ul>
            </div>

            {{-- 6. Discipline --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold">⚖️ 6. Discipline et climat scolaire</h5>
                <ul>
                    <li>Veille au respect des règles de vie</li>
                    <li>Gère les conflits et sanctions</li>
                    <li>Assure un environnement sécurisé et favorable aux apprentissages</li>
                </ul>
            </div>

            {{-- 7. Suivi --}}
            <div class="mb-4">
                <h5 class="text-primary fw-bold">📊 7. Suivi et évaluation</h5>
                <ul>
                    <li>Analyse les résultats scolaires</li>
                    <li>Met en place des actions d’amélioration</li>
                    <li>Suit les projets éducatifs</li>
                </ul>
            </div>

            {{-- 8. Représentation --}}
            <div>
                <h5 class="text-primary fw-bold">🌍 8. Représentation de l’établissement</h5>
                <ul>
                    <li>Représente l’école auprès des autorités et du public</li>
                    <li>Défend les intérêts de l’établissement</li>
                    <li>Valorise les performances et projets</li>
                </ul>
            </div>

        </div>

    </div>

</div>

    @elseif(auth()->user()?->role == 'censeur')

        <h3 class="text-info">Espace Censeur</h3>
        
<div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

       <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">

    <h3 class="mb-0">🎓 Rôle du CENSEUR du CPEG LE GLORIEUX</h3>

    <a href="{{ route('roles.pdf', 'censeur') }}" class="btn btn-light btn-sm">
        📄 Télécharger PDF
    </a>

</div>

        <div class="card-body">

            {{-- 1. Organisation pédagogique --}}
            <div class="mb-4">
                <h5 class="text-success fw-bold">📚 1. Organisation pédagogique</h5>
                <ul>
                    <li>Élaborer et gérer les emplois du temps</li>
                    <li>Planifier les devoirs, compositions et examens</li>
                    <li>Suivre l’exécution des programmes</li>
                    <li>Organiser les conseils de classe</li>
                </ul>
            </div>

            {{-- 2. Encadrement des enseignants --}}
            <div class="mb-4">
                <h5 class="text-success fw-bold">👨‍🏫 2. Encadrement des enseignants</h5>
                <ul>
                    <li>Suivre la présence et la ponctualité des enseignants</li>
                    <li>Veiller au respect des horaires</li>
                    <li>Participer à la répartition des classes et des matières</li>
                </ul>
            </div>

            {{-- 3. Suivi des élèves --}}
            <div class="mb-4">
                <h5 class="text-success fw-bold">👩‍🎓 3. Suivi des élèves</h5>
                <ul>
                    <li>Contrôler les absences et retards</li>
                    <li>Veiller à l’assiduité et au travail des élèves</li>
                    <li>Participer à l’orientation scolaire</li>
                    <li>Suivre les résultats académiques</li>
                </ul>
            </div>

            {{-- 4. Discipline --}}
            <div class="mb-4">
                <h5 class="text-success fw-bold">⚖️ 4. Discipline et vie scolaire</h5>
                <ul>
                    <li>Faire respecter le règlement intérieur</li>
                    <li>Gérer les sanctions disciplinaires</li>
                    <li>Maintenir l’ordre et la bonne conduite dans l’établissement</li>
                    <li>Superviser les surveillants</li>
                </ul>
            </div>

            {{-- 5. Gestion des évaluations --}}
            <div class="mb-4">
                <h5 class="text-success fw-bold">📊 5. Gestion des évaluations et résultats</h5>
                <ul>
                    <li>Organiser les examens (internes et officiels)</li>
                    <li>Centraliser et publier les notes</li>
                    <li>Produire les bulletins scolaires</li>
                </ul>
            </div>

            {{-- 6. Gestion administrative --}}
            <div class="mb-4">
                <h5 class="text-success fw-bold">🗂️ 6. Gestion administrative liée aux élèves</h5>
                <ul>
                    <li>Tenir les dossiers scolaires</li>
                    <li>Gérer les inscriptions pédagogiques</li>
                    <li>Préparer les statistiques scolaires</li>
                </ul>
            </div>

            {{-- 7. Collaboration --}}
            <div class="mb-4">
                <h5 class="text-success fw-bold">🤝 7. Collaboration avec le directeur</h5>
                <ul>
                    <li>Assister le directeur dans ses fonctions</li>
                    <li>Proposer des améliorations pédagogiques</li>
                    <li>Participer à la prise de décisions</li>
                </ul>
            </div>

            {{-- Résumé --}}
            <div class="mt-4 p-3 bg-light rounded-3">
                <h5 class="fw-bold text-dark">✔️ En résumé</h5>
                <ul class="mb-0">
                    <li>Le responsable de la vie pédagogique quotidienne</li>
                    <li>Le chef de l’organisation scolaire</li>
                    <li>Un acteur clé de la discipline et du suivi des élèves</li>
                </ul>
            </div>

        </div>

    </div>

</div>
        @elseif(auth()?->user()->role == 'secretaire')
        <h3 class="text-info">Espace SECRETAIRE</h3>
       <div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

       <div class="card-header bg-info text-white rounded-top-4 d-flex justify-content-between align-items-center">

    <h3 class="mb-0">🗂️ Rôle de la Secrétaire de l'Administration et des Finances</h3>

    <a href="{{ route('roles.pdf', 'secretaire') }}" class="btn btn-light btn-sm">
        📄 Télécharger PDF
    </a>

</div>

        <div class="card-body fs-4">

            {{-- Introduction --}}
            <p>
                La secrétaire joue un rôle clé dans le fonctionnement administratif et financier de l’établissement.
                Elle assure la gestion des documents, l’accueil, l'enregistrement des paiements et la communication interne et externe.
            </p>

            {{-- 1. Accueil --}}
            <div class="mb-4">
                <h5 class="text-info fw-bold">👩‍💼 1. Accueil et information</h5>
                <ul>
                    <li>Accueillir les élèves, parents et visiteurs</li>
                    <li>Renseigner et orienter le public</li>
                    <li>Répondre aux appels téléphoniques</li>
                </ul>
            </div>

            {{-- 2. Gestion administrative --}}
            <div class="mb-4">
                <h5 class="text-info fw-bold">📁 2. Gestion administrative</h5>
                <ul>
                    <li>Rédiger les courriers et documents officiels</li>
                    <li>Classer et archiver les dossiers</li>
                    <li>Gérer les inscriptions et dossiers des élèves</li>
                </ul>
            </div>
{{-- 3. Gestion des recettes --}}
            <div class="mb-4">
                <h5 class="text-info fw-bold">💵 3. Gestion des recettes</h5>
                <ul>
                    <li>Encaisser les frais de scolarité et autres paiements</li>
                    <li>Délivrer les reçus aux élèves et parents</li>
                    <li>Enregistrer toutes les entrées d’argent</li>
                </ul>
            </div>
            {{-- 4. Suivi des élèves --}}
            <div class="mb-4">
                <h5 class="text-info fw-bold">📚 4. Suivi des dossiers scolaires</h5>
                <ul>
                    <li>Mettre à jour les informations des élèves</li>
                    <li>Préparer les bulletins et relevés de notes</li>
                    <li>Gérer les attestations et certificats</li>
                </ul>
            </div>

            {{-- 5. Communication --}}
            <div class="mb-4">
                <h5 class="text-info fw-bold">📞 5. Communication</h5>
                <ul>
                    <li>Assurer la liaison entre administration, enseignants et parents</li>
                    <li>Diffuser les informations importantes</li>
                    <li>Organiser les réunions et convocations</li>
                </ul>
            </div>

            {{-- 6. Appui à la direction --}}
            <div class="mb-4">
                <h5 class="text-info fw-bold">🤝 6. Appui à la direction</h5>
                <ul>
                    <li>Assister le directeur dans ses tâches administratives</li>
                    <li>Préparer les documents de travail</li>
                    <li>Suivre les dossiers en cours</li>
                </ul>
            </div>

            {{-- 7. Organisation --}}
            <div class="mb-4">
                <h5 class="text-info fw-bold">📅 7. Organisation</h5>
                <ul>
                    <li>Gérer l’agenda administratif</li>
                    <li>Planifier les rendez-vous</li>
                    <li>Assurer le bon fonctionnement du secrétariat</li>
                </ul>
            </div>

            {{-- Résumé --}}
            <div class="mt-4 p-3 bg-light rounded-3">
                <h5 class="fw-bold text-dark">✔️ En résumé</h5>
                <ul class="mb-0">
                    <li>Le pilier administratif de l’établissement</li>
                    <li>Un relais de communication essentiel</li>
                    <li>Un soutien indispensable à la direction</li>
                </ul>
            </div>

        </div>

    </div>

</div>

        @elseif(auth()?->user()->role == 'comptable')

        <h3 class="text-info">Espace COMPTABLE </h3>
       <div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-warning text-dark rounded-top-4 d-flex justify-content-between align-items-center">

    <h3 class="mb-0">💰 Rôle du Comptable</h3>

    <a href="{{ route('roles.pdf', 'comptable') }}" class="btn btn-dark btn-sm">
        📄 Télécharger PDF
    </a>

</div>

        <div class="card-body fs-4">

            {{-- Introduction --}}
            <p>
                Le comptable est responsable de la gestion financière de l’établissement.
                Il veille à la bonne tenue des comptes, à la transparence financière et au respect des règles budgétaires.
            </p>

            {{-- 1. Gestion des recettes --}}
            {{--<div class="mb-4">
                <h5 class="text-warning fw-bold">💵 1. Gestion des recettes</h5>
                <ul>
                    <li>Encaisser les frais de scolarité et autres paiements</li>
                    <li>Délivrer les reçus aux élèves et parents</li>
                    <li>Enregistrer toutes les entrées d’argent</li>
                </ul>
            </div>--}}

            {{-- 2. Gestion des dépenses --}}
            <div class="mb-4">
                <h5 class="text-warning fw-bold">💸 1. Gestion des dépenses</h5>
                <ul>
                    <li>Effectuer les paiements autorisés</li>
                    <li>Contrôler les dépenses de l’établissement</li>
                    <li>Conserver les pièces justificatives (factures, reçus)</li>
                </ul>
            </div>

            {{-- 3. Tenue de la comptabilité --}}
            <div class="mb-4">
                <h5 class="text-warning fw-bold">📊 2. Tenue de la comptabilité</h5>
                <ul>
                    <li>Tenir à jour les livres comptables</li>
                    <li>Enregistrer toutes les opérations financières</li>
                    <li>Établir les bilans et rapports financiers</li>
                </ul>
            </div>

            {{-- 4. Gestion du budget --}}
            <div class="mb-4">
                <h5 class="text-warning fw-bold">📈 3. Gestion budgétaire</h5>
                <ul>
                    <li>Participer à l’élaboration du budget</li>
                    <li>Suivre l’exécution du budget</li>
                    <li>Proposer des ajustements si nécessaire</li>
                </ul>
            </div>

            {{-- 5. Collaboration --}}
            <div class="mb-4">
                <h5 class="text-warning fw-bold">🤝 4. Collaboration avec la direction</h5>
                <ul>
                    <li>Conseiller le directeur sur les décisions financières</li>
                    <li>Fournir des rapports réguliers</li>
                    <li>Participer aux réunions administratives</li>
                </ul>
            </div>

            {{-- 5. Contrôle et sécurité --}}
            <div class="mb-4">
                <h5 class="text-warning fw-bold">🔒 6. Contrôle et sécurité financière</h5>
                <ul>
                    <li>Assurer la transparence des opérations</li>
                    <li>Éviter les erreurs et fraudes</li>
                    <li>Respecter les règles comptables et financières</li>
                </ul>
            </div>

            {{-- Résumé --}}
            <div class="mt-4 p-3 bg-light rounded-3">
                <h5 class="fw-bold text-dark">✔️ En résumé</h5>
                <ul class="mb-0">
                    <li>Le garant de la bonne gestion financière</li>
                    <li>Un acteur clé de la transparence</li>
                    <li>Un conseiller financier de l’établissement</li>
                </ul>
            </div>

        </div>

    </div>

</div>

         @elseif(auth()?->user()->role == 'admin')

        <h3 class="text-info">Espace ADMIN</h3>
       <div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4 d-flex justify-content-between align-items-center">

     <h3 class="mb-0">⚙️ Rôle de l’Administrateur (Admin)</h3>

    <a href="{{ route('roles.pdf', 'admin') }}" class="btn btn-dark btn-sm">
        📄 Télécharger PDF
    </a>

</div>

        <div class="card-body fs-4">

            {{-- Introduction --}}
            <p>
                L’administrateur est le responsable technique et organisationnel du système.
                Il veille au bon fonctionnement de la plateforme, à la gestion des utilisateurs
                et à la sécurité des données.
            </p>

            {{-- 1. Gestion des utilisateurs --}}
            <div class="mb-4">
                <h5 class="text-danger fw-bold">👥 1. Gestion des utilisateurs</h5>
                <ul>
                    <li>Créer, modifier et supprimer les comptes utilisateurs</li>
                    <li>Attribuer les rôles (directeur, enseignant, parent, etc.)</li>
                    <li>Gérer les accès et autorisations</li>
                </ul>
            </div>

            {{-- 2. Gestion du système --}}
            <div class="mb-4">
                <h5 class="text-danger fw-bold">🖥️ 2. Gestion du système</h5>
                <ul>
                    <li>Configurer les paramètres de l’application</li>
                    <li>Assurer le bon fonctionnement de la plateforme</li>
                    <li>Maintenir et mettre à jour le système</li>
                </ul>
            </div>

            {{-- 3. Gestion des données --}}
            <div class="mb-4">
                <h5 class="text-danger fw-bold">🗂️ 3. Gestion des données</h5>
                <ul>
                    <li>Superviser les bases de données</li>
                    <li>Assurer la sauvegarde régulière des données</li>
                    <li>Garantir l’intégrité des informations</li>
                </ul>
            </div>

            {{-- 4. Sécurité --}}
            <div class="mb-4">
                <h5 class="text-danger fw-bold">🔐 4. Sécurité</h5>
                <ul>
                    <li>Protéger le système contre les intrusions</li>
                    <li>Gérer les mots de passe et accès sécurisés</li>
                    <li>Surveiller les activités suspectes</li>
                </ul>
            </div>

            {{-- 5. Assistance --}}
            <div class="mb-4">
                <h5 class="text-danger fw-bold">🛠️ 5. Assistance technique</h5>
                <ul>
                    <li>Aider les utilisateurs en cas de problème</li>
                    <li>Résoudre les bugs techniques</li>
                    <li>Former les utilisateurs à l’utilisation du système</li>
                </ul>
            </div>

            {{-- 6. Suivi et amélioration --}}
            <div class="mb-4">
                <h5 class="text-danger fw-bold">📊 6. Suivi et amélioration</h5>
                <ul>
                    <li>Analyser les performances du système</li>
                    <li>Proposer des améliorations</li>
                    <li>Optimiser l’expérience utilisateur</li>
                </ul>
            </div>

            {{-- Résumé --}}
            <div class="mt-4 p-3 bg-light rounded-3">
                <h5 class="fw-bold text-dark">✔️ En résumé</h5>
                <ul class="mb-0">
                    <li>Le garant du bon fonctionnement du système</li>
                    <li>Le responsable de la sécurité</li>
                    <li>Un support technique essentiel</li>
                </ul>
            </div>

        </div>

    </div>

</div>
 @elseif(auth()->user()?->role == 'enseignant')

        <h3 class="text-info">Espace ENSEIGNANT</h3>
       <div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4 d-flex justify-content-between align-items-center">

     <h3 class="mb-0">👨‍🏫 Rôle de l’Enseignant</h3>

    <a href="{{ route('roles.pdf', 'enseignant') }}" class="btn btn-dark btn-sm">
        📄 Télécharger PDF
    </a>

</div>


        <div class="card-body fs-4">

            {{-- Introduction --}}
            <p>
                L’enseignant est un acteur central du système éducatif.
                Il assure la transmission des connaissances, l’encadrement des élèves
                et contribue à leur formation intellectuelle, morale et sociale.
            </p>

            {{-- 1. Transmission des connaissances --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">📚 1. Transmission des connaissances</h5>
                <ul>
                    <li>Dispenser les cours selon les programmes officiels</li>
                    <li>Préparer les leçons et supports pédagogiques</li>
                    <li>Adapter l’enseignement au niveau des élèves</li>
                </ul>
            </div>

            {{-- 2. Évaluation --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">📝 2. Évaluation des élèves</h5>
                <ul>
                    <li>Organiser les devoirs, tests et examens</li>
                    <li>Corriger et noter les copies</li>
                    <li>Apprécier les performances des élèves</li>
                </ul>
            </div>

            {{-- 3. Encadrement --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">👨‍🎓 3. Encadrement des élèves</h5>
                <ul>
                    <li>Accompagner les élèves dans leur apprentissage</li>
                    <li>Encourager la discipline et le respect</li>
                    <li>Conseiller et orienter les élèves</li>
                </ul>
            </div>

            {{-- 4. Gestion de la classe --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">🏫 4. Gestion de la classe</h5>
                <ul>
                    <li>Maintenir un climat favorable à l’apprentissage</li>
                    <li>Gérer le temps et l’organisation des cours</li>
                    <li>Assurer la discipline en classe</li>
                </ul>
            </div>

            {{-- 5. Collaboration --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">🤝 5. Collaboration</h5>
                <ul>
                    <li>Travailler avec les autres enseignants</li>
                    <li>Participer aux réunions pédagogiques</li>
                    <li>Collaborer avec l’administration</li>
                </ul>
            </div>

            {{-- 6. Suivi pédagogique --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">📊 6. Suivi pédagogique</h5>
                <ul>
                    <li>Suivre l’évolution des élèves</li>
                    <li>Analyser les résultats scolaires</li>
                    <li>Mettre en place des actions de remédiation</li>
                </ul>
            </div>

            {{-- 7. Relations avec les parents --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">👨‍👩‍👧 7. Relations avec les parents</h5>
                <ul>
                    <li>Informer les parents des résultats</li>
                    <li>Participer aux rencontres parents-enseignants</li>
                    <li>Donner des conseils pour le suivi à domicile</li>
                </ul>
            </div>

            {{-- 8. Développement professionnel --}}
            <div class="mb-4">
                <h5 class="text-secondary fw-bold">📖 8. Développement professionnel</h5>
                <ul>
                    <li>Se former régulièrement</li>
                    <li>Actualiser ses méthodes pédagogiques</li>
                    <li>Innover dans les pratiques d’enseignement</li>
                </ul>
            </div>

            {{-- Résumé --}}
            <div class="mt-4 p-3 bg-light rounded-3">
                <h5 class="fw-bold text-dark">✔️ En résumé</h5>
                <ul class="mb-0">
                    <li>Le transmetteur du savoir</li>
                    <li>Un encadreur et éducateur</li>
                    <li>Un acteur clé de la réussite des élèves</li>
                </ul>
            </div>

        </div>

    </div>

</div>
 @elseif(auth()->user()?->role == 'parent')

        <h3 class="text-info">Espace PARENT</h3>
       <div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-info text-white rounded-top-4 d-flex justify-content-between align-items-center">

     <h3 class="mb-0">👨‍👩‍👧 Rôle du Parent d’Élève</h3>

    <a href="{{ route('roles.pdf', 'parent') }}" class="btn btn-light btn-sm">
        📄 Télécharger PDF
    </a>

</div>

        <div class="card-body fs-4">

            {{-- Introduction --}}
            <p>
                Le parent d’élève est un acteur essentiel de la réussite scolaire.
                Il accompagne, soutient et encadre son enfant tout au long de son parcours éducatif.
            </p>

            {{-- 1. Suivi scolaire --}}
            <div class="mb-4">
                <h5 class="text-dark fw-bold">📚 1. Suivi de la scolarité</h5>
                <ul>
                    <li>Vérifier régulièrement les cahiers et devoirs</li>
                    <li>Suivre les résultats scolaires</li>
                    <li>Encourager le travail et la persévérance</li>
                </ul>
            </div>

            {{-- 2. Encadrement --}}
            <div class="mb-4">
                <h5 class="text-dark fw-bold">🏡 2. Encadrement à la maison</h5>
                <ul>
                    <li>Créer un environnement favorable aux études</li>
                    <li>Instaurer une discipline et des habitudes de travail</li>
                    <li>Veiller au respect des horaires</li>
                </ul>
            </div>

            {{-- 3. Communication --}}
            <div class="mb-4">
                <h5 class="text-dark fw-bold">📞 3. Communication avec l’école</h5>
                <ul>
                    <li>Participer aux réunions parents-administration de l'école</li>
                    <li>Échanger avec les enseignants sur les difficultés</li>
                    <li>Se tenir informé de la vie scolaire</li>
                </ul>
            </div>

            {{-- 4. Responsabilité --}}
            <div class="mb-4">
                <h5 class="text-dark fw-bold">⚖️ 4. Responsabilité éducative</h5>
                <ul>
                    <li>Veiller à l’assiduité et à la ponctualité</li>
                    <li>S’assurer du respect du règlement intérieur</li>
                    <li>Encadrer le comportement de l’enfant</li>
                </ul>
            </div>

            {{-- 5. Soutien moral --}}
            <div class="mb-4">
                <h5 class="text-dark fw-bold">❤️ 5. Soutien moral et motivation</h5>
                <ul>
                    <li>Encourager et valoriser les efforts</li>
                    <li>Aider l’enfant à surmonter les difficultés</li>
                    <li>Renforcer la confiance en soi</li>
                </ul>
            </div>

            {{-- 6. Collaboration --}}
            <div class="mb-4">
                <h5 class="text-dark fw-bold">🤝 6. Collaboration avec l’établissement</h5>
                <ul>
                    <li>Respecter les décisions de l’école</li>
                    <li>Participer aux activités scolaires</li>
                    <li>Soutenir les actions éducatives</li>
                </ul>
            </div>

            {{-- Résumé --}}
            <div class="mt-4 p-3 bg-light rounded-3">
                <h5 class="fw-bold text-dark">✔️ En résumé</h5>
                <ul class="mb-0">
                    <li>Un accompagnateur essentiel de l’élève</li>
                    <li>Un partenaire de l’école</li>
                    <li>Un soutien moral et éducatif permanent</li>
                </ul>
            </div>

        </div>

    </div>

</div>
    @elseif(auth()->user()?->role == 'surveillant')

        <h3 class="text-warning">Espace Surveillant</h3>
       
<div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-info text-white rounded-top-4 d-flex justify-content-between align-items-center">

      <h3 class="mb-0">👨‍🏫 Rôle du Surveillant</h3>

    <a href="{{ route('roles.pdf', 'surveillant') }}" class="btn btn-light btn-sm">
        📄 Télécharger PDF
    </a>

</div>

        <div class="card-body fs-5">

            {{-- Introduction --}}
            <p>
                Le surveillant joue un rôle essentiel dans le bon fonctionnement de l’établissement scolaire.
                Il veille à la discipline, à la sécurité et au respect du règlement intérieur.
            </p>

            {{-- 1. Surveillance des élèves --}}
            <h5 class="mt-4 text-success fw-bold">1. Surveillance des élèves</h5>
            <ul>
                <li>Assurer la surveillance pendant les récréations</li>
                <li>Contrôler les entrées et sorties des élèves</li>
                <li>Veiller au bon comportement des élèves dans l’enceinte de l’établissement</li>
            </ul>

            {{-- 2. Discipline --}}
            <h5 class="mt-4 text-success fw-bold">2. Maintien de la discipline</h5>
            <ul>
                <li>Faire respecter le règlement intérieur</li>
                <li>Signaler les comportements indisciplinés</li>
                <li>Appliquer les consignes données par l’administration</li>
            </ul>

            {{-- 3. Gestion des absences --}}
            <h5 class="mt-4 text-success fw-bold">3. Gestion des absences et retards</h5>
            <ul>
                <li>Contrôler la présence des élèves</li>
                <li>Enregistrer les absences et retards</li>
                <li>Informer l’administration en cas d’irrégularité</li>
            </ul>

            {{-- 4. Sécurité --}}
            <h5 class="mt-4 text-success fw-bold">4. Sécurité des élèves</h5>
            <ul>
                <li>Prévenir les accidents</li>
                <li>Intervenir en cas de conflit entre élèves</li>
                <li>Assurer un environnement sécurisé</li>
            </ul>

            {{-- 5. Collaboration --}}
            <h5 class="mt-4 text-success fw-bold">5. Collaboration avec l’équipe éducative</h5>
            <ul>
                <li>Travailler avec les enseignants et l’administration</li>
                <li>Transmettre les informations importantes</li>
                <li>Participer à la vie scolaire</li>
            </ul>

        </div>

    </div>

</div>


    @else

        <h3>Rôle non défini</h3>

    @endif
</marquee>
</div>

@endsection