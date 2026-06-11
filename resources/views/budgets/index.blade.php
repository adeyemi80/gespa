@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header text-white d-flex justify-content-between align-items-center rounded-top-4"
             style="background: linear-gradient(90deg, #198754, #20c997);">
            <h4 class="mb-0">
                <i class="bi bi-wallet2 me-2"></i> Liste des Budgets
            </h4>
            <a href="{{ route('budgets.create') }}" class="btn btn-light btn-sm fw-bold">
                <i class="bi bi-plus-circle me-1"></i> Nouveau Budget
            </a>
        </div>

        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Année</th>
                            <th>Montant prévu</th>
                            <th>Période</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($budgets as $budget)
                        <tr>
                            <td class="fw-bold">{{ $budget->id }}</td>
                            <td>{{ $budget->nom }}</td>
                            <td>{{ $budget->categorie->nom ?? '-' }}</td>
                            <td>{{ $budget->annee->nom ?? '-' }}</td>
                            <td class="fw-semibold text-success">
                                {{ number_format($budget->montant_prevu, 0, ',', ' ') }} FCFA
                            </td>
                            <td>{{ $budget->periode }}</td>
                            <td>{{ $budget->created_at->format('d/m/Y') }}</td>
                            <td class="d-flex justify-content-center gap-1">
                                <a href="{{ route('budgets.edit', $budget) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('budgets.destroy', $budget) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Supprimer ce budget ?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                Aucun budget enregistré
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $budgets->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
