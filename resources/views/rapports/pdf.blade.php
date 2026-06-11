<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport PDF</title>
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

        .total {
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
        <h2>Rapport - {{ $categorie->nom }}</h2>
        <p><strong>Période :</strong> {{ $date_debut }} au {{ $date_fin }}</p>
    </header>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th style="text-align:right;">Montant (F CFA)</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr>
                <td style="text-align:center;">{{ \Carbon\Carbon::parse($t->date_transaction)->format('d/m/Y') }}</td>
                <td>{{ $t->categorie->description }}</td>
                <td style="text-align:right;">{{ number_format($t->montant, 0, ',', ' ') }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;">Total</td>
                <td style="text-align:right;">{{ number_format($somme, 0, ',', ' ') }} F</td>
            </tr>
        </tfoot>
    </table>

    <footer>
        Généré automatiquement par le système de gestion financière - {{ date('d/m/Y H:i') }}
    </footer>
</body>
</html>
