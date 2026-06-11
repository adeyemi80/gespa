<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes - {{ $examen->type }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 4px; text-align: center; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>

@php use Carbon\Carbon; @endphp

<h2>
Notes des CANDIDATS - Examen Blanc {{ $examen->type }}<br>
Session de {{ Carbon::parse($examen->date_debut)->locale('fr')->translatedFormat('F Y') }}
</h2>

<table>
    <thead>
        <tr>
            <th>N° Table</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Classe</th>
            @foreach($matieres as $matiere)
                <th>{{ $matiere->nom }}</th>
            @endforeach
            <th>Moyenne</th>
        </tr>
    </thead>
    <tbody>
        @foreach($examen->participants as $p)
        <tr>
            <td>{{ $p->numero_table }}</td>
            <td>{{ $p->inscription->eleve->nom }}</td>
            <td>{{ $p->inscription->eleve->prenom }}</td>
            <td>{{ $p->inscription->classe->nom }}</td>
            
            @foreach($matieres as $matiere)
                @php
                    $note = $p->notes()->where('matiere_id', $matiere->id)->first();
                @endphp
                <td>{{ $note ? number_format($note->note,2) : '-' }}</td>
            @endforeach

            <td>{{ number_format($p->moyenne,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>