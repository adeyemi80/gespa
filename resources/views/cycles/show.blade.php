@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>👁 Détails du cycle</h3>

    <div class="card">
        <div class="card-body">
            <p><strong>ID :</strong> {{ $cycle->id }}</p>
            <p><strong>Nom :</strong> {{ $cycle->nom }}</p>
            <p><strong>Ordre :</strong> {{ $cycle->ordre }}</p>
            <p><strong>Créé le :</strong> {{ optional($cycle->created_at)->format('d/m H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('cycles.edit', $cycle) }}" class="btn btn-warning mt-3">✏️ Modifier</a>
    <a href="{{ route('cycles.index') }}" class="btn btn-secondary mt-3">⬅ Retour</a>

</div>
@endsection