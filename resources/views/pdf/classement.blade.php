<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Classement Trimestriel</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>

    <h3>📊 Classement Trimestriel</h3>
<p>
    Classe : {{ $classement->first()->classe->nom ?? '-' }} <br>
    Trimestre : {{ $classement->first()->trimestre->nom ?? '-' }}
</p>
    <table>
        <thead>
            <tr>
                <th>Rang</th>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Moyenne</th>
            </tr>
        </thead>

        <tbody>
            @foreach($classement as $item)
                <tr>
                    <td>{{ $item->rang }}</td>
                    <td>{{ $item->inscription->eleve->matricule ?? '-' }}</td>
                    <td>{{ $item->inscription->eleve->nom ?? '-' }}</td>
                    <td>{{ $item->inscription->eleve->prenom ?? '-' }}</td>
                    <td>{{ number_format($item->moyenne_trimestrielle, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>