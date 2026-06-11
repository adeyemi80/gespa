@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <h3>🔄 Mouvement de stock : {{ $article->nom }}</h3>

    <form method="POST" action="{{ route('articles.mouvement', $article) }}">
        @csrf

        <div class="mb-3">
            <label>Type de mouvement</label>
            <select name="type_mouvement" class="form-select" required>
                <option value="entree">Entrée</option>
                <option value="sortie">Sortie</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" name="quantite" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-success">✅ Enregistrer</button>
        <a href="{{ route('articles.index') }}" class="btn btn-secondary">🔙 Retour</a>
    </form>
</div>
@endsection
