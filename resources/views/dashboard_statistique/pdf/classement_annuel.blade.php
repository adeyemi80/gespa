<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Classement Annuel</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .stats {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    {{-- 🔝 HEADER --}}
<table class="header-table">
    <tr>
        <td class="logo" style="width:150%;">
            <img 
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}"
                style="max-width: 740px; max-height: 100px; width: auto; height: auto;"
                alt="Logo"
            >
        </td>

        <td style="width:50%; text-align:right;">
            <strong>Année scolaire :</strong> {{ $annee->nom ?? '' }}
        </td>
    </tr>
</table>

<h2>
    RESULTATS ANNUELS 
</h2>

    <div class="stats">
        <strong>Effectif :</strong> {{ $stats['effectif'] }} |
        <strong>Moyenne générale :</strong> {{ number_format($stats['moyenne_generale'], 2) }} |
        <strong>Admis :</strong> {{ $stats['admis'] }} |
        <strong>Échoués :</strong> {{ $stats['echoues'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Rang</th>
                <th>Élève</th>
                <th>Classe</th>
                <th>Moyenne</th>
                <th>Décision</th>
            </tr>
        </thead>

        <tbody>
            @foreach($topEleves as $index => $moyenne)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $moyenne->inscription->eleve->nom }} {{ $moyenne->inscription->eleve->prenom }}</td>
                    <td>{{ $moyenne->inscription->classe->nom ?? '' }}</td>
                    <td>{{ $moyenne->moyenne_annuelle }}</td>
                    <td>{{ $moyenne->passage }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>