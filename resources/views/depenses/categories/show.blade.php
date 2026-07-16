@extends('tableau.neutre')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            {{ $categorie->nom }}
            <span class="badge bg-secondary align-middle">{{ $categorie->code }}</span>
            @if ($categorie->actif)
                <span class="badge bg-success align-middle">Active</span>
            @else
                <span class="badge bg-secondary align-middle">Inactive</span>
            @endif
        </h1>
        <div>
            <a href="{{ route('categories-depenses.edit', $categorie) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('categories-depenses.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @if ($categorie->description)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted">Description</h6>
                <p class="mb-0">{{ $categorie->description }}</p>
            </div>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Types de dépenses rattachés</span>
            <a href="{{ route('types-depenses.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i> Ajouter un type
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th class="text-center">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorie->typesDepenses as $type)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $type->code }}</span></td>
                            <td>
                                <a href="{{ route('types-depenses.show', $type) }}">{{ $type->nom }}</a>
                            </td>
                            <td class="text-center">
                                @if ($type->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Aucun type de dépense rattaché à cette catégorie.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection