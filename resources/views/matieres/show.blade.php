@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <h2>Détails de la Matière</h2>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">{{ $matiere->nom }}</h5>
              <h5 class="card-title">{{ $matiere->type }}</h5>
            <p><strong>Coefficient:</strong> {{ $matiere->coefficient }}</p>
            <p><strong>Classe:</strong> {{ $matiere->classe->nom ?? 'Non défini' }}</p>
            <p><strong>Enseignant:</strong> {{ $matiere->enseignant->nom ?? 'Aucun' }}</p>
        </div>
    </div>

    <a href="{{ route('matieres.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>

@endsection
