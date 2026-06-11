<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export TD PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Liste des Paiements TD</h2>
    <table>
        <thead>
            <tr>
                <th>Élève</th>
                <th>Total payé (FCFA)</th>
                <th>Total non payé (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $ligne)
                <tr>
                    <td>{{ $ligne['nom'] }}</td>
                    <td>{{ number_format($ligne['total_paye'], 0, ',', ' ') }}</td>
                    <td>{{ number_format($ligne['total_non_paye'], 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
