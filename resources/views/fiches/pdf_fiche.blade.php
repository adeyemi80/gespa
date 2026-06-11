<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Base Bootstrap-like */
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 20px;
        }

        h3 {
            font-size: 16px;
            text-align: center;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: rgba(216, 230, 227, 0.85);
            color: rgb(31, 27, 27);
            text-align: center;
        }

        td.text-start {
            text-align: left;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            text-align: left;
        }

        .logo img {
            width: 400px;
            height: auto;
        }

        .info {
            text-align: left;
            font-size: 13px;
        }

        .info strong {
            display: inline-block;
            width: 160px;
        }

        .table-container {
            margin-top: 10px;
        }

        /* Page break pour PDF */
        .page-break { page-break-after: always; }

        /* Lignes alternées pour tableau */
        tbody tr:nth-child(odd) { background-color: #f8f9fa; }

    </style>
</head>
<body>

@foreach($fiches as $index => $fiche)

    <table class="header-table">
        <tr>
            <td class="logo" style="width:50%;">
                {{-- Encodage image pour DomPDF --}}
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}" alt="Logo">
            </td>
            <td class="info" style="width:50%; font-size:14px;">
    <div><strong>Année scolaire :</strong> {{ $annee->nom }}</div>
    <div><strong>Classe :</strong> {{ $classe->nom }}</div>
    <div><strong>Matière :</strong> {{ $fiche['matiere']->nom }}</div>
    <div><strong>Coefficient :</strong> {{ $fiche['matiere']->coefficient }}</div>
    <div><strong> Professeur :</strong></div>
</td>

        </tr>
    </table>
{{--FICHES DE NOTES DE TOUTES LES MATIERES D'UNE CLASSE--}}
    <h3>FICHE DE NOTES DU {{ strtoupper($trimestre->nom) }} – {{ $fiche['matiere']->nom }}</h3>

    <div class="table-container">
        <table class="table table-bordered table-sm text-center align-middle">
            <thead>
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
                    <td class="text-start">{{ $res['eleve']->nom }} {{ $res['eleve']->prenom }}</td>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td></td><td></td>
                    <td></td>
                    <!--<td>{{ $res['matiere']->coefficient ?? '---' }}</td>-->
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Page break uniquement si ce n'est pas la dernière fiche --}}
    @if($index < count($fiches) - 1)
        <div class="page-break"></div>
    @endif

@endforeach

</body>
</html>
