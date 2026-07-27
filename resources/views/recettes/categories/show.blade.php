@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            {{ $categorie->nom }}
            <span class="badge bg-secondary align-middle">{{ $categorie->code }}</span>
            @if ($categorie->actif)
                <span class="badge bg-success align-middle">Active</span>
            @else
                <span class="badge bg-secondary align-middle">Inactive</span>
            @endif
        </h1>
        <div>
            <a href="{{ route('categories-recettes.edit', $categorie) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('categories-recettes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @if ($categorie->description)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted">Description</h6>
                <p class="mb-0">{{ $categorie->description }}</p>
            </div>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Budgets rattachés</span>
            <a href="{{ route('budgets-recettes.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i> Gérer les budgets
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Année scolaire</th>
                        <th class="text-end">Prévu</th>
                        <th class="text-end">Réalisé</th>
                        <th class="text-center">Taux</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorie->budgets as $budget)
                        <tr>
                            <td>{{ $budget->annee->nom }}</td>
                            <td class="text-end">{{ number_format($budget->montant_prevu, 0, ',', ' ') }} FCFA</td>
                            <td class="text-end">{{ number_format($budget->montant_realise, 0, ',', ' ') }} FCFA</td>
                            <td class="text-center">{{ $budget->taux_realisation }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Aucun budget rattaché à cette catégorie.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection