@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
     {{-- Message de succès --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            ✅ {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    <h3>Liste des utilisateurs</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>N°</th>
                <th>Nom & Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->nom }} {{ $user->prenom }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">Voir</a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Modifier</a>
                 <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Supprimer ?')" class="btn btn-sm btn-danger">
                                        🗑
                                    </button>
                                </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
