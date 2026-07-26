@csrf

<div class="mb-3">
    <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
    <input type="text" name="code" id="code" maxlength="10"
           class="form-control @error('code') is-invalid @enderror"
           value="{{ old('code', $categorie->code ?? '') }}" required>
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
    <input type="text" name="nom" id="nom"
           class="form-control @error('nom') is-invalid @enderror"
           value="{{ old('nom', $categorie->nom ?? '') }}" required>
    @error('nom')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" rows="3"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $categorie->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check form-switch mb-3">
    <input type="checkbox" name="actif" id="actif" value="1" class="form-check-input"
           {{ old('actif', $categorie->actif ?? true) ? 'checked' : '' }}>
    <label for="actif" class="form-check-label">Catégorie active</label>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Enregistrer
    </button>
    <a href="{{ route('categories-recettes.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>