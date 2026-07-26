@extends('tableau.neutre')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Nouvelle catégorie de recette</h1>
        <a href="{{ route('categories-recettes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('categories-recettes.store') }}">
                @include('recettes.categories.form')
            </form>
        </div>
    </div>

</div>
@endsection