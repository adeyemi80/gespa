@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow rounded">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-pencil-square me-2"></i>
                    <h5 class="mb-0">✏️ Modifier l’enseignant</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('enseignants.update', $enseignant) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('enseignants.form')

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('enseignants.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-arrow-left-circle"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
