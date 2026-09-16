@extends('tableau.neutre')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                <i class="bi bi-pencil-square"></i>
                Modifier la recette
            </h2>

            <p class="text-muted mb-0">
                Modification de la recette #{{ $recette->id }}
            </p>
        </div>

        <a href="{{ route('recettes.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Retour aux recettes

        </a>

    </div>


    {{-- =========================================================
         MESSAGES D'ERREUR
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle"></i>
                Impossible de modifier la recette
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row g-4">

        {{-- =====================================================
             COLONNE PRINCIPALE
        ====================================================== --}}
        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        <i class="bi bi-cash-stack"></i>

                        Informations de la recette

                    </h5>

                </div>


                <div class="card-body">

                    <form action="{{ route('recettes.update', $recette) }}"
                          method="POST">

                        @csrf

                        @method('PUT')


                        {{-- =================================================
                             DATE
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="date_paiement"
                                   class="form-label fw-semibold">

                                Date du paiement
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="date_paiement"
                                   id="date_paiement"
                                   class="form-control @error('date_paiement') is-invalid @enderror"
                                   value="{{ old(
                                       'date_paiement',
                                       $recette->date_paiement
                                           ? \Carbon\Carbon::parse($recette->date_paiement)->format('Y-m-d')
                                           : ''
                                   ) }}"
                                   required>

                            @error('date_paiement')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             MONTANT
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="montant_verse"
                                   class="form-label fw-semibold">

                                Montant versé
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="montant_verse"
                                       id="montant_verse"
                                       class="form-control text-end @error('montant_verse') is-invalid @enderror"
                                       value="{{ old(
                                           'montant_verse',
                                           $recette->montant_verse
                                       ) }}"
                                       min="0.01"
                                       step="0.01"
                                       required>

                                <span class="input-group-text">
                                    FCFA
                                </span>

                                @error('montant_verse')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <small class="text-muted">
                                Montant actuellement enregistré :
                                <strong>
                                    {{ number_format(
                                        (float) $recette->montant_verse,
                                        2,
                                        ',',
                                        ' '
                                    ) }}
                                    FCFA
                                </strong>
                            </small>

                        </div>


                        {{-- =================================================
                             MODE DE PAIEMENT
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="mode_paiement"
                                   class="form-label fw-semibold">

                                Mode de paiement
                                <span class="text-danger">*</span>

                            </label>

                            <select name="mode_paiement"
                                    id="mode_paiement"
                                    class="form-select @error('mode_paiement') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                <option value="Espèces"
                                    {{ old(
                                        'mode_paiement',
                                        $recette->mode_paiement
                                    ) === 'Espèces' ? 'selected' : '' }}>

                                    Espèces

                                </option>

                                <option value="Mobile Money"
                                    {{ old(
                                        'mode_paiement',
                                        $recette->mode_paiement
                                    ) === 'Mobile Money' ? 'selected' : '' }}>

                                    Mobile Money

                                </option>

                                <option value="Chèque"
                                    {{ old(
                                        'mode_paiement',
                                        $recette->mode_paiement
                                    ) === 'Chèque' ? 'selected' : '' }}>

                                    Chèque

                                </option>

                                <option value="Virement"
                                    {{ old(
                                        'mode_paiement',
                                        $recette->mode_paiement
                                    ) === 'Virement' ? 'selected' : '' }}>

                                    Virement

                                </option>

                                <option value="Carte bancaire"
                                    {{ old(
                                        'mode_paiement',
                                        $recette->mode_paiement
                                    ) === 'Carte bancaire' ? 'selected' : '' }}>

                                    Carte bancaire

                                </option>

                            </select>

                            @error('mode_paiement')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             CATÉGORIE
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="categorie_recette_id"
                                   class="form-label fw-semibold">

                                Catégorie de recette
                                <span class="text-danger">*</span>

                            </label>

                            <select name="categorie_recette_id"
                                    id="categorie_recette_id"
                                    class="form-select @error('categorie_recette_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner une catégorie --
                                </option>

                                @foreach($categories as $categorie)

                                    <option value="{{ $categorie->id }}"
                                        {{ old(
                                            'categorie_recette_id',
                                            $recette->categorie_recette_id
                                        ) == $categorie->id ? 'selected' : '' }}>

                                        {{ $categorie->nom }}

                                    </option>

                                @endforeach

                            </select>

                            @error('categorie_recette_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             ANNÉE
                        ================================================== --}}
                        <div class="mb-3">

                            <label for="annee_id"
                                   class="form-label fw-semibold">

                                Année scolaire
                                <span class="text-danger">*</span>

                            </label>

                            <select name="annee_id"
                                    id="annee_id"
                                    class="form-select @error('annee_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    -- Sélectionner une année --
                                </option>

                                @foreach($annees as $annee)

                                    <option value="{{ $annee->id }}"
                                        {{ old(
                                            'annee_id',
                                            $recette->annee_id
                                        ) == $annee->id ? 'selected' : '' }}>

                                        {{ $annee->libelle
                                            ?? $annee->nom
                                            ?? $annee->annee
                                            ?? 'Année #' . $annee->id }}

                                    </option>

                                @endforeach

                            </select>

                            @error('annee_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             BOUTONS
                        ================================================== --}}
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">

                            <a href="{{ route('recettes.index') }}"
                               class="btn btn-outline-secondary">

                                <i class="bi bi-x-circle"></i>
                                Annuler

                            </a>


                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-circle"></i>
                                Enregistrer les modifications

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             COLONNE INFORMATIONS
        ====================================================== --}}
        <div class="col-lg-4">

            {{-- =================================================
                 RÉSUMÉ
            ================================================== --}}
            <div class="card shadow-sm mb-4">

                <div class="card-header bg-light">

                    <strong>
                        <i class="bi bi-info-circle"></i>
                        Informations de la recette
                    </strong>

                </div>


                <div class="card-body">

                    {{-- ID --}}
                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Identifiant
                        </small>

                        <strong>
                            #{{ $recette->id }}
                        </strong>

                    </div>


                    {{-- NUMÉRO REÇU --}}
                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Numéro de reçu
                        </small>

                        <span class="badge bg-dark">

                            {{ $recette->numero_recu ?? '-' }}

                        </span>

                    </div>


                    {{-- ORIGINE --}}
                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Origine de la recette
                        </small>

                        @if($recette->paiement_id)

                            <span class="badge bg-primary">

                                <i class="bi bi-mortarboard"></i>

                                Scolarité

                            </span>

                            <div class="small text-muted mt-1">

                                Paiement #{{ $recette->paiement_id }}

                            </div>

                        @elseif($recette->paiement_achat_id)

                            <span class="badge bg-warning text-dark">

                                <i class="bi bi-cart"></i>

                                Achat

                            </span>

                            <div class="small text-muted mt-1">

                                Paiement achat
                                #{{ $recette->paiement_achat_id }}

                            </div>

                        @else

                            <span class="badge bg-secondary">

                                Origine inconnue

                            </span>

                        @endif

                    </div>


                    {{-- INSCRIPTION --}}
                    @if($recette->inscription)

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Inscription
                            </small>

                            <strong>

                                #{{ $recette->inscription_id }}

                            </strong>

                            @if($recette->inscription->eleve)

                                <div class="mt-1">

                                    {{ $recette->inscription->eleve->nom }}
                                    {{ $recette->inscription->eleve->prenom }}

                                </div>

                                @if($recette->inscription->eleve->matricule)

                                    <small class="text-muted">

                                        Matricule :
                                        {{ $recette->inscription->eleve->matricule }}

                                    </small>

                                @endif

                            @endif

                        </div>

                    @endif


                    {{-- PAIEMENT --}}
                    @if($recette->paiement_id)

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Paiement associé
                            </small>

                            <strong>
                                #{{ $recette->paiement_id }}
                            </strong>

                        </div>

                    @endif


                    {{-- PAIEMENT ACHAT --}}
                    @if($recette->paiement_achat_id)

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Paiement achat associé
                            </small>

                            <strong>
                                #{{ $recette->paiement_achat_id }}
                            </strong>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 AVERTISSEMENT
            ================================================== --}}
            <div class="alert alert-warning">

                <div class="fw-bold mb-2">

                    <i class="bi bi-exclamation-triangle"></i>

                    Attention

                </div>

                <div class="small">

                    La modification du montant, de la date ou du
                    mode de paiement peut également modifier les
                    informations du paiement associé.

                    <br><br>

                    Le numéro de reçu et les relations avec
                    l'inscription ou l'achat ne sont pas modifiés
                    depuis ce formulaire.

                </div>

            </div>


            {{-- =================================================
                 MONTANT ACTUEL
            ================================================== --}}
            <div class="card shadow-sm">

                <div class="card-body text-center">

                    <small class="text-muted d-block mb-2">
                        Montant actuellement enregistré
                    </small>

                    <h3 class="text-success mb-0">

                        {{ number_format(
                            (float) $recette->montant_verse,
                            2,
                            ',',
                            ' '
                        ) }}

                        <small>FCFA</small>

                    </h3>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
