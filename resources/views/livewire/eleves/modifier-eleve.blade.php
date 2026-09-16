<div>

    {{-- ============================================================
        TITRE
    ============================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Modifier les informations d'un élève
            </h5>

        </div>

        <div class="card-body">

            {{-- ====================================================
                SÉLECTION ANNÉE / CYCLE / CLASSE / ÉLÈVE
            ===================================================== --}}

            <div class="row g-3">

                {{-- ANNÉE --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Année scolaire
                    </label>

                    <select
                        wire:model.live="annee_id"
                        class="form-select @error('annee_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Sélectionner l'année --
                        </option>

                        @foreach($annees as $annee)

                            <option value="{{ $annee->id }}">
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


                {{-- CYCLE --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Cycle
                    </label>

                    <select
                        wire:model.live="cycle_id"
                        class="form-select @error('cycle_id') is-invalid @enderror"
                        @disabled(!$annee_id)
                    >

                        <option value="">
                            -- Sélectionner le cycle --
                        </option>

                        @foreach($cycles as $cycle)

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

                </div>


                {{-- CLASSE --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Classe
                    </label>

                    <select
                        wire:model.live="classe_id"
                        class="form-select @error('classe_id') is-invalid @enderror"
                        @disabled(!$cycle_id)
                    >

                        <option value="">
                            -- Sélectionner la classe --
                        </option>

                        @foreach($classes as $classe)

                            <option value="{{ $classe->id }}">
                                {{ $classe->nom }}
                            </option>

                        @endforeach

                    </select>

                    @error('classe_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- ÉLÈVE / INSCRIPTION --}}
                <div class="col-md-3">

                    <label class="form-label fw-bold">
                        Élève
                    </label>

                    <select
                        wire:model.live="inscription_id"
                        class="form-select @error('inscription_id') is-invalid @enderror"
                        @disabled(!$classe_id)
                    >

                        <option value="">
                            -- Sélectionner l'élève --
                        </option>

                        @foreach($inscriptions as $inscription)

                            @if($inscription->eleve)

                                <option value="{{ $inscription->id }}">

                                    {{ $inscription->eleve->nom }}
                                    {{ $inscription->eleve->prenom }}

                                    — {{ $inscription->eleve->matricule }}

                                </option>

                            @endif

                        @endforeach

                    </select>

                    @error('inscription_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- ====================================================
                BOUTON AFFICHER
            ===================================================== --}}

            <div class="mt-4">

                <button
                    type="button"
                    wire:click="afficherEleve"
                    wire:loading.attr="disabled"
                    class="btn btn-primary"
                    @disabled(!$inscription_id)
                >

                    <span wire:loading.remove wire:target="afficherEleve">

                        <i class="bi bi-eye"></i>

                        Afficher

                    </span>

                    <span
                        wire:loading
                        wire:target="afficherEleve"
                    >

                        <span class="spinner-border spinner-border-sm"></span>

                        Chargement...

                    </span>

                </button>

            </div>

        </div>

    </div>


    {{-- ============================================================
        MESSAGE DE SUCCÈS
    ============================================================= --}}

    @if(session()->has('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================
        FORMULAIRE
    ============================================================= --}}

    @if($eleveSelectionne)

        <div class="card shadow-sm">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">

                    <i class="bi bi-person-vcard"></i>

                    Informations de l'élève

                </h5>

            </div>


            <div class="card-body">

                <form wire:submit="enregistrer">

                    <div class="row g-4">


                        {{-- ==================================================
                            PHOTO
                        =================================================== --}}

                        <div class="col-md-3 text-center">

                            @if($photo)

                                <img
                                    src="{{ $photo->temporaryUrl() }}"
                                    class="img-thumbnail rounded-circle"
                                    style="
                                        width:180px;
                                        height:180px;
                                        object-fit:cover;
                                    "
                                >

                            @elseif($ancienne_photo)

                                <img
                                    src="{{ asset('storage/' . $ancienne_photo) }}"
                                    class="img-thumbnail rounded-circle"
                                    style="
                                        width:180px;
                                        height:180px;
                                        object-fit:cover;
                                    "
                                >

                            @else

                                <div
                                    class="border rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="
                                        width:180px;
                                        height:180px;
                                    "
                                >

                                    <i
                                        class="bi bi-person"
                                        style="font-size:80px;"
                                    ></i>

                                </div>

                            @endif


                            <div class="mt-3">

                                <label class="form-label fw-bold">
                                    Nouvelle photo
                                </label>

                                <input
                                    type="file"
                                    wire:model="photo"
                                    class="form-control"
                                    accept="image/*"
                                >

                                <div
                                    wire:loading
                                    wire:target="photo"
                                    class="text-primary small mt-1"
                                >
                                    Chargement de la photo...
                                </div>

                                @error('photo')

                                    <div class="text-danger small">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- ==================================================
                            INFORMATIONS
                        =================================================== --}}

                        <div class="col-md-9">

                            <div class="row g-3">


                                {{-- NOM --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-bold">
                                        Nom
                                    </label>

                                    <input
                                        type="text"
                                        wire:model="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                    >

                                    @error('nom')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- PRÉNOM --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-bold">
                                        Prénom(s)
                                    </label>

                                    <input
                                        type="text"
                                        wire:model="prenom"
                                        class="form-control @error('prenom') is-invalid @enderror"
                                    >

                                    @error('prenom')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- DATE DE NAISSANCE --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-bold">
                                        Date de naissance
                                    </label>

                                    <input
                                        type="date"
                                        wire:model="date_naissance"
                                        class="form-control"
                                    >

                                </div>


                                {{-- SEXE --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-bold">
                                        Sexe
                                    </label>

                                    <select
                                        wire:model="sexe"
                                        class="form-select @error('sexe') is-invalid @enderror"
                                    >

                                        <option value="">
                                            -- Sélectionner --
                                        </option>

                                        <option value="M">
                                            Masculin
                                        </option>

                                        <option value="F">
                                            Féminin
                                        </option>

                                    </select>

                                    @error('sexe')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- NATIONALITÉ --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-bold">
                                        Nationalité
                                    </label>

                                    <input
                                        type="text"
                                        wire:model="nationalite"
                                        class="form-control"
                                    >

                                </div>


                                {{-- LIEU DE NAISSANCE --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-bold">
                                        Lieu de naissance
                                    </label>

                                    <input
                                        type="text"
                                        wire:model="lieu_naissance"
                                        class="form-control"
                                    >

                                </div>


                                {{-- MATRICULE --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-bold">
                                        Matricule
                                    </label>

                                    <input
                                        type="text"
                                        wire:model="matricule"
                                        class="form-control @error('matricule') is-invalid @enderror"
                                    >

                                    @error('matricule')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- STATUT --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-bold">
                                        Statut
                                    </label>

                                    <select
                                        wire:model="statut"
                                        class="form-select @error('statut') is-invalid @enderror"
                                    >

                                        <option value="passant">
                                            Passant
                                        </option>

                                        <option value="redoublant">
                                            Redoublant
                                        </option>

                                    </select>

                                    @error('statut')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- NUMÉRO D'ORDRE --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-bold">
                                        Numéro d'ordre
                                    </label>

                                    <input
                                        type="text"
                                        wire:model="numero_ordre"
                                        class="form-control"
                                    >

                                </div>


                                {{-- NUM EDUCMASTER --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-bold">
                                        N° EducMaster
                                    </label>

                                    <input
                                        type="text"
                                        wire:model="numeducmaster"
                                        class="form-control"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ==================================================
                        INFORMATIONS D'INSCRIPTION
                    =================================================== --}}

                    <div class="alert alert-info mt-4">

                        <div class="row">

                            <div class="col-md-4">

                                <strong>Année :</strong>

                                @php
                                    $anneeSelectionnee = $annees->firstWhere(
                                        'id',
                                        (int) $annee_id
                                    );
                                @endphp

                                {{ $anneeSelectionnee?->nom }}

                            </div>


                            <div class="col-md-4">

                                <strong>Classe :</strong>

                                @php
                                    $classeSelectionnee = collect($classes)->firstWhere(
                                        'id',
                                        (int) $classe_id
                                    );
                                @endphp

                                {{ $classeSelectionnee?->nom }}

                            </div>


                            <div class="col-md-4">

                                <strong>ID inscription :</strong>

                                {{ $inscription_id }}

                            </div>

                        </div>

                    </div>


                    {{-- ==================================================
                        BOUTONS
                    =================================================== --}}

                    <div class="mt-4 pt-3 border-top">

                        <button
                            type="submit"
                            class="btn btn-success"
                            wire:loading.attr="disabled"
                            wire:target="enregistrer"
                        >

                            <span
                                wire:loading.remove
                                wire:target="enregistrer"
                            >

                                <i class="bi bi-save"></i>

                                Enregistrer les modifications

                            </span>

                            <span
                                wire:loading
                                wire:target="enregistrer"
                            >

                                <span class="spinner-border spinner-border-sm"></span>

                                Enregistrement...

                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>
