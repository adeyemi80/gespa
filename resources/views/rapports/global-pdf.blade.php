<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Global PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 15px; }
        p { text-align: center; margin-bottom: 25px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #343a40; color: #fff; }
        tbody tr:nth-child(even) { background-color: #f2f2f2; }
        tfoot td { font-weight: bold; }
        .totaux { margin-top: 20px; }
        .totaux h4, .totaux h3 { margin: 5px 0; }
    </style>
</head>
<body>
    <h2>Rapport Global</h2>
    <p>Période : {{ $date_debut }} au {{ $date_fin }}</p>

    <table>
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Total Recettes</th>
                <th>Total Dépenses</th>
                <th>Solde</th>
            </tr>
        </thead>
        <tbody>
        @foreach($recap as $r)
            <tr>
                <td>{{ $r['categorie'] }}</td>
                <td>{{ number_format($r['recettes'], 0, ',', ' ') }} F</td>
                <td>{{ number_format($r['depenses'], 0, ',', ' ') }} F</td>
                <td>{{ number_format($r['solde'], 0, ',', ' ') }} F</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="totaux">
        <h4>Total Recettes Globales : {{ number_format($totalRecettes, 0, ',', ' ') }} F</h4>
        <h4>Total Dépenses Globales : {{ number_format($totalDepenses, 0, ',', ' ') }} F</h4>
        <h3>Solde Global : {{ number_format($soldeGlobal, 0, ',', ' ') }} F</h3>
    </div>
</body>
</html>
