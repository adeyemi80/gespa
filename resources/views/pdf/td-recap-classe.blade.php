<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
h2   { text-align: center; margin-bottom: 4px; }
p    { text-align: center; color: #555; margin-top: 0; }
table { width: 100%; border-collapse: collapse; margin-top: 16px; }
th    { background: #222; color: #fff; padding: 6px 8px; }
td    { padding: 5px 8px; border: 1px solid #ccc; }
tfoot td { background: #eee; font-weight: bold; }
.right  { text-align: right; }
.center { text-align: center; }
.danger { color: #c0392b; }
.success{ color: #27ae60; }
</style>
</head>
<body>
<h2>Récapitulatif TD — {{ $classe->niveau }}</h2>
<p>
        Année : {{ $annee->libelle ?? $annee->id }}
        @if($mode === 'mois')
            — Mois : {{ $moisNom }}
        @else
            — Année complète
        @endif
</p>
<table>
<thead>
<tr>
<th>Nom</th>
<th>Prénom</th>
<th class="center">Nbre TD suivi</th>
<th class="right">Dû cumulé</th>
<th class="right">Payé cumulé</th>
<th class="right">Reste</th>
</tr>
</thead>
<tbody>
            @foreach($lignes as $ligne)
<tr>
<td>{{ $ligne['nom'] }}</td>
<td>{{ $ligne['prenom'] }}</td>
<td class="center">{{ $ligne['nb_td'] }}</td>
<td class="right">{{ number_format($ligne['du'],    0, ',', ' ') }} F</td>
<td class="right success">{{ number_format($ligne['paye'],  0, ',', ' ') }} F</td>
<td class="right {{ $ligne['reste'] > 0 ? 'danger' : 'success' }}">
                    {{ number_format($ligne['reste'], 0, ',', ' ') }} F
</td>
</tr>
            @endforeach
</tbody>
<tfoot>
<tr>
<td colspan="2">TOTAUX</td>
<td class="center">{{ $totaux['nb_td'] }}</td>
<td class="right">{{ number_format($totaux['du'],    0, ',', ' ') }} F</td>
<td class="right success">{{ number_format($totaux['paye'],  0, ',', ' ') }} F</td>
<td class="right danger">{{ number_format($totaux['reste'], 0, ',', ' ') }} F</td>
</tr>
</tfoot>
</table>
</body>
</html>