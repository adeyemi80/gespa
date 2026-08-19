@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-warning">
                ✏️ Modifier une dépense
            </h2>
            <p class="text-muted mb-0">
                Mise à jour des informations de la dépense
            </p>
        </div>

        <a href="{{ route('oloyes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow">

        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Modification de la dépense</h5>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Veuillez corriger les erreurs suivantes :</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('oloyes.update', $oloye) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Date -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">
                            Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="date"
                            value="{{ old('date', $oloye->date->format('Y-m-d')) }}"
                            class="form-control @error('date') is-invalid @enderror"
                            required>

                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">
                            Catégorie
                        </label>

                        <select
                            name="categorie"
                            class="form-select @error('categorie') is-invalid @enderror">

                            <option value="">-- Choisir --</option>

                            @foreach([
                                'Ciment',
                                'Fer',
                                'Sable',
                                'Gravier',
                                "Main d'œuvre",
                                'Plomberie',
                                'Électricité',
                                'Peinture',
                                'Transport',
                                'Autres'
                            ] as $categorie)

                                <option value="{{ $categorie }}"
                                    {{ old('categorie', $oloye->categorie) == $categorie ? 'selected' : '' }}>
                                    {{ $categorie }}
                                </option>

                            @endforeach

                        </select>

                        @error('categorie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <!-- Montant -->
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Montant (FCFA) <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="montant"
                            value="{{ old('montant', $oloye->montant) }}"
                            class="form-control @error('montant') is-invalid @enderror"
                            required>

                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <!-- Libellé -->
                    <div class="col-md-12 mb-3">

                        <label class="form-label fw-bold">
                            Libellé <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="libelle"
                            value="{{ old('libelle', $oloye->libelle) }}"
                            class="form-control @error('libelle') is-invalid @enderror"
                            required>

                        @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <!-- Bénéficiaire -->
                    <div class="col-md-12 mb-3">

                        <label class="form-label fw-bold">
                            Bénéficiaire
                        </label>

                        <input
                            type="text"
                            name="beneficiaire"
                            value="{{ old('beneficiaire', $oloye->beneficiaire) }}"
                            class="form-control @error('beneficiaire') is-invalid @enderror">

                        @error('beneficiaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <!-- Observation -->
                    <div class="col-md-12 mb-3">

                        <label class="form-label fw-bold">
                            Observation
                        </label>

                        <textarea
                            name="observation"
                            rows="4"
                            class="form-control @error('observation') is-invalid @enderror">{{ old('observation', $oloye->observation) }}</textarea>

                        @error('observation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('oloye.index') }}" class="btn btn-secondary btn-lg">
                        Annuler
                    </a>

                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="bi bi-save"></i>
                        Mettre à jour
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection