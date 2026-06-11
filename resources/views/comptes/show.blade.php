@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-white rounded-top-4"
                     style="background: linear-gradient(90deg, #0d6efd, #6610f2);">
                    <h3 class="mb-0"><i class="bi bi-card-list me-2"></i> Détails du Compte</h3>
                </div>

                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Nom :</span>
                            <span>{{ $compte->nom }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Type :</span>
                            <span>{{ $compte->type }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Solde initial :</span>
                            <span>{{ number_format($compte->solde_initial, 2, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Date de création :</span>
                            <span>{{ $compte->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('comptes.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left-circle me-1"></i> Retour
                    </a>
                    <a href="{{ route('comptes.edit', $compte->id) }}" class="btn btn-warning px-4">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
