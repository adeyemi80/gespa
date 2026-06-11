<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>📊 Rapport des opérations</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
        }

        header h2 {
            margin: 0;
            color: #007BFF;
        }

        p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #007BFF;
            color: white;
            text-align: center;
        }

        td {
            text-align: left;
        }

        tfoot td {
            font-weight: bold;
            background: #f2f2f2;
        }

        .totaux {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #007BFF;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <header>
         <img src="file://{{ public_path('images/entete.png') }}" width="" height="" alt="Logo">
        <h2>📊 Rapport des opérations financières </h2>
        <p><strong>Période :</strong> {{ $date_debut }} au {{ $date_fin }}</p>
    </header>

    <div class="totaux">
        <p><strong>Total recettes :</strong> {{ number_format($recettes, 2, ',', ' ') }}F CFA</p>
        <p><strong>Total dépenses :</strong> {{ number_format($depenses, 2, ',', ' ') }}F CFA</p>
        <p><strong>Solde :</strong> {{ number_format($solde, 2, ',', ' ') }}F CFA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th style="text-align:right;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($operations as $operation)
            <tr>
                <td style="text-align:center;">{{ \Carbon\Carbon::parse($operation->date)->format('d/m/Y') }}</td>
                <td>{{ $operation->libelle }}</td>
                <td>
                    @if($operation->categorie === 'recette')
                        Recette
                    @else
                        Dépense
                    @endif
                </td>
                <td>{{ $operation->description }}</td>
                <td style="text-align:right; width: 120px;">
    {{ number_format($operation->montant, 2, ',', ' ') }} F CFA
</td>
            </tr>
            @endforeach
        </tbody>
    </table>

   <footer>
    Généré automatiquement par le système de gestion financière adekolatrsor{{ date('d/m/Y H:i') }} 
    <span style="float:right;">
        Page <span class="page"></span> <!--/ <span class="topage"></span>-->
    </span>
</footer>

<style>
    .page:after { content: counter(page); }
    .topage:after { content: counter(pages); }
</style>

</body>
</html>
