@extends('tableau.neutre')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-cash-coin"></i>
                Gestion des paiements
            </h3>

            <p class="text-muted mb-0">
                Liste des paiements scolaires enregistrés
            </p>
        </div>

        <a href="{{ route('paiements.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Nouveau paiement

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FILTRES --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('paiements.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Année --}}

                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Année scolaire
                        </label>

                        <select name="annee_id"
                                class="form-select">

                            <option value="">
                                Toutes les années
                            </option>

                            @foreach($annees as $annee)

                                <option value="{{ $annee->id }}"
                                    {{ request('annee_id') == $annee->id ? 'selected' : '' }}>

                                    {{ $annee->nom }}

                                    @if($annee->en_cours)
                                        (En cours)
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Recherche --}}

                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Recherche
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Nom, prénom, matricule, reçu, frais...">

                    </div>


                    {{-- Boutons --}}

                    <div class="col-md-4 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search"></i>
                            Rechercher

                        </button>

                        <a href="{{ route('paiements.index') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-clockwise"></i>
                            Réinitialiser

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TABLEAU --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <span>
                    <i class="bi bi-list-ul me-1"></i>
                    Liste des paiements
                </span>

                <span class="badge bg-light text-dark">

                    {{ $paiements->total() }}

                    paiement(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($paiements->count())

                <div class="table-responsive">

                    <table class="table table-hover table-striped align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>#</th>

                                <th>Source</th>

                                <th>Date</th>

                                <th>Reçu</th>

                                <th>Élève</th>

                                <th>Classe</th>

                                <th>Frais</th>

                                <th class="text-end">
                                    Montant
                                </th>

                                <th>Mode</th>

                                <th class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($paiements as $paiement)

                                <tr>

                                    {{-- ================================================= --}}
                                    {{-- NUMÉRO --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        {{ $paiements->firstItem() + $loop->index }}

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- SOURCE --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @if($paiement->source === 'paiement_achat')

                                            <span class="badge bg-info text-dark">

                                                <i class="bi bi-cart-check me-1"></i>

                                                Paiement achat

                                            </span>

                                        @else

                                            <span class="badge bg-success">

                                                <i class="bi bi-cash-stack me-1"></i>

                                                Paiement

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- DATE --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @if($paiement->date_paiement)

                                            {{ \Carbon\Carbon::parse(
                                                $paiement->date_paiement
                                            )->format('d/m/Y') }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- NUMÉRO REÇU --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @if($paiement->numero_recu)

                                            <span class="badge bg-secondary">

                                                {{ $paiement->numero_recu }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- ÉLÈVE --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @if($paiement->inscription?->eleve)

                                            <div class="fw-bold">

                                                {{ $paiement->inscription->eleve->nom }}

                                                {{ $paiement->inscription->eleve->prenom }}

                                            </div>


                                            @if($paiement->inscription->eleve->matricule)

                                                <small class="text-muted">

                                                    <i class="bi bi-person-badge me-1"></i>

                                                    {{ $paiement->inscription->eleve->matricule }}

                                                </small>

                                            @endif


                                        @elseif(
                                            $paiement->source === 'paiement_achat'
                                            &&
                                            ($paiement->nom || $paiement->prenom)
                                        )

                                            <div class="fw-bold">

                                                {{ $paiement->nom }}
                                                {{ $paiement->prenom }}

                                            </div>


                                            @if($paiement->telephone)

                                                <small class="text-muted">

                                                    <i class="bi bi-telephone me-1"></i>

                                                    {{ $paiement->telephone }}

                                                </small>

                                            @endif


                                        @else

                                            <span class="text-muted">
                                                Élève introuvable
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- CLASSE --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @if($paiement->inscription?->classe)

                                            {{ $paiement->inscription->classe->nom }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- FRAIS --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @if($paiement->frais)

                                            {{ $paiement->frais->nom }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- MONTANT --}}
                                    {{-- ================================================= --}}

                                    <td class="text-end fw-bold">

                                        {{ number_format(
                                            (float) ($paiement->montant_affiche ?? 0),
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        F

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- MODE DE PAIEMENT --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @php

                                            $mode = strtolower(
                                                trim($paiement->mode_paiement ?? '')
                                            );

                                        @endphp


                                        @if(
                                            $mode === 'especes' ||
                                            $mode === 'espèces'
                                        )

                                            <span class="badge bg-success">

                                                <i class="bi bi-cash me-1"></i>

                                                Espèces

                                            </span>


                                        @elseif(
                                            $mode === 'mobile_money' ||
                                            $mode === 'mobile money'
                                        )

                                            <span class="badge bg-warning text-dark">

                                                <i class="bi bi-phone me-1"></i>

                                                Mobile Money

                                            </span>


                                        @elseif($mode === 'virement')

                                            <span class="badge bg-info text-dark">

                                                <i class="bi bi-bank me-1"></i>

                                                Virement

                                            </span>


                                        @elseif(
                                            $mode === 'cheque' ||
                                            $mode === 'chèque'
                                        )

                                            <span class="badge bg-primary">

                                                <i class="bi bi-credit-card me-1"></i>

                                                Chèque

                                            </span>


                                        @else

                                            <span class="badge bg-secondary">

                                                {{ $paiement->mode_paiement ?? '-' }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- ACTIONS --}}
                                    {{-- ================================================= --}}

                                    <td class="text-center">

                                        <div class="btn-group"
                                             role="group">


                                            {{-- ========================================= --}}
                                            {{-- PAIEMENT CLASSIQUE --}}
                                            {{-- ========================================= --}}

                                            @if($paiement->source === 'paiement')

                                                {{-- Voir --}}

                                                @if(Route::has('paiements.show'))

                                                    <a href="{{ route(
                                                        'paiements.show',
                                                        $paiement
                                                    ) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Voir">

                                                        <i class="bi bi-eye"></i>

                                                    </a>

                                                @endif


                                                {{-- Modifier --}}

                                                @if(Route::has('paiements.edit'))

                                                    <a href="{{ route(
                                                        'paiements.edit',
                                                        $paiement
                                                    ) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Modifier">

                                                        <i class="bi bi-pencil"></i>

                                                    </a>

                                                @endif


                                                {{-- Supprimer --}}

                                                @if(Route::has('paiements.destroy'))

                                                    <form method="POST"
                                                          action="{{ route(
                                                              'paiements.destroy',
                                                              $paiement
                                                          ) }}"
                                                          class="d-inline"
                                                          onsubmit="return confirm(
                                                              'Voulez-vous vraiment supprimer ce paiement ?'
                                                          );">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Supprimer">

                                                            <i class="bi bi-trash"></i>

                                                        </button>

                                                    </form>

                                                @endif


                                            {{-- ========================================= --}}
                                            {{-- PAIEMENT ACHAT --}}
                                            {{-- ========================================= --}}

                                            @elseif($paiement->source === 'paiement_achat')

                                                {{-- Badge Achat --}}

                                                <span class="badge bg-info text-dark me-1"
                                                      title="Paiement achat">

                                                    <i class="bi bi-cart-check"></i>

                                                    Achat

                                                </span>


                                                {{-- Supprimer le reçu complet --}}

                                                @if(
                                                    !empty($paiement->numero_recu) &&
                                                    Route::has('paiements.achat.destroy')
                                                )

                                                    <form method="POST"
                                                          action="{{ route(
                                                              'paiements.achat.destroy',
                                                              [
                                                                  'numeroRecu' => $paiement->numero_recu
                                                              ]
                                                          ) }}"
                                                          class="d-inline"
                                                          onsubmit="return confirm(
                                                              'ATTENTION !\n\n' +
                                                              'Vous allez supprimer complètement le reçu {{ $paiement->numero_recu }}.\n\n' +
                                                              'Toutes les lignes de ce reçu seront supprimées et les montants correspondants seront retirés des frais de l\\'élève.\n\n' +
                                                              'Cette opération est irréversible.\n\n' +
                                                              'Voulez-vous continuer ?'
                                                          );">

                                                        @csrf

                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Supprimer le reçu complet">

                                                            <i class="bi bi-trash"></i>

                                                        </button>

                                                    </form>

                                                @endif

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- ========================================================= --}}
                {{-- PAGINATION --}}
                {{-- ========================================================= --}}

                @if($paiements->hasPages())

                    <div class="card-footer">

                        {{ $paiements->links() }}

                    </div>

                @endif


            @else

                {{-- ========================================================= --}}
                {{-- AUCUN RÉSULTAT --}}
                {{-- ========================================================= --}}

                <div class="text-center py-5">

                    <i class="bi bi-cash-stack fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        Aucun paiement trouvé
                    </h5>

                    <p class="text-muted">

                        Aucun paiement ne correspond
                        aux critères sélectionnés.

                    </p>

                    <a href="{{ route('paiements.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle me-1"></i>

                        Enregistrer un paiement

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection