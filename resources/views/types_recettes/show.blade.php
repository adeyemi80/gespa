@extends('tableau.neutre')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Type de recette
            </h1>

            <p class="text-muted mb-0">
                Détails du type de recette
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('types-recettes.index') }}"
               class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>

            <a href="{{ route('types-recettes.edit', $typesRecette) }}"
               class="btn btn-warning">
                <i class="bi bi-pencil"></i>
                Modifier
            </a>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                {{ $typesRecette->nom }}
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="fw-bold text-muted">
                        Nom
                    </label>

                    <div class="fs-5">
                        {{ $typesRecette->nom }}
                    </div>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="fw-bold text-muted">
                        Statut
                    </label>

                    <div>

                        @if($typesRecette->actif)

                            <span class="badge bg-success fs-6">
                                Actif
                            </span>

                        @else

                            <span class="badge bg-secondary fs-6">
                                Inactif
                            </span>

                        @endif

                    </div>

                </div>

                <div class="col-12 mb-4">

                    <label class="fw-bold text-muted">
                        Description
                    </label>

                    <div class="border rounded p-3 bg-light">

                        @if($typesRecette->description)

                            {!! nl2br(e($typesRecette->description)) !!}

                        @else

                            <span class="text-muted">
                                Aucune description.
                            </span>

                        @endif

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="fw-bold text-muted">
                        Créé le
                    </label>

                    <div>
                        {{ $typesRecette->created_at?->format('d/m/Y à H:i') }}
                    </div>

                </div>

                <div class="col-md-6">

                    <label class="fw-bold text-muted">
                        Dernière modification
                    </label>

                    <div>
                        {{ $typesRecette->updated_at?->format('d/m/Y à H:i') }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection