@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="mb-4 text-primary">📚 Liste des classes</h1>

            {{-- Message de succès --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="position: relative; z-index: 1050;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Bouton d'ajout --}}
            <a href="{{ route('classes.create') }}" class="btn btn-primary mb-3">
                ➕ Ajouter une classe
            </a>

            {{-- Tableau des classes --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle bg-white">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>Nom</th>
                            <th>Niveau</th>
                            <th>Cycle</th>
                            <th style="width: 15%;">Statut</th>
                            <th style="width: 25%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $classe)
                        <tr>
                            <td class="text-center">{{ $classe->id }}</td>
                            <td>{{ $classe->nom }}</td>
                            <td>{{ $classe->niveau ?? 'Non défini' }}</td>
                            <td>{{ $classe->cycle->nom }}</td>
                            <td class="text-center">
                                @if($classe->active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center d-flex justify-content-center gap-1">
                                <a href="{{ route('classes.show', $classe) }}" class="btn btn-info btn-sm" title="Voir">
                                    👁️
                                </a>
                                <a href="{{ route('classes.edit', $classe) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    ✏️
                                </a>

                             

                                {{-- Supprimer --}}
                                <form action="{{ route('classes.destroy', $classe) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer cette classe ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucune classe trouvée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $classes->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 4000); // Ferme l'alerte après 4 secondes
</script>
@endsection
