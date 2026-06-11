@extends('tableau.neutre')

@section('title', "Détails de l'élève")

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="row justify-content-center mt-3">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Détails de l'élève</span>
                <a href="{{ route('eleves.index') }}" class="btn btn-primary btn-sm">&larr; Retour</a>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush text-dark">
                    <li class="list-group-item"><strong>ID :</strong> {{ $eleve->id }}</li>
                    <li class="list-group-item"><strong>Nom :</strong> {{ $eleve->nom }}</li>
                    <li class="list-group-item"><strong>Prénom :</strong> {{ $eleve->prenom }}</li>
                    <li class="list-group-item"><strong>Date de naissance :</strong> {{ $eleve->date_naissance }}</li>
                    <li class="list-group-item"><strong>Sexe :</strong> {{ $eleve->sexe }}</li>
                    <li class="list-group-item"><strong>Téléphone :</strong> {{ $eleve->telephone }}</li>
                    <li class="list-group-item"><strong>Email :</strong> {{ $eleve->email }}</li>
                    <li class="list-group-item"><strong>Parent :</strong> 
                        @if($eleve->paren)
                            {{ $eleve->paren->nom }} {{ $eleve->paren->prenom }}
                        @else
                            <em>Aucun</em>
                        @endif
                    </li>
                    <li class="list-group-item"><strong>Lieu de naissance :</strong> {{ $eleve->lieu_naissance }}</li>
                    <li class="list-group-item"><strong>Matricule :</strong> {{ $eleve->matricule }}</li>
                </ul>

                <div class="mt-3">
                    <a href="{{ route('eleves.edit', $eleve) }}" class="btn btn-warning">Modifier</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
