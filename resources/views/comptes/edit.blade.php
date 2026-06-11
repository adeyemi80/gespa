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
                <div class="card-header bg-gradient text-white rounded-top-4"
                     style="background: linear-gradient(90deg, #0d6efd, #6610f2);">
                    <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Modifier Compte</h3>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('comptes.update', $compte->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Nom du compte --}}
                        <div class="mb-3">
                            <label for="nom" class="form-label fw-bold">Nom du compte</label>
                            <input type="text" class="form-control shadow-sm @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $compte->nom) }}" required>
                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Type</label>
                            <select name="type" id="type" class="form-select shadow-sm @error('type') is-invalid @enderror" required>
                                <option value="Banque" {{ old('type', $compte->type)=='Banque'?'selected':'' }}>Banque</option>
                                <option value="Caisse" {{ old('type', $compte->type)=='Caisse'?'selected':'' }}>Caisse</option>
                                <option value="Mobile Money" {{ old('type', $compte->type)=='Mobile Money'?'selected':'' }}>Mobile Money</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Solde initial --}}
                        <div class="mb-3">
                            <label for="solde_initial" class="form-label fw-bold">Solde initial</label>
                            <input type="number" step="0.01" class="form-control shadow-sm @error('solde_initial') is-invalid @enderror" 
                                   id="solde_initial" name="solde_initial" value="{{ old('solde_initial', $compte->solde_initial) }}" required>
                            @error('solde_initial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('comptes.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save2 me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
