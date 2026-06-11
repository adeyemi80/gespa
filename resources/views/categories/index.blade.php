@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center rounded-top-4"
             style="background: linear-gradient(90deg, #0d6efd, #20c997);">
            <h3 class="mb-0"><i class="bi bi-tags-fill me-2"></i> Liste des Catégories</h3>
            <a href="{{ route('categories.create') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nouvelle Catégorie
            </a>
        </div>

        <div class="card-body p-4">
            {{-- Message succès --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            {{-- Tableau --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $categorie)
                        <tr>
                            <td class="fw-bold">{{ $categorie->id }}</td>
                            <td>{{ $categorie->nom }}</td>
                             <td>{{ $categorie->type }}</td>
                            <td>{{ $categorie->description ?? '—' }}</td>
                            <td>{{ $categorie->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('categories.show', $categorie->id) }}" 
                                   class="btn btn-sm btn-info me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('categories.edit', $categorie->id) }}" 
                                   class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $categorie->id) }}" method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Supprimer cette catégorie ?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Aucune catégorie trouvée
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
