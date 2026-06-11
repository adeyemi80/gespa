@extends('tableau.neutre')

@section('title', 'Rôle du Censeur')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-success text-white rounded-top-4">
            <h3 class="mb-0">🎓 Rôle du Censeur du CPEG LE GLORIEUX</h3>
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

@endsection