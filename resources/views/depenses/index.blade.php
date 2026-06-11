@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-list-task"></i> Liste des Dépenses</h3>
            <!--<a href="{{ route('depenses.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Nouvelle Dépense
            </a>-->
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Libellé</th>
                            <th>Montant</th>
                            <th>Catégorie</th>
                             <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depenses as $depense)
                            <tr class="text-center">
                                <td>{{ $depense->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}</td>
                                <td class="text-start">{{ $depense->libelle }}</td>
                                <td><span class="badge bg-danger">{{ number_format($depense->montant, 2, ',', ' ') }} FCFA</span></td>
                                <td>{{ $depense->categorie ?? '-' }}</td>
                                  <td>{{ $depense->description ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('depenses.show', $depense) }}" class="btn btn-info btn-sm me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('depenses.edit', $depense) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('depenses.destroy', $depense) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Aucune dépense trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $depenses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
