@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-5" style="max-width:600px;"> {{-- réduit la largeur --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-success text-white rounded-top-3 py-2">
            <h5 class="mb-0">➕ Ajouter une opération</h5>
        </div>
        <div class="card-body p-3"> {{-- réduit padding --}}
            <form action="{{ route('operations.store') }}" method="POST">
                @csrf
                {{-- Message de succès --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 w-75 text-center" role="alert" style="z-index: 1050;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <!-- Date -->
                <div class="mb-2">
                    <label class="form-label fw-bold">📅 Date</label>
                    <input type="date" 
                           class="form-control form-control-sm @error('date') is-invalid @enderror"
                           id="date" 
                           name="date" 
                           value="{{ old('date') ?? now()->toDateString() }}" 
                           required>
                    @error('date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Libellé -->
                <div class="mb-2">
                    <label class="form-label fw-bold">🏷️ Libellé</label>
                    <select name="libelle" id="libelle" class="form-select form-select-sm @error('libelle') is-invalid @enderror" required>
                        <option value="">-- Choisir le libellé --</option>
                        <option value="salaires">Salaires</option>
                        <option value="fournitures">Fournitures</option>
                        <option value="réparation">Réparation</option>
                        <option value="dons">Dons</option>
                        <option value="uniforme">Uniforme</option>
                        <option value="tenue de sport">Tenue de sport</option>
                        <option value="scolarités">Scolarités</option>
                        <option value="arriérés de la scolarité">Arriérés de la scolarité</option>
                        <option value="électricité">Électricité</option>
                        <option value="eau">Eau</option>
                        <option value="YESSOUFOU A. Affissou">YESSOUFOU Affissou</option>
                        <option value="ADEYEMI Kolawolé">ADEYEMI Kolawolé</option>
                        <option value="remboursement">Remboursement</option>
                        <option value="achat">Achat</option>
                        <option value="forfait">Forfait</option>
                        <option value="photocopie">Photocopie</option>
                        <option value="autres dépenses">Autres Dépenses</option>
                        <option value="autres recettes">Autres Recettes</option>
                    </select>
                    @error('libelle')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Montant -->
                <div class="mb-2">
                    <label class="form-label fw-bold">💰 Montant</label>
                    <input type="number" step="0.01" name="montant" class="form-control form-control-sm @error('montant') is-invalid @enderror" value="{{ old('montant') }}" required>
                    @error('montant')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Catégorie -->
                <div class="mb-2">
                    <label class="form-label fw-bold">📂 Catégorie</label>
                    <select name="categorie" class="form-select form-select-sm @error('categorie') is-invalid @enderror" required>
                        <option value="recette" {{ old('categorie') == 'recette' ? 'selected' : '' }}>✅ Recette</option>
                        <option value="dépense" {{ old('categorie') == 'dépense' ? 'selected' : '' }}>❌ Dépense</option>
                    </select>
                    @error('categorie')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Description -->
                <div class="mb-2">
                    <label class="form-label fw-bold">📝 Description</label>
                    <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('operations.index') }}" class="btn btn-secondary btn-sm">⬅️ Retour</a>
                    <button type="submit" class="btn btn-success btn-sm">💾 Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if(alert) bootstrap.Alert.getOrCreateInstance(alert).close();
    }, 4000);
</script>
@endsection
