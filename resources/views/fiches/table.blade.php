<table class="table table-bordered table-sm text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th rowspan="2">N°</th>
            <th rowspan="2">Nom et Prénoms</th>
            <th colspan="5">Évaluation Ponctuelle d’Étape (EPE)</th>
            <th rowspan="2">Devoir1</th>
            <th rowspan="2">Devoir2</th>
            <th rowspan="2">Moyenne</th>
            <!--<th rowspan="2">Coef</th>-->
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
    @foreach($resultats as $i => $res)
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
           <!-- <td>{{ $coef }}</td>-->

            {{-- Moy coef --}}
            <td></td>

            {{-- Rang --}}
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
