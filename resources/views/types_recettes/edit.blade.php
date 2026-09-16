@extends('tableau.neutre')

@section('content')

<div class="container-fluid">


{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Modifier le type de recette
        </h1>

        <p class="text-muted mb-0">
            Modification de :
            <strong>{{ $typesRecette->nom }}</strong>
        </p>
    </div>

    <a href="{{ route('types-recettes.index') }}"
       class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>
        Retour

    </a>

</div>


{{-- Erreurs de validation --}}
@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show">

        <strong>
            Veuillez corriger les erreurs suivantes :
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- Message de succès --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- Carte principale --}}
<div class="card shadow-sm border-0">

    <div class="card-header bg-warning">

        <h5 class="mb-0">

            <i class="bi bi-pencil-square"></i>

            Modifier le type de recette

        </h5>

    </div>


    <div class="card-body">

        {{-- FORMULAIRE DE MODIFICATION --}}
        <form action="{{ route('types-recettes.update', $typesRecette) }}"
              method="POST">

            @csrf
            @method('PUT')


            <div class="row">

                {{-- Catégorie --}}
                <div class="col-md-6 mb-3">

                    <label for="categories_recettes_id"
                           class="form-label">

                        Catégorie de recette

                        <span class="text-danger">*</span>

                    </label>


                    <select name="categories_recettes_id"
                            id="categories_recettes_id"
                            class="form-select @error('categories_recettes_id') is-invalid @enderror"
                            required>

                        <option value="">
                            -- Sélectionner une catégorie --
                        </option>


                        @foreach($categories as $categorie)

                            <option value="{{ $categorie->id }}"
                                {{ old(
                                    'categories_recettes_id',
                                    $typesRecette->categories_recettes_id
                                ) == $categorie->id ? 'selected' : '' }}>

                                {{ $categorie->nom }}

                            </option>

                        @endforeach

                    </select>


                    @error('categories_recettes_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Nom --}}
                <div class="col-md-6 mb-3">

                    <label for="nom"
                           class="form-label">

                        Nom du type de recette

                        <span class="text-danger">*</span>

                    </label>


                    <input type="text"
                           name="nom"
                           id="nom"
                           class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom', $typesRecette->nom) }}"
                           placeholder="Exemple : Scolarité"
                           required>


                    @error('nom')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Statut --}}
                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Statut
                    </label>


                    <div class="form-check form-switch mt-2">

                        <input class="form-check-input"
                               type="checkbox"
                               name="actif"
                               value="1"
                               id="actif"
                               {{ old('actif', $typesRecette->actif) ? 'checked' : '' }}>


                        <label class="form-check-label"
                               for="actif">

                            Type actif

                        </label>

                    </div>

                </div>


                {{-- Description --}}
                <div class="col-12 mb-3">

                    <label for="description"
                           class="form-label">

                        Description

                    </label>


                    <textarea name="description"
                              id="description"
                              rows="5"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Description du type de recette...">{{ old('description', $typesRecette->description) }}</textarea>


                    @error('description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            <hr>


            {{-- Boutons de modification --}}
            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('types-recettes.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-x-circle"></i>

                    Annuler

                </a>


                <button type="submit"
                        class="btn btn-warning">

                    <i class="bi bi-save"></i>

                    Enregistrer les modifications

                </button>

            </div>


        </form>


        {{-- FORMULAIRE DE SUPPRESSION --}}
        <div class="border-top mt-4 pt-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h6 class="text-danger mb-1">
                        Zone dangereuse
                    </h6>

                    <small class="text-muted">
                        La suppression de ce type de recette est définitive.
                    </small>

                </div>


                <form action="{{ route('types-recettes.destroy', $typesRecette) }}"
                      method="POST"
                      onsubmit="return confirm('Voulez-vous vraiment supprimer le type « {{ addslashes($typesRecette->nom) }} » ?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="bi bi-trash"></i>

                        Supprimer

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


</div>

@endsection
