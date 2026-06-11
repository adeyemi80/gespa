@extends('tableau.neutre')

@section('title', "Fiches de notes - {$classe->nom}")

@section('content')
<div class="container-fluid py-4">
<a href="{{ route('fiches.export.pdf', [
        'annee_id' => $annee->id,
        'trimestre_id' => $trimestre->id,
        'classe_id' => $classe->id
    ]) }}" class="btn btn-danger mb-3">
    📥 Exporter PDF
</a>
 {{-- Bouton Retour --}}
        <a href="{{ route('fiches.formulaire') }}" class="btn btn-secondary me-2">
            ← Retour 
        </a>
    <h3 class="text-center mb-4">
        Fiches de notes – {{ $classe->nom }} – {{ $trimestre->nom }}
    </h3>

    @foreach($fiches as $fiche)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $fiche['matiere']->nom }}</h5>

                <table class="table table-bordered table-sm text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2">N°</th>
                            <th rowspan="2">Nom et Prénoms</th>
                            <th colspan="5">Évaluation Ponctuelle d’Étape (EPE)</th>
                            <th rowspan="2">Devoir1</th>
                            <th rowspan="2">Devoir2</th>
                            <th rowspan="2">Moyenne</th>
                           <!-- <th rowspan="2">Coef</th>-->
                            <th rowspan="2">Moy. Coef</th>
                            <th rowspan="2">Rang</th>
                        </tr>
                        <tr>
                            <th>EPE1</th>
                            <th>EPE2</th>
                            <th>EPE3</th>
                            <th>EPE4</th>
                            <th>Moy EPE</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($fiche['resultats'] as $i => $res)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-start">
                                    {{ $res['eleve']->nom }} {{ $res['eleve']->prenom }}
                                </td>

                                {{-- EPE --}}
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                {{-- Devoirs --}}
                                <td></td>
                                <td></td>

                                {{-- Moyennes --}}
                                <td></td>

                                {{-- Coef --}}
                                <!--<td>{{ $res['matiere']->coefficient ?? '---' }}</td>-->

                                {{-- Moy coef --}}
                                <td></td>

                                {{-- Rang --}}
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Page break pour PDF --}}
        <div class="page-break"></div>
    @endforeach

</div>

<style>
/* Pour PDF : chaque matière commence sur une nouvelle page */
.page-break { page-break-after: always; }
</style>
@endsection
