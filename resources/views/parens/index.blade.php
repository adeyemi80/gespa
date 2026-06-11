@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="p-4 bg-white shadow rounded">
        <h1 class="mb-4 text-primary">👪 Liste des Parents</h1>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('parens.create') }}" class="btn btn-primary">
                ➕ Ajouter un parent
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle bg-white">
                <thead class="table-primary text-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parens as $paren)
                        <tr>
                            <td>{{ $paren->id }}</td>
                            <td>{{ $paren->nom_parent }}</td>
                            <td>{{ $paren->prenom_parent }}</td>
                            <td>{{ $paren->telephone_parent }}</td>
                            <td>{{ $paren->adresse_parent }}</td>
                            <td class="d-flex justify-content-center gap-2">
                                <a href="{{ route('parens.show', $paren) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('parens.edit', $paren) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('parens.destroy', $paren) }}" method="POST" onsubmit="return confirm('Supprimer ce parent ?')" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    setTimeout(function () {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 4000);
</script>
@endsection
