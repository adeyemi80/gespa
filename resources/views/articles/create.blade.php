@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">➕ Ajouter Article</h4>
        </div>
        <div class="card-body">

            {{-- Affichage des erreurs de validation --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('articles.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="nom" class="form-label fw-bold">Nom de l'article</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom') }}" required>
                </div>

                <div class="mb-3">
                    <label for="reference" class="form-label fw-bold">Référence</label>
                    <input type="text" id="reference" name="reference" class="form-control" value="{{ old('reference') }}" required>
                </div>

                <div class="mb-3">
                    <label for="type_id" class="form-label fw-bold">Type</label>
                    <select id="type_id" name="type_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="prix_achat" class="form-label fw-bold">Prix d'achat</label>
                        <input type="number" id="prix_achat" name="prix_achat" class="form-control" min="0" step="0.01" value="{{ old('prix_achat', 0) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="prix_vente" class="form-label fw-bold">Prix de vente</label>
                        <input type="number" id="prix_vente" name="prix_vente" class="form-control" min="0" step="0.01" value="{{ old('prix_vente', 0) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="stock_min" class="form-label fw-bold">Stock minimum</label>
                        <input type="number" id="stock_min" name="stock_min" class="form-control" min="0" value="{{ old('stock_min', 0) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="quantite" class="form-label fw-bold">Quantité initiale</label>
                    <input type="number" id="quantite" name="quantite" class="form-control" min="0" value="{{ old('quantite', 0) }}" required>
                </div>

                <div class="mb-3">
                    <label for="type_mouvement" class="form-label fw-bold">Type de mouvement initial</label>
                    <select id="type_mouvement" name="type_mouvement" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="entree" {{ old('type_mouvement') == 'entree' ? 'selected' : '' }}>Entrée</option>
                        <option value="sortie" {{ old('type_mouvement') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">✅ Enregistrer</button>
                    <a href="{{ route('articles.index') }}" class="btn btn-secondary">🔙 Retour</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
