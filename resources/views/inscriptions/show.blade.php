@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-3 text-bg-dark">
    <h2>Détails de l'inscription</h2>

    <ul class="list-group">
        <li class="list-group-item"><strong>Élève :</strong> {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }}</li>
        <li class="list-group-item"><strong>Classe :</strong> {{ $inscription->classe->nom }}</li>
        <li class="list-group-item"><strong>Année scolaire :</strong> {{ $inscription->annee->nom }}</li>
        <li class="list-group-item"><strong>Date d'inscription :</strong> {{ $inscription->date_inscription }}</li>
    </ul>

    <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary mt-3">← Retour</a>
</div>
@endsection
