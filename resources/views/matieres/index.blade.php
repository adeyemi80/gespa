@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-primary mb-0">📘 Liste des Matières</h4>
        <a class="btn btn-success" href="{{ route('matieres.create') }}">
            <i class="bi bi-plus-circle"></i> Ajouter une Matière
        </a>
    </div>

    {{-- Message de succès --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            ✅ {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- Carte et tableau --}}
    <div class="card shadow-sm border-0 rounded">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 bg-white text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Coefficient</th>
                            <th>Classe</th>
                            <th>Enseignant</th>
                            <th width="180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($matieres as $matiere)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $matiere->nom }}</td>
                                <td>{{ $matiere->type }}</td>
                                <td>{{ $matiere->coefficient }}</td>
                                <td>
    @foreach($matiere->classes as $classe)
        {{ $classe->nom }}@if(!$loop->last), @endif
    @endforeach
</td>
                                <td>{{ $matiere->enseignant->nom ?? 'Aucun' }}</td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('matieres.show', $matiere->id) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('matieres.edit', $matiere->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('matieres.destroy', $matiere->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Aucune matière enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-3">
                {{ $matieres->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Fermeture auto de l'alerte --}}
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 4000);
</script>
@endsection
