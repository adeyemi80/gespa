@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-primary mb-0">📑 Liste des Notes</h4>
                <a class="btn btn-success" href="{{ route('notes.create') }}">
                    ➕ Ajouter une note
                </a>
            </div>

            {{-- Message de succès --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            {{-- Tableau --}}
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 bg-white text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Élève</th>
                                     <th>Classe</th>
                                    <th>Matière</th>
                                    <th>Moyenne d'Interrogation</th>
                                    <th>Devoir 1</th>
                                    <th>Devoir 2</th>
                                    <th>Moyenne Matière</th>
                                     <th>Appréciation</th>
                                    <th>Trimestre</th>
                                    <th>Année</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notes as $note)
                                    <tr>
                                        <td>{{ $note->id }}</td>
                                        <td>{{ optional($note->inscription->eleve)->nom ?? 'Classe inconnue' }} {{ optional($note->inscription->eleve)->prenom ?? 'Classe inconnue' }}</td>
                                       <td>
    {{ optional($note->inscription->classe)->nom ?? 'Classe introuvable' }}
</td>
                                        <td>{{ $note->matiere->nom }}</td>
                                        <td>{{ $note->moyenne_interro }}</td>
                                        <td>{{ $note->devoir1 }}</td>
                                        <td>{{ $note->devoir2 }}</td>
                                        <td><strong>{{ $note->moyenne_matiere }}</strong></td>
                                         <td><strong>{{ $note->appreciation }}</strong></td>
                                        <td>{{ $note->trimestre->nom }}</td>
                                        <td>{{ $note->annee->nom }}</td>
                                        <td class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('notes.show', $note->id) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                           <!-- <a href="{{ route('notes.edit', $note->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>-->
                                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette note ?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="p-3">
                        {{ $notes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script fermeture alerte --}}
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
