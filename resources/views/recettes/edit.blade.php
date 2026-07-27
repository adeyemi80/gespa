{{-- resources/views/recettes/edit.blade.php --}}
@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Modifier la recette</h1>
                    <p class="text-muted mb-0 small">Mets à jour les informations du paiement enregistré</p>
                </div>
                <a href="{{ route('recettes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('recettes.update', $recette) }}">
                        @csrf
                        @method('PUT')
                        @include('recettes.form')

                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> Mettre à jour
                            </button>
                            <a href="{{ route('recettes.index') }}" class="btn btn-outline-secondary px-4">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection