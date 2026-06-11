@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold">📄 Détails de l'Epreuve</h1>
        <a href="{{ route('tests.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <ul class="list-group list-group-flush mb-3">
                 <li class="list-group-item"><strong>Titre :</strong> {{ $test->titre ?? '-' }}</li>
                <li class="list-group-item"><strong>Matière :</strong> {{ $test->matiere->nom ?? '-' }}</li>
                <li class="list-group-item"><strong>Classe :</strong> {{ $test->classe->nom ?? '-' }}</li>
                <li class="list-group-item"><strong>Année :</strong> {{ $test->annee->nom ?? '-' }}</li>
                <li class="list-group-item"><strong>Type :</strong> 
                    <span class="badge bg-primary">{{ ucfirst($test->type) }}</span>
                </li>
                <li class="list-group-item"><strong>Période :</strong> {{ $test->trimestre->nom ?? '-' }}</li>
                <li class="list-group-item"><strong>Description :</strong><br>{{ $test->description ?? '-' }}</li>
                @if($test->fichier)
                    <li class="list-group-item">
                        <strong>Fichier :</strong> 
                        <a href="{{ asset('storage/' . $test->fichier) }}" target="_blank" class="btn btn-sm btn-outline-success ms-2">
                            <i class="bi bi-download me-1"></i>Télécharger
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
