<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; font-size: 13px; margin: 20px; }
h3   { font-size: 16px; text-align: center; margin-bottom: 20px; text-transform: uppercase; }
table { width: 100%; border-collapse: collapse; font-size: 12px; }
th, td { border: 1px solid #000; padding: 6px; }
th { background-color: #eee; text-align: center; }
.header-table td { border: none; vertical-align: middle; }
.logo img { width: 400px; height: auto; }
.info strong { display: inline-block; width: 160px; }
.table-container { margin-top: 20px; }
</style>
</head>
<body>

<table class="header-table">
    <tr>
        <td class="logo" style="width:50%;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}" alt="Logo">
        </td>
        <td class="info" style="width:50%; font-size:14px;">
            <div><strong>Année scolaire :</strong> {{ $annee->nom }}</div>
            <div><strong>Classe :</strong>         {{ $classe->nom }}</div>
            <div><strong>Matière :</strong>        {{ $matiere->nom }}</div>
            <div><strong>Coefficient :</strong>    {{ $coef }}</div>
            <div><strong>Professeur :</strong></div>
        </td>
    </tr>
</table>

<h3>FICHE DE NOTES DU {{ strtoupper($trimestre->nom) }}</h3>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th rowspan="2">N°</th>
                <th rowspan="2">Nom et Prénoms</th>
                <th colspan="5">Évaluation Ponctuelle d'Étape (EPE)</th>
                <th rowspan="2">Devoir 1</th>
                <th rowspan="2">Devoir 2</th>
                <th rowspan="2">Moyenne</th>
                <th rowspan="2">Moy. Coef</th>
                <th rowspan="2">Rang</th>
            </tr>
            <tr>
                <th>EPE1</th><th>EPE2</th><th>EPE3</th><th>EPE4</th><th>Moy EPE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultats as $i => $res)
                <tr>
                    <td style="text-align:center">{{ $i + 1 }}</td>
                    <td>{{ $res['eleve']->nom }} {{ $res['eleve']->prenom }}</td>
                    <td></td><td></td><td></td><td></td>
                    <td style="text-align:center">{{ $res['moy_epe'] ?? '' }}</td>
                    <td style="text-align:center">{{ $res['note']->devoir ?? '' }}</td>
                    <td style="text-align:center">{{ $res['note']->mcc    ?? '' }}</td>
                    <td style="text-align:center">
                        {{ isset($res['moyenne'])  ? number_format($res['moyenne'],  2) : '' }}
                    </td>
                    <td style="text-align:center">
                        {{ isset($res['moy_coef']) ? number_format($res['moy_coef'], 2) : '' }}
                    </td>
                    <td style="text-align:center">{{ $res['rang'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>