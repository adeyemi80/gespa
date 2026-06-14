@extends('tableau.neutre')

@section('content')
<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-plus-circle"></i> Nouveau tarif TD</span>
            <a href="{{ route('td-tarifs.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> VOIR LES TARIFS
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('td-tarifs.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Année --}}
                    <div class="col-md-6">
                        <label class="form-label">Année <span class="text-danger">*</span></label>
                        <select name="annee_id"
                                class="form-select @error('annee_id') is-invalid @enderror">
                            <option value="">Choisir une année</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee->id }}"
                                    {{ old('annee_id', $anneeEnCours?->id) == $annee->id ? 'selected' : '' }}>
                                    {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
                                    @if($annee->en_cours) ✅ @endif
                                </option>
                            @endforeach
                        </select>
                        @error('annee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Catégorie --}}
                    <div class="col-md-6">
                        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select name="categorie"
                                class="form-select @error('categorie') is-invalid @enderror">
                            <option value="">Choisir une catégorie</option>
                            @foreach(\App\Models\TdTarif::CATEGORIES as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('categorie') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div class="col-md-6">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type"
                                class="form-select @error('type') is-invalid @enderror">
                            <option value="">Choisir un type</option>
                            @foreach(\App\Models\TdTarif::TYPES as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('type') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Montant --}}
                    <div class="col-md-6">
                        <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" name="montant" min="0" step="0.01"
                               class="form-control @error('montant') is-invalid @enderror"
                               placeholder="Ex: 5000"
                               value="{{ old('montant') }}">
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Créer le tarif
                    </button>
                    <a href="{{ route('td-tarifs.index') }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection