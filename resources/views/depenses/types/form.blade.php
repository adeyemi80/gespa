@csrf

<div class="mb-3">
    <label for="categorie_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
    <select name="categorie_id" id="categorie_id"
            class="form-select @error('categorie_id') is-invalid @enderror" required>
        <option value="">-- Sélectionner une catégorie --</option>
        @foreach ($categories as $categorie)
            <option value="{{ $categorie->id }}"
                {{ (int) old('categorie_id', $type->categorie_id ?? '') === $categorie->id ? 'selected' : '' }}>
                {{ $categorie->nom }}
            </option>
        @endforeach
    </select>
    @error('categorie_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
    <input type="text" name="nom" id="nom"
           class="form-control @error('nom') is-invalid @enderror"
           value="{{ old('nom', $type->nom ?? '') }}" required>
    @error('nom')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" rows="3"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $type->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check form-switch mb-3">
    <input type="checkbox" name="actif" id="actif" value="1" class="form-check-input"
           {{ old('actif', $type->actif ?? true) ? 'checked' : '' }}>
    <label for="actif" class="form-check-label">Type actif</label>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Enregistrer
    </button>
    <a href="{{ route('types-depenses.index') }}" class="btn btn-outline-secondary">Annuler</a>
</div>