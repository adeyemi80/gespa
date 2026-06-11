@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-info text-white rounded-top-4">
            <h4 class="mb-0">📊 Détails de l'opération</h4>
        </div>
        <div class="card-body p-4">
            <ul class="list-group list-group-flush fs-5">
                <li class="list-group-item"><strong>📅 Date :</strong> {{ $operation->date }}</li>
                <li class="list-group-item"><strong>🏷️ Libellé :</strong> {{ $operation->libelle }}</li>
                <li class="list-group-item"><strong>💰 Montant :</strong> 
                    <span class="badge {{ $operation->categorie == 'recette' ? 'bg-success' : 'bg-danger' }} fs-6">
                        {{ number_format($operation->montant, 2, ',', ' ') }} FCFA
                    </span>
                </li>
                <li class="list-group-item"><strong>📂 Catégorie :</strong> {{ ucfirst($operation->categorie) }}</li>
                <li class="list-group-item"><strong>📝 Description :</strong> {{ $operation->description ?? '---' }}</li>
            </ul>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('operations.index') }}" class="btn btn-secondary btn-lg">⬅️ Retour</a>
            <a href="{{ route('operations.edit', $operation->id) }}" class="btn btn-warning btn-lg">✏️ Modifier</a>
        </div>
    </div>
</div>
@endsection
