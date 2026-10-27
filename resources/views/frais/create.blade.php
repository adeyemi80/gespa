@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    {{-- Bouton retour --}}
    <div class="mb-3">
        <button
            type="button"
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary">
            ⬅️ Retour
        </button>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0">➕ Nouveau frais avec échéances</h5>
        </div>

        <div class="card-body p-4">

            {{-- Message de succès --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>
                </div>
            @endif

            {{-- Messages d'erreur --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4">

                    <h6 class="fw-bold">
                        ⚠️ Veuillez corriger les erreurs suivantes :
                    </h6>

                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>
            @endif

            <form method="POST" action="{{ route('frais.store') }}">
                @csrf

                {{-- =====================================================
                     DONNÉES PRÉPARÉES POUR JAVASCRIPT
                ====================================================== --}}

                @php
    $categoriesPourJs = [];

    foreach ($categoriesRecettes as $categorie) {
        $nomsFrais = [];

        foreach ($categorie->frais as $frais) {
            if (!empty($frais->nom) && !in_array($frais->nom, $nomsFrais)) {
                $nomsFrais[] = $frais->nom;
            }
        }

        $categoriesPourJs[] = [
            'id' => $categorie->id,
            'nom' => $categorie->nom,
            'code' => $categorie->code,
            'frais' => $nomsFrais
        ];
    }
@endphp

                {{-- =====================================================
                     CATÉGORIES DE RECETTES
                ====================================================== --}}

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        📂 Choisir une catégorie de recette
                        <span class="text-danger">*</span>
                    </label>

                    {{-- Valeurs envoyées au contrôleur --}}
                    <input
                        type="hidden"
                        name="categorie_recette_id"
                        id="categorie_recette_id"
                        value="{{ old('categorie_recette_id') }}"
                        required
                    >

                    <input
                        type="hidden"
                        name="nom"
                        id="nom"
                        value="{{ old('nom') }}"
                        required
                    >

                    <div class="row g-3" id="categories-container">

                        @forelse ($categoriesRecettes as $categorie)

                            <div class="col-6 col-md-4 col-lg-3">

                                <button
                                    type="button"
                                    class="categorie-card w-100 h-100 {{ old('categorie_recette_id') == $categorie->id ? 'active' : '' }}"
                                    data-id="{{ $categorie->id }}"
                                    data-nom="{{ $categorie->nom }}"
                                    data-code="{{ $categorie->code }}"
                                    aria-pressed="{{ old('categorie_recette_id') == $categorie->id ? 'true' : 'false' }}"
                                >

                                    <div class="categorie-icon">
                                        @switch($categorie->code)

                                            @case('SCO')
                                                🎓
                                                @break

                                            @case('INS')
                                                📝
                                                @break

                                            @case('REI')
                                                🔄
                                                @break

                                            @case('UNI')
                                                👕
                                                @break

                                            @case('SPO')
                                                ⚽
                                                @break

                                            @case('LAC')
                                                👔
                                                @break

                                            @case('TD')
                                                📚
                                                @break

                                            @case('ANG')
                                                🌍
                                                @break

                                            @case('ASS')
                                                🛡️
                                                @break

                                            @case('TRA')
                                                🚌
                                                @break

                                            @case('BEP')
                                            @case('BAC')
                                                📄
                                                @break

                                            @default
                                                💰

                                        @endswitch
                                    </div>

                                    <div class="fw-bold">
                                        {{ $categorie->nom }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $categorie->frais->count() }}
                                        frais associés
                                    </small>

                                </button>

                            </div>

                        @empty

                            <div class="col-12">
                                <div class="alert alert-warning">
                                    Aucune catégorie de recette n'est disponible.
                                </div>
                            </div>

                        @endforelse

                    </div>

                    @error('categorie_recette_id')
                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- =====================================================
                     FRAIS ASSOCIÉS À LA CATÉGORIE
                ====================================================== --}}

                <div class="card border-0 shadow-sm rounded-3 mb-4">

                    <div class="card-header bg-light py-3">

                        <h6 class="mb-1 fw-bold">
                            🧾 Frais disponibles dans cette catégorie
                        </h6>

                        <small
                            class="text-muted"
                            id="categorie-selectionnee"
                            aria-live="polite">
                            Sélectionnez une catégorie ci-dessus.
                        </small>

                    </div>

                    <div class="card-body">

                        <div
                            id="frais-container"
                            class="row g-3"
                            aria-live="polite">

                            <div class="col-12 text-muted">
                                <i class="bi bi-info-circle"></i>
                                Choisissez une catégorie pour afficher ses frais.
                            </div>

                        </div>

                        @error('nom')
                            <div class="text-danger small mt-3">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                {{-- =====================================================
                     MONTANT
                ====================================================== --}}

                <div class="row g-3 mb-3">

                    <div class="col-md-6">

                        <label for="montant" class="form-label fw-semibold">
                            💰 Montant total (FCFA)
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            id="montant"
                            name="montant"
                            class="form-control"
                            min="1"
                            step="1"
                            value="{{ old('montant') }}"
                            required
                        >

                        @error('montant')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- =================================================
                         CYCLE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="cycle" class="form-label fw-semibold">
                            🎓 Cycle
                        </label>

                        <select
                            id="cycle"
                            name="cycle_id"
                            class="form-select">

                            <option value="">
                                -- Choisir un cycle --
                            </option>

                            @foreach ($cycles as $cycle)
                                <option
                                    value="{{ $cycle->id }}"
                                    {{ old('cycle_id') == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->nom }}
                                </option>
                            @endforeach

                        </select>

                        @error('cycle_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- =================================================
                         CLASSE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="classe" class="form-label fw-semibold">
                            🏫 Classe
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="classe"
                            name="classe_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Choisir une classe --
                            </option>

                        </select>

                        @error('classe_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- =================================================
                         ANNÉE SCOLAIRE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="annee_id" class="form-label fw-semibold">
                            📅 Année scolaire
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="annee_id"
                            name="annee_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Sélectionner une année --
                            </option>

                            @foreach ($annees as $annee)
                                <option
                                    value="{{ $annee->id }}"
                                    {{ old('annee_id') == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->nom }}
                                </option>
                            @endforeach

                        </select>

                        @error('annee_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                {{-- =====================================================
                     DESCRIPTION
                ====================================================== --}}

                <div class="mb-4">

                    <label for="description" class="form-label fw-semibold">
                        📝 Description (facultative)
                    </label>

                    <input
                        type="text"
                        id="description"
                        name="description"
                        class="form-control"
                        value="{{ old('description') }}"
                        placeholder="Ajouter une description si nécessaire"
                    >

                    @error('description')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <hr class="my-4">

                {{-- =====================================================
                     ÉCHÉANCES
                ====================================================== --}}

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h6 class="text-primary fw-bold mb-0">
                        📆 Échéances de paiement
                    </h6>

                    <span class="badge bg-light text-dark">
                        Tranches
                    </span>

                </div>

                <div id="echeances-container">

                    <div class="row g-3 mb-3 echeance-item">

                        <div class="col-md-4">

                            <label class="form-label">
                                Libellé
                            </label>

                            <input
                                type="text"
                                name="echeances[0][libelle]"
                                class="form-control"
                                placeholder="Ex. : 1ère tranche"
                                value="{{ old('echeances.0.libelle') }}"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Montant (FCFA)
                            </label>

                            <input
                                type="number"
                                name="echeances[0][montant]"
                                class="form-control"
                                placeholder="Montant"
                                min="1"
                                step="1"
                                value="{{ old('echeances.0.montant') }}"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Date limite
                            </label>

                            <input
                                type="date"
                                name="echeances[0][date_limite]"
                                class="form-control"
                                value="{{ old('echeances.0.date_limite') }}"
                                required
                            >

                        </div>

                    </div>

                </div>

                @error('echeances')
                    <div class="text-danger small mb-3">
                        {{ $message }}
                    </div>
                @enderror

                <div class="mb-4">

                    <button
                        type="button"
                        class="btn btn-outline-success"
                        id="add-echeance">

                        ➕ Ajouter une tranche

                    </button>

                </div>

                {{-- =====================================================
                     BOUTONS D'ACTION
                ====================================================== --}}

                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">

                    <button
                        type="reset"
                        class="btn btn-outline-secondary">

                        ↺ Réinitialiser

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary px-4">

                        💾 Enregistrer le frais

                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

{{-- ================================================================
     STYLES
================================================================ --}}

<style>
    .categorie-card {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
        padding: 20px 10px;
        text-align: center;
        transition: all 0.25s ease;
        color: #334155;
        min-height: 145px;
        cursor: pointer;
    }

    .categorie-card:hover {
        border-color: #0d6efd;
        background: #f0f7ff;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(13, 110, 253, 0.10);
    }

    .categorie-card.active {
        border-color: #0d6efd;
        background: #eaf3ff;
        color: #084298;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
    }

    .categorie-card:focus-visible,
    .frais-option:focus-visible {
        outline: 3px solid rgba(13, 110, 253, 0.35);
        outline-offset: 3px;
    }

    .categorie-icon {
        font-size: 30px;
        margin-bottom: 10px;
    }

    .frais-option {
        display: block;
        width: 100%;
        height: 100%;
        min-height: 60px;
        text-align: left;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 14px;
        background: #fff;
        color: #334155;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .frais-option:hover {
        background: #f8fafc;
        border-color: #0d6efd;
    }

    .frais-option.active {
        background: #eaf3ff;
        border: 2px solid #0d6efd;
        color: #084298;
    }

    .frais-option .frais-check {
        float: right;
        display: none;
        color: #0d6efd;
        font-weight: bold;
    }

    .frais-option.active .frais-check {
        display: inline;
    }

    #frais-container .alert {
        border-radius: 10px;
    }

    @media (max-width: 576px) {
        .categorie-card {
            padding: 15px 8px;
            min-height: 125px;
        }

        .categorie-icon {
            font-size: 25px;
        }
    }
</style>

{{-- ================================================================
     SCRIPT : CYCLES, CLASSES ET ÉCHÉANCES
================================================================ --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const cycle = document.getElementById('cycle');
    const classe = document.getElementById('classe');

    const ancienneClasse = @json(old('classe_id'));

    /*
    |--------------------------------------------------------------------------
    | Charger les classes selon le cycle sélectionné
    |--------------------------------------------------------------------------
    */

    async function chargerClasses(cycleId, classePreselectionnee = '') {

        classe.innerHTML = '';

        const optionInitiale = document.createElement('option');
        optionInitiale.value = '';
        optionInitiale.textContent = cycleId
            ? 'Chargement des classes...'
            : '-- Choisir une classe --';

        classe.appendChild(optionInitiale);

        if (!cycleId) {
            return;
        }

        try {

            const response = await fetch(
                `/cycles/${encodeURIComponent(cycleId)}/classes`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!response.ok) {
                throw new Error('Impossible de charger les classes.');
            }

            const data = await response.json();

            classe.innerHTML = '';

            const optionDefaut = document.createElement('option');
            optionDefaut.value = '';
            optionDefaut.textContent = '-- Choisir une classe --';

            classe.appendChild(optionDefaut);

            data.forEach(function (element) {

                const option = document.createElement('option');

                option.value = element.id;
                option.textContent = element.nom;

                if (
                    classePreselectionnee !== '' &&
                    String(element.id) === String(classePreselectionnee)
                ) {
                    option.selected = true;
                }

                classe.appendChild(option);

            });

        } catch (error) {

            console.error(error);

            classe.innerHTML = '';

            const optionErreur = document.createElement('option');
            optionErreur.value = '';
            optionErreur.textContent = '-- Erreur de chargement des classes --';

            classe.appendChild(optionErreur);

        }
    }

    if (cycle) {

        cycle.addEventListener('change', function () {
            chargerClasses(this.value);
        });

        if (cycle.value) {
            chargerClasses(cycle.value, ancienneClasse);
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Ajouter dynamiquement des échéances
    |--------------------------------------------------------------------------
    */

    let index = 1;

    const addEcheanceButton = document.getElementById('add-echeance');
    const echeancesContainer = document.getElementById('echeances-container');

    addEcheanceButton.addEventListener('click', function () {

        const row = document.createElement('div');

        row.classList.add(
            'row',
            'g-3',
            'mb-3',
            'echeance-item'
        );

        row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Libellé</label>
                <input
                    type="text"
                    name="echeances[${index}][libelle]"
                    class="form-control"
                    placeholder="Ex. : ${index + 1}e tranche"
                    required
                >
            </div>

            <div class="col-md-4">
                <label class="form-label">Montant (FCFA)</label>
                <input
                    type="number"
                    name="echeances[${index}][montant]"
                    class="form-control"
                    placeholder="Montant"
                    min="1"
                    step="1"
                    required
                >
            </div>

            <div class="col-md-4">
                <label class="form-label">Date limite</label>
                <input
                    type="date"
                    name="echeances[${index}][date_limite]"
                    class="form-control"
                    required
                >
            </div>

            <div class="col-12 text-end">
                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger supprimer-echeance">
                    🗑️ Supprimer cette tranche
                </button>
            </div>
        `;

        echeancesContainer.appendChild(row);

        index++;

    });

    /*
    |--------------------------------------------------------------------------
    | Supprimer une échéance ajoutée
    |--------------------------------------------------------------------------
    */

    echeancesContainer.addEventListener('click', function (event) {

        const bouton = event.target.closest('.supprimer-echeance');

        if (!bouton) {
            return;
        }

        const ligne = bouton.closest('.echeance-item');

        if (ligne) {
            ligne.remove();
        }

    });

});
</script>

{{-- ================================================================
     SCRIPT : CATÉGORIES ET FRAIS ASSOCIÉS
================================================================ --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const categorieInput = document.getElementById('categorie_recette_id');
    const nomInput = document.getElementById('nom');

    const categoriesContainer = document.getElementById('categories-container');
    const fraisContainer = document.getElementById('frais-container');

    const categorieSelectionnee = document.getElementById('categorie-selectionnee');

    
    const categories = @json($categoriesPourJs);

    /*
    |--------------------------------------------------------------------------
    | Afficher les frais d'une catégorie
    |--------------------------------------------------------------------------
    */

    function afficherFrais(categorieId, nomPreselectionne = '') {

        const categorie = categories.find(function (item) {
            return String(item.id) === String(categorieId);
        });

        fraisContainer.innerHTML = '';

        if (!categorie) {

            categorieInput.value = '';
            nomInput.value = '';

            categorieSelectionnee.textContent =
                'Sélectionnez une catégorie ci-dessus.';

            fraisContainer.innerHTML = `
                <div class="col-12 text-muted">
                    Choisissez une catégorie pour afficher ses frais.
                </div>
            `;

            return;
        }

        categorieInput.value = categorie.id;

        categorieSelectionnee.textContent =
            categorie.code + ' - ' + categorie.nom;

        if (!Array.isArray(categorie.frais) || categorie.frais.length === 0) {

            nomInput.value = '';

            fraisContainer.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-warning mb-0">
                        <strong>Aucun frais configuré.</strong><br>
                        Cette catégorie ne possède actuellement aucun frais
                        enregistré dans la base de données.
                    </div>
                </div>
            `;

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Créer une carte pour chaque frais associé
        |--------------------------------------------------------------------------
        */

        categorie.frais.forEach(function (nomFrais) {

            const col = document.createElement('div');

            col.className = 'col-12 col-md-6 col-lg-4';

            const bouton = document.createElement('button');

            bouton.type = 'button';
            bouton.className = 'frais-option';

            bouton.setAttribute('aria-pressed', 'false');

            bouton.innerHTML = `
                <span class="fw-semibold"></span>
                <span class="frais-check">✓ Sélectionné</span>
            `;

            bouton.querySelector('.fw-semibold').textContent = nomFrais;

            if (nomFrais === nomPreselectionne) {

                bouton.classList.add('active');
                bouton.setAttribute('aria-pressed', 'true');

            }

            bouton.addEventListener('click', function () {

                fraisContainer
                    .querySelectorAll('.frais-option')
                    .forEach(function (element) {

                        element.classList.remove('active');
                        element.setAttribute('aria-pressed', 'false');

                    });

                bouton.classList.add('active');
                bouton.setAttribute('aria-pressed', 'true');

                nomInput.value = nomFrais;

            });

            col.appendChild(bouton);
            fraisContainer.appendChild(col);

        });

        /*
        |--------------------------------------------------------------------------
        | Vérifier si le frais précédent existe toujours
        |--------------------------------------------------------------------------
        */

        const fraisExiste = categorie.frais.some(function (nomFrais) {
            return nomFrais === nomPreselectionne;
        });

        if (!fraisExiste) {
            nomInput.value = '';
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Sélectionner une catégorie
    |--------------------------------------------------------------------------
    */

    categoriesContainer
        .querySelectorAll('.categorie-card')
        .forEach(function (bouton) {

            bouton.addEventListener('click', function () {

                categoriesContainer
                    .querySelectorAll('.categorie-card')
                    .forEach(function (element) {

                        element.classList.remove('active');
                        element.setAttribute('aria-pressed', 'false');

                    });

                bouton.classList.add('active');
                bouton.setAttribute('aria-pressed', 'true');

                /*
                 * Changer de catégorie réinitialise le frais sélectionné.
                 */
                nomInput.value = '';

                afficherFrais(bouton.dataset.id);

            });

        });

    /*
    |--------------------------------------------------------------------------
    | Restaurer la sélection après une erreur de validation
    |--------------------------------------------------------------------------
    */

    const ancienneCategorie = categorieInput.value;
    const ancienNom = nomInput.value;

    if (ancienneCategorie) {

        const boutonCategorie = categoriesContainer.querySelector(
            `.categorie-card[data-id="${ancienneCategorie}"]`
        );

        if (boutonCategorie) {

            categoriesContainer
                .querySelectorAll('.categorie-card')
                .forEach(function (element) {

                    element.classList.remove('active');
                    element.setAttribute('aria-pressed', 'false');

                });

            boutonCategorie.classList.add('active');
            boutonCategorie.setAttribute('aria-pressed', 'true');

            afficherFrais(ancienneCategorie, ancienNom);

        }

    }

});
</script>

@endsection