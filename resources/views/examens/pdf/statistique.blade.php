<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Classement - {{ $examen->type }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background-color: #f0f0f0; }
        .top1 { background-color: gold; }
        .top2 { background-color: silver; }
        .top3 { background-color: #cd7f32; } /* bronze */
    </style>
</head>
<body>
@php use Carbon\Carbon; @endphp

<h2>
Classement des participants à l'Examen Blanc de - {{ $examen->type }}<br>
Session de {{ Carbon::parse($examen->date_debut)->locale('fr')->translatedFormat('F Y') }}
</h2>

{{-- Moyenne générale et statistiques --}}
<p>
Moyenne générale : {{ number_format($stats['moyenne_generale'],2) }} <br>
Plus Faible Moyenne : {{ number_format($stats['min'],2) }}, Plus Forte Moyenne : {{ number_format($stats['max'],2) }}
</p>

<table>
    <thead>
        <tr>
            <th>Rang</th>
            <th>N° Table</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Classe</th>
            <th>Moyenne</th>
            <th>Mention</th>
        </tr>
    </thead>
    <tbody>
        @php
            function rangFrGenre($rang,$sexe) { return $rang==1 ? ($sexe=='F'?'1ère':'1er') : $rang.'ème'; }
            $rang=1; $lastMoy=null;
        @endphp

        @foreach($participants as $index => $p)
            @if($lastMoy !== null && $p->moyenne < $lastMoy)
                @php $rang = $index+1; @endphp
            @endif

            @php
                // Mention
                $mention = $p->moyenne >= 10 ? 'Admis' : 'Ajourné';

                // Classement top 3
                $classeTop = '';
                if($index==0) $classeTop='top1';
                elseif($index==1) $classeTop='top2';
                elseif($index==2) $classeTop='top3';
            @endphp

            <tr class="{{ $classeTop }}">
                <td>{{ rangFrGenre($rang,$p->inscription->eleve->sexe) }}</td>
                <td>{{ $p->numero_table }}</td>
                <td>{{ $p->inscription->eleve->nom }}</td>
                <td>{{ $p->inscription->eleve->prenom }}</td>
                <td>{{ $p->inscription->classe->nom }}</td>
                <td>{{ number_format($p->moyenne,2) }}</td>
                <td>{{ $mention }}</td>
            </tr>

            @php $lastMoy = $p->moyenne; @endphp
        @endforeach
    </tbody>
</table>

</body>
</html>