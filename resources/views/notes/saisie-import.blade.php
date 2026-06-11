@extends('tableau.neutre')

@section('title', 'Import de notes')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <h2 class="mb-4">
        <i class="bi bi-file-earmark-arrow-up me-2"></i>
        IMPORTATION SELECTIVE DES NOTES
    </h2>
    @livewire(\App\Livewire\NoteImportWizard::class)
</div>
@endsection