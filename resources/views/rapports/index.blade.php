@extends('tableau.neutre')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-file-earmark-bar-graph"></i> Générer un Rapport
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('rapports.resultats') }}" method="POST">
                        @csrf

                        {{-- Catégorie --}}
                        <div class="mb-3">
                            <label for="categorie_id" class="form-label fw-bold">Catégorie</label>
                            <select name="categorie_id" class="form-select shadow-sm" required>
                                <option value="">-- Sélectionner une catégorie --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date début --}}
                        <div class="mb-3">
                            <label for="date_debut" class="form-label fw-bold">Date début</label>
                            <input type="date" class="form-control shadow-sm" name="date_debut" required>
                        </div>

                        {{-- Date fin --}}
                        <div class="mb-3">
                            <label for="date_fin" class="form-label fw-bold">Date fin</label>
                            <input type="date" class="form-control shadow-sm" name="date_fin" required>
                        </div>

                        {{-- Boutons --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left-circle"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-search"></i> Afficher
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-muted small text-center">
                    <i class="bi bi-info-circle"></i> Sélectionnez une catégorie et une période pour générer le rapport.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
