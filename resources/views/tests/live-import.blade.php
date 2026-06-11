@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
    <div class="container py-4">
        <h3>Import avancé — Livewire</h3>
        <livewire:test-importer />
    </div>
@endsection
