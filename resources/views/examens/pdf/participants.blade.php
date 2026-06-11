<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Participants</title>
    <style>
        body { font-family: DejaVu Sans; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
    </style>
</head>
<body>

<h2>
Liste des CANDIDATS à l'Examen Blanc de - {{ $examen->type }}
Session de {{ \Carbon\Carbon::parse($examen->date_debut)->translatedFormat('F Y') }}
</h2>

<table>
    <thead>
        <tr>
            <th>N° Table</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Classe</th>
        </tr>
    </thead>
    <tbody>
        @foreach($examen->participants as $p)
        <tr>
            <td>{{ $p->numero_table }}</td>
            <td>{{ $p->inscription->eleve->nom }}</td>
            <td>{{ $p->inscription->eleve->prenom }}</td>
            <td>{{ $p->inscription->classe->nom }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>