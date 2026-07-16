@extends('tableau.neutre')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Types de dépenses</h1>
        <a href="{{ route('types-depenses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouveau type
        </a>
    </div>

    @if (session('succes'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('succes') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('erreur'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('erreur') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('types-depenses.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="recherche" class="form-control"
                           placeholder="Rechercher par code ou nom..."
                           value="{{ request('recherche') }}">
                </div>
                <div class="col-md-3">
                    <select name="categorie_id" class="form-select">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}"
                                {{ (int) request('categorie_id') === $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                </div>
                @if (request('recherche') || request('categorie_id'))
                    <div class="col-auto">
                        <a href="{{ route('types-depenses.index') }}" class="btn btn-outline-secondary">
                            Réinitialiser
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $type)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $type->code }}</span></td>
                            <td>
                                <a href="{{ route('types-depenses.show', $type) }}">{{ $type->nom }}</a>
                            </td>
                            <td>
                                <a href="{{ route('categories-depenses.show', $type->categorie) }}" class="text-muted">
                                    {{ $type->categorie->nom }}
                                </a>
                            </td>
                            <td class="text-center">
                                @if ($type->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('types-depenses.edit', $type) }}"
                                   class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('types-depenses.destroy', $type) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce type de dépense ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucun type de dépense trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $types->links() }}
    </div>

</div>
@endsection