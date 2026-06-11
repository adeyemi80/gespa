@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-info text-white d-flex align-items-center">
            <i class="bi bi-receipt me-2 fs-4"></i>
            <h3 class="mb-0">Détails de la Dépense</h3>
        </div>

        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>ID :</strong>
                    <span>{{ $depense->id }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Date :</strong>
                    <span>{{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Libellé :</strong>
                    <span>{{ $depense->libelle }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Montant :</strong>
                    <span class="text-danger fw-bold">{{ number_format($depense->montant, 2, ',', ' ') }} FCFA</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Catégorie :</strong>
                    <span>{{ $depense->categorie ?? '-' }}</span>
                </li>
            </ul>

            <div class="text-end mt-3">
                <a href="{{ route('depenses.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
