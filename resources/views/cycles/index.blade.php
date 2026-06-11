@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>📚 Liste des cycles</h3>
        <a href="{{ route('cycles.create') }}" class="btn btn-primary">
            ➕ Ajouter un cycle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cycles as $cycle)
                <tr>
                    <td>{{ $cycle->id }}</td>
                    <td>{{ $cycle->nom }}</td>
                    <td>{{ $cycle->ordre }}</td>
                    <td>
                        <a href="{{ route('cycles.show', $cycle) }}" class="btn btn-info btn-sm">👁</a>
                        <a href="{{ route('cycles.edit', $cycle) }}" class="btn btn-warning btn-sm">✏️</a>

                        <form action="{{ route('cycles.destroy', $cycle) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Supprimer ce cycle ?')" class="btn btn-danger btn-sm">
                                🗑
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucun cycle trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection