@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-3 text-bg-dark">
    <h2>Modifier l'inscription</h2>

    <form action="{{ route('inscriptions.update', $inscription) }}" method="POST">
        @csrf
        @method('PUT')

        @include('inscriptions.form')

        <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
        <a href="{{ route('inscriptions.index') }}" class="btn btn-secondary mt-3">Annuler</a>
    </form>
</div>
@endsection
