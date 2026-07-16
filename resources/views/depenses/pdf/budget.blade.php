<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Budget des dépenses</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #212529;
        }

        .entete {
            text-align: center;
            margin-bottom: 20px;
        }

        .entete h1 {
            font-size: 16px;
            margin: 0 0 4px 0;
        }

        .entete p {
            font-size: 11px;
            color: #555555;
            margin: 0;
        }

        table.synthese {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.synthese td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
            width: 25%;
        }

        table.synthese .libelle {
            font-size: 9px;
            color: #666666;
            display: block;
            margin-bottom: 3px;
        }

        table.synthese .valeur {
            font-size: 13px;
            font-weight: bold;
        }

        table.detail {
            width: 100%;
            border-collapse: collapse;
        }

        table.detail th {
            background-color: #343a40;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        table.detail td {
            padding: 6px 8px;
            border-bottom: 1px solid #dddddd;
            font-size: 10px;
        }

        table.detail tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #343a40;
            font-weight: bold;
        }

        .pied-page {
            margin-top: 20px;
            font-size: 9px;
            color: #888888;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="entete">
        <h1>Budget Dépenses Année Scolaire {{ $annee->nom }}</h1>
        <p>Généré le {{ $dateGeneration->format('d/m/Y à H:i') }}</p>
    </div>

    <table class="synthese">
        <tr>
            <td>
                <span class="libelle">TOTAL ALLOUÉ</span>
                <span class="valeur">{{ number_format($totalAlloue, 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="libelle">TOTAL UTILISÉ</span>
                <span class="valeur">{{ number_format($totalUtilise, 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="libelle">TOTAL RESTANT</span>
                <span class="valeur {{ $totalRestant < 0 ? 'text-danger' : '' }}">
                    {{ number_format($totalRestant, 0, ',', ' ') }} FCFA
                </span>
            </td>
            <td>
                <span class="libelle">TAUX D'UTILISATION</span>
                <span class="valeur">{{ $tauxGlobal }}%</span>
            </td>
        </tr>
    </table>

    <table class="detail">
        <thead>
            <tr>
                <th>Code</th>
                <th>Catégorie</th>
                <th class="text-right">Alloué (FCFA)</th>
                <th class="text-right">Utilisé (FCFA)</th>
                <th class="text-right">Restant (FCFA)</th>
                <th class="text-center">Taux</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($budgets as $budget)
                <tr>
                    <td>{{ $budget->categorie->code }}</td>
                    <td>{{ $budget->categorie->nom }}</td>
                    <td class="text-right">{{ number_format($budget->montant_alloue, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($budget->montant_utilise, 0, ',', ' ') }}</td>
                    <td class="text-right {{ $budget->montant_restant < 0 ? 'text-danger' : '' }}">
                        {{ number_format($budget->montant_restant, 0, ',', ' ') }}
                    </td>
                    <td class="text-center">{{ $budget->taux_utilisation }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun budget défini pour cette année scolaire.</td>
                </tr>
            @endforelse

            @if ($budgets->isNotEmpty())
                <tr class="total-row">
                    <td colspan="2">TOTAL</td>
                    <td class="text-right">{{ number_format($totalAlloue, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($totalUtilise, 0, ',', ' ') }}</td>
                    <td class="text-right {{ $totalRestant < 0 ? 'text-danger' : '' }}">
                        {{ number_format($totalRestant, 0, ',', ' ') }}
                    </td>
                    <td class="text-center">{{ $tauxGlobal }}%</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="pied-page">
        GESPA — Gestion des dépenses — Document généré automatiquement
    </div>

</body>
</html>