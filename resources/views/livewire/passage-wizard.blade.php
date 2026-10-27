<div class="container-fluid py-4"
     style="background-color:#f8f9fa; min-height:100vh;">

    {{-- ============================================================
         EN-TÊTE
    ============================================================= --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <h4 class="fw-bold text-primary mb-1">
                        🎓 Gestion des passages et redoublements
                    </h4>

                    <small class="text-muted">
                        Traitement des élèves d'une année scolaire vers l'année suivante
                    </small>
                </div>

                <div class="d-flex align-items-center gap-2">

                    {{-- HISTORIQUE --}}
                    @if(!empty($historique_passages))

                        <div class="dropdown">

                            <button class="btn btn-sm btn-outline-danger dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                ↩ Annuler un traitement

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow rounded-3">

                                @foreach($historique_passages as $i => $passage)

                                    @php

                                        $passageId = is_object($passage)
                                            ? $passage->id
                                            : ($passage['id'] ?? $i);

                                        $passageLabel = is_object($passage)
                                            ? trim(
                                                ($passage->eleve->nom ?? '') . ' ' .
                                                ($passage->eleve->prenom ?? '')
                                            ) . ' → ' .
                                            ($passage->classe->nom ?? 'Classe')
                                            : ($passage['label'] ?? 'Traitement #' . ($i + 1));

                                    @endphp

                                    <li>

                                        <button type="button"
                                                class="dropdown-item text-danger"
                                                wire:click="annulerPassage({{ $passageId }})"
                                                wire:confirm="Annuler le traitement : {{ $passageLabel }} ?">

                                            <i class="bi bi-arrow-counterclockwise me-2"></i>

                                            {{ $passageLabel }}

                                        </button>

                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        Étape {{ $step }} / 5
                    </span>

                </div>

            </div>

            {{-- BARRE DE PROGRESSION --}}
            <div class="progress rounded-pill" style="height:8px;">

                <div class="progress-bar bg-primary"
                     role="progressbar"
                     style="width: {{ ($step / 5) * 100 }}%;">
                </div>

            </div>

        </div>
    </div>


    {{-- ============================================================
         MESSAGES
    ============================================================= --}}

    @if($errorMessage)

        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3"
             role="alert"
             wire:key="alert-error-{{ md5($errorMessage) }}">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <strong>Erreur :</strong>

            {{ $errorMessage }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($warningMessage)

        <div class="alert alert-warning alert-dismissible fade show shadow-sm rounded-3"
             role="alert"
             wire:key="alert-warning-{{ md5($warningMessage) }}">

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            <strong>Attention :</strong>

            {{ $warningMessage }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($successMessage)

        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3"
             role="alert"
             wire:key="alert-success-{{ md5($successMessage) }}">

            <i class="bi bi-check-circle-fill me-2"></i>

            <strong>Succès :</strong>

            {{ $successMessage }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif



    {{-- ============================================================
         ÉTAPE 1 : PARAMÈTRES
    ============================================================= --}}

    @if($step == 1)

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <div class="d-flex align-items-center">

                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">

                        <i class="bi bi-sliders fs-4"></i>

                    </div>

                    <div>

                        <h5 class="fw-bold mb-1">
                            1. Paramètres du traitement
                        </h5>

                        <small class="text-muted">
                            Sélectionnez l'année, le cycle et la classe à traiter.
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <div class="row g-4">

                    {{-- ANNÉE SOURCE --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Année scolaire source
                        </label>

                        <select class="form-select form-select-lg rounded-3"
                                wire:model.live="annee_source_id">

                            <option value="">
                                -- Année source --
                            </option>

                            @foreach($annees as $annee)

                                <option value="{{ $annee->id }}">
                                    {{ $annee->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- ANNÉE ACCUEIL --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Année scolaire d'accueil
                        </label>

                        <select class="form-select form-select-lg rounded-3"
                                wire:model.live="annee_accueil_id">

                            <option value="">
                                -- Année d'accueil --
                            </option>

                            @foreach($annees as $annee)

                                <option value="{{ $annee->id }}">
                                    {{ $annee->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CYCLE --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Cycle
                        </label>

                        <select class="form-select form-select-lg rounded-3"
                                wire:model.live="cycle_id">

                            <option value="">
                                -- Sélectionner le cycle --
                            </option>

                            @foreach($cycles as $cycle)

                                <option value="{{ $cycle->id }}">
                                    {{ $cycle->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CLASSE --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Classe source
                        </label>

                        <select class="form-select form-select-lg rounded-3"
                                wire:model.live="classe_id">

                            <option value="">
                                -- Sélectionner la classe --
                            </option>

                            @foreach($classes as $classe)

                                <option value="{{ $classe->id }}">
                                    {{ $classe->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- RÈGLE --}}
                <div class="alert alert-info border-0 rounded-4 mt-4 mb-0">

                    <div class="d-flex">

                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>

                        <div>

                            <strong>Règle de traitement :</strong>

                            <ul class="mb-0 mt-2">

                                <li>
                                    Moyenne annuelle
                                    <strong>≥ {{ $seuil_passage }}/20</strong>
                                    →
                                    <span class="text-success fw-bold">
                                        PASSÉ / PASSANT
                                    </span>
                                </li>

                                <li>
                                    Moyenne annuelle
                                    <strong>&lt; {{ $seuil_passage }}/20</strong>
                                    →
                                    <span class="text-danger fw-bold">
                                        REDOUBLANT
                                    </span>
                                </li>

                                <li>
                                    Un élève sans moyenne annuelle calculée
                                    n'est pas traité automatiquement.
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>


                <div class="text-end mt-4">

                    <button type="button"
                            class="btn btn-primary btn-lg px-4 rounded-3"
                            wire:click="nextStep"
                            wire:loading.attr="disabled"
                            @disabled(!$cycle_id || !$classe_id || !$annee_source_id || !$annee_accueil_id)>

                        <span wire:loading.remove wire:target="nextStep">

                            Continuer

                            <i class="bi bi-arrow-right ms-1"></i>

                        </span>

                        <span wire:loading wire:target="nextStep">

                            <span class="spinner-border spinner-border-sm me-2"></span>

                            Chargement...

                        </span>

                    </button>

                </div>

            </div>

        </div>

    @endif



    {{-- ============================================================
         ÉTAPE 2 : SÉLECTION DES ÉLÈVES
    ============================================================= --}}

    @if($step == 2)

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-0 p-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            👨‍🎓 2. Élèves à traiter
                        </h5>

                        <small class="text-muted">
                            Vérifiez la moyenne annuelle, la décision et la destination.
                        </small>

                    </div>

                    <div class="text-center">

                        <div class="badge bg-primary fs-5 px-4 py-2 rounded-pill">

                            {{ count($selected_eleves) }}

                            /

                            {{ $eleves->count() }}

                        </div>

                        <small class="d-block text-muted mt-1">
                            sélectionnés
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                {{-- SELECT ALL --}}
                @if($eleves->count())

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div class="form-check">

                            <input type="checkbox"
                                   id="selectAll"
                                   class="form-check-input"
                                   wire:model.live="selectAll">

                            <label for="selectAll"
                                   class="form-check-label fw-bold">

                                Sélectionner tous les élèves

                            </label>

                        </div>

                        <span class="text-muted small">

                            Seuil :

                            <strong>{{ $seuil_passage }}/20</strong>

                        </span>

                    </div>

                @endif


                {{-- TABLEAU --}}
                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width:50px;">
                                    #
                                </th>

                                <th style="width:70px;">
                                    Sélection
                                </th>

                                <th>
                                    Matricule
                                </th>

                                <th>
                                    Élève
                                </th>

                                <th class="text-center">
                                    Moyenne annuelle
                                </th>

                                <th class="text-center">
                                    Décision
                                </th>

                                <th class="text-center">
                                    Destination
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($eleves as $index => $insc)

                                @php

                                    $data = $elevesData[$insc->id] ?? [
                                        'moyenne' => null,
                                        'decision' => 'non_calculée',
                                    ];

                                    $moyenne = $data['moyenne'] ?? null;

                                    $decisionBrute = strtolower(
                                        trim($data['decision'] ?? '')
                                    );

                                    $estPassant = in_array($decisionBrute, [
                                        'passé',
                                        'passe',
                                        'passant',
                                        'admis'
                                    ], true);

                                    $estRedoublant = in_array($decisionBrute, [
                                        'redoublant',
                                        'redoublé',
                                        'redouble',
                                        'redoublement'
                                    ], true);

                                    if (
                                        !$estPassant &&
                                        !$estRedoublant &&
                                        $moyenne !== null
                                    ) {
                                        if (
                                            (float) $moyenne >=
                                            (float) $seuil_passage
                                        ) {
                                            $estPassant = true;
                                        } else {
                                            $estRedoublant = true;
                                        }
                                    }

                                    $classeDestination = null;

                                    if (
                                        $estPassant &&
                                        $classe_accueil_id &&
                                        $classes_accueil
                                    ) {
                                        $classeDestination =
                                            $classes_accueil->firstWhere(
                                                'id',
                                                (int) $classe_accueil_id
                                            );
                                    }

                                @endphp


                                <tr wire:key="eleve-row-{{ $insc->id }}">

                                    {{-- NUMÉRO --}}
                                    <td class="fw-bold text-muted">
                                        {{ $index + 1 }}
                                    </td>


                                    {{-- CHECKBOX --}}
                                    <td>

                                        <input type="checkbox"
                                               class="form-check-input"
                                               wire:model.live="selected_eleves"
                                               value="{{ $insc->id }}">

                                    </td>


                                    {{-- MATRICULE --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $insc->eleve->matricule ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- ÉLÈVE --}}
                                    <td>

                                        <div class="fw-bold text-dark">

                                            {{ $insc->eleve->nom ?? '' }}

                                            {{ $insc->eleve->prenom ?? '' }}

                                        </div>

                                    </td>


                                    {{-- MOYENNE --}}
                                    <td class="text-center">

                                        @if($moyenne !== null)

                                            <span class="fw-bold fs-5
                                                {{ (float) $moyenne >= (float) $seuil_passage
                                                    ? 'text-success'
                                                    : 'text-danger' }}">

                                                {{ number_format(
                                                    (float) $moyenne,
                                                    2,
                                                    ',',
                                                    ' '
                                                ) }}

                                            </span>

                                            <small class="text-muted">
                                                /20
                                            </small>

                                        @else

                                            <span class="badge bg-secondary">
                                                Non calculée
                                            </span>

                                        @endif

                                    </td>


                                    {{-- DÉCISION --}}
                                    <td class="text-center">

                                        @if($estPassant)

                                            <span class="badge bg-success rounded-pill px-3 py-2">

                                                <i class="bi bi-check-circle me-1"></i>

                                                PASSÉ

                                            </span>

                                        @elseif($estRedoublant)

                                            <span class="badge bg-danger rounded-pill px-3 py-2">

                                                <i class="bi bi-arrow-repeat me-1"></i>

                                                REDOUBLANT

                                            </span>

                                        @else

                                            <span class="badge bg-secondary rounded-pill px-3 py-2">

                                                <i class="bi bi-question-circle me-1"></i>

                                                NON CALCULÉE

                                            </span>

                                        @endif

                                    </td>


                                    {{-- DESTINATION --}}
                                    <td class="text-center">

                                        @if($estPassant)

                                            @if($classeDestination)

                                                <span class="text-success fw-semibold">

                                                    <i class="bi bi-arrow-right-circle me-1"></i>

                                                    {{ $classeDestination->nom }}

                                                </span>

                                            @else

                                                <span class="text-warning fw-semibold">

                                                    <i class="bi bi-hourglass-split me-1"></i>

                                                    Classe supérieure à sélectionner

                                                </span>

                                            @endif

                                        @elseif($estRedoublant)

                                            <span class="text-danger fw-semibold">

                                                <i class="bi bi-arrow-repeat me-1"></i>

                                                {{ $insc->classe->nom ?? 'Même classe' }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                Aucun traitement
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7">

                                        <div class="alert alert-warning mb-0">

                                            <i class="bi bi-exclamation-triangle me-2"></i>

                                            Aucun élève trouvé pour cette classe et cette année.

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- LÉGENDE --}}
                @if($eleves->count())

                    <div class="row g-3 mt-4">

                        <div class="col-md-4">

                            <div class="p-3 bg-success bg-opacity-10 rounded-3">

                                <span class="badge bg-success me-2">
                                    PASSÉ
                                </span>

                                <small>
                                    Moyenne ≥ {{ $seuil_passage }}/20
                                </small>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="p-3 bg-danger bg-opacity-10 rounded-3">

                                <span class="badge bg-danger me-2">
                                    REDOUBLANT
                                </span>

                                <small>
                                    Moyenne &lt; {{ $seuil_passage }}/20
                                </small>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="p-3 bg-secondary bg-opacity-10 rounded-3">

                                <span class="badge bg-secondary me-2">
                                    NON CALCULÉE
                                </span>

                                <small>
                                    Aucun traitement automatique
                                </small>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- BOUTONS --}}
                <div class="d-flex justify-content-between mt-4">

                    <button type="button"
                            class="btn btn-outline-secondary px-4 rounded-3"
                            wire:click="$set('step', 1)">

                        <i class="bi bi-arrow-left me-1"></i>

                        Retour

                    </button>


                    <button type="button"
                            class="btn btn-success px-4 rounded-3"
                            wire:click="nextStep"
                            wire:loading.attr="disabled"
                            @disabled(count($selected_eleves) === 0)>

                        <span wire:loading.remove wire:target="nextStep">

                            Continuer

                            <i class="bi bi-arrow-right ms-1"></i>

                        </span>

                        <span wire:loading wire:target="nextStep">

                            <span class="spinner-border spinner-border-sm me-2"></span>

                            Chargement...

                        </span>

                    </button>

                </div>

            </div>

        </div>

    @endif



    {{-- ============================================================
         ÉTAPE 3 : CLASSE D'ACCUEIL
    ============================================================= --}}

    @if($step == 3)

        @php

            $elevesSelectionnes = $eleves->whereIn(
                'id',
                array_map('intval', $selected_eleves)
            );


            $nombrePassants = $elevesSelectionnes
                ->filter(function ($insc) use ($elevesData, $seuil_passage) {

                    $data = $elevesData[$insc->id] ?? [];

                    $moyenne = $data['moyenne'] ?? null;

                    $decision = strtolower(
                        trim($data['decision'] ?? '')
                    );

                    if (in_array($decision, [
                        'passé',
                        'passe',
                        'passant',
                        'admis'
                    ], true)) {
                        return true;
                    }

                    return $moyenne !== null &&
                           (float) $moyenne >= (float) $seuil_passage;

                })
                ->count();


            $nombreRedoublants = $elevesSelectionnes
                ->filter(function ($insc) use ($elevesData, $seuil_passage) {

                    $data = $elevesData[$insc->id] ?? [];

                    $moyenne = $data['moyenne'] ?? null;

                    $decision = strtolower(
                        trim($data['decision'] ?? '')
                    );

                    if (in_array($decision, [
                        'redoublant',
                        'redoublé',
                        'redouble',
                        'redoublement'
                    ], true)) {
                        return true;
                    }

                    return $moyenne !== null &&
                           (float) $moyenne < (float) $seuil_passage;

                })
                ->count();


            $nombreNonCalcul = $elevesSelectionnes
                ->filter(function ($insc) use ($elevesData) {

                    $data = $elevesData[$insc->id] ?? [];

                    return ($data['moyenne'] ?? null) === null;

                })
                ->count();


            $classeSource = $elevesSelectionnes
                ->first()?->classe;


            $classeDestinationSelectionnee = $classe_accueil_id
                ? $classes_accueil->firstWhere(
                    'id',
                    (int) $classe_accueil_id
                )
                : null;

        @endphp


        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-0 p-4">

                <h5 class="fw-bold mb-1">
                    🏫 3. Classe d'accueil
                </h5>

                <small class="text-muted">
                    La classe d'accueil concerne uniquement les élèves passés.
                    Les redoublants restent dans leur classe actuelle.
                </small>

            </div>


            <div class="card-body p-4">

                {{-- RÉSUMÉ --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-4">

                        <div class="card border-0 bg-success bg-opacity-10 rounded-4">

                            <div class="card-body text-center">

                                <div class="fs-2 fw-bold text-success">
                                    {{ $nombrePassants }}
                                </div>

                                <div class="fw-semibold">
                                    Élève(s) passé(s)
                                </div>

                                <small class="text-muted">
                                    Vers la classe supérieure
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="card border-0 bg-danger bg-opacity-10 rounded-4">

                            <div class="card-body text-center">

                                <div class="fs-2 fw-bold text-danger">
                                    {{ $nombreRedoublants }}
                                </div>

                                <div class="fw-semibold">
                                    Redoublant(s)
                                </div>

                                <small class="text-muted">
                                    Même classe
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="card border-0 bg-secondary bg-opacity-10 rounded-4">

                            <div class="card-body text-center">

                                <div class="fs-2 fw-bold text-secondary">
                                    {{ $nombreNonCalcul }}
                                </div>

                                <div class="fw-semibold">
                                    Non calculé(s)
                                </div>

                                <small class="text-muted">
                                    Non traités
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- CLASSE DES PASSÉS --}}
                @if($nombrePassants > 0)

                    <div class="alert alert-success border-0 rounded-4">

                        <div class="d-flex">

                            <i class="bi bi-arrow-up-circle-fill fs-3 me-3"></i>

                            <div class="flex-grow-1">

                                <h6 class="fw-bold mb-2">
                                    Élèves passés
                                </h6>

                                <p class="mb-3">

                                    Les {{ $nombrePassants }} élève(s)
                                    ayant une moyenne annuelle supérieure ou égale à
                                    {{ $seuil_passage }}/20 seront inscrits
                                    dans la classe supérieure.

                                </p>


                                <label class="form-label fw-semibold">

                                    Classe d'accueil

                                </label>


                                <select class="form-select form-select-lg rounded-3"
                                        wire:model.live="classe_accueil_id">

                                    <option value="">
                                        -- Sélectionner la classe supérieure --
                                    </option>

                                    @foreach($classes_accueil as $c)

                                        <option value="{{ $c->id }}">

                                            {{ $c->nom }}

                                            @if(isset($c->niveau))
                                                — {{ $c->niveau }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @if($classes_accueil->isEmpty())

                                    <div class="alert alert-warning mt-3 mb-0">

                                        <i class="bi bi-exclamation-triangle me-2"></i>

                                        Aucune classe supérieure disponible
                                        pour cette classe.

                                    </div>

                                @endif


                                @if($classeDestinationSelectionnee)

                                    <div class="mt-3 p-3 bg-white rounded-3 border">

                                        <i class="bi bi-check-circle-fill text-success me-2"></i>

                                        Classe d'accueil sélectionnée :

                                        <strong class="text-primary">

                                            {{ $classeDestinationSelectionnee->nom }}

                                        </strong>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif


                {{-- REDOUBLANTS --}}
                @if($nombreRedoublants > 0)

                    <div class="alert alert-warning border-0 rounded-4">

                        <div class="d-flex">

                            <i class="bi bi-arrow-repeat fs-3 me-3"></i>

                            <div>

                                <h6 class="fw-bold">
                                    Élèves redoublants
                                </h6>

                                <p class="mb-0">

                                    Les {{ $nombreRedoublants }} élève(s)
                                    redoublant(s) seront réinscrits
                                    dans leur classe actuelle :

                                    <strong>
                                        {{ $classeSource?->nom ?? 'Classe source' }}
                                    </strong>.

                                </p>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- NON CALCULÉS --}}
                @if($nombreNonCalcul > 0)

                    <div class="alert alert-secondary border-0 rounded-4">

                        <i class="bi bi-info-circle me-2"></i>

                        {{ $nombreNonCalcul }} élève(s)
                        n'ont pas de moyenne annuelle calculée.

                        Ils ne seront pas inscrits automatiquement.

                    </div>

                @endif


                {{-- APERÇU --}}
                <div class="card border rounded-4 mt-4">

                    <div class="card-header bg-light border-0">

                        <strong>
                            📋 Aperçu des décisions
                        </strong>

                    </div>


                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Élève
                                    </th>

                                    <th class="text-center">
                                        Moyenne
                                    </th>

                                    <th class="text-center">
                                        Décision
                                    </th>

                                    <th>
                                        Destination
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($elevesSelectionnes as $insc)

                                    @php

                                        $data = $elevesData[$insc->id] ?? [
                                            'moyenne' => null,
                                            'decision' => 'non_calculée',
                                        ];

                                        $moyenne = $data['moyenne'] ?? null;

                                        $decision = strtolower(
                                            trim($data['decision'] ?? '')
                                        );

                                        $estPassant = in_array($decision, [
                                            'passé',
                                            'passe',
                                            'passant',
                                            'admis'
                                        ], true);

                                        $estRedoublant = in_array($decision, [
                                            'redoublant',
                                            'redoublé',
                                            'redouble',
                                            'redoublement'
                                        ], true);

                                        if (
                                            !$estPassant &&
                                            !$estRedoublant &&
                                            $moyenne !== null
                                        ) {

                                            if (
                                                (float) $moyenne >=
                                                (float) $seuil_passage
                                            ) {
                                                $estPassant = true;
                                            } else {
                                                $estRedoublant = true;
                                            }

                                        }

                                    @endphp


                                    <tr wire:key="preview-step3-{{ $insc->id }}">

                                        <td class="fw-semibold">

                                            {{ $insc->eleve->nom ?? '' }}

                                            {{ $insc->eleve->prenom ?? '' }}

                                        </td>


                                        <td class="text-center">

                                            @if($moyenne !== null)

                                                <strong>

                                                    {{ number_format(
                                                        (float) $moyenne,
                                                        2,
                                                        ',',
                                                        ' '
                                                    ) }}/20

                                                </strong>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td class="text-center">

                                            @if($estPassant)

                                                <span class="badge bg-success">
                                                    PASSÉ
                                                </span>

                                            @elseif($estRedoublant)

                                                <span class="badge bg-warning text-dark">
                                                    REDOUBLANT
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    NON CALCULÉE
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if($estPassant)

                                                @if($classeDestinationSelectionnee)

                                                    <span class="text-success fw-semibold">

                                                        <i class="bi bi-arrow-right me-1"></i>

                                                        {{ $classeDestinationSelectionnee->nom }}

                                                    </span>

                                                @else

                                                    <span class="text-warning fw-semibold">

                                                        Classe supérieure à sélectionner

                                                    </span>

                                                @endif

                                            @elseif($estRedoublant)

                                                <span class="text-danger fw-semibold">

                                                    <i class="bi bi-arrow-repeat me-1"></i>

                                                    {{ $insc->classe->nom ?? 'Même classe' }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    Aucun traitement
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- BOUTONS --}}
                <div class="d-flex justify-content-between mt-4">

                    <button type="button"
                            class="btn btn-outline-secondary px-4 rounded-3"
                            wire:click="$set('step', 2)">

                        <i class="bi bi-arrow-left me-1"></i>

                        Retour

                    </button>


                    <button type="button"
                            class="btn btn-warning px-4 rounded-3"
                            wire:click="confirm"
                            wire:loading.attr="disabled"
                            @disabled($nombrePassants > 0 && !$classe_accueil_id)>

                        <span wire:loading.remove wire:target="confirm">

                            Vérifier les décisions

                            <i class="bi bi-arrow-right ms-1"></i>

                        </span>

                        <span wire:loading wire:target="confirm">

                            <span class="spinner-border spinner-border-sm me-2"></span>

                            Vérification...

                        </span>

                    </button>

                </div>

            </div>

        </div>

    @endif



    {{-- ============================================================
         ÉTAPE 4 : CONFIRMATION
    ============================================================= --}}

    @if($step == 4)

        @php

            $elevesSelectionnes = $eleves->whereIn(
                'id',
                array_map('intval', $selected_eleves)
            );


            $nombrePassants = $elevesSelectionnes
                ->filter(function ($insc) use ($elevesData, $seuil_passage) {

                    $data = $elevesData[$insc->id] ?? [];

                    $moyenne = $data['moyenne'] ?? null;

                    $decision = strtolower(
                        trim($data['decision'] ?? '')
                    );

                    if (in_array($decision, [
                        'passé',
                        'passe',
                        'passant',
                        'admis'
                    ], true)) {
                        return true;
                    }

                    return $moyenne !== null &&
                           (float) $moyenne >= (float) $seuil_passage;

                })
                ->count();


            $nombreRedoublants = $elevesSelectionnes
                ->filter(function ($insc) use ($elevesData, $seuil_passage) {

                    $data = $elevesData[$insc->id] ?? [];

                    $moyenne = $data['moyenne'] ?? null;

                    $decision = strtolower(
                        trim($data['decision'] ?? '')
                    );

                    if (in_array($decision, [
                        'redoublant',
                        'redoublé',
                        'redouble',
                        'redoublement'
                    ], true)) {
                        return true;
                    }

                    return $moyenne !== null &&
                           (float) $moyenne < (float) $seuil_passage;

                })
                ->count();


            $nombreNonCalcul = $elevesSelectionnes
                ->filter(function ($insc) use ($elevesData) {

                    $data = $elevesData[$insc->id] ?? [];

                    return ($data['moyenne'] ?? null) === null;

                })
                ->count();


            $classeDestinationSelectionnee = $classe_accueil_id
                ? $classes_accueil->firstWhere(
                    'id',
                    (int) $classe_accueil_id
                )
                : null;

        @endphp


        <div class="card border-0 shadow rounded-4">

            <div class="card-body p-5">

                <div class="text-center mb-4">

                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex p-4 mb-3">

                        <i class="bi bi-exclamation-triangle-fill fs-1"></i>

                    </div>

                    <h3 class="fw-bold">
                        Confirmation du traitement
                    </h3>

                    <p class="text-muted mb-0">

                        Vérifiez attentivement les informations
                        avant de créer les nouvelles inscriptions.

                    </p>

                </div>


                {{-- RÉSUMÉ --}}
                <div class="row justify-content-center g-3 mb-4">

                    <div class="col-md-3">

                        <div class="border rounded-4 p-3 text-center">

                            <div class="fs-3 fw-bold text-primary">
                                {{ count($selected_eleves) }}
                            </div>

                            <small class="text-muted">
                                Sélectionnés
                            </small>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border border-success rounded-4 p-3 text-center bg-success bg-opacity-10">

                            <div class="fs-3 fw-bold text-success">
                                {{ $nombrePassants }}
                            </div>

                            <small>
                                Passés
                            </small>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border border-danger rounded-4 p-3 text-center bg-danger bg-opacity-10">

                            <div class="fs-3 fw-bold text-danger">
                                {{ $nombreRedoublants }}
                            </div>

                            <small>
                                Redoublants
                            </small>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded-4 p-3 text-center bg-secondary bg-opacity-10">

                            <div class="fs-3 fw-bold text-secondary">
                                {{ $nombreNonCalcul }}
                            </div>

                            <small>
                                Non traités
                            </small>

                        </div>

                    </div>

                </div>


                {{-- DESTINATION PASSÉS --}}
                @if($nombrePassants > 0)

                    <div class="alert alert-success rounded-4">

                        <strong>

                            <i class="bi bi-arrow-up-circle me-2"></i>

                            Élèves passés :

                        </strong>

                        {{ $nombrePassants }} élève(s) seront inscrits en

                        <strong>

                            {{ $classeDestinationSelectionnee?->nom
                                ?? 'classe supérieure' }}

                        </strong>.

                    </div>

                @endif


                {{-- DESTINATION REDOUBLANTS --}}
                @if($nombreRedoublants > 0)

                    <div class="alert alert-warning rounded-4">

                        <strong>

                            <i class="bi bi-arrow-repeat me-2"></i>

                            Redoublants :

                        </strong>

                        {{ $nombreRedoublants }} élève(s) seront
                        réinscrits dans leur classe actuelle.

                    </div>

                @endif


                {{-- AVERTISSEMENT --}}
                @if($nombreNonCalcul > 0)

                    <div class="alert alert-secondary rounded-4">

                        <strong>

                            <i class="bi bi-info-circle me-2"></i>

                            Attention :

                        </strong>

                        {{ $nombreNonCalcul }} élève(s)
                        sans moyenne annuelle ne seront pas traités automatiquement.

                    </div>

                @endif


                {{-- TABLEAU FINAL --}}
                <div class="table-responsive mb-4">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Élève
                                </th>

                                <th class="text-center">
                                    Moyenne
                                </th>

                                <th class="text-center">
                                    Décision
                                </th>

                                <th>
                                    Destination
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($elevesSelectionnes as $insc)

                                @php

                                    $data = $elevesData[$insc->id] ?? [
                                        'moyenne' => null,
                                        'decision' => 'non_calculée',
                                    ];

                                    $moyenne = $data['moyenne'] ?? null;

                                    $decision = strtolower(
                                        trim($data['decision'] ?? '')
                                    );

                                    $estPassant = in_array($decision, [
                                        'passé',
                                        'passe',
                                        'passant',
                                        'admis'
                                    ], true);

                                    $estRedoublant = in_array($decision, [
                                        'redoublant',
                                        'redoublé',
                                        'redouble',
                                        'redoublement'
                                    ], true);

                                    if (
                                        !$estPassant &&
                                        !$estRedoublant &&
                                        $moyenne !== null
                                    ) {

                                        $estPassant =
                                            (float) $moyenne >=
                                            (float) $seuil_passage;

                                        $estRedoublant =
                                            !$estPassant;

                                    }

                                @endphp


                                <tr wire:key="final-step4-{{ $insc->id }}">

                                    <td class="fw-semibold">

                                        {{ $insc->eleve->nom ?? '' }}

                                        {{ $insc->eleve->prenom ?? '' }}

                                    </td>


                                    <td class="text-center">

                                        @if($moyenne !== null)

                                            <strong>

                                                {{ number_format(
                                                    (float) $moyenne,
                                                    2,
                                                    ',',
                                                    ' '
                                                ) }}/20

                                            </strong>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        @if($estPassant)

                                            <span class="badge bg-success">
                                                PASSÉ
                                            </span>

                                        @elseif($estRedoublant)

                                            <span class="badge bg-warning text-dark">
                                                REDOUBLANT
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                NON CALCULÉE
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($estPassant)

                                            <span class="text-success fw-semibold">

                                                <i class="bi bi-arrow-right me-1"></i>

                                                {{ $classeDestinationSelectionnee?->nom
                                                    ?? 'Classe supérieure' }}

                                            </span>

                                        @elseif($estRedoublant)

                                            <span class="text-danger fw-semibold">

                                                <i class="bi bi-arrow-repeat me-1"></i>

                                                {{ $insc->classe->nom
                                                    ?? 'Même classe' }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                Aucun traitement
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- INFORMATION --}}
                <div class="alert alert-warning rounded-4">

                    <i class="bi bi-shield-exclamation me-2"></i>

                    <strong>Important :</strong>

                    cette opération créera une nouvelle inscription
                    pour l'année d'accueil.

                    L'inscription de l'année source sera conservée
                    afin de préserver l'historique scolaire.

                </div>


                {{-- BOUTONS --}}
                <div class="d-flex justify-content-center gap-3 mt-4">

                    <button type="button"
                            class="btn btn-outline-secondary btn-lg px-4 rounded-3"
                            wire:click="$set('step', 3)">

                        <i class="bi bi-arrow-left me-1"></i>

                        Retour

                    </button>


                    <button type="button"
                            class="btn btn-success btn-lg px-5 rounded-3"
                            wire:click="executePassage"
                            wire:loading.attr="disabled">

                        <span wire:loading.remove wire:target="executePassage">

                            <i class="bi bi-check-circle me-2"></i>

                            Confirmer le traitement

                        </span>

                        <span wire:loading wire:target="executePassage">

                            <span class="spinner-border spinner-border-sm me-2"></span>

                            Traitement en cours...

                        </span>

                    </button>

                </div>

            </div>

        </div>

    @endif



    {{-- ============================================================
         ÉTAPE 5 : SUCCÈS
    ============================================================= --}}

    @if($step == 5)

        <div class="card border-0 shadow rounded-4">

            <div class="card-body text-center p-5">

                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-4 mb-4">

                    <i class="bi bi-check-circle-fill fs-1"></i>

                </div>


                <h3 class="fw-bold text-success mb-2">
                    Traitement terminé
                </h3>


                <p class="text-muted">

                    Le traitement des passages et redoublements
                    a été effectué avec succès.

                </p>


                <div class="alert alert-info rounded-4 text-start mx-auto"
                     style="max-width:700px;">

                    <i class="bi bi-info-circle-fill me-2"></i>

                    <strong>Historique conservé :</strong>

                    les inscriptions de l'année précédente
                    restent intactes.

                    Les élèves passés ont été affectés à la classe
                    d'accueil et les redoublants ont été réinscrits
                    dans leur classe source pour l'année d'accueil.

                </div>


                <div class="row justify-content-center mt-4">

                    <div class="col-md-4">

                        <div class="card border-success bg-success bg-opacity-10 rounded-4">

                            <div class="card-body">

                                <i class="bi bi-arrow-up-circle-fill text-success fs-2"></i>

                                <h6 class="fw-bold mt-2">
                                    Élèves passés
                                </h6>

                                <small class="text-muted">
                                    Affectés à leur classe supérieure
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="card border-warning bg-warning bg-opacity-10 rounded-4">

                            <div class="card-body">

                                <i class="bi bi-arrow-repeat text-warning fs-2"></i>

                                <h6 class="fw-bold mt-2">
                                    Redoublants
                                </h6>

                                <small class="text-muted">
                                    Réinscrits dans leur classe source
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                <button type="button"
                        class="btn btn-primary btn-lg px-5 rounded-3 mt-4"
                        wire:click="$set('step', 1)">

                    <i class="bi bi-plus-circle me-2"></i>

                    Nouveau traitement

                </button>

            </div>

        </div>

    @endif

</div>