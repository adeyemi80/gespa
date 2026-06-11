@extends('classes.layout')

@section('content')
<button 
    onclick="if (window.history.length > 1) { history.back(); } else { window.location.href='{{ route('tableau.accueil') }}'; }" 
    class="btn btn-secondary">
    ⬅️ Retour
</button>
<div class="container py-4">

    <h4 class="mb-3">👁 Détail échéance – {{ $frais->nom }}</h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table">
                <tr>
                    <th>Libellé</th>
                    <td>{{ $echeance->nom }}</td>
                </tr>
                <tr>
                    <th>Montant</th>
                    <td>{{ number_format($echeance->montant, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr>
                    <th>Date limite</th>
                    <td>{{ \Carbon\Carbon::parse($echeance->date_limite)->format('d/m/Y') }}</td>
                </tr>
            </table>

            <div class="text-end">
                <a href="{{ route('frais.echeances.index', $frais->id) }}" class="btn btn-secondary">⬅ Retour</a>
            </div>

        </div>
    </div>
</div>
@endsection
