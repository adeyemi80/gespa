@extends('tableau.neutre')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Types de recettes</h1>
            <p class="text-muted mb-0">
                Gestion des différents types de recettes.
            </p>
        </div>

        <a href="{{ route('types-recettes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter un type
        </a>
    </div>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Erreur :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Liste des types de recettes
                </h5>

                <span class="badge bg-secondary">
                    {{ $typesRecettes->count() }} type(s)
                </span>
            </div>
        </div>

        <div class="card-body">

            @if($typesRecettes->count() > 0)

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="70">#</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th width="120">Statut</th>
                                <th width="180" class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($typesRecettes as $type)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $type->nom }}
                                        </strong>
                                    </td>

                                    <td>
                                        @if($type->description)
                                            {{ Str::limit($type->description, 80) }}
                                        @else
                                            <span class="text-muted">
                                                Aucune description
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($type->actif)
                                            <span class="badge bg-success">
                                                Actif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Inactif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route('types-recettes.show', $type) }}"
                                           class="btn btn-sm btn-info text-white"
                                           title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('types-recettes.edit', $type) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('types-recettes.destroy', $type) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce type de recette ?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else

                <div class="text-center py-5">
                    <i class="bi bi-folder-x display-4 text-muted"></i>

                    <h5 class="mt-3">
                        Aucun type de recette
                    </h5>

                    <p class="text-muted">
                        Vous n'avez encore créé aucun type de recette.
                    </p>

                    <a href="{{ route('types-recettes.create') }}"
                       class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i>
                        Ajouter le premier type
                    </a>
                </div>

            @endif

        </div>
    </div>

</div>
@endsection