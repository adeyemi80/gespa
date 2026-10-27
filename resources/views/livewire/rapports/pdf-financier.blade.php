<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>Rapport financier</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        .filters {
            margin-bottom: 18px;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .cards {
            width: 100%;
            margin-bottom: 20px;
        }

        .cards td {
            width: 33.33%;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .label {
            color: #555;
            font-size: 9px;
        }

        .amount {
            font-size: 14px;
            font-weight: bold;
            margin-top: 6px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th,
        table.report td {
            border: 1px solid #ccc;
            padding: 7px;
        }

        table.report th {
            background: #eaf0f8;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #f0f0f0;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #666;
            text-align: right;
        }
    </style>
</head>

<body>

    <h1>RAPPORT FINANCIER GÉNÉRAL</h1>

    <div class="subtitle">
        Gestion financière de l'établissement
    </div>

    <div class="filters">
        <strong>Année scolaire :</strong>
        {{ $annee->nom ?? 'Toutes les années' }}

        &nbsp;&nbsp; | &nbsp;&nbsp;

        <strong>Cycle :</strong>
        {{ $cycle->nom ?? 'Tous les cycles' }}

        &nbsp;&nbsp; | &nbsp;&nbsp;

        <strong>Classe :</strong>
        {{ $classe->nom ?? 'Toutes les classes' }}
    </div>

    <table class="cards">
        <tr>
            <td>
                <div class="label">Total attendu</div>
                <div class="amount">
                    {{ number_format($rapport['attenduGeneral'], 0, ',', ' ') }}
                    FCFA
                </div>
            </td>

            <td>
                <div class="label">Total encaissé</div>
                <div class="amount">
                    {{ number_format($rapport['encaisseGeneral'], 0, ',', ' ') }}
                    FCFA
                </div>
            </td>

            <td>
                <div class="label">Reste calculé</div>
                <div class="amount">
                    {{ number_format($rapport['resteGeneral'], 0, ',', ' ') }}
                    FCFA
                </div>
            </td>
        </tr>
    </table>

    <h3>Détail par catégorie de recette</h3>

    <table class="report">

        <thead>
            <tr>
                <th>Code</th>
                <th>Catégorie</th>
                <th class="right">Montant attendu</th>
                <th class="right">Montant encaissé</th>
                <th class="right">Reste calculé</th>
            </tr>
        </thead>

        <tbody>
            @foreach($rapport['categories'] as $categorie)
                <tr>
                    <td>{{ $categorie['code'] }}</td>

                    <td>{{ $categorie['nom'] }}</td>

                    <td class="right">
                        {{ number_format($categorie['attendu'], 0, ',', ' ') }}
                    </td>

                    <td class="right">
                        {{ number_format($categorie['encaisse'], 0, ',', ' ') }}
                    </td>

                    <td class="right">
                        {{ number_format($categorie['reste'], 0, ',', ' ') }}
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="total">
                <td colspan="2">TOTAL</td>

                <td class="right">
                    {{ number_format($rapport['attenduGeneral'], 0, ',', ' ') }}
                </td>

                <td class="right">
                    {{ number_format($rapport['encaisseGeneral'], 0, ',', ' ') }}
                </td>

                <td class="right">
                    {{ number_format($rapport['resteGeneral'], 0, ',', ' ') }}
                </td>
            </tr>
        </tfoot>

    </table>

    <div class="footer">
        Rapport généré le {{ $dateEdition }}
    </div>

</body>
</html>