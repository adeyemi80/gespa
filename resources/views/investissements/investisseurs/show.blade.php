@extends('tableau.neutre')

@section('content')

<div class="container-fluid py-4">

    <!-- Bouton Retour -->
    <div class="mb-4">
        <button
            onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
            class="btn btn-secondary btn-lg shadow-sm">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </button>
    </div>

    <!-- En-tête -->
    <div class="card shadow border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-person-circle text-primary"></i>
                        {{ $investisseur->nom }} {{ $investisseur->prenom }}
                    </h2>

                    <p class="text-muted fs-5 mb-0">
                        {{ $investisseur->profession ?: 'Investisseur' }}
                    </p>

                </div>

                <div>

                    <a href="{{ route('investisseurs.edit',$investisseur) }}"
                       class="btn btn-warning btn-lg">
                        <i class="bi bi-pencil-square"></i>
                        Modifier
                    </a>

                    <a href="{{ route('investisseurs.index') }}"
                       class="btn btn-dark btn-lg">
                        <i class="bi bi-list"></i>
                        Liste
                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- Coordonnées -->
    <div class="card shadow border-0 mb-4">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-telephone"></i>
                Coordonnées
            </h4>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="fw-bold fs-5">Téléphone</label>
                    <div class="form-control form-control-lg bg-light">
                        {{ $investisseur->telephone ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold fs-5">Email</label>
                    <div class="form-control form-control-lg bg-light">
                        {{ $investisseur->email ?: '—' }}
                    </div>
                </div>

                <div class="col-12">
                    <label class="fw-bold fs-5">Adresse</label>
                    <div class="form-control form-control-lg bg-light" style="min-height:80px">
                        {{ $investisseur->adresse ?: '—' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    @php
        $totalVerse = $investisseur->investissements->flatMap->versements->sum('montant');
        $totalRetire = $investisseur->investissements->flatMap->retraitsCapital->sum('montant');
        $capitalDisponible = $totalVerse - $totalRetire;
    @endphp

    <!-- Statistiques -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0 text-center">

                <div class="card-body">

                    <h6 class="text-muted">Total versé</h6>

                    <h3 class="fw-bold text-primary">
                        {{ number_format($totalVerse,0,',',' ') }}
                    </h3>

                    <div>F CFA</div>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0 text-center">

                <div class="card-body">

                    <h6 class="text-muted">Total retiré</h6>

                    <h3 class="fw-bold text-danger">
                        {{ number_format($totalRetire,0,',',' ') }}
                    </h3>

                    <div>F CFA</div>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0 text-center">

                <div class="card-body">

                    <h6 class="text-muted">Capital disponible</h6>

                    <h3 class="fw-bold text-success">
                        {{ number_format($capitalDisponible,0,',',' ') }}
                    </h3>

                    <div>F CFA</div>

                </div>

            </div>

        </div>

    </div>

    <!-- Tableau -->
    <div class="card shadow border-0">

        <div class="card-header bg-success text-white">

            <h4 class="mb-0">
                <i class="bi bi-cash-stack"></i>
                Investissements ({{ $investisseur->investissements->count() }})
            </h4>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr class="text-center">

                        <th>Investissement</th>
                        <th>Statut</th>
                        <th>Total versé</th>
                        <th>Total retiré</th>
                        <th>Capital disponible</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($investisseur->investissements as $investissement)

                        @php
                            $verseInv = $investissement->versements->sum('montant');
                            $retireInv = $investissement->retraitsCapital->sum('montant');
                        @endphp

                        <tr>

                            <td class="fw-bold">
                                #{{ $investissement->id }}
                            </td>

                            <td class="text-center">

                                @if($investissement->statut=='actif')

                                    <span class="badge bg-success fs-6">
                                        Actif
                                    </span>

                                @else

                                    <span class="badge bg-secondary fs-6">
                                        {{ ucfirst($investissement->statut) }}
                                    </span>

                                @endif

                            </td>

                            <td class="text-end fw-semibold">
                                {{ number_format($verseInv,0,',',' ') }} F CFA
                            </td>

                            <td class="text-end fw-semibold text-danger">
                                {{ number_format($retireInv,0,',',' ') }} F CFA
                            </td>

                            <td class="text-end fw-bold text-success">
                                {{ number_format($verseInv-$retireInv,0,',',' ') }} F CFA
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-5 text-muted fs-4">

                                <i class="bi bi-inbox"></i><br>

                                Aucun investissement trouvé.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection