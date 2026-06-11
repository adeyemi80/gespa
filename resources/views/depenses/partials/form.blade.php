<div class="row g-4">

    {{-- Date --}}
    <div class="col-md-6">
        <label for="date" class="form-label fw-semibold">📅 Date</label>
        <input type="date"
               id="date"
               name="date"
               class="form-control @error('date') is-invalid @enderror"
               value="{{ old('date', $depense->date ?? now()->toDateString()) }}"
               required>

        @error('date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Libellé --}}
    <div class="col-md-6">
        <label for="libelle" class="form-label fw-semibold">
            📝 Libellé
            <small class="text-muted d-block">
                (Ex: Achat de craies, Réparation imprimante, Facture électricité...)
            </small>
        </label>

        <input type="text"
               id="libelle"
               name="libelle"
               class="form-control @error('libelle') is-invalid @enderror"
               value="{{ old('libelle', $depense->libelle ?? '') }}"
               required>

        @error('libelle')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Montant --}}
    <div class="col-md-6">
        <label for="montant" class="form-label fw-semibold">💰 Montant</label>
        <input type="number"
               step="0.01"
               id="montant"
               name="montant"
               class="form-control @error('montant') is-invalid @enderror"
               value="{{ old('montant', $depense->montant ?? '') }}"
               required>

        @error('montant')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Catégorie --}}
    <div class="col-md-6">
        <label for="categorie" class="form-label fw-semibold">📂 Catégorie</label>
        <select name="categorie"
                id="categorie"
                class="form-select @error('categorie') is-invalid @enderror"
                required>

            <option value="">-- Choisir une catégorie --</option>

            @php
                $categories = [
                    'salaires' => 'Salaires',
                    'fournitures' => 'Fournitures',
                    'achat' => 'Achat',
                    'transport' => 'Transport',
                    'facture' => 'Facture',
                    'maintenance' => 'Maintenance',
                    'pedagogie' => 'Pédagogie',
                    'construction' => 'Construction',
                    'reparation' => 'Réparation',
                    'autres' => 'Autres',
                ];
            @endphp

            @foreach($categories as $value => $label)
                <option value="{{ $value }}"
                    {{ old('categorie', $depense->categorie ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @error('categorie')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Description --}}
    <div class="col-12">
        <label for="description" class="form-label fw-semibold">🗒 Description (facultative)</label>
        <textarea name="description"
                  id="description"
                  rows="3"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Ajoutez des détails supplémentaires...">{{ old('description', $depense->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>