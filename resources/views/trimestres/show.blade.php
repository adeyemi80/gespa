@extends('tableau.neutre')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Détails du trimestre</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ $trimestre->nom }}</p>
                    <p><strong>Période :</strong> {{ $trimestre->periode ?? '-' }}</p>
                    <p><strong>Ordre :</strong> {{ $trimestre->ordre }}</p>
                    <p><strong>Année(s) :</strong>
                        @if($trimestre->annees->isNotEmpty())
                            {{ $trimestre->annees->pluck('nom')->join(', ') }}
                        @else
                            -
                        @endif
                    </p>

                    <a href="{{ route('trimestres.index') }}" class="btn btn-outline-secondary mt-3">Retour</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
