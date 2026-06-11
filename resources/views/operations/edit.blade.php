@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4">
            <h4 class="mb-0">✏️ Modifier l'opération</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('operations.update', $operation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">📅 Date</label>
                    <input type="date" name="date" class="form-control form-control-lg" 
                           value="{{ $operation->date }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">🏷️ Libellé</label>
                    <input type="text" name="libelle" class="form-control form-control-lg" 
                           value="{{ $operation->libelle }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">💰 Montant</label>
                    <input type="number" step="0.01" name="montant" class="form-control form-control-lg" 
                           value="{{ $operation->montant }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">📂 Catégorie</label>
                    <select name="categorie" class="form-select form-select-lg" required>
                        <option value="recette" {{ $operation->categorie == 'recette' ? 'selected' : '' }}>✅ Recette</option>
                        <option value="dépense" {{ $operation->categorie == 'dépense' ? 'selected' : '' }}>❌ Dépense</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">📝 Description</label>
                    <textarea name="description" class="form-control form-control-lg">{{ $operation->description }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('operations.index') }}" class="btn btn-secondary btn-lg">⬅️ Retour</a>
                    <button type="submit" class="btn btn-warning btn-lg">💾 Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
