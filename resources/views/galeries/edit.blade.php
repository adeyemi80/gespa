@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>✏️ Modifier galerie</h3>

    <form action="{{ route('galeries.update', $galerie) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Titre</label>
            <input type="text"
                   name="titre"
                   value="{{ $galerie->titre }}"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description"
                      class="form-control">{{ $galerie->description }}</textarea>
        </div>

        <button class="btn btn-primary">
            Mettre à jour
        </button>

        <a href="{{ route('galeries.index') }}"
           class="btn btn-secondary">
            Retour
        </a>

    </form>

</div>

@endsection