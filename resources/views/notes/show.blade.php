@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Détails de la note</h5>
                </div>
                <div class="card-body">
                    <p><strong>Élève :</strong> {{ $note->inscription->eleve->nom }} {{ $note->inscription->eleve->prenom }}</p>
                    <p><strong>Classe :</strong> {{ $note->inscription->classe->nom ?? '-' }}</p>
                    <p><strong>Année scolaire :</strong> {{ $note->inscription->annee->nom ?? '-' }}</p>
                    <p><strong>Matière :</strong> {{ $note->matiere->nom }}</p>
                    <p><strong>Moyenne d'Interrogation :</strong> {{ $note->moyenne_interro }}</p>
                    <p><strong>Devoir 1 :</strong> {{ $note->devoir1 }}</p>
                    <p><strong>Devoir 2 :</strong> {{ $note->devoir2 }}</p>
                    <p><strong>Moyenne :</strong> <strong>{{ $note->moyenne_matiere }}</strong></p>
                    <p><strong>Trimestre :</strong> {{ $note->trimestre->nom }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-warning">Modifier</a>
                    <a href="{{ route('notes.index') }}" class="btn btn-secondary">Retour</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
