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
                <div class="card-header bg-gradient text-white rounded-top-4"
                     style="background: linear-gradient(90deg, #6f42c1, #20c997);">
                    <h3 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i> Détails de la Transaction</h3>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Type :</strong>
                            @if($transaction->type == 'recette')
                                <span class="badge bg-success fs-6"><i class="bi bi-arrow-up-circle me-1"></i> Recette</span>
                            @else
                                <span class="badge bg-danger fs-6"><i class="bi bi-arrow-down-circle me-1"></i> Dépense</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Montant :</strong>
                            <span class="fw-semibold text-primary">{{ number_format($transaction->montant,2,',',' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Catégorie :</strong>
                            <span>{{ $transaction->categorie->nom ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Compte :</strong>
                            <span>{{ $transaction->compte->nom ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Date :</strong>
                            <span>{{ $transaction->date->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="card-footer text-end bg-light rounded-bottom-4">
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left-circle me-1"></i> Retour
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
