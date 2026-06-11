@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">👨‍🏫 Liste des enseignants</h5>
            <a href="{{ route('enseignants.create') }}" class="btn btn-light btn-sm">
                ➕ Ajouter un enseignant
            </a>
        </div>

        <div class="card-body px-0">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mx-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive px-3">
                <table class="table table-bordered table-hover align-middle bg-white text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Email</th>
                            <th>Classe(s)</th>
                            <th>Matière(s)</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enseignants as $enseignant)
                            <tr>
                                <td>{{ $enseignant->matricule }}</td>
                                <td>{{ $enseignant->nom }}</td>
                                <td>{{ $enseignant->prenom }}</td>
                                <td>{{ $enseignant->sexe }}</td>
                                <td>{{ $enseignant->email }}</td>
                                <td>
    {{ $enseignant->classes->pluck('nom')->implode(', ') }}
</td>
                                <td>@if($enseignant->matiere)
    {{ $enseignant->matiere->nom }}
@else
    <span class="text-muted">Aucune matière</span>
@endif</td>
                                <td>{{ $enseignant->telephone }}</td>
                                <td class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('enseignants.show', $enseignant->id) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('enseignants.edit', $enseignant->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('enseignants.destroy', $enseignant->id) }}" method="POST" onsubmit="return confirm('Supprimer cet enseignant ?')">
                                        @csrf
                                        @method('DELETE')
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

            <div class="mt-3 px-3">
                {{ $enseignants->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Script de fermeture automatique de l’alerte --}}
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
