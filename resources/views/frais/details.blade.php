@extends('tableau.neutre')

@section('content')
<div class="container">
    <h2>Frais de {{ $eleve->nom }} {{ $eleve->prenom }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Frais</th>
                <th>Échéance</th>
                <th>Montant</th>
                <th>Payé</th>
                <th>Reste</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($frais as $f)
                @foreach($eleve->echeances()->where('frais_id', $f->id)->get() as $e)
                    @php
                        $totalPaye = $eleve->paiements()->where('echeance_id', $e->id)->sum('montant_paye');
                        $reste = $e->montant - $totalPaye;
                    @endphp
                    <tr>
                        <td>{{ $f->nom_frais }}</td>
                        <td>{{ $e->date_limite->format('d/m/Y') }}</td>
                        <td>{{ number_format($e->montant, 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($reste, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @if($reste == 0)
                                <span class="badge bg-success">Payé</span>
                            @else
                                <span class="badge bg-danger">Restant</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
