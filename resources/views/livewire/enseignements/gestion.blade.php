<div class="container-fluid py-4"
     style="background-color:#f8f9fa; min-height:100vh;">

    {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex flex-column flex-lg-row justify-content-between
                        align-items-lg-center gap-3">

                <div>

                    <div class="d-flex align-items-center mb-1">

                        <div class="bg-primary-subtle text-primary rounded-3
                                    d-flex align-items-center justify-content-center me-3"
                             style="width:48px;height:48px;">

                            <i class="bi bi-person-workspace fs-4"></i>

                        </div>

                        <div>

                            <h4 class="fw-bold mb-0">
                                Affectation des enseignants
                            </h4>

                            <p class="text-muted mb-0 small">
                                Gestion des affectations pédagogiques par année,
                                cycle, classe et matière.
                            </p>

                        </div>

                    </div>

                </div>


                <button
                    type="button"
                    class="btn btn-primary rounded-3 px-4"
                    wire:click="nouveau"
                >
                    <i class="bi bi-plus-circle me-1"></i>
                    Nouvelle affectation
                </button>

            </div>

        </div>

    </div>


    {{-- ============================================================
         MESSAGES
    ============================================================ --}}

    @if (session()->has('success'))

        <div class="alert alert-success alert-dismissible fade show
                    rounded-3 shadow-sm border-0 mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
         FILTRES
    ============================================================ --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center
                        gap-2 mb-4">

                <div>

                    <h6 class="fw-bold mb-1">
                        <i class="bi bi-funnel-fill text-primary me-2"></i>
                        Filtrer les affectations
                    </h6>

                    <small class="text-muted">
                        Affinez la liste par année, cycle, classe, matière ou enseignant.
                    </small>

                </div>


                <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary rounded-3"
                    wire:click="$set('filtre_annee_id', null)"
                >
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Réinitialiser
                </button>

            </div>


            <div class="row g-3">

                {{-- ====================================================
                     ANNÉE FILTRE
                ===================================================== --}}

                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Année scolaire
                    </label>

                    <select
                        wire:model.live="filtre_annee_id"
                        class="form-select"
                    >

                        <option value="">
                            Toutes les années
                        </option>

                        @foreach ($annees as $annee)

                            <option value="{{ $annee->id }}">

                                {{ $annee->libelle
                                    ?? $annee->nom
                                    ?? $annee->annee
                                    ?? $annee->id }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ====================================================
                     CYCLE FILTRE
                ===================================================== --}}

                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Cycle
                    </label>

                    <select
                        wire:model.live="filtre_cycle_id"
                        class="form-select"
                        @disabled(!$filtre_annee_id)
                    >

                        <option value="">

                            @if (!$filtre_annee_id)

                                -- Choisir d'abord une année --

                            @else

                                Tous les cycles

                            @endif

                        </option>

                        @foreach ($cycles as $cycle)

                            <option value="{{ $cycle->id }}">
                                {{ $cycle->nom }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ====================================================
                     CLASSE FILTRE
                ===================================================== --}}

                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Classe
                    </label>

                    <select
                        wire:model.live="filtre_classe_id"
                        class="form-select"
                        @disabled(!$filtre_cycle_id)
                    >

                        <option value="">

                            @if (!$filtre_annee_id)

                                -- Choisir d'abord une année --

                            @elseif (!$filtre_cycle_id)

                                -- Choisir d'abord un cycle --

                            @else

                                Toutes les classes

                            @endif

                        </option>

                        @foreach ($classesFiltre as $classe)

                            <option value="{{ $classe->id }}">

                                {{ $classe->nom }}

                                @if ($classe->niveau)
                                    — {{ $classe->niveau }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ====================================================
                     MATIÈRE FILTRE
                ===================================================== --}}

                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Matière
                    </label>

                    <select
                        class="form-select"
                        wire:model.live="filtre_matiere_id"
                    >

                        <option value="">
                            Toutes les matières
                        </option>

                        @foreach ($matieres as $matiere)

                            <option value="{{ $matiere->id }}">
                                {{ $matiere->nom }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ====================================================
                     ENSEIGNANT
                ===================================================== --}}

                <div class="col-lg-4 col-md-6">

                    <label class="form-label fw-semibold">
                        Enseignant
                    </label>

                    <select
                        class="form-select"
                        wire:model.live="filtre_enseignant_id"
                    >

                        <option value="">
                            Tous les enseignants
                        </option>

                        @foreach ($enseignants as $enseignant)

                            <option value="{{ $enseignant->id }}">

                                {{ $enseignant->nom }}
                                {{ $enseignant->prenom }}

                                @if ($enseignant->matricule)
                                    — {{ $enseignant->matricule }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ====================================================
                     ÉTAT
                ===================================================== --}}

                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">
                        État
                    </label>

                    <select
                        class="form-select"
                        wire:model.live="filtre_actif"
                    >

                        <option value="">
                            Tous
                        </option>

                        <option value="1">
                            Actif
                        </option>

                        <option value="0">
                            Inactif
                        </option>

                    </select>

                </div>


                {{-- ====================================================
                     RECHERCHE
                ===================================================== --}}

                <div class="col-lg-6 col-md-12">

                    <label class="form-label fw-semibold">
                        Recherche
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-primary"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Matière, classe, enseignant, matricule..."
                            wire:model.live.debounce.400ms="search"
                        >

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         TABLEAU
    ============================================================ --}}

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center
                        gap-2 mb-4">

                <div>

                    <h6 class="fw-bold mb-1">
                        <i class="bi bi-list-check text-primary me-2"></i>
                        Affectations pédagogiques
                    </h6>

                    <small class="text-muted">

                        {{ $enseignements->total() }}

                        affectation(s)

                    </small>

                </div>


                @if ($filtre_annee_id || $filtre_cycle_id || $filtre_classe_id ||
                     $filtre_matiere_id || $filtre_enseignant_id ||
                     $filtre_actif !== '' || trim($search) !== '')

                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">

                        <i class="bi bi-funnel me-1"></i>
                        Filtres actifs

                    </span>

                @endif

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Année</th>

                            <th>Cycle</th>

                            <th>Classe</th>

                            <th>Matière</th>

                            <th>Enseignant</th>

                            <th class="text-center">
                                Coef.
                            </th>

                            <th class="text-center">
                                H/S
                            </th>

                            <th class="text-center">
                                État
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($enseignements as $enseignement)

                            <tr wire:key="enseignement-{{ $enseignement->id }}">

                                {{-- ID --}}

                                <td>

                                    <span class="badge bg-light text-dark border">
                                        {{ $enseignement->id }}
                                    </span>

                                </td>


                                {{-- ANNÉE --}}

                                <td>

                                    @if ($enseignement->annee)

                                        <span class="badge bg-light text-dark border">

                                            {{ $enseignement->annee->libelle
                                                ?? $enseignement->annee->nom
                                                ?? $enseignement->annee->annee
                                                ?? $enseignement->annee_id }}

                                        </span>

                                    @else

                                        <span class="text-muted">—</span>

                                    @endif

                                </td>


                                {{-- CYCLE --}}

                                <td>

                                    @if ($enseignement->classe &&
                                         $enseignement->classe->cycle)

                                        <span class="badge bg-primary-subtle text-primary">

                                            {{ $enseignement->classe->cycle->nom }}

                                        </span>

                                    @elseif ($enseignement->classe)

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- CLASSE --}}

                                <td>

                                    @if ($enseignement->classe)

                                        <span class="fw-semibold">

                                            {{ $enseignement->classe->nom }}

                                        </span>

                                        @if ($enseignement->classe->niveau)

                                            <br>

                                            <small class="text-muted">
                                                {{ $enseignement->classe->niveau }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- MATIÈRE --}}

                                <td>

                                    @if ($enseignement->matiere)

                                        <span class="fw-semibold">

                                            {{ $enseignement->matiere->nom }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- ENSEIGNANT --}}

                                <td>

                                    @if ($enseignement->enseignant)

                                        <div class="d-flex align-items-center">

                                            <div
                                                class="rounded-circle bg-primary-subtle
                                                       text-primary d-flex
                                                       align-items-center
                                                       justify-content-center me-2"
                                                style="width:38px;height:38px;"
                                            >

                                                <i class="bi bi-person"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $enseignement->enseignant->nom }}

                                                    {{ $enseignement->enseignant->prenom }}

                                                </div>

                                                @if ($enseignement->enseignant->matricule)

                                                    <small class="text-muted">

                                                        <i class="bi bi-person-vcard me-1"></i>

                                                        {{ $enseignement->enseignant->matricule }}

                                                    </small>

                                                @endif

                                            </div>

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            Non renseigné
                                        </span>

                                    @endif

                                </td>


                                {{-- COEFFICIENT --}}

                                <td class="text-center">

                                    <span class="badge bg-primary rounded-pill">

                                        {{ number_format(
                                            (float) $enseignement->coefficient,
                                            2,
                                            ',',
                                            ''
                                        ) }}

                                    </span>

                                </td>


                                {{-- HEURES / SEMAINE --}}

                                <td class="text-center">

                                    @if ($enseignement->heures_par_semaine !== null)

                                        <span class="fw-semibold">
                                            {{ $enseignement->heures_par_semaine }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- ÉTAT --}}

                                <td class="text-center">

                                    @if ($enseignement->actif)

                                        <span class="badge bg-success">

                                            <i class="bi bi-check-circle me-1"></i>
                                            Actif

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            <i class="bi bi-pause-circle me-1"></i>
                                            Inactif

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}

                                <td class="text-end">

                                    <div class="btn-group">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            wire:click="edit({{ $enseignement->id }})"
                                            title="Modifier"
                                        >

                                            <i class="bi bi-pencil"></i>

                                        </button>


                                        @if ($enseignement->actif)

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-warning"
                                                wire:click="desactiver({{ $enseignement->id }})"
                                                title="Désactiver"
                                            >

                                                <i class="bi bi-pause-circle"></i>

                                            </button>

                                        @else

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-success"
                                                wire:click="activer({{ $enseignement->id }})"
                                                title="Activer"
                                            >

                                                <i class="bi bi-check-circle"></i>

                                            </button>

                                        @endif


                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            wire:click="delete({{ $enseignement->id }})"
                                            wire:confirm="Voulez-vous vraiment supprimer cette affectation ?"
                                            title="Supprimer"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10" class="text-center py-5">

                                    <div class="mb-3">

                                        <i class="bi bi-inbox fs-1 text-muted"></i>

                                    </div>

                                    <h6 class="fw-semibold">
                                        Aucune affectation trouvée
                                    </h6>

                                    <p class="text-muted mb-3">

                                        Aucune affectation pédagogique ne correspond
                                        aux critères sélectionnés.

                                    </p>

                                    <button
                                        type="button"
                                        class="btn btn-primary btn-sm rounded-3"
                                        wire:click="nouveau"
                                    >

                                        <i class="bi bi-plus-circle me-1"></i>
                                        Ajouter une affectation

                                    </button>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if ($enseignements->hasPages())

                <div class="mt-4">

                    {{ $enseignements->links() }}

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
         MODAL CRÉATION / MODIFICATION
    ============================================================ --}}

    @if ($showModal)

        <div
            class="modal fade show d-block"
            tabindex="-1"
            style="background:rgba(0,0,0,.55);"
            wire:keydown.escape="fermerModal"
        >

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg rounded-4">


                    {{-- ====================================================
                         HEADER
                    ===================================================== --}}

                    <div class="modal-header border-0 px-4 pt-4">

                        <div>

                            <h5 class="modal-title fw-bold mb-1">

                                @if ($enseignement_id)

                                    <i class="bi bi-pencil-square text-primary me-2"></i>
                                    Modifier l'affectation

                                @else

                                    <i class="bi bi-person-plus text-primary me-2"></i>
                                    Nouvelle affectation

                                @endif

                            </h5>

                            <small class="text-muted">

                                Définissez l'année, le cycle, la classe,
                                la matière et l'enseignant.

                            </small>

                        </div>


                        <button
                            type="button"
                            class="btn-close"
                            wire:click="fermerModal"
                            aria-label="Fermer"
                        ></button>

                    </div>


                    {{-- ====================================================
                         FORMULAIRE
                    ===================================================== --}}

                    <form wire:submit="save">

                        <div class="modal-body px-4">

                            <div class="row g-3">


                                {{-- ==================================================
                                     ANNÉE
                                =================================================== --}}

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Année scolaire
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select
                                        class="form-select @error('annee_id') is-invalid @enderror"
                                        wire:model.live="annee_id"
                                    >

                                        <option value="">
                                            -- Sélectionner l'année --
                                        </option>

                                        @foreach ($annees as $annee)

                                            <option value="{{ $annee->id }}">

                                                {{ $annee->libelle
                                                    ?? $annee->nom
                                                    ?? $annee->annee
                                                    ?? $annee->id }}

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('annee_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ==================================================
                                     CYCLE
                                =================================================== --}}

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Cycle
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select
                                        class="form-select @error('cycle_id') is-invalid @enderror"
                                        wire:model.live="cycle_id"
                                        @disabled(!$annee_id)
                                    >

                                        <option value="">

                                            @if (!$annee_id)

                                                -- Choisir d'abord une année --

                                            @else

                                                -- Sélectionner le cycle --

                                            @endif

                                        </option>

                                        @foreach ($cycles as $cycle)

                                            <option value="{{ $cycle->id }}">

                                                {{ $cycle->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('cycle_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    @if (!$annee_id)

                                        <div class="form-text">

                                            <i class="bi bi-info-circle me-1"></i>

                                            Sélectionnez d'abord une année scolaire.

                                        </div>

                                    @endif

                                </div>


                                {{-- ==================================================
                                     CLASSE
                                =================================================== --}}

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Classe
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select
                                        class="form-select @error('classe_id') is-invalid @enderror"
                                        wire:model.live="classe_id"
                                        @disabled(!$cycle_id)
                                    >

                                        <option value="">

                                            @if (!$annee_id)

                                                -- Choisir d'abord une année --

                                            @elseif (!$cycle_id)

                                                -- Choisir d'abord un cycle --

                                            @elseif ($classes->isEmpty())

                                                -- Aucune classe disponible --

                                            @else

                                                -- Sélectionner la classe --

                                            @endif

                                        </option>

                                        @foreach ($classes as $classe)

                                            <option value="{{ $classe->id }}">

                                                {{ $classe->nom }}

                                                @if ($classe->niveau)

                                                    — {{ $classe->niveau }}

                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('classe_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                    @if ($cycle_id && $classes->isEmpty())

                                        <div class="form-text text-warning">

                                            <i class="bi bi-exclamation-triangle me-1"></i>

                                            Aucune classe n'est rattachée à ce cycle
                                            pour l'année sélectionnée.

                                        </div>

                                    @elseif (!$cycle_id)

                                        <div class="form-text">

                                            <i class="bi bi-info-circle me-1"></i>

                                            Sélectionnez d'abord un cycle.

                                        </div>

                                    @endif

                                </div>


                                {{-- ==================================================
                                     MATIÈRE
                                =================================================== --}}

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Matière
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select
                                        class="form-select @error('matiere_id') is-invalid @enderror"
                                        wire:model.live="matiere_id"
                                        @disabled(!$classe_id)
                                    >

                                        <option value="">

                                            @if (!$classe_id)

                                                -- Choisir d'abord une classe --

                                            @elseif ($matieres->isEmpty())

                                                -- Aucune matière disponible --

                                            @else

                                                -- Sélectionner la matière --

                                            @endif

                                        </option>

                                        @foreach ($matieres as $matiere)

                                            <option value="{{ $matiere->id }}">

                                                {{ $matiere->nom }}

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('matiere_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ==================================================
                                     ENSEIGNANT
                                =================================================== --}}

                                <div class="col-12">

                                    <label class="form-label fw-semibold">

                                        Enseignant
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select
                                        class="form-select @error('enseignant_id') is-invalid @enderror"
                                        wire:model="enseignant_id"
                                    >

                                        <option value="">
                                            -- Sélectionner l'enseignant --
                                        </option>

                                        @foreach ($enseignants as $enseignant)

                                            <option value="{{ $enseignant->id }}">

                                                {{ $enseignant->nom }}
                                                {{ $enseignant->prenom }}

                                                @if ($enseignant->matricule)

                                                    — {{ $enseignant->matricule }}

                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                    @error('enseignant_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- ==================================================
                                     PARAMÈTRES PÉDAGOGIQUES
                                =================================================== --}}

                                <div class="col-12">

                                    <div class="border rounded-4 p-3 bg-light">

                                        <div class="d-flex align-items-center mb-3">

                                            <div class="bg-white rounded-3 shadow-sm
                                                        d-flex align-items-center
                                                        justify-content-center me-2"
                                                 style="width:38px;height:38px;">

                                                <i class="bi bi-sliders text-primary"></i>

                                            </div>

                                            <div>

                                                <div class="fw-bold">
                                                    Paramètres pédagogiques
                                                </div>

                                                <small class="text-muted">
                                                    Définissez les horaires et le coefficient.
                                                </small>

                                            </div>

                                        </div>


                                        <div class="row g-3">


                                            {{-- COEFFICIENT --}}

                                            <div class="col-md-4">

                                                <label class="form-label fw-semibold">

                                                    Coefficient
                                                    <span class="text-danger">*</span>

                                                </label>

                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    class="form-control @error('coefficient') is-invalid @enderror"
                                                    wire:model="coefficient"
                                                    placeholder="Ex : 2"
                                                >

                                                @error('coefficient')

                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>

                                                @enderror

                                            </div>


                                            {{-- VOLUME HORAIRE --}}

                                            <div class="col-md-4">

                                                <label class="form-label fw-semibold">
                                                    Volume horaire
                                                </label>

                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    class="form-control @error('volume_horaire') is-invalid @enderror"
                                                    wire:model="volume_horaire"
                                                    placeholder="Ex : 180"
                                                >

                                                @error('volume_horaire')

                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>

                                                @enderror

                                            </div>


                                            {{-- HEURES / SEMAINE --}}

                                            <div class="col-md-4">

                                                <label class="form-label fw-semibold">
                                                    Heures / semaine
                                                </label>

                                                <input
                                                    type="number"
                                                    step="0.5"
                                                    min="0"
                                                    class="form-control @error('heures_par_semaine') is-invalid @enderror"
                                                    wire:model="heures_par_semaine"
                                                    placeholder="Ex : 5"
                                                >

                                                @error('heures_par_semaine')

                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>

                                                @enderror

                                            </div>


                                        </div>

                                    </div>

                                </div>


                                {{-- ==================================================
                                     ÉTAT
                                =================================================== --}}

                                <div class="col-12">

                                    <div class="border rounded-4 p-3">

                                        <div class="form-check form-switch">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                role="switch"
                                                wire:model="actif"
                                                id="actif"
                                            >

                                            <label
                                                class="form-check-label fw-semibold"
                                                for="actif"
                                            >

                                                Affectation active

                                            </label>

                                        </div>

                                        <small class="text-muted">

                                            Une affectation inactive peut être conservée
                                            dans l'historique sans être considérée comme active.

                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ====================================================
                             FOOTER
                        ===================================================== --}}

                        <div class="modal-footer border-0 px-4 pb-4">

                            <button
                                type="button"
                                class="btn btn-light border rounded-3 px-4"
                                wire:click="fermerModal"
                            >

                                <i class="bi bi-x-circle me-1"></i>
                                Annuler

                            </button>


                            <button
                                type="submit"
                                class="btn btn-primary rounded-3 px-4"
                                wire:loading.attr="disabled"
                            >

                                <span wire:loading.remove>

                                    @if ($enseignement_id)

                                        <i class="bi bi-check-lg me-1"></i>
                                        Enregistrer les modifications

                                    @else

                                        <i class="bi bi-save me-1"></i>
                                        Enregistrer

                                    @endif

                                </span>


                                <span wire:loading>

                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Traitement...

                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>
