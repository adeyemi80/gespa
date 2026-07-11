@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold text-primary mb-1">
                💰 Gestion des versements
            </h2>
            <p class="text-muted mb-0">
                Historique des versements des investisseurs
            </p>
        </div>

        <div>

            <a href="{{ route('versements.export-pdf', request()->query()) }}"
               class="btn btn-danger me-2">
                <i class="bi bi-file-earmark-pdf"></i>
                Exporter PDF
            </a>

            <a href="{{ route('versements.create') }}"
               class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Nouveau versement
            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif


    <!-- Filtres -->

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <strong>
                <i class="bi bi-funnel"></i>
                Filtres de recherche
            </strong>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('versements.index') }}">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">

                            Recherche

                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Nom, prénom ou téléphone">

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">

                            Investissement

                        </label>

                        <select
                            name="investissement_id"
                            class="form-select">

                            <option value="">

                                Tous les investissements

                            </option>

                            @foreach($investissements as $investissement)

                                <option
                                    value="{{ $investissement->id }}"
                                    {{ request('investissement_id')==$investissement->id?'selected':'' }}>

                                    {{ $investissement->investisseur->nom ?? '' }}
                                    {{ $investissement->investisseur->prenom ?? '' }}

                                    - #{{ $investissement->id }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2 mb-3">

                        <label class="form-label fw-bold">

                            Du

                        </label>

                        <input
                            type="date"
                            name="date_debut"
                            value="{{ request('date_debut') }}"
                            class="form-control">

                    </div>


                    <div class="col-md-2 mb-3">

                        <label class="form-label fw-bold">

                            Au

                        </label>

                        <input
                            type="date"
                            name="date_fin"
                            value="{{ request('date_fin') }}"
                            class="form-control">

                    </div>


                    <div class="col-md-1 d-grid mb-3">

                        <label class="form-label">&nbsp;</label>

                        <button class="btn btn-dark">

                            Filtrer

                        </button>

                    </div>

                </div>

                @if(request()->filled('search')
                || request()->filled('investissement_id')
                || request()->filled('date_debut')
                || request()->filled('date_fin'))

                    <a href="{{ route('versements.index') }}"
                       class="btn btn-outline-secondary">

                        Réinitialiser

                    </a>

                @endif

            </form>

        </div>

    </div>


    <!-- Tableau -->

    <div class="card shadow">

        <div class="card-header bg-success text-white">

            <strong>

                <i class="bi bi-table"></i>

                Liste des versements

            </strong>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                    <tr>

                        <th>Date</th>

                        <th>Investisseur</th>

                        <th>Investissement</th>

                        <th class="text-end">Montant</th>

                        <th>Mode</th>

                        <th>Référence</th>

                        <th width="220">Actions</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($versements as $versement)

                        <tr>

                            <td>

                                {{ \Carbon\Carbon::parse($versement->date_versement)->format('d/m/Y') }}

                            </td>

                            <td>

                                <strong>

                                    {{ $versement->investissement->investisseur->nom ?? '' }}

                                    {{ $versement->investissement->investisseur->prenom ?? '' }}

                                </strong>

                            </td>

                            <td>

                                <span class="badge bg-primary">

                                    #{{ $versement->investissement_id }}

                                </span>

                            </td>

                            <td class="text-end text-success fw-bold">

                                {{ number_format($versement->montant,0,',',' ') }}

                                FCFA

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $versement->mode_paiement ?: 'Non renseigné' }}

                                </span>

                            </td>

                            <td>

                                {{ $versement->reference ?: '-' }}

                            </td>

                            <td class="text-center">

    <a href="{{ route('versements.show', $versement) }}"
       class="btn btn-sm btn-info"
       title="Voir">
        <i class="bi bi-eye-fill"></i>
    </a>

    <a href="{{ route('versements.edit', $versement) }}"
       class="btn btn-sm btn-warning"
       title="Modifier">
        <i class="bi bi-pencil-square"></i>
    </a>

    <form action="{{ route('versements.destroy', $versement) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Supprimer ce versement ?');">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-sm btn-danger"
                title="Supprimer">
            <i class="bi bi-trash-fill"></i>
        </button>

    </form>

</td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-4">

                                Aucun versement trouvé.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <div class="mt-3">

        {{ $versements->appends(request()->query())->links() }}

    </div>

</div>

@endsection