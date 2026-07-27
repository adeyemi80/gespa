{{-- resources/views/recettes/_form.blade.php --}}
@php
    $recette = $recette ?? null;
@endphp

@if ($errors->any())
    <div class="alert alert-danger shadow-sm" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Corrige les erreurs suivantes :</strong>
        </div>
        <ul class="mb-0 ps-4 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label fw-semibold">Date de paiement <span class="text-danger">*</span></label>
        <input type="date" name="date_paiement" required
               value="{{ old('date_paiement', $recette?->date_paiement?->format('Y-m-d')) }}"
               class="form-control @error('date_paiement') is-invalid @enderror">
        @error('date_paiement')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Montant versé <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" name="montant_verse" required
                   value="{{ old('montant_verse', $recette?->montant_verse) }}"
                   class="form-control @error('montant_verse') is-invalid @enderror">
            <span class="input-group-text">FCFA</span>
            @error('montant_verse')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Inscription <span class="text-danger">*</span></label>
        <select name="inscription_id" required class="form-select @error('inscription_id') is-invalid @enderror">
            <option value="">— Sélectionner —</option>
            @foreach ($inscriptions as $inscription)
                <option value="{{ $inscription->id }}" @selected(old('inscription_id', $recette?->inscription_id) == $inscription->id)>
                    {{ $inscription->id }} {{-- ⚠️ remplace par un champ lisible, ex: nom de l'élève --}}
                </option>
            @endforeach
        </select>
        @error('inscription_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Paiement <span class="text-danger">*</span></label>
        <select name="paiement_id" required class="form-select @error('paiement_id') is-invalid @enderror">
            <option value="">— Sélectionner —</option>
            @foreach ($paiements as $paiement)
                <option value="{{ $paiement->id }}" @selected(old('paiement_id', $recette?->paiement_id) == $paiement->id)>
                    {{ $paiement->id }} {{-- ⚠️ remplace par un champ lisible --}}
                </option>
            @endforeach
        </select>
        @error('paiement_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Catégorie de recette</label>
        <select name="categorie_recette_id" class="form-select @error('categorie_recette_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id }}" @selected(old('categorie_recette_id', $recette?->categorie_recette_id) == $categorie->id)>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>
        @error('categorie_recette_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Année</label>
        <select name="annee_id" class="form-select @error('annee_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach ($annees as $annee)
                <option value="{{ $annee->id }}" @selected(old('annee_id', $recette?->annee_id) == $annee->id)>
                    {{ $annee->nom }}
                </option>
            @endforeach
        </select>
        @error('annee_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Mode de paiement</label>
        <select name="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror">
            <option value="">— Sélectionner —</option>
            @foreach (['especes' => 'Espèces', 'mobile_money' => 'Mobile Money', 'cheque' => 'Chèque'] as $value => $label)
                <option value="{{ $value }}" @selected(old('mode_paiement', $recette?->mode_paiement) == $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('mode_paiement')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Numéro de reçu</label>
        <input type="text" name="numero_recu"
               value="{{ old('numero_recu', $recette?->numero_recu) }}"
               class="form-control @error('numero_recu') is-invalid @enderror">
        @error('numero_recu')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>