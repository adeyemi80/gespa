<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Budget des recettes</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #212529; }
        .entete { text-align: center; margin-bottom: 20px; }
        .entete h1 { font-size: 16px; margin: 0 0 4px 0; }
        .entete p { font-size: 11px; color: #555555; margin: 0; }
        table.synthese { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.synthese td { border: 1px solid #dddddd; padding: 8px; text-align: center; width: 25%; }
        table.synthese .libelle { font-size: 9px; color: #666666; display: block; margin-bottom: 3px; }
        table.synthese .valeur { font-size: 13px; font-weight: bold; }
        table.detail { width: 100%; border-collapse: collapse; }
        table.detail th { background-color: #198754; color: #ffffff; padding: 6px 8px; text-align: left; font-size: 10px; }
        table.detail td { padding: 6px 8px; border-bottom: 1px solid #dddddd; font-size: 10px; }
        table.detail tr:nth-child(even) { background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-danger { color: #dc3545; font-weight: bold; }
        .text-success { color: #198754; font-weight: bold; }
        .total-row td { border-top: 2px solid #198754; font-weight: bold; }
        .pied-page { margin-top: 20px; font-size: 9px; color: #888888; text-align: center; }
    </style>
</head>
<body>

    <div class="entete">
        <h1>Budget des recettes</h1>
        <p>Année scolaire : {{ $annee->nom }}</p>
        <p>Généré le {{ $dateGeneration->format('d/m/Y à H:i') }}</p>
    </div>

    <table class="synthese">
        <tr>
            <td>
                <span class="libelle">TOTAL PRÉVU</span>
                <span class="valeur">{{ number_format($totalPrevu, 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="libelle">TOTAL RÉALISÉ</span>
                <span class="valeur">{{ number_format($totalRealise, 0, ',', ' ') }} FCFA</span>
            </td>
            <td>
                <span class="libelle">ÉCART</span>
                <span class="valeur {{ $totalEcart < 0 ? 'text-danger' : 'text-success' }}">
                    {{ $totalEcart >= 0 ? '+' : '' }}{{ number_format($totalEcart, 0, ',', ' ') }} FCFA
                </span>
            </td>
            <td>
                <span class="libelle">TAUX DE RÉALISATION</span>
                <span class="valeur">{{ $tauxGlobal }}%</span>
            </td>
        </tr>
    </table>

    <table class="detail">
        <thead>
            <tr>
                <th>Code</th>
                <th>Catégorie</th>
                <th class="text-right">Prévu (FCFA)</th>
                <th class="text-right">Réalisé (FCFA)</th>
                <th class="text-right">Écart (FCFA)</th>
                <th class="text-center">Taux</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($budgets as $budget)
                <tr>
                    <td>{{ $budget->categorie->code }}</td>
                    <td>{{ $budget->categorie->nom }}</td>
                    <td class="text-right">{{ number_format($budget->montant_prevu, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($budget->montant_realise, 0, ',', ' ') }}</td>
                    <td class="text-right {{ $budget->montant_ecart < 0 ? 'text-danger' : 'text-success' }}">
                        {{ $budget->montant_ecart >= 0 ? '+' : '' }}{{ number_format($budget->montant_ecart, 0, ',', ' ') }}
                    </td>
                    <td class="text-center">{{ $budget->taux_realisation }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun budget de recette défini pour cette année scolaire.</td>
                </tr>
            @endforelse

            @if ($budgets->isNotEmpty())
                <tr class="total-row">
                    <td colspan="2">TOTAL</td>
                    <td class="text-right">{{ number_format($totalPrevu, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($totalRealise, 0, ',', ' ') }}</td>
                    <td class="text-right {{ $totalEcart < 0 ? 'text-danger' : 'text-success' }}">
                        {{ $totalEcart >= 0 ? '+' : '' }}{{ number_format($totalEcart, 0, ',', ' ') }}
                    </td>
                    <td class="text-center">{{ $tauxGlobal }}%</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="pied-page">
        GESPA — Gestion des recettes — Document généré automatiquement
    </div>

</body>
</html>