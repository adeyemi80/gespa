@extends('tableau.neutre')

@section('title', 'Modifier un élève externe TD')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="fas fa-user-edit me-2"></i>
                Modifier un élève externe TD
            </h3>

            <p class="text-muted mb-0">
                Matricule :
                <span class="fw-semibold">{{ $eleve->matricule }}</span>
            </p>
        </div>

        <a href="{{ route('eleves-externes-td.index') }}"
           class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>
            Retour à la liste
        </a>

    </div>

    {{-- Erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Veuillez corriger les erreurs suivantes :</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('eleves-externes-td.update', $eleve->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-header bg-light border-0 rounded-top-4 py-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-id-card me-2 text-primary"></i>
                    Informations de l'élève
                </h5>
            </div>

            <div class="card-body p-4">

                <div class="row g-3">

                    {{-- Matricule --}}
                    <div class="col-md-6">

                        <label for="matricule" class="form-label fw-semibold">
                            Matricule <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="matricule"
                            id="matricule"
                            class="form-control @error('matricule') is-invalid @enderror"
                            value="{{ old('matricule', $eleve->matricule) }}"
                            maxlength="255"
                            required
                        >

                        @error('matricule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Sexe --}}
                    <div class="col-md-6">

                        <label for="sexe" class="form-label fw-semibold">
                            Sexe
                        </label>

                        <select
                            name="sexe"
                            id="sexe"
                            class="form-select @error('sexe') is-invalid @enderror"
                        >
                            <option value="">Sélectionner</option>

                            <option value="Masculin"
                                @selected(old('sexe', $eleve->sexe) === 'Masculin')>
                                Masculin
                            </option>

                            <option value="Féminin"
                                @selected(old('sexe', $eleve->sexe) === 'Féminin')>
                                Féminin
                            </option>

                            <option value="M"
                                @selected(old('sexe', $eleve->sexe) === 'M')>
                                M
                            </option>

                            <option value="F"
                                @selected(old('sexe', $eleve->sexe) === 'F')>
                                F
                            </option>

                        </select>

                        @error('sexe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Nom --}}
                    <div class="col-md-6">

                        <label for="nom" class="form-label fw-semibold">
                            Nom <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="nom"
                            id="nom"
                            class="form-control @error('nom') is-invalid @enderror"
                            value="{{ old('nom', $eleve->nom) }}"
                            maxlength="255"
                            required
                        >

                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Prénom --}}
                    <div class="col-md-6">

                        <label for="prenom" class="form-label fw-semibold">
                            Prénom(s) <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="prenom"
                            id="prenom"
                            class="form-control @error('prenom') is-invalid @enderror"
                            value="{{ old('prenom', $eleve->prenom) }}"
                            maxlength="255"
                            required
                        >

                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Date de naissance --}}
                    <div class="col-md-6">

                        <label for="date_naissance" class="form-label fw-semibold">
                            Date de naissance
                        </label>

                        <input
                            type="date"
                            name="date_naissance"
                            id="date_naissance"
                            class="form-control @error('date_naissance') is-invalid @enderror"
                            value="{{ old(
                                'date_naissance',
                                $eleve->date_naissance
                                    ? $eleve->date_naissance->format('Y-m-d')
                                    : ''
                            ) }}"
                            max="{{ now()->toDateString() }}"
                        >

                        @error('date_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Téléphone --}}
                    <div class="col-md-6">

                        <label for="telephone" class="form-label fw-semibold">
                            Téléphone de l'élève
                        </label>

                        <input
                            type="text"
                            name="telephone"
                            id="telephone"
                            class="form-control @error('telephone') is-invalid @enderror"
                            value="{{ old('telephone', $eleve->telephone) }}"
                            maxlength="30"
                        >

                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Téléphone parent --}}
                    <div class="col-md-6">

                        <label for="telephone_parent" class="form-label fw-semibold">
                            Téléphone du parent
                        </label>

                        <input
                            type="text"
                            name="telephone_parent"
                            id="telephone_parent"
                            class="form-control @error('telephone_parent') is-invalid @enderror"
                            value="{{ old('telephone_parent', $eleve->telephone_parent) }}"
                            maxlength="30"
                        >

                        @error('telephone_parent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Adresse --}}
                    <div class="col-md-6">

                        <label for="adresse" class="form-label fw-semibold">
                            Adresse
                        </label>

                        <textarea
                            name="adresse"
                            id="adresse"
                            rows="2"
                            maxlength="1000"
                            class="form-control @error('adresse') is-invalid @enderror"
                        >{{ old('adresse', $eleve->adresse) }}</textarea>

                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Observations --}}
                    <div class="col-12">

                        <label for="observations" class="form-label fw-semibold">
                            Observations
                        </label>

                        <textarea
                            name="observations"
                            id="observations"
                            rows="3"
                            maxlength="5000"
                            class="form-control @error('observations') is-invalid @enderror"
                        >{{ old('observations', $eleve->observations) }}</textarea>

                        @error('observations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Statut --}}
                    <div class="col-md-6">

                        <label for="actif" class="form-label fw-semibold">
                            Statut
                        </label>

                        <select name="actif" id="actif" class="form-select">

                            <option value="1"
                                @selected((string) old('actif', (int) $eleve->actif) === '1')>
                                Actif
                            </option>

                            <option value="0"
                                @selected((string) old('actif', (int) $eleve->actif) === '0')>
                                Inactif
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 flex-wrap">

            <a href="{{ route('eleves-externes-td.index') }}"
               class="btn btn-light border rounded-pill px-4">
                Annuler
            </a>

            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-save me-2"></i>
                Enregistrer les modifications
            </button>

        </div>

    </form>

</div>
@endsection