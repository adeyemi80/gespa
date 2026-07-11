<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export des versements</title>
    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        .entete {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
        }

        .entete h1 {
            font-size: 18px;
            margin: 0 0 4px 0;
            color: #1f2937;
        }

        .entete p {
            margin: 0;
            font-size: 10px;
            color: #6b7280;
        }

        .groupe {
            margin-bottom: 18px;
        }

        .investisseur-titre {
            background-color: #eef2ff;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 12px;
            color: #3730a3;
            border-left: 4px solid #4f46e5;
        }

        table.versements {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.versements th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 5px 8px;
            font-size: 10px;
            color: #374151;
            border-bottom: 1px solid #d1d5db;
        }

        table.versements td {
            padding: 5px 8px;
            font-size: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.versements td.montant,
        table.versements th.montant {
            text-align: right;
        }

        .sous-total-ligne td {
            font-weight: bold;
            background-color: #f9fafb;
            border-top: 1px solid #9ca3af;
        }

        .total-general {
            margin-top: 20px;
            padding: 12px 15px;
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
            text-align: right;
            border-radius: 4px;
        }

        .pied-page {
            margin-top: 25px;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="entete">
        <h1>Rapport des versements</h1>
        <p>
            @if ($periode['debut'] || $periode['fin'])
                Période :
                {{ $periode['debut'] ? $periode['debut']->format('d/m/Y') : 'Début' }}
                &rarr;
                {{ $periode['fin'] ? $periode['fin']->format('d/m/Y') : 'Aujourd\'hui' }}
            @else
                Toutes périodes confondues
            @endif
            — Généré le {{ now()->format('d/m/Y à H:i') }}
        </p>
    </div>

    @forelse ($groupes as $groupe)
        <div class="groupe">
            <div class="investisseur-titre">
                {{ $groupe['investisseur']->nom ?? 'Investisseur inconnu' }}
                {{ $groupe['investisseur']->prenom ?? '' }}
                @if ($groupe['investisseur'] && $groupe['investisseur']->telephone)
                    — {{ $groupe['investisseur']->telephone }}
                @endif
            </div>

            <table class="versements">
                <thead>
                    <tr>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 15%;">Investissement</th>
                        <th style="width: 18%;">Mode</th>
                        <th style="width: 18%;">Référence</th>
                        <th style="width: 17%;" class="montant">Montant</th>
                        <th style="width: 20%;">Observation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupe['versements'] as $versement)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($versement->date_versement)->format('d/m/Y') }}</td>
                            <td>#{{ $versement->investissement_id }}</td>
                            <td>{{ $versement->mode_paiement ?: '—' }}</td>
                            <td>{{ $versement->reference ?: '—' }}</td>
                            <td class="montant">{{ number_format($versement->montant, 0, ',', ' ') }} F CFA</td>
                            <td>{{ $versement->observation ?: '—' }}</td>
                        </tr>
                    @endforeach

                    <tr class="sous-total-ligne">
                        <td colspan="4">Sous-total {{ $groupe['investisseur']->nom ?? '' }}</td>
                        <td class="montant">{{ number_format($groupe['sous_total'], 0, ',', ' ') }} F CFA</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    @empty
        <p style="text-align: center; color: #9ca3af; margin-top: 40px;">
            Aucun versement trouvé pour les critères sélectionnés.
        </p>
    @endforelse

    @if ($groupes->isNotEmpty())
        <div class="total-general">
            Total général : {{ number_format($totalGeneral, 0, ',', ' ') }} F CFA
        </div>
    @endif

    <div class="pied-page">
        GESPA — Gestion des investissements — Document généré automatiquement
    </div>

</body>
</html>