@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>✏️ Modifier média</h3>

    <form action="{{ route('medias.update', $media) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Titre</label>
            <input type="text"
                   name="titre"
                   value="{{ $media->titre }}"
                   class="form-control">
        </div>

        <button class="btn btn-primary">
            Mettre à jour
        </button>

        <a href="{{ route('galeries.show', $media->galerie_id) }}"
           class="btn btn-secondary">
            Retour
        </a>

    </form>

</div>

@endsection