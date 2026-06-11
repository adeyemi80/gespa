@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Modifier un examen blanc</h2>

    <form action="{{ route('examens-blancs.update', $examen->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="BEPC" {{ $examen->type == 'BEPC' ? 'selected' : '' }}>BEPC</option>
                <option value="BAC" {{ $examen->type == 'BAC' ? 'selected' : '' }}>BAC</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Date début</label>
            <input type="date" name="date_debut" class="form-control" value="{{ $examen->date_debut }}">
        </div>

        <div class="mb-3">
            <label>Date fin</label>
            <input type="date" name="date_fin" class="form-control" value="{{ $examen->date_fin }}">
        </div>

        <button class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('examens-blancs.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection