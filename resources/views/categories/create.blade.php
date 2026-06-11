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
                     style="background: linear-gradient(90deg, #198754, #20c997);">
                    <h3 class="mb-0"><i class="bi bi-tags-fill me-2"></i> Nouvelle Catégorie</h3>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        {{-- Nom --}}
                        <div class="mb-3">
                            <label for="nom" class="form-label fw-bold">Nom</label>
                             <select name="nom" id="nom" required class="form-select" type="text" :value="old('nom')" required autofocus autocomplete="nom">
                <option value="">-- Choisir une Catégorie --</option>
                <option value="salaires">Salaires</option>
                 <option value="fournitures">Fournitures</option>
                    <option value="réparation">Réparation</option>
                    <option value="dons">DONS</option>
                    <option value="uniforme">Uniforme</option>
                    <option value="tenue de sport"> Tenue de sport</option>
                    <option value="scolarités">Scolarités</option>
                    <option value="arriérés de la scolarité">Arriérés de la Scolarités</option>
                    <option value="électricité et eau">Electricité et Eau</option>
                    <option value="YESSOUFOU A. Affissou">YESSOUFOU Affissou</option>
                    <option value="ADEYEMI Kolawolé">ADEYEMI Kolawolé</option>
                      <option value="remboursement Hounkponou">Remboursement Hounkponou</option>
                      <option value="Achat Porte">Achat Porte</option>
                      <option value="salaire Justine">Salaire Justine</option>
                      <option value="salaire Aurel">Salaire Aurel</option>
                       <option value="salaire Koudous">Salaire Koudous</option>
                         <option value="forfait internet">Forfait Internet</option>
                          <option value=""></option>
                           <option value=""></option>
                            <option value=""></option>
                     <option value="autres dépenses">Autres Dépenses</option>
                      <option value="autres recettes">Autres Recettes</option>
            </select>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Type --}}
                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">Type</label>
                             <select name="type" id="type" required class="form-select" type="text" :value="old('type')" required autofocus autocomplete="type">
                <option value="">-- Choisir le Type --</option>
                <option value="recette">Recette</option>
                 <option value="dépense">Dépense</option>
            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description (optionnelle)</label>
                            <textarea class="form-control shadow-sm" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left-circle me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success px-4">
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
