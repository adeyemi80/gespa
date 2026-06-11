@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="p-4 bg-white shadow rounded">
        <h1 class="mb-4 text-info">👁️ Détails du parent</h1>

        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Nom</h6>
                        <p class="card-text fs-5">{{ $paren->nom_parent }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Prénom</h6>
                        <p class="card-text fs-5">{{ $paren->prenom_parent }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Téléphone</h6>
                        <p class="card-text fs-5">{{ $paren->telephone_parent }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-primary h-100">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Adresse</h6>
                        <p class="card-text fs-5">{{ $paren->adresse_parent }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('parens.index') }}" class="btn btn-secondary">
                ⬅ Retour à la liste
            </a>

            <a href="{{ route('parens.edit', $paren) }}" class="btn btn-warning">
                ✏️ Modifier
            </a>
        </div>
    </div>
</div>
@endsection
