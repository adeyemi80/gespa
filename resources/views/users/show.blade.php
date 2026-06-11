@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h3>Détails utilisateur</h3>

    <ul class="list-group">
        <li class="list-group-item"><strong>Nom :</strong> {{ $user->nom }}</li>
        <li class="list-group-item"><strong>Prénom :</strong> {{ $user->prenom }}</li>
        <li class="list-group-item"><strong>Email :</strong> {{ $user->email }}</li>
        <li class="list-group-item"><strong>Rôle :</strong> {{ $user->role }}</li>
    </ul>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
