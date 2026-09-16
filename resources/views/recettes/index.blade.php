```blade
@extends('tableau.neutre')

@section('content')

<div class="container-fluid">

    {{-- =========================
        EN-TÊTE
    ========================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                <i class="bi bi-cash-stack"></i>
                Gestion des recettes
            </h2>

            <p class="text-muted mb-0">
                Liste des recettes enregistrées
            </p>
        </div>

        <a href="{{ route('recettes.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Nouvelle recette
        </a>

    </div>


    {{-- =========================
        MESSAGE DE SUCCÈS
    ========================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================
        ERREURS
    ========================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                <i class="bi bi-exclamation-triangle"></i>
                Une erreur est survenue
            </strong>

            <ul class="mb-0 mt-2">

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


    {{-- =========================
        FILTRES
    ========================== --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-light">

            <strong>
                <i class="bi bi-funnel"></i>
                Filtres
            </strong>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('recettes.index') }}">

                <div class="row g-3">

                    {{-- Année --}}
                    <div class="col-md-5">

                        <label class="form-label">
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

                                    {{ $annee->libelle ?? $annee->nom ?? $annee->annee }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Catégorie --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Catégorie de recette
                        </label>

                        <select name="categorie_recette_id"
                                class="form-select">

                            <option value="">
                                Toutes les catégories
                            </option>

                            @foreach($categories as $categorie)

                                <option value="{{ $categorie->id }}"
                                    {{ request('categorie_recette_id') == $categorie->id ? 'selected' : '' }}>

                                    {{ $categorie->nom }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Boutons --}}
                    <div class="col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary flex-fill">

                                <i class="bi bi-search"></i>
                                Filtrer

                            </button>

                            <a href="{{ route('recettes.index') }}"
                               class="btn btn-outline-secondary">

                                <i class="bi bi-arrow-clockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================
        TABLEAU
    ========================== --}}
    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                <i class="bi bi-list-ul"></i>
                Liste des recettes
            </strong>

            <span class="badge bg-primary">

                {{ $recettes->total() }}
                recette(s)

            </span>

        </div>


        <div class="card-body p-0">

            @if($recettes->count())

                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th width="50">
                                    #
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    N° reçu
                                </th>

                                <th>
                                    Élève / Inscription
                                </th>

                                <th>
                                    Catégorie
                                </th>

                                <th class="text-end">
                                    Montant
                                </th>

                                <th>
                                    Mode
                                </th>

                                <th>
                                    Origine
                                </th>

                                <th>
                                    Année
                                </th>

                                <th width="140"
                                    class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($recettes as $recette)

                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $recette->id }}
                                    </td>


                                    {{-- DATE --}}
                                    <td>

                                        {{ $recette->date_paiement
                                            ? \Carbon\Carbon::parse($recette->date_paiement)->format('d/m/Y')
                                            : '-' }}

                                    </td>


                                    {{-- NUMÉRO REÇU --}}
                                    <td>

                                        <span class="fw-semibold">

                                            {{ $recette->numero_recu ?? '-' }}

                                        </span>

                                    </td>


                                    {{-- INSCRIPTION --}}
                                    <td>

                                        @if($recette->inscription)

                                            @php
                                                $eleve = $recette->inscription->eleve ?? null;
                                            @endphp

                                            @if($eleve)

                                                <div class="fw-semibold">

                                                    {{ $eleve->nom }}
                                                    {{ $eleve->prenom }}

                                                </div>

                                                <small class="text-muted">

                                                    Matricule :
                                                    {{ $eleve->matricule ?? '-' }}

                                                </small>

                                            @else

                                                <span class="text-muted">
                                                    Inscription #{{ $recette->inscription_id }}
                                                </span>

                                            @endif

                                        @else

                                            <span class="badge bg-secondary">

                                                Achat

                                            </span>

                                        @endif

                                    </td>


                                    {{-- CATÉGORIE --}}
                                    <td>

                                        @if($recette->categorieRecette)

                                            <span class="badge bg-info text-dark">

                                                {{ $recette->categorieRecette->nom }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- MONTANT --}}
                                    <td class="text-end">

                                        <strong class="text-success">

                                            {{ number_format(
                                                (float) $recette->montant_verse,
                                                2,
                                                ',',
                                                ' '
                                            ) }}

                                        </strong>

                                        <small>
                                            FCFA
                                        </small>

                                    </td>


                                    {{-- MODE DE PAIEMENT --}}
                                    <td>

                                        @php
                                            $mode = strtolower(trim($recette->mode_paiement ?? ''));
                                        @endphp

                                        @if($mode === 'espèces' || $mode === 'especes')

                                            <span class="badge bg-success">
                                                <i class="bi bi-cash"></i>
                                                {{ $recette->mode_paiement }}
                                            </span>

                                        @elseif($mode === 'mobile money')

                                            <span class="badge bg-primary">
                                                <i class="bi bi-phone"></i>
                                                {{ $recette->mode_paiement }}
                                            </span>

                                        @elseif($mode === 'chèque' || $mode === 'cheque')

                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-receipt"></i>
                                                {{ $recette->mode_paiement }}
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                {{ $recette->mode_paiement ?? '-' }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ORIGINE --}}
                                    <td>

                                        @if($recette->paiement_id)

                                            <span class="badge bg-primary">

                                                <i class="bi bi-mortarboard"></i>
                                                Scolarité

                                            </span>

                                            <br>

                                            <small class="text-muted">

                                                Paiement #{{ $recette->paiement_id }}

                                            </small>

                                        @elseif($recette->paiement_achat_id)

                                            <span class="badge bg-warning text-dark">

                                                <i class="bi bi-cart"></i>
                                                Achat

                                            </span>

                                            <br>

                                            <small class="text-muted">

                                                Paiement achat
                                                #{{ $recette->paiement_achat_id }}

                                            </small>

                                        @else

                                            <span class="badge bg-secondary">

                                                Inconnue

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ANNÉE --}}
                                    <td>

                                        @if($recette->annee)

                                            {{ $recette->annee->libelle
                                                ?? $recette->annee->nom
                                                ?? $recette->annee->annee
                                                ?? $recette->annee_id }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTIONS --}}
                                    <td>

                                        <div class="d-flex justify-content-center gap-1">

                                            {{-- Voir --}}
                                            <a href="{{ route('recettes.show', $recette) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="Voir">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            {{-- Modifier --}}
                                           {{-- <a href="{{ route('recettes.edit', $recette) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Modifier">

                                                <i class="bi bi-pencil"></i>

                                            </a>--}}


                                            {{-- Supprimer --}}
                                            {{--<form action="{{ route('recettes.destroy', $recette) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cette recette ? Cette opération peut avoir des conséquences sur le paiement associé.');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Supprimer">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>--}}

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- AUCUNE RECETTE --}}
                <div class="text-center py-5">

                    <i class="bi bi-inbox display-4 text-muted"></i>

                    <h5 class="mt-3">
                        Aucune recette trouvée
                    </h5>

                    <p class="text-muted">
                        Aucune recette ne correspond aux critères sélectionnés.
                    </p>

                    <a href="{{ route('recettes.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-circle"></i>
                        Ajouter une recette

                    </a>

                </div>

            @endif

        </div>


        {{-- =========================
            PAGINATION
        ========================== --}}
        @if($recettes->hasPages())

            <div class="card-footer">

                {{ $recettes->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
```
