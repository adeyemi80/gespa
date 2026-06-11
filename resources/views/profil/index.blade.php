@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <h3 class="mb-4">👥 Liste des utilisateurs</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('profil.create') }}" class="btn btn-primary mb-3">
        ➕ Nouvel utilisateur
    </a>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th width="200">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('images/default.png') }}"
                                     width="50" height="50" class="rounded-circle">
                            </td>
                            <td>{{ $user->nom }}</td>
                            <td>{{ $user->prenom }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('profil.show', $user->id) }}" class="btn btn-sm btn-info">👁</a>
                                <a href="{{ route('profil.edit', $user->id) }}" class="btn btn-sm btn-warning">✏️</a>

                                <form action="{{ route('profil.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Supprimer ?')" class="btn btn-sm btn-danger">
                                        🗑
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun utilisateur</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

            {{ $users->links() }}

        </div>
    </div>

</div>
@endsection