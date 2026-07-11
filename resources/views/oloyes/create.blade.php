@extends('tableau.neutre')

@section('content')

<div class="container py-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">
                🏠 Nouvelle dépense
            </h2>
            <p class="text-muted mb-0">
                Enregistrer une nouvelle dépense de construction
            </p>
        </div>

        <a href="{{ route('oloyes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informations de la dépense</h5>
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

            <form action="{{ route('oloyes.store') }}" method="POST">

                @csrf

                <div class="row">

                    <!-- Date -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">
                            Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="date"
                            class="form-control @error('date') is-invalid @enderror"
                            value="{{ old('date', date('Y-m-d')) }}"
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
                            <option value="Ciment" {{ old('categorie')=='Ciment'?'selected':'' }}>Ciment</option>
                            <option value="Fer" {{ old('categorie')=='Fer'?'selected':'' }}>Fer</option>
                            <option value="Sable" {{ old('categorie')=='Sable'?'selected':'' }}>Sable</option>
                            <option value="Gravier" {{ old('categorie')=='Gravier'?'selected':'' }}>Gravier</option>
                            <option value="Main d'œuvre" {{ old('categorie')=="Main d'œuvre"?'selected':'' }}>Main d'œuvre</option>
                            <option value="Plomberie" {{ old('categorie')=='Plomberie'?'selected':'' }}>Plomberie</option>
                            <option value="Électricité" {{ old('categorie')=='Électricité'?'selected':'' }}>Électricité</option>
                            <option value="Peinture" {{ old('categorie')=='Peinture'?'selected':'' }}>Peinture</option>
                            <option value="Transport" {{ old('categorie')=='Transport'?'selected':'' }}>Transport</option>
                            <option value="Autres" {{ old('categorie')=='Autres'?'selected':'' }}>Autres</option>

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
                            value="{{ old('montant') }}"
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
                            value="{{ old('libelle') }}"
                            class="form-control @error('libelle') is-invalid @enderror"
                            placeholder="Exemple : Achat de 50 sacs de ciment"
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
                            value="{{ old('beneficiaire') }}"
                            class="form-control @error('beneficiaire') is-invalid @enderror"
                            placeholder="Nom du fournisseur ou de l'artisan">

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
                            class="form-control @error('observation') is-invalid @enderror"
                            placeholder="Informations complémentaires...">{{ old('observation') }}</textarea>

                        @error('observation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end">

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i>
                        Enregistrer
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection