@extends('tableau.neutre')

@section('title', 'calcul moyennes')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h2 class="mb-4">
        <i class="bi bi-file-earmark-arrow-up me-2"></i>
        CALCUL DES MOYENNES D'INTERROGATION ET DE MATIERE
    </h2>
    @livewire(\App\Livewire\SaisieNotes::class)
</div>
@endsection