@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white rounded-top-4"
                     style="background: linear-gradient(90deg, #198754, #20c997);">
                    <h4 class="mb-0">
                        <i class="bi bi-wallet2 me-2"></i> Nouveau Budget
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('budgets.store') }}" method="POST" novalidate>
                        @csrf

                        {{-- Nom du budget --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom du budget</label>
                            <input type="text"
                                   name="nom"
                                   class="form-control shadow-sm @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}"
                                   placeholder="Ex : Budget fonctionnement"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Catégorie --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catégorie</label>
                            <select name="categorie_id"
                                    class="form-select shadow-sm @error('categorie_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}"
                                        {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Année --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Année scolaire</label>
                            <select name="annee_id"
                                    class="form-select shadow-sm @error('annee_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Sélectionner l'année --</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}"
                                        {{ old('annee_id') == $annee->id ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('annee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Montant prévu --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Montant prévu (FCFA)</label>
                            <input type="number"
                                   name="montant_prevu"
                                   class="form-control shadow-sm @error('montant_prevu') is-invalid @enderror"
                                   value="{{ old('montant_prevu') }}"
                                   min="0"
                                   step="1"
                                   placeholder="Ex : 500000"
                                   required>
                            @error('montant_prevu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Période --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Période</label>
                            <input type="text"
                                   name="periode"
                                   class="form-control shadow-sm @error('periode') is-invalid @enderror"
                                   value="{{ old('periode') }}"
                                   placeholder="Ex : Année 2025 / Janvier–Mars"
                                   required>
                            @error('periode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('budgets.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left-circle me-1"></i> Annuler
                            </a>
                            <button class="btn btn-success px-4">
                                <i class="bi bi-check-circle me-1"></i> Enregistrer
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
