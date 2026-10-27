@extends('tableau.neutre')

@section('title', 'Nouvel élève externe TD')

@section('content')

<div class="container py-4">

    {{-- Retour --}}
    <div class="mb-3">
        <button
            type="button"
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>
            Retour
        </button>
    </div>

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="fas fa-user-plus me-2"></i>
                Nouvel élève externe TD
            </h3>

            <p class="text-muted mb-0">
                Enregistrez l'élève et préparez automatiquement son inscription aux travaux dirigés.
            </p>
        </div>

        <a href="{{ route('eleves-externes-td.index') }}"
           class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-list me-2"></i>
            Liste des élèves
        </a>
    </div>

    {{-- Erreurs globales --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
            <div class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Impossible de valider le formulaire.
            </div>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Fermer"></button>
        </div>
    @endif

    <form action="{{ route('eleves-externes-td.store') }}"
          method="POST"
          id="formEleveExterneTd">

        @csrf

        {{-- ========================================================= --}}
        {{-- 1. INFORMATIONS PERSONNELLES                              --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-light border-0 rounded-top-4 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-id-card text-primary me-2"></i>
                    Informations personnelles
                </h5>
            </div>

            <div class="card-body p-4">

                <div class="row g-3">

                    {{-- Matricule --}}
                    <div class="col-md-6">
                        <label for="matricule" class="form-label fw-semibold">
                            Matricule
                            <span class="text-muted fw-normal">(facultatif)</span>
                        </label>

                        <input
                            type="text"
                            name="matricule"
                            id="matricule"
                            class="form-control @error('matricule') is-invalid @enderror"
                            value="{{ old('matricule') }}"
                            maxlength="255"
                            placeholder="Ex. TD-2026-0001">

                        @error('matricule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            Laissez vide pour générer automatiquement le matricule.
                        </small>
                    </div>

                    {{-- Sexe --}}
                    <div class="col-md-6">
                        <label for="sexe" class="form-label fw-semibold">
                            Sexe
                        </label>

                        <select
                            name="sexe"
                            id="sexe"
                            class="form-select @error('sexe') is-invalid @enderror">

                            <option value="">-- Sélectionner --</option>

                            <option value="Masculin"
                                @selected(old('sexe') === 'Masculin')>
                                Masculin
                            </option>

                            <option value="Féminin"
                                @selected(old('sexe') === 'Féminin')>
                                Féminin
                            </option>

                        </select>

                        @error('sexe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nom --}}
                    <div class="col-md-6">
                        <label for="nom" class="form-label fw-semibold">
                            Nom <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nom"
                            id="nom"
                            class="form-control @error('nom') is-invalid @enderror"
                            value="{{ old('nom') }}"
                            maxlength="255"
                            required>

                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Prénom --}}
                    <div class="col-md-6">
                        <label for="prenom" class="form-label fw-semibold">
                            Prénom(s) <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="prenom"
                            id="prenom"
                            class="form-control @error('prenom') is-invalid @enderror"
                            value="{{ old('prenom') }}"
                            maxlength="255"
                            required>

                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date de naissance --}}
                    <div class="col-md-6">
                        <label for="date_naissance" class="form-label fw-semibold">
                            Date de naissance
                        </label>

                        <input
                            type="date"
                            name="date_naissance"
                            id="date_naissance"
                            class="form-control @error('date_naissance') is-invalid @enderror"
                            value="{{ old('date_naissance') }}"
                            max="{{ now()->toDateString() }}">

                        @error('date_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Téléphone élève --}}
                    <div class="col-md-6">
                        <label for="telephone" class="form-label fw-semibold">
                            Téléphone de l'élève
                        </label>

                        <input
                            type="text"
                            name="telephone"
                            id="telephone"
                            class="form-control @error('telephone') is-invalid @enderror"
                            value="{{ old('telephone') }}"
                            maxlength="30"
                            placeholder="Numéro de téléphone">

                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Téléphone parent --}}
                    <div class="col-md-6">
                        <label for="telephone_parent" class="form-label fw-semibold">
                            Téléphone du parent
                        </label>

                        <input
                            type="text"
                            name="telephone_parent"
                            id="telephone_parent"
                            class="form-control @error('telephone_parent') is-invalid @enderror"
                            value="{{ old('telephone_parent') }}"
                            maxlength="30"
                            placeholder="Téléphone du responsable">

                        @error('telephone_parent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Adresse --}}
                    <div class="col-md-6">
                        <label for="adresse" class="form-label fw-semibold">
                            Adresse
                        </label>

                        <textarea
                            name="adresse"
                            id="adresse"
                            rows="2"
                            maxlength="1000"
                            class="form-control @error('adresse') is-invalid @enderror"
                            placeholder="Adresse de résidence">{{ old('adresse') }}</textarea>

                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Observations --}}
                    <div class="col-12">
                        <label for="observations" class="form-label fw-semibold">
                            Observations
                        </label>

                        <textarea
                            name="observations"
                            id="observations"
                            rows="3"
                            maxlength="5000"
                            class="form-control @error('observations') is-invalid @enderror"
                            placeholder="Informations complémentaires...">{{ old('observations') }}</textarea>

                        @error('observations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="col-md-6">
                        <label for="actif" class="form-label fw-semibold">
                            Statut
                        </label>

                        <select
                            name="actif"
                            id="actif"
                            class="form-select @error('actif') is-invalid @enderror">

                            <option value="1"
                                @selected(old('actif', '1') == '1')>
                                Actif
                            </option>

                            <option value="0"
                                @selected(old('actif') == '0')>
                                Inactif
                            </option>

                        </select>

                        @error('actif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 2. INSCRIPTION AUX TRAVAUX DIRIGÉS                       --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-light border-0 rounded-top-4 py-3">
                <h5 class="fw-bold mb-1">
                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                    Inscription aux travaux dirigés
                </h5>

                <small class="text-muted">
                    L'inscription sera créée automatiquement avec l'élève.
                </small>
            </div>

            <div class="card-body p-4">

                <div class="row g-3">

                    {{-- Année scolaire --}}
                    <div class="col-md-6">
                        <label for="annee_id" class="form-label fw-semibold">
                            Année scolaire <span class="text-danger">*</span>
                        </label>

                        <select
                            name="annee_id"
                            id="annee_id"
                            class="form-select @error('annee_id') is-invalid @enderror"
                            required>

                            <option value="">-- Sélectionner une année --</option>

                            @foreach ($annees as $annee)
                                <option
                                    value="{{ $annee->id }}"
                                    @selected((string) old('annee_id') === (string) $annee->id)>

                                    {{ $annee->libelle
                                        ?? $annee->nom
                                        ?? $annee->annee
                                        ?? 'Année #' . $annee->id }}

                                </option>
                            @endforeach

                        </select>

                        @error('annee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cycle --}}
                    <div class="col-md-6">
                        <label for="cycle_id" class="form-label fw-semibold">
                            Cycle <span class="text-danger">*</span>
                        </label>

                        <select
                            name="cycle_id"
                            id="cycle_id"
                            class="form-select @error('cycle_id') is-invalid @enderror"
                            required>

                            <option value="">-- Sélectionner un cycle --</option>

                            @foreach ($cycles as $cycle)
                                <option
                                    value="{{ $cycle->id }}"
                                    @selected((string) old('cycle_id') === (string) $cycle->id)>

                                    {{ $cycle->nom }}

                                </option>
                            @endforeach

                        </select>

                        @error('cycle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Classe --}}
                    <div class="col-md-6">
                        <label for="classe_id" class="form-label fw-semibold">
                            Classe des TD <span class="text-danger">*</span>
                        </label>

                        <select
                            name="classe_id"
                            id="classe_id"
                            class="form-select @error('classe_id') is-invalid @enderror"
                            required
                            disabled>

                            <option value="">
                                -- Sélectionner d'abord un cycle --
                            </option>

                            @foreach ($classes as $classe)
                                <option
                                    value="{{ $classe->id }}"
                                    data-cycle="{{ $classe->cycle_id }}"
                                    @selected((string) old('classe_id') === (string) $classe->id)>

                                    {{ $classe->nom }}

                                </option>
                            @endforeach

                        </select>

                        @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Frais TD --}}
                    <div class="col-md-6">
                        <label for="frais_id" class="form-label fw-semibold">
                            Frais TD <span class="text-danger">*</span>
                        </label>

                        <select
                            name="frais_id"
                            id="frais_id"
                            class="form-select @error('frais_id') is-invalid @enderror"
                            required
                            disabled>

                            <option value="">
                                -- Sélectionner d'abord l'année et la classe --
                            </option>

                            {{-- Les options seront chargées dynamiquement. --}}

                        </select>

                        @error('frais_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">
                            Seuls les frais TD existants pour l'année et la classe sélectionnées seront proposés.
                        </small>
                    </div>

                    {{-- Date d'inscription --}}
                    <div class="col-md-6">
                        <label for="date_inscription" class="form-label fw-semibold">
                            Date d'inscription <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="date_inscription"
                            id="date_inscription"
                            class="form-control @error('date_inscription') is-invalid @enderror"
                            value="{{ old('date_inscription', now()->toDateString()) }}"
                            max="{{ now()->toDateString() }}"
                            required>

                        @error('date_inscription')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Récapitulatif du frais --}}
                <div id="resumeFrais"
                     class="alert alert-info border-0 rounded-3 mt-4 mb-0 d-none">

                    <div class="d-flex align-items-start gap-3">
                        <i class="fas fa-receipt fa-lg mt-1"></i>

                        <div>
                            <div class="fw-bold">Frais TD sélectionné</div>

                            <div id="libelleFrais">—</div>

                            <div class="mt-1">
                                Montant :
                                <strong id="montantFrais">—</strong>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 3. INFORMATIONS IMPORTANTES                               --}}
        {{-- ========================================================= --}}

        <div class="alert alert-info border-0 rounded-4 mb-4">
            <div class="d-flex gap-3">
                <i class="fas fa-info-circle fa-lg mt-1"></i>

                <div>
                    <strong>Enregistrement automatique</strong>

                    <p class="mb-0 mt-1">
                        En validant ce formulaire, l'application enregistrera
                        l'élève externe et créera son inscription TD pour
                        l'année et la classe sélectionnées. Le montant de
                        l'inscription sera repris du frais existant.
                        Aucun paiement ne sera enregistré à cette étape.
                    </p>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 4. BOUTONS                                                --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 flex-wrap mb-4">

            <a href="{{ route('eleves-externes-td.index') }}"
               class="btn btn-light border rounded-pill px-4">

                <i class="fas fa-times me-2"></i>
                Annuler
            </a>

            <button
                type="submit"
                id="btnEnregistrer"
                class="btn btn-primary rounded-pill px-4">

                <i class="fas fa-save me-2"></i>
                Enregistrer l'élève et son inscription
            </button>

        </div>

    </form>
</div>

{{-- ============================================================= --}}
{{-- JAVASCRIPT : CASCADE ANNÉE / CYCLE / CLASSE / FRAIS            --}}
{{-- ============================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const anneeSelect = document.getElementById('annee_id');
    const cycleSelect = document.getElementById('cycle_id');
    const classeSelect = document.getElementById('classe_id');
    const fraisSelect = document.getElementById('frais_id');

    const resumeFrais = document.getElementById('resumeFrais');
    const libelleFrais = document.getElementById('libelleFrais');
    const montantFrais = document.getElementById('montantFrais');

    const form = document.getElementById('formEleveExterneTd');
    const bouton = document.getElementById('btnEnregistrer');

    const anciennesClasses = Array.from(classeSelect.options)
        .filter(option => option.value !== '');

    const ancienneClasse = @json(old('classe_id', ''));
    const ancienFrais = @json(old('frais_id', ''));

    const ancienneAnnee = @json(old('annee_id', ''));
    const ancienCycle = @json(old('cycle_id', ''));

    let requeteFrais = null;

    /**
     * Vider la sélection des frais.
     */
    function viderFrais(message = '-- Sélectionner d’abord l’année et la classe --') {

        fraisSelect.innerHTML = '';

        const option = document.createElement('option');
        option.value = '';
        option.textContent = message;

        fraisSelect.appendChild(option);
        fraisSelect.disabled = true;

        resumeFrais.classList.add('d-none');
        libelleFrais.textContent = '—';
        montantFrais.textContent = '—';
    }

    /**
     * Filtrer les classes selon le cycle sélectionné.
     */
    function filtrerClasses() {

        const cycleId = cycleSelect.value;
        const classePrecedente = classeSelect.value || ancienneClasse;

        classeSelect.innerHTML = '';

        const optionInitiale = document.createElement('option');
        optionInitiale.value = '';
        optionInitiale.textContent = cycleId
            ? '-- Sélectionner une classe --'
            : '-- Sélectionner d’abord un cycle --';

        classeSelect.appendChild(optionInitiale);

        let classeRetenue = false;

        anciennesClasses.forEach(function (option) {

            if (option.dataset.cycle === cycleId && cycleId !== '') {

                const copie = option.cloneNode(true);

                classeSelect.appendChild(copie);

                if (String(copie.value) === String(classePrecedente)) {
                    copie.selected = true;
                    classeRetenue = true;
                }
            }
        });

        classeSelect.disabled = !cycleId;

        if (!classeRetenue) {
            classeSelect.value = '';
        }

        viderFrais();
    }

    /**
     * Charger les frais TD correspondant à l’année et à la classe.
     */
    async function chargerFrais(restaurerAncienFrais = false) {

        const anneeId = anneeSelect.value;
        const cycleId = cycleSelect.value;
        const classeId = classeSelect.value;

        viderFrais('-- Chargement des frais TD --');

        if (!anneeId || !cycleId || !classeId) {
            viderFrais('-- Sélectionner l’année et la classe --');
            return;
        }

        /*
         * Annuler une requête précédente si l'utilisateur change
         * rapidement ses sélections.
         */
        if (requeteFrais) {
            requeteFrais.abort();
        }

        requeteFrais = new AbortController();

        fraisSelect.disabled = true;

        try {

            const url = new URL(
                @json(route('eleves-externes-td.frais-disponibles')),
                window.location.origin
            );

            url.searchParams.set('annee_id', anneeId);
            url.searchParams.set('cycle_id', cycleId);
            url.searchParams.set('classe_id', classeId);

            const response = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: requeteFrais.signal
            });

            const donnees = await response.json();

            if (!response.ok) {
                throw new Error(
                    donnees.message || 'Impossible de charger les frais TD.'
                );
            }

            fraisSelect.innerHTML = '';

            const optionInitiale = document.createElement('option');
            optionInitiale.value = '';

            if (!donnees.frais || donnees.frais.length === 0) {

                optionInitiale.textContent =
                    '-- Aucun frais TD disponible pour cette classe --';

                fraisSelect.appendChild(optionInitiale);
                fraisSelect.disabled = true;

                return;
            }

            optionInitiale.textContent = '-- Sélectionner un frais TD --';
            fraisSelect.appendChild(optionInitiale);

            donnees.frais.forEach(function (frais) {

                const option = document.createElement('option');

                option.value = frais.id;

                option.dataset.libelle = frais.libelle;
                option.dataset.montant = frais.montant;

                option.textContent =
                    frais.libelle + ' — ' +
                    formaterMontant(frais.montant);

                fraisSelect.appendChild(option);
            });

            fraisSelect.disabled = false;

            /*
             * Restaurer l'ancien frais après une erreur de validation,
             * uniquement s'il est encore disponible.
             */
            if (restaurerAncienFrais && ancienFrais) {

                const optionExistante = Array.from(fraisSelect.options)
                    .find(option => String(option.value) === String(ancienFrais));

                if (optionExistante) {
                    fraisSelect.value = ancienFrais;
                    afficherResumeFrais();
                }
            }

        } catch (erreur) {

            if (erreur.name === 'AbortError') {
                return;
            }

            console.error(erreur);

            viderFrais('-- Erreur de chargement des frais TD --');

        }
    }

    /**
     * Afficher le montant du frais sélectionné.
     */
    function afficherResumeFrais() {

        const option = fraisSelect.options[fraisSelect.selectedIndex];

        if (!option || !option.value) {
            resumeFrais.classList.add('d-none');
            return;
        }

        libelleFrais.textContent = option.dataset.libelle || option.textContent;

        montantFrais.textContent = formaterMontant(option.dataset.montant);

        resumeFrais.classList.remove('d-none');
    }

    /**
     * Formater le montant.
     */
    function formaterMontant(montant) {

        const valeur = Number(montant);

        if (!Number.isFinite(valeur)) {
            return montant ?? '—';
        }

        return new Intl.NumberFormat('fr-FR', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(valeur) + ' FCFA';
    }

    /*
     * Changement d’année :
     * le frais doit être rechargé pour la nouvelle année.
     */
    anneeSelect.addEventListener('change', function () {
        chargerFrais(false);
    });

    /*
     * Changement de cycle :
     * filtrer les classes, puis recharger les frais.
     */
    cycleSelect.addEventListener('change', function () {

        filtrerClasses();

        /*
         * On ne recharge pas immédiatement : la classe doit d’abord
         * être choisie pour éviter de proposer un frais incorrect.
         */
    });

    /*
     * Changement de classe :
     * recharger les frais de l’année et de la classe.
     */
    classeSelect.addEventListener('change', function () {
        chargerFrais(false);
    });

    /*
     * Changement de frais :
     * actualiser le récapitulatif financier.
     */
    fraisSelect.addEventListener('change', afficherResumeFrais);

    /*
     * Initialisation : conserver les valeurs soumises en cas d'erreur.
     */
    if (ancienCycle) {
        cycleSelect.value = ancienCycle;
    }

    filtrerClasses();

    if (ancienneClasse) {
        const optionClasse = Array.from(classeSelect.options)
            .find(option => String(option.value) === String(ancienneClasse));

        if (optionClasse) {
            classeSelect.value = ancienneClasse;
        }
    }

    if (ancienneAnnee) {
        anneeSelect.value = ancienneAnnee;
    }

    if (anneeSelect.value && classeSelect.value) {
        chargerFrais(true);
    }

    /*
     * Éviter les doubles clics pendant l'envoi du formulaire.
     */
    form.addEventListener('submit', function (event) {

        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
            return;
        }

        if (!anneeSelect.value ||
            !cycleSelect.value ||
            !classeSelect.value ||
            !fraisSelect.value) {

            event.preventDefault();

            alert('Veuillez sélectionner l’année scolaire, le cycle, la classe et le frais TD.');

            return;
        }

        bouton.disabled = true;

        bouton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
            'Enregistrement en cours...';
    });

});
</script>

@endsection