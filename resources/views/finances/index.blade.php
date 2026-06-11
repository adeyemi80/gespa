@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
@php
$moisNoms = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];
@endphp
<div class="container mt-4">
    <h1 class="mb-4 text-center">Gestion des finances</h1>

    <!-- Résumé global -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total des Recettes</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalRecettes, 2, ',', ' ') }} FCFA</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Total des Dépenses</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalDepenses, 2, ',', ' ') }} FCFA</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white {{ $solde >=0 ? 'bg-primary' : 'bg-warning' }} mb-3">
                <div class="card-header">Solde</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($solde, 2, ',', ' ') }} FCFA</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets pour Recettes / Dépenses -->
    <ul class="nav nav-tabs mb-3" id="financeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="recettes-tab" data-bs-toggle="tab" data-bs-target="#recettes" type="button" role="tab">Recettes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="depenses-tab" data-bs-toggle="tab" data-bs-target="#depenses" type="button" role="tab">Dépenses</button>
        </li>
    </ul>

    <div class="tab-content" id="financeTabsContent">
        <!-- Recettes -->
        <div class="tab-pane fade show active" id="recettes" role="tabpanel">
            <h4>Par jour</h4>
            <table class="table table-striped table-bordered mb-3">
                <thead class="table-dark">
                    <tr><th>Jour</th><th>Total des Recettes</th></tr>
                </thead>
                <tbody>
                    @foreach($recettesParJour as $r)
                    <tr>
                        <td>{{ $r->jour }}</td>
                        <td>{{ number_format($r->total, 2, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4>Par mois</h4>
            <table class="table table-striped table-bordered mb-3">
                <thead class="table-dark">
                    <tr><th>Année</th><th>Mois</th><th>Total des Recettes</th></tr>
                </thead>
                <tbody>
                    @foreach($recettesParMois as $r)
                    <tr>
                        <td>{{ $r->annee }}</td>
                        <td>{{ $moisNoms[$r->mois] ?? 'Inconnu' }}</td>
                        <td>{{ number_format($r->total, 2, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4>Par année</h4>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr><th>Année</th><th>Total des Recettes</th></tr>
                </thead>
                <tbody>
                    @foreach($recettesParAnnee as $r)
                    <tr>
                        <td>{{ $r->annee }}</td>
                        <td>{{ number_format($r->total, 2, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Dépenses -->
        <div class="tab-pane fade" id="depenses" role="tabpanel">
            <h4>Par jour</h4>
            <table class="table table-striped table-bordered mb-3">
                <thead class="table-dark">
                    <tr><th>Jour</th><th>Total des Dépenses</th></tr>
                </thead>
                <tbody>
                    @foreach($depensesParJour as $d)
                    <tr>
                        <td>{{ $d->jour }}</td>
                        <td>{{ number_format($d->total, 2, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4>Par mois</h4>
            <table class="table table-striped table-bordered mb-3">
                <thead class="table-dark">
                    <tr><th>Année</th><th>Mois</th><th>Total des Dépenses</th></tr>
                </thead>
                <tbody>
                    @foreach($depensesParMois as $d)
                    <tr>
                        <td>{{ $d->annee }}</td>
                        <td>{{ $moisNoms[$d->mois] ?? 'Inconnu' }}</td>
                        <td>{{ number_format($d->total, 2, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4>Par année</h4>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr><th>Année</th><th>Total des Dépenses</th></tr>
                </thead>
                <tbody>
                    @foreach($depensesParAnnee as $d)
                    <tr>
                        <td>{{ $d->annee }}</td>
                        <td>{{ number_format($d->total, 2, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
