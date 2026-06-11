@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">👁️ Détails du frais de l’élève</h5>
        </div>

        <div class="card-body">

            {{-- Informations élève --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Élève :</strong><br>
                    {{ $inscription_frai->inscription->eleve->nom ?? '—' }}
                    {{ $inscription_frai->inscription->eleve->prenom ?? '' }}
                </div>

                <div class="col-md-6">
                    <strong>Classe :</strong><br>
                    {{ $inscription_frai->inscription->classe->nom ?? '—' }}
                </div>
            </div>

            <hr>

            {{-- Informations du frais --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Frais :</strong><br>
                    {{ $inscription_frai->frais->nom ?? '—' }}
                </div>

                <div class="col-md-6">
                    <strong>Année scolaire :</strong><br>
                    {{ $inscription_frai->annee->nom ?? '—' }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Montant total :</strong><br>
                    {{ number_format($inscription_frai->montant_frais, 0) }} F
                </div>

                <div class="col-md-4">
                    <strong>Montant payé :</strong><br>
                    {{ number_format($inscription_frai->montant_paye, 0) }} F
                </div>

                <div class="col-md-4">
                    <strong>Reste à payer :</strong><br>
                    {{ number_format($inscription_frai->reste, 0) }} F
                </div>
            </div>

            <div class="mb-3">
                <strong>Statut :</strong>
                @if($inscription_frai->statut === 'soldé')
                    <span class="badge bg-success">Soldé</span>
                @elseif($inscription_frai->statut === 'partiellement_payé')
                    <span class="badge bg-warning text-dark">Partiellement payé</span>
                @else
                    <span class="badge bg-danger">Non payé</span>
                @endif
            </div>

            <div class="mb-3">
                <strong>En arriéré :</strong>
                @if($inscription_frai->est_arriere)
                    <span class="badge bg-danger">Oui</span>
                @else
                    <span class="badge bg-success">Non</span>
                @endif
            </div>

            <hr>

            {{-- Actions --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('inscription-frais.index') }}"
                   class="btn btn-secondary">
                    ⬅️ Retour
                </a>

                <div>
                    <a href="{{ route('inscription-frais.edit', $inscription_frai->id) }}"
                       class="btn btn-warning">
                        ✏️ Modifier
                    </a>

                    <form action="{{ route('inscription-frais.destroy', $inscription_frai->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Supprimer ce frais ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            🗑️ Supprimer
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
