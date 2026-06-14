@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-eye"></i> Détail du tarif #{{ $tdTarif->id }}</span>
            <div class="d-flex gap-2">
                <a href="{{ route('td-tarifs.edit', $tdTarif) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="{{ route('td-tarifs.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3 text-muted">Identifiant</dt>
                <dd class="col-sm-9">#{{ $tdTarif->id }}</dd>

                <dt class="col-sm-3 text-muted">Année</dt>
                <dd class="col-sm-9">
                    {{ $tdTarif->annee->libelle ?? $tdTarif->annee->nom ?? '—' }}
                </dd>

                <dt class="col-sm-3 text-muted">Catégorie</dt>
                <dd class="col-sm-9">{{ $tdTarif->label_categorie }}</dd>

                <dt class="col-sm-3 text-muted">Type</dt>
                <dd class="col-sm-9">
                    <span class="badge
                        @if($tdTarif->type === 'seance') bg-info text-dark
                        @elseif($tdTarif->type === 'mois') bg-warning text-dark
                        @else bg-success
                        @endif">
                        {{ $tdTarif->label_type }}
                    </span>
                </dd>

                <dt class="col-sm-3 text-muted">Montant</dt>
                <dd class="col-sm-9 fw-semibold fs-5">
                    {{ number_format($tdTarif->montant, 0, ',', ' ') }} FCFA
                </dd>

                <dt class="col-sm-3 text-muted">Créé le</dt>
                <dd class="col-sm-9">{{ $tdTarif->created_at->format('d/m/Y à H:i') }}</dd>

                <dt class="col-sm-3 text-muted">Modifié le</dt>
                <dd class="col-sm-9">{{ $tdTarif->updated_at->format('d/m/Y à H:i') }}</dd>
            </dl>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <form action="{{ route('td-tarifs.destroy', $tdTarif) }}" method="POST"
                  onsubmit="return confirm('Supprimer définitivement ce tarif ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </form>
            <a href="{{ route('td-tarifs.edit', $tdTarif) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        </div>
    </div>
</div>
@endsection