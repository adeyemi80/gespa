@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container mt-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white d-flex align-items-center">
            <i class="bi bi-plus-circle me-2 fs-4"></i>
            <h3 class="mb-0">Nouvelle Recette</h3>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('recettes.store') }}" method="POST">
                @csrf
                @include('recettes.partials.form', ['recette' => null])

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bi bi-check-circle"></i> Enregistrer
                    </button>
                    <a href="{{ route('recettes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
