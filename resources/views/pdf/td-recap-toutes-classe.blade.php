<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body        { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1          { text-align: center; font-size: 13px; margin-bottom: 2px; }
        .subtitle   { text-align: center; color: #555; font-size: 10px; margin-bottom: 14px; }
        h2          { font-size: 11px; background: #222; color: #fff;
                      padding: 4px 8px; margin: 18px 0 0 0; }
        table       { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        th          { background: #444; color: #fff; padding: 5px 7px; text-align: left; }
        th.right,
        td.right    { text-align: right; }
        th.center,
        td.center   { text-align: center; }
        td          { padding: 4px 7px; border-bottom: 1px solid #ddd; }
        tr.warning  { background: #fff8e1; }
        tfoot td    { background: #eeeeee; font-weight: bold; border-top: 2px solid #999; }
        .danger     { color: #c0392b; }
        .success    { color: #27ae60; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

<h1>Récapitulatif TD — Toutes les classes</h1>
<p class="subtitle">
    Année : {{ $annee->libelle ?? $annee->id }}
    @if($request->mode === 'mois')
        &nbsp;—&nbsp; Mois : {{ $request->mois }}
    @else
        &nbsp;—&nbsp; Année complète
    @endif
</p>

@foreach($blocs as $i => $bloc)

    <h2>{{ $bloc['classe']->niveau }}</h2>

    <table>
        <thead>
            <tr>
                <th style="width:18%">Nom</th>
                <th style="width:18%">Prénom</th>
                <th class="center" style="width:12%">Nbre TD suivi</th>
                <th class="right"  style="width:17%">Dû cumulé</th>
                <th class="right"  style="width:17%">Payé cumulé</th>
                <th class="right"  style="width:18%">Reste</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bloc['lignes'] as $ligne)
                <tr class="{{ $ligne['reste'] > 0 ? 'warning' : '' }}">
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
                <td class="center">{{ $bloc['totaux']['nb_td'] }}</td>
                <td class="right">{{ number_format($bloc['totaux']['du'],    0, ',', ' ') }} F</td>
                <td class="right success">{{ number_format($bloc['totaux']['paye'],  0, ',', ' ') }} F</td>
                <td class="right danger">{{ number_format($bloc['totaux']['reste'], 0, ',', ' ') }} F</td>
            </tr>
        </tfoot>
    </table>

    {{-- Saut de page entre classes (sauf après la dernière) --}}
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif

@endforeach

</body>
</html>