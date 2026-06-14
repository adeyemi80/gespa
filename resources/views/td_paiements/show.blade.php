@extends('tableau.neutre')

@section('title', 'Détail paiement TD')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="max-width: 640px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('td-paiements.index', ['annee_id' => $tdPaiement->annee_id]) }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold">Détail du paiement</h4>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-bold">
            Reçu de paiement TD
        </div>
        <div class="card-body">

            <dl class="row mb-0">

                <dt class="col-sm-4 text-muted">Élève</dt>
                <dd class="col-sm-8 fw-semibold">
                    {{ $tdPaiement->eleve->nom }} {{ $tdPaiement->eleve->prenom }}
                </dd>

                <dt class="col-sm-4 text-muted">Année scolaire</dt>
                <dd class="col-sm-8">
                    {{ $tdPaiement->annee->libelle ?? $tdPaiement->annee->nom ?? $tdPaiement->annee_id }}
                </dd>

                <dt class="col-sm-4 text-muted">Date du paiement</dt>
                <dd class="col-sm-8">
                    {{ \Carbon\Carbon::parse($tdPaiement->date_paiement)->format('d/m/Y') }}
                </dd>

                <dt class="col-sm-4 text-muted">Montant</dt>
                <dd class="col-sm-8">
                    <span class="fs-5 fw-bold text-success">
                        {{ number_format($tdPaiement->montant, 0, ',', ' ') }} F
                    </span>
                </dd>

                <dt class="col-sm-4 text-muted">Référence</dt>
                <dd class="col-sm-8">{{ $tdPaiement->reference ?? '—' }}</dd>

                <dt class="col-sm-4 text-muted">Observation</dt>
                <dd class="col-sm-8">{{ $tdPaiement->observation ?? '—' }}</dd>

                <dt class="col-sm-4 text-muted">Enregistré le</dt>
                <dd class="col-sm-8 text-muted small">
                    {{ $tdPaiement->created_at->format('d/m/Y à H:i') }}
                </dd>

            </dl>

        </div>
        <div class="card-footer d-flex gap-2">
            <a href="{{ route('td-paiements.edit', $tdPaiement) }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>

            <form action="{{ route('td-paiements.destroy', $tdPaiement) }}"
                  method="POST"
                  onsubmit="return confirm('Supprimer ce paiement définitivement ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

</div>
@endsection