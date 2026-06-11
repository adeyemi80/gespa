@extends('tableau.neutre')

@section('title', 'Fiche de notes')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<a href="{{ url()->previous() }}" class="btn btn-secondary">
    ⬅️ Retour
</a>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h4 class="fw-bold mb-0 flex-grow-1 text-center">
            📄 Fiche de notes – {{ $trimestre->nom }}
        </h4>

        <a href="{{ route('fiches.pdf', request()->all()) }}" class="btn btn-danger">
            📥 Exporter PDF
        </a>
    </div>

    {{-- En-tête --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Année scolaire :</strong> {{ $annee->nom }} <br>
            <strong>Classe :</strong> {{ $classe->nom }} <br>
            <strong>Matière :</strong> {{ $matiere->nom }}
        </div>
        <div class="col-md-6">
            <strong>Coefficient :</strong> {{ $matiere->coefficient }} <br>
            <strong>Professeur :</strong> {{ $matiere->professeur->nom ?? '---' }}
        </div>
    </div>

    @include('fiches.table')

</div>
@endsection
