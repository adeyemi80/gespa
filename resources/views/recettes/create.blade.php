{{-- resources/views/recettes/create.blade.php --}}
@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Nouvelle recette</h1>

    <form method="POST" action="{{ route('recettes.store') }}" class="space-y-4">
        @csrf
        @include('recettes.form')

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Enregistrer
            </button>
            <a href="{{ route('recettes.index') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection