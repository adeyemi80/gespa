@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">

    <h3>➕ Nouvelle galerie</h3>

    <form action="{{ route('galeries.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">
            <label>Titre</label>
            <input type="text"
                   name="titre"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description"
                      class="form-control"></textarea>
        </div>

        <button class="btn btn-success">
            Enregistrer
        </button>

        <a href="{{ route('galeries.index') }}"
           class="btn btn-secondary">
            Retour
        </a>

    </form>

</div>

@endsection