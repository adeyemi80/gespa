@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>✏️ Modifier le cycle</h3>

    <form action="{{ route('cycles.update', $cycle) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom du cycle</label>
            <input type="text" name="nom" value="{{ $cycle->nom }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ordre</label>
            <input type="number" name="ordre" value="{{ $cycle->ordre }}" class="form-control">
        </div>

        <button class="btn btn-primary">💾 Mettre à jour</button>
        <a href="{{ route('cycles.index') }}" class="btn btn-secondary">Retour</a>
    </form>

</div>
@endsection