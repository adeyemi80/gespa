<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Classement des élèves</title>

    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }

        h2 { text-align: center; margin-bottom: 20px; }

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
            background: #f2f2f2;
        }

        .good { color: green; }
        .bad { color: red; }
    </style>
</head>
<body>

<h2>
    Classement des Résultats du {{$trimestre->nom}} des élèves
</h2>

<table>
    <thead>
        <tr>
            <th>Rang</th>
            <th>Nom</th>
            <th>Classe</th>
            <th>Moyenne</th>
        </tr>
    </thead>

    <tbody>
        @foreach($topEleves as $i => $ins)
        <tr>

            {{-- 🏆 RANG --}}
            <td>
    {{ $i + 1 }}
</td>

            {{-- 👨‍🎓 NOM --}}
            <td>
                {{ $ins->eleve->nom ?? '' }} {{ $ins->eleve->prenom ?? '' }}
            </td>

            {{-- 🏫 CLASSE --}}
            <td>
                {{ $ins->classe->nom ?? '' }}
            </td>

            {{-- 📊 MOYENNE --}}
            <td class="{{ ($ins->moyenne >= 10) ? 'good' : 'bad' }}">
                {{ $ins->moyenne }}
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>