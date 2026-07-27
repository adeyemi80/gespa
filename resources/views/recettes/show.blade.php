{{-- resources/views/recettes/show.blade.php --}}
@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Détail de la recette</h1>
                    <p class="text-muted mb-0 small">Reçu n° {{ $recette->numero_recu ?? '—' }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('recettes.edit', $recette) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Modifier
                    </a>
                    <a href="{{ route('recettes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Date de paiement</span>
                            <span class="fw-semibold">{{ $recette->date_paiement->format('d/m/Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Montant versé</span>
                            <span class="fw-bold text-success fs-5">{{ number_format($recette->montant_verse, 2, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Catégorie</span>
                            <span>
                                @if ($recette->categorieRecette)
                                    <span class="badge bg-primary-subtle text-primary-emphasis fw-semibold">
                                        {{ $recette->categorieRecette->nom }}
                                    </span>
                                @else
                                    <span class="fw-medium text-muted">—</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Année</span>
                            <span class="fw-medium">{{ $recette->annee?->nom ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Mode de paiement</span>
                            <span>
                                @if ($recette->mode_paiement)
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                        {{ $recette->mode_paiement }}
                                    </span>
                                @else
                                    <span class="fw-medium text-muted">—</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">N° reçu</span>
                            <span class="fw-medium">{{ $recette->numero_recu ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Inscription</span>
                            <span class="fw-medium">{{ $recette->inscription->eleve->nom }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <span class="text-muted">Paiement lié</span>
                            <span class="fw-medium">{{ $recette->paiement_id }}</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection