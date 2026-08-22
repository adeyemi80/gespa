<div>

    {{-- ========================================================= --}}
    {{-- TITRE --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                <i class="bi bi-cart-check"></i>
                Paiement Achat
            </h5>

        </div>

        <div class="card-body">

            {{-- ================================================= --}}
            {{-- IDENTIFICATION ACHETEUR --}}
            {{-- ================================================= --}}

            <h6 class="fw-bold mb-3">
                1. Identification de l'élève / acheteur
            </h6>

            <div class="row g-3">

                {{-- ANNÉE --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Année scolaire
                    </label>

                    <select
                        wire:model.live="anneeId"
                        class="form-select"
                    >

                        <option value="">
                            -- Année scolaire --
                        </option>

                        @foreach($annees as $annee)

                            <option value="{{ $annee->id }}">
                                {{ $annee->nom }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CYCLE --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Cycle
                    </label>

                    <select
                        wire:model.live="cycleId"
                        class="form-select"
                    >

                        <option value="">
                            -- Cycle --
                        </option>

                        @foreach($cycles as $cycle)

                            <option value="{{ $cycle->id }}">
                                {{ $cycle->nom }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CLASSE --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Classe
                    </label>

                    <select
                        wire:model.live="classeId"
                        class="form-select"
                    >

                        <option value="">
                            -- Classe --
                        </option>

                        @foreach($classes as $classe)

    <option value="{{ $classe['id'] }}">
        {{ $classe['nom'] }}
    </option>

@endforeach

                    </select>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ÉLÈVE INSCRIT --}}
            {{-- ================================================= --}}

            {{--<div class="row g-3 mt-1">

                <div class="col-md-6">

                    <label class="form-label">
                        Élève inscrit
                    </label>

                    <select
                        wire:model.live="inscriptionId"
                        class="form-select"
                    >

                        <option value="">
                            -- Aucun / saisie manuelle --
                        </option>

                        @foreach($inscriptions as $inscription)

    <option value="{{ $inscription['id'] }}">
        {{ $inscription['nom'] }} 
    </option>

@endforeach

                    </select>

                    <small class="text-muted">
                        Laissez « Aucun » si l'acheteur n'est pas inscrit.
                    </small>

                </div>

            </div>--}}


            {{-- ================================================= --}}
            {{-- NOM / PRÉNOM / TÉLÉPHONE --}}
            {{-- ================================================= --}}

            <div class="row g-3 mt-1">

                {{-- NOM --}}

                <div class="col-md-4">

                    <label class="form-label">

                        Nom

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        wire:model="nom"
                        class="form-control @error('nom') is-invalid @enderror"
                        placeholder="Nom de l'élève / acheteur"
                    >

                    @error('nom')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- PRÉNOM --}}

                <div class="col-md-4">

                    <label class="form-label">

                        Prénom

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        wire:model="prenom"
                        class="form-control @error('prenom') is-invalid @enderror"
                        placeholder="Prénom de l'élève / acheteur"
                    >

                    @error('prenom')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- TÉLÉPHONE --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Téléphone
                    </label>

                    <input
                        type="text"
                        wire:model="telephone"
                        class="form-control"
                        placeholder="Téléphone"
                    >

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- CATÉGORIES DE RECETTES --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h6 class="mb-0 fw-bold">

                2. Catégories de recettes

                <span class="text-muted fw-normal">
                    (plusieurs choix possibles)
                </span>

            </h6>

        </div>


        <div class="card-body">

            @if(count($categoriesRecettes))

                <div class="row g-3">

                    @foreach($categoriesRecettes as $categorie)

    <div class="col-md-3 col-sm-6">

        @php
            $categorieSelectionnee =
                in_array(
                    $categorie['id'],
                    $categoriesSelectionnees
                );
        @endphp

        <div class="card h-100
            {{ $categorieSelectionnee
                ? 'border-primary shadow-sm'
                : ''
            }}"
        >

            <div class="card-body">

                <div class="form-check">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="categorie-{{ $categorie['id'] }}"
                        value="{{ $categorie['id'] }}"
                        wire:model.live="categoriesSelectionnees"
                    >

                    <label
                        class="form-check-label fw-bold"
                        for="categorie-{{ $categorie['id'] }}"
                    >
                        {{ $categorie['nom'] }}
                    </label>

                </div>

                @if(!empty($categorie['code']))
                    <small class="text-muted">
                        {{ $categorie['code'] }}
                    </small>
                @endif

            </div>

        </div>

    </div>

@endforeach
                </div>

            @else

                <div class="alert alert-warning mb-0">

                    <i class="bi bi-exclamation-triangle"></i>

                    Aucune catégorie d'achat n'est disponible.

                </div>

            @endif


            @if(count($categoriesSelectionnees))

                <div class="alert alert-info mt-3 mb-0">

                    <i class="bi bi-info-circle"></i>

                    <strong>
                        {{ count($categoriesSelectionnees) }}
                    </strong>

                    catégorie(s) sélectionnée(s).

                </div>

            @endif

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- ARTICLES --}}
    {{-- ========================================================= --}}

    @if(count($fraisDisponibles))

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0 fw-bold">

                    3. Articles / frais

                </h6>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    @foreach($fraisDisponibles as $index => $frais)

    <div class="col-md-4">

        <div class="card h-100
            {{ $frais['selectionne']
                ? 'border-primary shadow-sm'
                : ''
            }}"
        >

            <div class="card-body">

                {{-- Catégorie --}}
                @if(!empty($frais['categorie']))
                    <span class="badge bg-primary mb-2">
                        {{ $frais['categorie'] }}
                    </span>
                @endif

                {{-- Article --}}
                <h6 class="fw-bold">
                    {{ $frais['nom'] }}
                </h6>

                {{-- Description --}}
                @if(!empty($frais['description']))
                    <p class="text-muted small mb-2">
                        {{ $frais['description'] }}
                    </p>
                @endif

                {{-- Montant --}}
                <div class="mt-3">

                    <span class="text-muted small">
                        Montant :
                    </span>

                    <strong class="fs-5 text-primary">
                        {{ number_format(
                            $frais['montant'],
                            0,
                            ',',
                            ' '
                        ) }}
                        FCFA
                    </strong>

                </div>

                {{-- Sélection --}}
                <div class="form-check mt-3">

                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="frais-{{ $index }}"
                        wire:click="toggleFrais({{ $index }})"
                        @checked($frais['selectionne'])
                    >

                    <label
                        class="form-check-label"
                        for="frais-{{ $index }}"
                    >
                        Ajouter au panier
                    </label>

                </div>

            </div>

        </div>

    </div>

@endforeach

                </div>


                {{-- Aucun montant --}}

                @if(
                    collect($fraisDisponibles)
                        ->sum(
                            fn ($frais) =>
                                (float) ($frais['montant'] ?? 0)
                        ) <= 0
                )

                    <div class="alert alert-warning mt-3 mb-0">

                        <i class="bi bi-exclamation-triangle"></i>

                        Aucun montant n'est configuré pour les articles
                        sélectionnés dans cette classe et cette année scolaire.

                    </div>

                @endif

            </div>

        </div>

    @elseif(count($categoriesSelectionnees))

        <div class="alert alert-warning mb-4">

            <i class="bi bi-info-circle"></i>

            Aucun article n'est disponible pour les catégories
            sélectionnées.

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- PANIER --}}
    {{-- ========================================================= --}}

    @if(count($achats))

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h6 class="mb-0 fw-bold">

                    4. Panier

                </h6>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Catégorie
                                </th>

                                <th>
                                    Article
                                </th>

                                <th class="text-end">
                                    Montant
                                </th>

                                <th width="60">
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($achats as $index => $achat)

                                <tr>

                                    <td>

                                        <span class="badge bg-primary">

                                            {{ $achat['categorie'] }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $achat['nom'] }}

                                    </td>


                                    <td class="text-end">

                                        <strong>

                                            {{ number_format(
                                                (float) $achat['montant'],
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </strong>

                                    </td>


                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            wire:click="supprimerAchat({{ $index }})"
                                            title="Retirer du panier"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="card-footer">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        TOTAL
                    </strong>

                    <strong class="fs-5 text-success">

                        {{ number_format(
                            (float) $total,
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </strong>

                </div>

            </div>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- PAIEMENT --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <h6 class="mb-0 fw-bold">

                5. Paiement

            </h6>

        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- MODE DE PAIEMENT --}}

                <div class="col-md-6">

                    <label class="form-label">

                        Mode de paiement

                        <span class="text-danger">*</span>

                    </label>

                    <select
                        wire:model="modePaiement"
                        class="form-select @error('modePaiement') is-invalid @enderror"
                    >

                        <option value="">
                            -- Sélectionner --
                        </option>

                        <option value="Espèces">
                            Espèces
                        </option>

                        <option value="Mobile Money">
                            Mobile Money
                        </option>

                        <option value="Virement">
                            Virement
                        </option>

                        <option value="Chèque">
                            Chèque
                        </option>

                    </select>


                    @error('modePaiement')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- DATE --}}

                <div class="col-md-6">

                    <label class="form-label">

                        Date

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="date"
                        wire:model="datePaiement"
                        class="form-control @error('datePaiement') is-invalid @enderror"
                    >


                    @error('datePaiement')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- ERREURS PANIER --}}

            @error('achats')

                <div class="alert alert-danger mt-3">

                    <i class="bi bi-exclamation-triangle"></i>

                    {{ $message }}

                </div>

            @enderror


            {{-- TOTAL --}}

            <div class="d-flex justify-content-between align-items-center mt-4">

                <div>

                    <span class="text-muted">
                        Total à payer
                    </span>

                    <div class="fs-3 fw-bold">

                        {{ number_format(
                            (float) $total,
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </div>

                </div>


                {{-- BOUTON --}}

                <button
                    type="button"
                    wire:click="enregistrer"
                    wire:loading.attr="disabled"
                    wire:target="enregistrer"
                    class="btn btn-success btn-lg"
                    @disabled($total <= 0)
                >

                    <span
                        wire:loading
                        wire:target="enregistrer"
                    >

                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                        ></span>

                        Enregistrement...

                    </span>


                    <span
                        wire:loading.remove
                        wire:target="enregistrer"
                    >

                        <i class="bi bi-printer"></i>

                        Enregistrer et imprimer

                    </span>

                </button>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- MESSAGE DE SUCCÈS --}}
    {{-- ========================================================= --}}

    @if($successMessage)

        <div class="alert alert-success mt-4">

            <div>

                <i class="bi bi-check-circle-fill"></i>

                {{ $successMessage }}

            </div>


            @if($numeroRecuGenere)

                <div class="mt-2">

                    <strong>

                        Reçu :

                        {{ $numeroRecuGenere }}

                    </strong>

                </div>

            @endif

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- MESSAGES D'IMPRESSION --}}
    {{-- ========================================================= --}}

    <div
        x-data
        x-on:impressionEchouee.window="
            alert(
                'Le paiement a été enregistré, mais l’impression du reçu a échoué.'
            )
        "
    ></div>


    <div
        x-data
        x-on:impressionReussie.window=""
    ></div>

</div>