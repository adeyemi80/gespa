<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
        }

        .header h1 {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
        }

        .info-bloc {
            margin-bottom: 20px;
        }

        .info-bloc table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-bloc td {
            padding: 5px 8px;
            font-size: 12px;
        }

        .info-bloc td:first-child {
            font-weight: bold;
            width: 160px;
            color: #444;
        }

        .recap-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .recap-table thead tr {
            background-color: #2c3e50;
            color: #fff;
        }

        .recap-table th,
        .recap-table td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        .recap-table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .montant {
            text-align: right;
            font-weight: bold;
        }

        .badge-danger  { color: #c0392b; font-weight: bold; }
        .badge-success { color: #27ae60; font-weight: bold; }
        .badge-warning { color: #e67e22; font-weight: bold; }
        .badge-primary { color: #2980b9; font-weight: bold; }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    {{-- En-tête --}}
    <div class="header">
        <h1>Récapitulatif TD</h1>
        <p>
            {{ $resultat['eleve']->nom }} {{ $resultat['eleve']->prenom }}
            &mdash; {{ $resultat['classe']->niveau }}
            &mdash; {{ $moisNom }}
            &mdash; {{ $annee->libelle ?? $annee->nom ?? $annee->id }}
        </p>
    </div>

    {{-- Informations générales --}}
    <div class="info-bloc">
        <table>
            <tr>
                <td>Élève</td>
                <td>{{ $resultat['eleve']->nom }} {{ $resultat['eleve']->prenom }}</td>
            </tr>
            <tr>
                <td>Classe</td>
                <td>{{ $resultat['classe']->niveau }}</td>
            </tr>
            <tr>
                <td>Année scolaire</td>
                <td>{{ $annee->libelle ?? $annee->nom ?? $annee->id }}</td>
            </tr>
            <tr>
                <td>Période</td>
                <td>{{ $moisNom }}</td>
            </tr>
            <tr>
                <td>Mode de paiement</td>
                <td>{{ ucfirst($resultat['mode_paiement']) }}</td>
            </tr>
        </table>
    </div>

    {{-- Tableau récapitulatif --}}
    <table class="recap-table">
        <thead>
            <tr>
                <th>Indicateur</th>
                <th style="text-align:right;">Montant (F)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Arriéré avant ce mois</td>
                <td class="montant badge-warning">
                    {{ number_format($resultat['arriere_avant_ce_mois'], 0, ',', ' ') }}
                </td>
            </tr>
            <tr>
                <td>Dû ce mois</td>
                <td class="montant badge-primary">
                    {{ number_format($resultat['montant_du_mois'], 0, ',', ' ') }}
                </td>
            </tr>
            <tr>
                <td><strong>Dû cumulé</strong></td>
                <td class="montant badge-primary">
                    {{ number_format($resultat['montant_du_cumule'], 0, ',', ' ') }}
                </td>
            </tr>
            <tr>
                <td>Payé cumulé</td>
                <td class="montant badge-success">
                    {{ number_format($resultat['montant_paye_cumule'], 0, ',', ' ') }}
                </td>
            </tr>
            <tr style="background-color: #fdecea;">
                <td><strong>Reste à payer</strong></td>
                <td class="montant badge-danger">
                    {{ number_format($resultat['reste_a_payer_cumule'], 0, ',', ' ') }}
                </td>
            </tr>
            @if($resultat['avance'] > 0)
            <tr style="background-color: #eafaf1;">
                <td><strong>Avance</strong></td>
                <td class="montant badge-success">
                    {{ number_format($resultat['avance'], 0, ',', ' ') }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- Pied de page --}}
    <div class="footer">
        Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}
    </div>

</body>
</html>