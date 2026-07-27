@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Catégories de recettes</h1>
        <a href="{{ route('categories-recettes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nouvelle catégorie
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
            <form method="GET" action="{{ route('categories-recettes.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="recherche" class="form-control"
                           placeholder="Rechercher par code ou nom..."
                           value="{{ request('recherche') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                </div>
                @if (request('recherche'))
                    <div class="col-auto">
                        <a href="{{ route('categories-recettes.index') }}" class="btn btn-outline-secondary">
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
                        <th>Description</th>
                        <th class="text-center">Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $categorie)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $categorie->code }}</span></td>
                            <td>
                                <a href="{{ route('categories-recettes.show', $categorie) }}">
                                    {{ $categorie->nom }}
                                </a>
                            </td>
                            <td class="text-muted small">{{ Str::limit($categorie->description, 60) ?: '—' }}</td>
                            <td class="text-center">
                                @if ($categorie->actif)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('categories-recettes.edit', $categorie) }}"
                                   class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('categories-recettes.destroy', $categorie) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette catégorie ?');">
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
                                Aucune catégorie de recette trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categories->links() }}
    </div>

</div>
@endsection