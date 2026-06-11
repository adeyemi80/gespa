@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📋 Liste des Notes de Conduite</h3>
        <a href="{{ route('conduites.create') }}" class="btn btn-success">
            ➕ Ajouter une note
        </a>
    </div>

    <div class="alert alert-info text-center w-75 mx-auto mb-4" role="alert">
        ⚠️ Chaque élève possède une <strong>note de conduite par trimestre</strong>.
    </div>

    <!-- Bouton vers le formulaire d'import -->
    <a href="{{ route('conduites.import') }}" class="btn btn-primary mb-3">
        📥 Importer des notes de conduite
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center w-75 mx-auto" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if($conduites->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white text-center">
                <thead class="table-primary">
                    <tr>
                        <th>👨‍🎓 Élève</th>
                        <th>🏫 Classe</th>
                        <th>🗓️ Trimestre</th>
                        <th>📚 Année Scolaire</th>
                        <th>📈 Note</th>
                        <th class="text-center">⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conduites as $conduite)
                        <tr>
                            <td>
                                {{ $conduite->inscription->eleve->nom ?? 'N/A' }}
                                {{ $conduite->inscription->eleve->prenom ?? '' }}
                            </td>
                            <td>{{ $conduite->inscription->classe->nom ?? 'N/A' }}</td>
                            <td>{{ $conduite->trimestre->nom ?? 'N/A' }}</td>
                            <td>{{ $conduite->annee->nom ?? 'N/A' }}</td>
                            <td>{{ $conduite->note_conduite }}</td>
                            <td class="text-nowrap d-flex justify-content-center gap-1">
                                <a href="{{ route('conduites.show', $conduite) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                    <i class="bi bi-eye-fill"></i> Voir
                                </a>
                                <a href="{{ route('conduites.edit', $conduite) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </a>
                                <form action="{{ route('conduites.destroy', $conduite) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                        <i class="bi bi-trash-fill"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 px-3 d-flex justify-content-center">
            {{ $conduites->links() }}
        </div>
    @else
        <div class="alert alert-info text-center w-75 mx-auto">
            📭 Aucune note de conduite enregistrée pour le moment.
        </div>
    @endif
</div>

{{-- Script pour fermeture automatique des alertes --}}
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 4000);
</script>
@endsection
