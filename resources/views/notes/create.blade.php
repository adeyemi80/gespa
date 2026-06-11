@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ajouter une note</h5>
                    <a href="{{ route('notes.index') }}" class="btn btn-light btn-sm">&larr; Retour</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('notes.store') }}" method="POST">
                        @csrf

                        {{-- Inclure les champs du formulaire --}}
                        @include('notes.form')

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                            <a href="{{ route('notes.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
