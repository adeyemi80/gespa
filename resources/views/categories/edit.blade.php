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
                     style="background: linear-gradient(90deg, #0d6efd, #20c997);">
                    <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Modifier Catégorie</h3>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('categories.update', $categorie->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Nom --}}
                        <div class="mb-3">
                            <label for="nom" class="form-label fw-bold">Nom</label>
                            <input type="text" class="form-control shadow-sm @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $categorie->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Type</label>
                            <input type="text" class="form-control shadow-sm @error('type') is-invalid @enderror" 
                                   id="type" name="type" value="{{ old('type', $categorie->type) }}" required>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description (optionnelle)</label>
                            <textarea class="form-control shadow-sm" id="description" name="description" rows="3">{{ old('description', $categorie->description) }}</textarea>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary px-4">
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
