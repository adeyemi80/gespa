@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card text-bg-dark">
            <div class="card-header">
                <div class="float-start">
                    Détails de la classe
                </div>
                <div class="float-end">
                    <a href="{{ route('classes.index') }}" class="btn btn-primary btn-sm">&larr; Retour</a>
                </div>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush text-dark">
                    <li class="list-group-item"><strong>ID :</strong> {{ $classe->id }}</li>
                    <li class="list-group-item"><strong>Nom :</strong> {{ $classe->nom }}</li>
                    <li class="list-group-item"><strong>Niveau :</strong> {{ $classe->niveau ?? 'Non défini' }}</li>
                </ul>

                <div class="mt-3">
                    <a href="{{ route('classes.edit', $classe) }}" class="btn btn-warning">Modifier</a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
