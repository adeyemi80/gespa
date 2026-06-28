@extends('tableau.neutre')

@section('content')

<button
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }"
    class="btn btn-secondary mb-3">
    ⬅️ Retour
</button>

<div class="container py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-12">

            {{-- En-tête --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-primary mb-0">📑 Liste des Notes</h4>
                <a class="btn btn-success" href="{{ route('notes.create') }}">
                    ➕ Ajouter une note
                </a>
            </div>

            {{-- Alertes session --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ✅ Composant Livewire --}}
            @livewire('notes-filtre')

        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) bootstrap.Alert.getOrCreateInstance(alert).close();
    }, 4000);
</script>

@endsection