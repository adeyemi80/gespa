@extends('classes.layout')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-primary">📚 Classes pour l'année {{ $annee->nom }}</h1>

    {{-- Message de succès / erreur --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Bouton d'ajout --}}
    <a href="{{ route('classes.create') }}" class="btn btn-primary mb-3">➕ Ajouter une classe</a>

    {{-- Tableau des classes --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle bg-white">
            <thead class="table-primary text-center">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>Nom</th>
                    <th>Niveau</th>
                    <th style="width: 15%;">Statut</th>
                    <th style="width: 25%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $classe)
                    @php
                        $pivot = $classe->annees->first(); // Relation filtrée sur l'année
                    @endphp
                    <tr>
                        <td class="text-center">{{ $classe->id }}</td>
                        <td>{{ $classe->nom }}</td>
                        <td>{{ $classe->niveau ?? 'Non défini' }}</td>
                        <td class="text-center">
                            <form action="{{ route('classes.toggleActiveAnnees', ['classe' => $classe->id, 'annee' => $annee->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $pivot->pivot->active ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $pivot->pivot->active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('classes.show', $classe) }}" class="btn btn-info btn-sm me-1" title="Voir">👁️</a>
                            <a href="{{ route('classes.edit', $classe) }}" class="btn btn-warning btn-sm me-1" title="Modifier">✏️</a>
                            <form action="{{ route('classes.destroy', $classe) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Supprimer cette classe ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucune classe trouvée pour cette année.</td>
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

{{-- Fermer automatiquement les alertes --}}
<script>
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 4000);
</script>
@endsection
