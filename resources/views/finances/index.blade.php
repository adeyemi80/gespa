@extends('tableau.neutre')

@section('content')

<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>

@php
    $moisNoms = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];
@endphp

<div class="container mt-4">

    <!-- TITRE -->
    <h1 class="mb-4 text-center">
        Gestion des finances
    </h1>

    <!-- ========================= -->
    <!-- SÉLECTION DE L'ANNÉE -->
    <!-- ========================= -->

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <strong>Filtrer les finances par année scolaire</strong>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('finances.index') }}">

                <div class="row align-items-end">

                    <div class="col-md-8">

                        <label for="annee_id" class="form-label fw-bold">
                            Année scolaire
                        </label>

                        <select
                            name="annee_id"
                            id="annee_id"
                            class="form-select"
                            onchange="this.form.submit()"
                            required
                        >

                            @foreach($annees as $annee)

                                <option
                                    value="{{ $annee->id }}"
                                    {{ (string)$anneeId === (string)$annee->id ? 'selected' : '' }}
                                >

                                    {{ $annee->nom }}

                                    @if($annee->en_cours)
                                        — Année en cours
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        @if($anneeSelectionnee)

                            <div class="alert alert-info mb-0">
                                <strong>Année affichée :</strong><br>

                                {{ $anneeSelectionnee->nom }}

                                @if($anneeSelectionnee->en_cours)
                                    <span class="badge bg-success ms-2">
                                        En cours
                                    </span>
                                @endif
                            </div>

                        @endif

                    </div>

                </div>

            </form>

        </div>
    </div>


    <!-- ========================= -->
    <!-- RÉSUMÉ FINANCIER -->
    <!-- ========================= -->

    <div class="row mb-4">

        <!-- RECETTES -->
        <div class="col-md-4">

            <div class="card text-white bg-success mb-3 shadow-sm">

                <div class="card-header">
                    Total des Recettes
                </div>

                <div class="card-body">

                    <h5 class="card-title">
                        {{ number_format($totalRecettes, 2, ',', ' ') }}
                        FCFA
                    </h5>

                </div>

            </div>

        </div>


        <!-- DÉPENSES -->
        <div class="col-md-4">

            <div class="card text-white bg-danger mb-3 shadow-sm">

                <div class="card-header">
                    Total des Dépenses
                </div>

                <div class="card-body">

                    <h5 class="card-title">
                        {{ number_format($totalDepenses, 2, ',', ' ') }}
                        FCFA
                    </h5>

                </div>

            </div>

        </div>


        <!-- SOLDE -->
        <div class="col-md-4">

            <div class="card text-white
                {{ $solde >= 0 ? 'bg-primary' : 'bg-warning' }}
                mb-3 shadow-sm">

                <div class="card-header">
                    Solde
                </div>

                <div class="card-body">

                    <h5 class="card-title">

                        {{ number_format($solde, 2, ',', ' ') }}
                        FCFA

                    </h5>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================= -->
    <!-- ONGLET RECETTES / DEPENSES -->
    <!-- ========================= -->

    <ul class="nav nav-tabs mb-3" id="financeTabs" role="tablist">

        <!-- RECETTES -->
        <li class="nav-item" role="presentation">

            <button
                class="nav-link active"
                id="recettes-tab"
                data-bs-toggle="tab"
                data-bs-target="#recettes"
                type="button"
                role="tab">

                💰 Recettes

            </button>

        </li>


        <!-- DEPENSES -->
        <li class="nav-item" role="presentation">

            <button
                class="nav-link"
                id="depenses-tab"
                data-bs-toggle="tab"
                data-bs-target="#depenses"
                type="button"
                role="tab">

                💸 Dépenses

            </button>

        </li>

    </ul>


    <div class="tab-content" id="financeTabsContent">


        <!-- ===================================================== -->
        <!-- RECETTES -->
        <!-- ===================================================== -->

        <div
            class="tab-pane fade show active"
            id="recettes"
            role="tabpanel"
            aria-labelledby="recettes-tab">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-success text-white">

                    <h4 class="mb-0">
                        Recettes
                        @if($anneeSelectionnee)
                            — {{ $anneeSelectionnee->nom }}
                        @endif
                    </h4>

                </div>

                <div class="card-body">


                    <!-- RECETTES PAR JOUR -->

                    <h5 class="mb-3">
                        📅 Recettes par jour
                    </h5>

                    @if($recettesParJour->count() > 0)

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered mb-4">

                                <thead class="table-dark">

                                    <tr>
                                        <th>Jour</th>
                                        <th>Total des Recettes</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($recettesParJour as $r)

                                        <tr>

                                            <td>
                                                {{ \Carbon\Carbon::parse($r->jour)->format('d/m/Y') }}
                                            </td>

                                            <td class="fw-bold">
                                                {{ number_format($r->total, 2, ',', ' ') }}
                                                FCFA
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-info">
                            Aucune recette enregistrée pour cette année scolaire.
                        </div>

                    @endif


                    <!-- RECETTES PAR MOIS -->

                    <h5 class="mb-3">
                        📊 Recettes par mois
                    </h5>

                    @if($recettesParMois->count() > 0)

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered mb-4">

                                <thead class="table-dark">

                                    <tr>
                                        <th>Année</th>
                                        <th>Mois</th>
                                        <th>Total des Recettes</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($recettesParMois as $r)

                                        <tr>

                                            <td>
                                                {{ (int) $r->annee }}
                                            </td>

                                            <td>
                                                {{ $moisNoms[(int)$r->mois] ?? 'Inconnu' }}
                                            </td>

                                            <td class="fw-bold">
                                                {{ number_format($r->total, 2, ',', ' ') }}
                                                FCFA
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-info">
                            Aucune recette mensuelle disponible.
                        </div>

                    @endif


                    <!-- RECETTES PAR ANNÉE -->

                    <h5 class="mb-3">
                        📈 Total des recettes
                    </h5>

                    @if($recettesParAnnee->count() > 0)

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered">

                                <thead class="table-dark">

                                    <tr>
                                        <th>Année</th>
                                        <th>Total des Recettes</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($recettesParAnnee as $r)

                                        <tr>

                                            <td>
                                                {{ (int) $r->annee }}
                                            </td>

                                            <td class="fw-bold text-success">
                                                {{ number_format($r->total, 2, ',', ' ') }}
                                                FCFA
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-info">
                            Aucun total annuel de recette disponible.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- DEPENSES -->
        <!-- ===================================================== -->

        <div
            class="tab-pane fade"
            id="depenses"
            role="tabpanel"
            aria-labelledby="depenses-tab">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-danger text-white">

                    <h4 class="mb-0">
                        Dépenses
                        @if($anneeSelectionnee)
                            — {{ $anneeSelectionnee->nom }}
                        @endif
                    </h4>

                </div>

                <div class="card-body">


                    <!-- DEPENSES PAR JOUR -->

                    <h5 class="mb-3">
                        📅 Dépenses par jour
                    </h5>

                    @if($depensesParJour->count() > 0)

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered mb-4">

                                <thead class="table-dark">

                                    <tr>
                                        <th>Jour</th>
                                        <th>Total des Dépenses</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($depensesParJour as $d)

                                        <tr>

                                            <td>
                                                {{ \Carbon\Carbon::parse($d->jour)->format('d/m/Y') }}
                                            </td>

                                            <td class="fw-bold">
                                                {{ number_format($d->total, 2, ',', ' ') }}
                                                FCFA
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-info">
                            Aucune dépense enregistrée pour cette année scolaire.
                        </div>

                    @endif


                    <!-- DEPENSES PAR MOIS -->

                    <h5 class="mb-3">
                        📊 Dépenses par mois
                    </h5>

                    @if($depensesParMois->count() > 0)

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered mb-4">

                                <thead class="table-dark">

                                    <tr>
                                        <th>Année</th>
                                        <th>Mois</th>
                                        <th>Total des Dépenses</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($depensesParMois as $d)

                                        <tr>

                                            <td>
                                                {{ (int) $d->annee }}
                                            </td>

                                            <td>
                                                {{ $moisNoms[(int)$d->mois] ?? 'Inconnu' }}
                                            </td>

                                            <td class="fw-bold">
                                                {{ number_format($d->total, 2, ',', ' ') }}
                                                FCFA
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-info">
                            Aucune dépense mensuelle disponible.
                        </div>

                    @endif


                    <!-- DEPENSES PAR ANNÉE -->

                    <h5 class="mb-3">
                        📈 Total des dépenses
                    </h5>

                    @if($depensesParAnnee->count() > 0)

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered">

                                <thead class="table-dark">

                                    <tr>
                                        <th>Année</th>
                                        <th>Total des Dépenses</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($depensesParAnnee as $d)

                                        <tr>

                                            <td>
                                                {{ (int) $d->annee }}
                                            </td>

                                            <td class="fw-bold text-danger">
                                                {{ number_format($d->total, 2, ',', ' ') }}
                                                FCFA
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="alert alert-info">
                            Aucun total annuel de dépense disponible.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection