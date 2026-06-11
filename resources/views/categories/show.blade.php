@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient text-white rounded-top-4"
                     style="background: linear-gradient(90deg, #6f42c1, #20c997);">
                    <h3 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i> Détails de la Catégorie</h3>
                </div>

                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">{{ $categorie->nom }}</h4>
                    <p class="mb-2">{{ $categorie->description ?? 'Aucune description fournie.' }}</p>
                    <p class="mb-0"><strong>Créée le :</strong> {{ $categorie->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="card-footer text-end bg-light rounded-bottom-4">
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left-circle me-1"></i> Retour
                    </a>
                    <a href="{{ route('categories.edit', $categorie->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
