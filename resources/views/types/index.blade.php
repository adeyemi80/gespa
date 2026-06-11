@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📂 Liste des Types</h3>
        <a href="{{ route('types.create') }}" class="btn btn-success">➕ Ajouter Type</a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-primary text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $type)
                        <tr class="text-center">
                            <td>{{ $type->id }}</td>
                            <td>{{ $type->nom }}</td>
                            <td>
                                <a href="{{ route('types.edit', $type) }}" class="btn btn-sm btn-warning">✏️ Éditer</a>
                                <form action="{{ route('types.destroy', $type) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Voulez-vous vraiment supprimer ce type ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑 Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="3" class="text-muted">Aucun type trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
