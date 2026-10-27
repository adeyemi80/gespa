@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">

    {{-- ================================
         EN-TÊTE
    ================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Modifier l'inscription
            </h3>

            <p class="text-muted mb-0">
                Modification des informations d'inscription de l'élève
            </p>
        </div>

        <a href="{{ route('inscriptions.index') }}"
           class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i>
            Retour
        </a>

    </div>


    {{-- ================================
         MESSAGES D'ERREUR
    ================================= --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4"
             role="alert">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Veuillez corriger les erreurs suivantes :
            </div>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- ================================
         CARTE PRINCIPALE
    ================================= --}}
    <div class="card border-0 shadow rounded-4 overflow-hidden">

        {{-- En-tête --}}
        <div class="card-header bg-primary text-white py-3">

            <div class="d-flex align-items-center">

                <div class="bg-white text-primary rounded-circle d-flex
                            align-items-center justify-content-center me-3"
                     style="width:45px;height:45px;">

                    <i class="bi bi-person-lines-fill fs-4"></i>

                </div>

                <div>
                    <h5 class="mb-0 fw-bold">
                        Informations de l'inscription
                    </h5>

                    <small class="opacity-75">
                        Inscription n° {{ $inscription->id }}
                    </small>
                </div>

            </div>

        </div>


        {{-- Corps --}}
        <div class="card-body p-4 p-md-5">

            <form action="{{ route('inscriptions.update', $inscription->id) }}"
                  method="POST">

                @csrf
                @method('PUT')


                {{-- ================================
                     SECTION ÉLÈVE
                ================================= --}}
                <div class="mb-4">

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-4">
                        <i class="bi bi-person-fill me-2"></i>
                        Élève
                    </h6>

                    <div class="row g-4">

                        {{-- Élève --}}
                        <div class="col-md-6">

                            <label for="eleve_id"
                                   class="form-label fw-semibold">

                                Élève
                                <span class="text-danger">*</span>

                            </label>

                            <select name="eleve_id"
                                    id="eleve_id"
                                    class="form-select rounded-3 @error('eleve_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner un élève --
                                </option>

                                @foreach($eleves as $eleve)

                                    <option value="{{ $eleve->id }}"
                                        {{ old('eleve_id', $inscription->eleve_id) == $eleve->id ? 'selected' : '' }}>

                                        {{ $eleve->nom }}
                                        {{ $eleve->prenom }}

                                    </option>

                                @endforeach

                            </select>

                            @error('eleve_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ================================
                     SECTION SCOLARITÉ
                ================================= --}}
                <div class="mb-4">

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-4">

                        <i class="bi bi-mortarboard-fill me-2"></i>

                        Scolarité

                    </h6>


                    <div class="row g-4">

                        {{-- ==========================
                             CYCLE
                        =========================== --}}
                        <div class="col-md-4">

                            <label for="cycle_id"
                                   class="form-label fw-semibold">

                                Cycle
                                <span class="text-danger">*</span>

                            </label>

                            <select name="cycle_id"
                                    id="cycle_id"
                                    class="form-select rounded-3 @error('cycle_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner un cycle --
                                </option>

                                @foreach($cycles as $cycle)

                                    <option value="{{ $cycle->id }}"
                                        {{ old(
                                            'cycle_id',
                                            $inscription->classe->cycle_id ?? ''
                                        ) == $cycle->id ? 'selected' : '' }}>

                                        {{ $cycle->nom }}

                                    </option>

                                @endforeach

                            </select>

                            @error('cycle_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ==========================
                             CLASSE
                        =========================== --}}
                        <div class="col-md-4">

                            <label for="classe_id"
                                   class="form-label fw-semibold">

                                Classe
                                <span class="text-danger">*</span>

                            </label>

                            <select name="classe_id"
                                    id="classe_id"
                                    class="form-select rounded-3 @error('classe_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner une classe --
                                </option>

                                @foreach($classes as $classe)

                                    <option value="{{ $classe->id }}"
                                            data-cycle="{{ $classe->cycle_id }}"
                                        {{ old(
                                            'classe_id',
                                            $inscription->classe_id
                                        ) == $classe->id ? 'selected' : '' }}>

                                        {{ $classe->nom }}

                                    </option>

                                @endforeach

                            </select>

                            @error('classe_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Les classes sont filtrées selon le cycle sélectionné.
                            </div>

                        </div>


                        {{-- ==========================
                             ANNÉE
                        =========================== --}}
                        <div class="col-md-4">

                            <label for="annee_id"
                                   class="form-label fw-semibold">

                                Année scolaire
                                <span class="text-danger">*</span>

                            </label>

                            <select name="annee_id"
                                    id="annee_id"
                                    class="form-select rounded-3 @error('annee_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner une année --
                                </option>

                                @foreach($annees as $annee)

                                    <option value="{{ $annee->id }}"
                                        {{ old(
                                            'annee_id',
                                            $inscription->annee_id
                                        ) == $annee->id ? 'selected' : '' }}>

                                        {{ $annee->nom }}

                                    </option>

                                @endforeach

                            </select>

                            @error('annee_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ================================
                     SECTION INSCRIPTION
                ================================= --}}
                <div class="mb-4">

                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-4">

                        <i class="bi bi-journal-text me-2"></i>

                        Informations complémentaires

                    </h6>


                    <div class="row g-4">

                        {{-- Note --}}
                       {{-- <div class="col-md-6">

                            <label for="note_id"
                                   class="form-label fw-semibold">

                                Note
                                <span class="text-danger">*</span>

                            </label>

                            <select name="note_id"
                                    id="note_id"
                                    class="form-select rounded-3 @error('note_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner une note --
                                </option>

                                @foreach($notes as $note)

                                    <option value="{{ $note->id }}"
                                        {{ old(
                                            'note_id',
                                            $inscription->note_id
                                        ) == $note->id ? 'selected' : '' }}>

                                        {{ $note->nom ?? 'Note #' . $note->id }}

                                    </option>

                                @endforeach

                            </select>

                            @error('note_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>--}}


                        {{-- Date inscription --}}
                        <div class="col-md-6">

                            <label for="date_inscription"
                                   class="form-label fw-semibold">

                                Date d'inscription
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="date_inscription"
                                   id="date_inscription"
                                   class="form-control rounded-3 @error('date_inscription') is-invalid @enderror"
                                   value="{{ old(
                                       'date_inscription',
                                       $inscription->date_inscription
                                           ? \Carbon\Carbon::parse($inscription->date_inscription)->format('Y-m-d')
                                           : now()->format('Y-m-d')
                                   ) }}"
                                   required>

                            @error('date_inscription')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- ================================
                     RÉCAPITULATIF
                ================================= --}}
                <div class="alert alert-light border rounded-4 p-3 mb-4">

                    <div class="d-flex align-items-center">

                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>

                        <div>

                            <div class="fw-bold">
                                Récapitulatif
                            </div>

                            <small class="text-muted">
                                Le cycle détermine les classes disponibles.
                                Les frais scolaires seront automatiquement
                                synchronisés avec la classe sélectionnée.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- ================================
                     BOUTONS
                ================================= --}}
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">

                    <a href="{{ route('inscriptions.index') }}"
                       class="btn btn-light border rounded-pill px-4">

                        <i class="bi bi-x-circle me-1"></i>

                        Annuler

                    </a>

                    <button type="submit"
                            class="btn btn-primary rounded-pill px-5">

                        <i class="bi bi-check-circle me-1"></i>

                        Enregistrer les modifications

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ======================================================
     JAVASCRIPT : CYCLE → CLASSE
======================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const cycleSelect = document.getElementById('cycle_id');
    const classeSelect = document.getElementById('classe_id');

    if (!cycleSelect || !classeSelect) {
        return;
    }


    /*
     * Filtre les classes selon le cycle sélectionné.
     */
    function filtrerClasses() {

        const cycleId = cycleSelect.value;

        let classeActuelleVisible = false;

        Array.from(classeSelect.options).forEach(function (option) {

            // Option par défaut
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const optionCycle = option.dataset.cycle;

            if (cycleId && optionCycle === cycleId) {

                option.hidden = false;

                if (option.selected) {
                    classeActuelleVisible = true;
                }

            } else {

                option.hidden = true;

                // Évite qu'une classe d'un autre cycle
                // reste sélectionnée
                if (option.selected) {
                    option.selected = false;
                }
            }
        });


        /*
         * Si aucune classe correspondant au cycle
         * n'est sélectionnée, on revient à l'option vide.
         */
        if (!classeActuelleVisible) {
            classeSelect.value = '';
        }
    }


    /*
     * Lorsque le cycle change
     */
    cycleSelect.addEventListener('change', function () {

        classeSelect.value = '';

        filtrerClasses();

    });


    /*
     * Filtrage initial au chargement de la page.
     *
     * Important pour conserver automatiquement
     * le cycle et la classe actuels.
     */
    filtrerClasses();

});

</script>

@endsection

