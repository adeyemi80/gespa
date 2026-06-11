@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container">
    <h2>Importer les notes</h2>

    {{-- 🔹 Bouton de téléchargement du modèle Excel --}}
    <a href="{{ route('examens.notes.template', $examen->id) }}" class="btn btn-success mb-3">
        Télécharger le modèle Excel
    </a>

    {{-- 🔹 Formulaire d'importation --}}
    <form action="{{ route('examens.notes.preview') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="examen_id" value="{{ $examen->id }}">
        <input type="file" name="file" required>
        <button class="btn btn-primary">Prévisualiser</button>
    </form>
</div>
@endsection