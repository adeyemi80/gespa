<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Récapitulatif TD — Toutes les classes</title>
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
h2 { text-align: center; margin-bottom: 4px; }
h4 { margin: 14px 0 4px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
th, td { border: 1px solid #333; padding: 4px 6px; }
th { background: #333; color: #fff; text-align: center; }
td.num { text-align: right; }
tfoot td { font-weight: bold; background: #eee; }
</style>
</head>
<body>
<h2>Récapitulatif TD — {{ $cycle?->nom ?? 'Tous cycles' }}</h2>
<p style="text-align:center;">
        Année {{ $annee->nom ?? $annee->id }}
        — {{ $mode === 'mois' ? 'Mois de ' . $moisNom : 'Toute l’année' }}
</p>
    @foreach($recapToutesClasses as $bloc)
<h4>{{ $bloc['classe']->niveau }}</h4>
<table>
<thead>
<tr>
<th>Nom</th><th>Prénom</th><th>Nb TD</th>
<th>Dû</th><th>Payé</th><th>Reste</th>
</tr>
</thead>
<tbody>
                @foreach($bloc['lignes'] as $ligne)
<tr>
<td>{{ $ligne['nom'] }}</td>
<td>{{ $ligne['prenom'] }}</td>
<td>{{ $ligne['nb_td'] }}</td>
<td class="num">{{ number_format($ligne['du'], 0, ',', ' ') }} F</td>
<td class="num">{{ number_format($ligne['paye'], 0, ',', ' ') }} F</td>
<td class="num">{{ number_format($ligne['reste'], 0, ',', ' ') }} F</td>
</tr>
                @endforeach
</tbody>
<tfoot>
<tr>
<td colspan="2">TOTAUX</td>
<td>{{ $bloc['totaux']['nb_td'] }}</td>
<td class="num">{{ number_format($bloc['totaux']['du'], 0, ',', ' ') }} F</td>
<td class="num">{{ number_format($bloc['totaux']['paye'], 0, ',', ' ') }} F</td>
<td class="num">{{ number_format($bloc['totaux']['reste'], 0, ',', ' ') }} F</td>
</tr>
</tfoot>
</table>
    @endforeach

    @php
        $totGenNbTd  = collect($recapToutesClasses)->sum(fn($b) => $b['totaux']['nb_td']);
        $totGenDu    = collect($recapToutesClasses)->sum(fn($b) => $b['totaux']['du']);
        $totGenPaye  = collect($recapToutesClasses)->sum(fn($b) => $b['totaux']['paye']);
        $totGenReste = collect($recapToutesClasses)->sum(fn($b) => $b['totaux']['reste']);
    @endphp
    <table>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL GÉNÉRAL</td>
                <td>{{ $totGenNbTd }}</td>
                <td class="num">{{ number_format($totGenDu, 0, ',', ' ') }} F</td>
                <td class="num">{{ number_format($totGenPaye, 0, ',', ' ') }} F</td>
                <td class="num">{{ number_format($totGenReste, 0, ',', ' ') }} F</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>