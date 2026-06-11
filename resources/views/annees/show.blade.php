@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-5">
    <div class="col-md-8">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Détails de l'année scolaire</h5>
                <a href="{{ route('annees.index') }}" class="btn btn-light btn-sm">&larr; Retour</a>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>ID :</strong>
                        <span>{{ $annee->id }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Nom :</strong>
                        <span>{{ $annee->nom }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Début :</strong>
                        <span>{{ $annee->debut }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Fin :</strong>
                        <span>{{ $annee->fin }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>En cours :</strong>
                        <span class="badge {{ $annee->active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $annee->active ? 'Oui' : 'Non' }}
                        </span>
                    </li>
                </ul>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('annees.edit', $annee) }}" class="btn btn-warning shadow-sm">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
