@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <h3>✏️ Modifier Article</h3>

    <form method="POST" action="{{ route('articles.update', $article) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $article->nom) }}" required>
        </div>

        <div class="mb-3">
            <label>Type</label>
            <select name="type_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_id', $article->type_id) == $type->id ? 'selected' : '' }}>
                        {{ $type->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" name="quantite" class="form-control" min="0" value="{{ old('quantite', $article->quantite) }}" required>
        </div>

        <div class="mb-3">
            <label>Seuil d'alerte</label>
            <input type="number" name="seuil_alerte" class="form-control" min="0" value="{{ old('seuil_alerte', $article->seuil_alerte) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $article->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">✅ Mettre à jour</button>
        <a href="{{ route('articles.index') }}" class="btn btn-secondary">🔙 Retour</a>
    </form>
</div>
@endsection
