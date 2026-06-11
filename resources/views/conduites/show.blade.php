@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📄 Détails de la note de conduite</h4>
        </div>
        <div class="card-body">

            <div class="mb-3">
                <strong>Élève :</strong>
                {{ $conduite->inscription->eleve->nom ?? 'Non disponible' }} {{ $conduite->inscription->eleve->prenom ?? '' }}
            </div>

            <div class="mb-3">
                <strong>Matricule :</strong>
                {{ $conduite->inscription->eleve->matricule ?? 'Non disponible' }}
            </div>

            <div class="mb-3">
                <strong>Classe :</strong>
                {{ $conduite->classe->nom ?? 'Non renseignée' }}
            </div>

            <div class="mb-3">
                <strong>Trimestre :</strong>
                {{ $conduite->trimestre->nom ?? 'Non renseigné' }}
            </div>

            <div class="mb-3">
                <strong>Année scolaire :</strong>
                {{ $conduite->annee->nom ?? 'Non renseignée' }}
            </div>

            <div class="mb-3">
                <strong>Note de conduite :</strong>
                <span class="badge bg-success fs-5">{{ $conduite->note_conduite ?? 'N/A' }}/20</span>
            </div>

            <div class="mt-4">
                <a href="{{ route('conduites.index') }}" class="btn btn-secondary">
                    ⬅️ Retour à la liste
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
