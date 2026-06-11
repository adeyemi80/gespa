@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-receipt"></i> Détails de la Recette</h3>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>ID :</strong>
                    <span>{{ $recette->id }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Date :</strong>
                    <span>{{ \Carbon\Carbon::parse($recette->date)->format('d/m/Y') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Libellé :</strong>
                    <span>{{ $recette->libelle }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <strong>Montant :</strong>
                    <span class="text-success fw-bold">{{ number_format($recette->montant, 2, ',', ' ') }} FCFA</span>
                </li>
            </ul>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('recettes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>
@endsection
