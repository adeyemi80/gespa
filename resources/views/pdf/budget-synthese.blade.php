{{-- resources/views/pdf/budget-synthese.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, sans-serif; font-size: 11px; color: #212529; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        .sous-titre { color: #6c757d; font-size: 11px; margin-bottom: 20px; }
        h2 { font-size: 14px; margin-top: 25px; margin-bottom: 10px; border-bottom: 2px solid #dee2e6; padding-bottom: 5px; }

        .cartes { width: 100%; margin-bottom: 12px; }
        .cartes td { width: 25%; padding: 8px; text-align: center; border: 1px solid #dee2e6; }
        .carte-label { font-size: 9px; text-transform: uppercase; color: #6c757d; }
        .carte-valeur { font-size: 15px; font-weight: bold; margin-top: 3px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.data th { background: #f1f3f5; padding: 6px 8px; text-align: left; font-size: 10px; text-transform: uppercase; border: 1px solid #dee2e6; }
        table.data td { padding: 6px 8px; border: 1px solid #dee2e6; }
        table.data td.text-end, table.data th.text-end { text-align: right; }
        table.data tfoot td { font-weight: bold; background: #f8f9fa; }

        .positif { color: #198754; }
        .negatif { color: #dc3545; }

        .solde-box { width: 48%; display: inline-block; padding: 12px; border: 1px solid #dee2e6; margin-top: 10px; }
        .footer { margin-top: 30px; font-size: 9px; color: #adb5bd; text-align: center; }
    </style>
</head>
<body>
<div class="logo">
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}"
         alt="Logo" style="width:710px; height:160px;">
</div>
    <h1 style="font-size: 32px; text-align: center;">Budget Global Exercice {{ $anneeLibelle }}</h1>
    <p class="sous-titre">
          Généré le {{ now()->format('d/m/Y à H:i') }}
    </p>

    {{-- RECETTES --}}
    <h2>Budget des recettes</h2>

    <table class="cartes">
        <tr>
            <td>
                <div class="carte-label">Total prévu</div>
                <div class="carte-valeur">{{ number_format($totalPrevu, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="carte-label">Total réalisé</div>
                <div class="carte-valeur">{{ number_format($totalRealise, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="carte-label">Écart</div>
                <div class="carte-valeur {{ $totalEcart >= 0 ? 'positif' : 'negatif' }}">
                    {{ number_format($totalEcart, 0, ',', ' ') }} FCFA
                </div>
            </td>
            <td>
                <div class="carte-label">Taux de réalisation</div>
                <div class="carte-valeur">{{ $tauxRecettes }}%</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Code</th>
                <th>Catégorie</th>
                <th class="text-end">Prévu (FCFA)</th>
                <th class="text-end">Réalisé (FCFA)</th>
                <th class="text-end">Écart (FCFA)</th>
                <th class="text-end">Taux</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($budgetsRecettes as $ligne)
                <tr>
                    <td>{{ $ligne['code'] }}</td>
                    <td>{{ $ligne['nom'] }}</td>
                    <td class="text-end">{{ number_format($ligne['prevu'], 0, ',', ' ') }}</td>
                    <td class="text-end">{{ number_format($ligne['realise'], 0, ',', ' ') }}</td>
                    <td class="text-end {{ $ligne['ecart'] >= 0 ? 'positif' : 'negatif' }}">
                        {{ number_format($ligne['ecart'], 0, ',', ' ') }}
                    </td>
                    <td class="text-end">{{ $ligne['taux'] }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-end">{{ number_format($totalPrevu, 0, ',', ' ') }}</td>
                <td class="text-end">{{ number_format($totalRealise, 0, ',', ' ') }}</td>
                <td class="text-end {{ $totalEcart >= 0 ? 'positif' : 'negatif' }}">
                    {{ number_format($totalEcart, 0, ',', ' ') }}
                </td>
                <td class="text-end">{{ $tauxRecettes }}%</td>
            </tr>
        </tfoot>
    </table>

    {{-- DEPENSES --}}
    <h2>Budget des dépenses</h2>

    <table class="cartes">
        <tr>
            <td>
                <div class="carte-label">Total alloué</div>
                <div class="carte-valeur">{{ number_format($totalAlloue, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="carte-label">Total utilisé</div>
                <div class="carte-valeur">{{ number_format($totalUtilise, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="carte-label">Total restant</div>
                <div class="carte-valeur">{{ number_format($totalRestant, 0, ',', ' ') }} FCFA</div>
            </td>
            <td>
                <div class="carte-label">Taux d'utilisation</div>
                <div class="carte-valeur">{{ $tauxDepenses }}%</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Code</th>
                <th>Catégorie</th>
                <th class="text-end">Alloué (FCFA)</th>
                <th class="text-end">Utilisé (FCFA)</th>
                <th class="text-end">Restant (FCFA)</th>
                <th class="text-end">Taux</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($budgetsDepenses as $ligne)
                <tr>
                    <td>{{ $ligne['code'] }}</td>
                    <td>{{ $ligne['nom'] }}</td>
                    <td class="text-end">{{ number_format($ligne['alloue'], 0, ',', ' ') }}</td>
                    <td class="text-end">{{ number_format($ligne['utilise'], 0, ',', ' ') }}</td>
                    <td class="text-end">{{ number_format($ligne['restant'], 0, ',', ' ') }}</td>
                    <td class="text-end">{{ $ligne['taux'] }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-end">{{ number_format($totalAlloue, 0, ',', ' ') }}</td>
                <td class="text-end">{{ number_format($totalUtilise, 0, ',', ' ') }}</td>
                <td class="text-end">{{ number_format($totalRestant, 0, ',', ' ') }}</td>
                <td class="text-end">{{ $tauxDepenses }}%</td>
            </tr>
        </tfoot>
    </table>

    {{-- SOLDE --}}
    <h2>Solde</h2>

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; padding-right: 10px;">
                <div class="solde-box">
                    <div class="carte-label">Solde prévisionnel (Prévu − Alloué)</div>
                    <div class="carte-valeur {{ $soldePrevu >= 0 ? 'positif' : 'negatif' }}" style="font-size: 18px;">
                        {{ number_format($soldePrevu, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </td>
            <td style="width: 50%; padding-left: 10px;">
                <div class="solde-box">
                    <div class="carte-label">Solde réalisé (Réalisé − Utilisé)</div>
                    <div class="carte-valeur {{ $soldeRealise >= 0 ? 'positif' : 'negatif' }}" style="font-size: 18px;">
                        {{ number_format($soldeRealise, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <p class="footer">GESPA — Budget global — Document généré automatiquement</p>

</body>
</html>