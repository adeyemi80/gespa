@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <h3 class="mb-4 text-primary">📊 PAIEMENTS CUMULES</h3>

    {{-- Paiements par Jour --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">Par Jour</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total versé (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiementsParJour as $jour)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($jour->jour)->format('d/m/Y') }}</td>
                            <td>{{ number_format($jour->total, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paiements par Mois --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">Par Mois</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Mois / Année</th>
                        <th>Total versé (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiementsParMois as $mois)
                        <tr>
                            <td>{{ \Carbon\Carbon::create($mois->annee, $mois->mois, 1)->format('F Y') }}</td>
                            <td>{{ number_format($mois->total, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paiements par Année --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-white">Par Année</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Année</th>
                        <th>Total versé (FCFA)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiementsParAnnee as $annee)
                        <tr>
                            <td>{{ $annee->annee }}</td>
                            <td>{{ number_format($annee->total, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
