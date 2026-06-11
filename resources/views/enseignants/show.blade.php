@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h3>🔍 Détail de l’enseignant</h3>

    <ul class="list-group">
        <li class="list-group-item"><strong>Matricule :</strong> {{ $enseignant->matricule }}</li>
        <li class="list-group-item"><strong>Nom :</strong> {{ $enseignant->nom }}</li>
        <li class="list-group-item"><strong>Prénom :</strong> {{ $enseignant->prenom }}</li>
        <li class="list-group-item"><strong>Date de naissance :</strong> {{ $enseignant->date_naissance }}</li>
        <li class="list-group-item"><strong>Sexe :</strong> {{ $enseignant->sexe }}</li>
        <li class="list-group-item"><strong>Adresse :</strong> {{ $enseignant->adresse }}</li>
        <li class="list-group-item"><strong>Téléphone :</strong> {{ $enseignant->telephone }}</li>
        <li class="list-group-item"><strong>Email :</strong> {{ $enseignant->email }}</li>
    </ul>

    <a href="{{ route('enseignants.edit', $enseignant) }}" class="btn btn-warning mt-3">✏️ Modifier</a>
    <a href="{{ route('enseignants.index') }}" class="btn btn-secondary mt-3">↩️ Retour</a>
</div>
@endsection
