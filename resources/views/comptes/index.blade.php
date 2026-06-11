@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-wallet2 me-2"></i> Liste des Comptes</h2>
        <a href="{{ route('comptes.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-circle me-1"></i> Nouveau Compte
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive shadow-sm rounded-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark rounded-4">
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Solde initial</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comptes as $compte)
                    <tr>
                        <td>{{ $compte->id }}</td>
                        <td>{{ $compte->nom }}</td>
                        <td>{{ number_format($compte->solde_initial, 2, ',', ' ') }} FCFA</td>
                        <td>{{ $compte->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('comptes.show', $compte->id) }}" class="btn btn-sm btn-info me-1">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('comptes.edit', $compte->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('comptes.destroy', $compte->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce compte ?')">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucun compte trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
