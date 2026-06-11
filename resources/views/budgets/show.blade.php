@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow-lg border-0 rounded-4">

                {{-- En-tête --}}
                <div class="card-header text-white rounded-top-4"
                     style="background: linear-gradient(90deg, #0d6efd, #20c997);">
                    <h4 class="mb-0">
                        <i class="bi bi-wallet2 me-2"></i> Détails du Budget
                    </h4>
                </div>

                {{-- Corps --}}
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4 text-center">
                        {{ $budget->nom }}
                    </h4>

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Catégorie</span>
                            <span>{{ $budget->categorie->nom ?? 'N/A' }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Année scolaire</span>
                            <span>{{ $budget->annee->numfmt_get_locale ?? 'N/A' }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Montant prévu</span>
                            <span class="fw-bold text-success">
                                {{ number_format($budget->montant_prevu, 0, ',', ' ') }} FCFA
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Période</span>
                            <span>{{ $budget->periode }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">Créé le</span>
                            <span>{{ $budget->created_at->format('d/m/Y à H:i') }}</span>
                        </li>

                    </ul>
                </div>

                {{-- Pied --}}
                <div class="card-footer bg-light text-end rounded-bottom-4">
                    <a href="{{ route('budgets.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left-circle me-1"></i> Retour
                    </a>

                    <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
