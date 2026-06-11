@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <h4 class="mb-3">✏ Modifier échéance – {{ $frais->nom }}</h4>

    <form action="{{ route('frais.echeances.update', [$frais->id, $echeance->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="mb-3">
                    <label>Libellé</label>
                    <input name="libelle" value="{{ $echeance->nom }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Montant</label>
                    <input name="montant" type="number" value="{{ $echeance->montant }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Date limite</label>
                    <input name="date_limite" type="date" value="{{ $echeance->date_limite }}" class="form-control" required>
                </div>

                <div class="text-end">
                    <a href="{{ route('frais.echeances.index', $frais->id) }}" class="btn btn-light">Retour</a>
                    <button class="btn btn-success">💾 Enregistrer</button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection
