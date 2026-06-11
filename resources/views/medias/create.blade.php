@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>➕ Ajouter un média</h3>

    <p class="text-muted">
        Galerie : <strong>{{ $galerie->titre }}</strong>
    </p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('medias.store', $galerie->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label class="form-label">Titre (optionnel)</label>

            <input type="text"
                   name="titre"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Fichier (image ou vidéo)</label>

            <input type="file"
                   name="fichier"
                   class="form-control"
                   required>
        </div>

        <button class="btn btn-success">
            Enregistrer
        </button>

        <a href="{{ route('galeries.show', $galerie->id) }}"
           class="btn btn-secondary">
            Retour
        </a>

    </form>

</div>

@endsection