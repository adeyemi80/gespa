<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement - École</title>
    <style>
        @page { margin: 20mm; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 12px; 
            color: #333;
            max-width: 210mm; 
            margin: 0 auto; 
        }
        .header { 
            text-align: center; 
            border-bottom: 3px double #007bff; 
            padding-bottom: 15px; 
            margin-bottom: 20px;
        }
        .school-info { 
            font-size: 14px; 
            font-weight: bold; 
            color: #007bff;
        }
        .eleve-info { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 8px; 
            margin: 20px 0;
        }
        .frais-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
        }
        .frais-table th, .frais-table td { 
            border: 1px solid #ddd; 
            padding: 8px 12px; 
            text-align: left;
        }
        .frais-table th { 
            background: #007bff; 
            color: white;
        }
        .total-row { 
            font-weight: bold; 
            font-size: 13px; 
            background: #e9ecef;
        }
        .statut { 
            padding: 8px 15px; 
            border-radius: 20px; 
            font-weight: bold; 
            text-align: center;
            margin: 15px 0;
        }
        .statut.partiel { background: #ffc107; color: #000; }
        .statut.soldé { background: #28a745; color: white; }
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            font-size: 11px; 
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        /* ✅ MASQUER BOUTONS EN PDF/IMPRESSION */
        @media print, screen and (max-width: 768px) {
            .no-print { display: none !important; }
        }
        .no-print { margin-top: 30px; }
        .btn { 
            padding: 8px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-outline-secondary { border: 1px solid #6c757d; color: #6c757d; }
        .amount { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-info">
    <div class="logo">
        <img 
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}" 
            alt="Logo"
            style="width:300px; height:auto;"
        >
    </div>
</div>
          Adresse: AYELAWADJE, Cotonou, Benin <br>
            Tél: +229 0197189324/0197521637 | Email: complexeleglorieux@gmail.com
        </div>
        <h2>📄 RÉCÉPISSÉ DE PAIEMENT</h2>
        <div style="font-size: 16px; font-weight: bold;">Reçu n° : <span style="color: #007bff;">{{ $paiement->numero_recu ?? $numeroLot }}</span></div>
    </div>

    <div class="eleve-info">
        <table style="width: 100%; border-spacing: 0;">
            <tr>
                <td style="width: 30%;"><strong>Élève :</strong></td>
                <td>{{ $inscription->eleve->nom ?? 'BOUBACAR Mohamed' }} {{ $inscription->eleve->prenom ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Classe :</strong></td>
                <td>{{ $inscription->classe->nom ?? '4ème' }}</td>
            </tr>
            <tr>
                <td><strong>Année Scolaire :</strong></td>
                <td>{{ $inscription->annee->nom ?? '2025-2026' }}</td>
            </tr>
            <tr>
                <td><strong>Date :</strong></td>
                <td>{{ $paiement->date_paiement?->format('d/m/Y') ?? now()->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <h4>Frais payé(s) :</h4>
    <table class="frais-table">
        <thead>
            <tr>
                <th>Libellé</th>
                <th class="amount">Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details ?? [['nom' => 'scolarite', 'montant' => 30000]] as $detail)
                <tr>
                    <td>{{ ucfirst($detail['nom']) }}</td>
                    <td class="amount">{{ number_format($detail['montant'], 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="frais-table" style="margin-top: 0;">
        <tr class="total-row">
            <td><strong>Montant versé :</strong></td>
            <td class="amount"><strong>{{ number_format($total_ce_recu ?? 35000, 0, ',', ' ') }} FCFA</strong></td>
        </tr>
        <tr class="total-row">
            <td><strong>Total payé :</strong></td>
            <td class="amount"><strong>{{ number_format($total_paye ?? 35000, 0, ',', ' ') }} FCFA</strong></td>
        </tr>
        <tr class="total-row">
            <td><strong>Reste à payer :</strong></td>
            <td class="amount"><strong style="color: {{ ($reste ?? 65000) > 0 ? '#dc3545' : '#28a745' }}">{{ number_format($reste ?? 65000, 0, ',', ' ') }} FCFA</strong></td>
        </tr>
    </table>

    <div class="statut {{ strtolower($statut ?? 'Partiellement payé') }}">
        ✅ Statut : {{ $statut ?? 'Partiellement payé' }}
    </div>

    <div class="footer">
        <p>💳 Mode paiement : {{ $paiement->mode_paiement ?? 'Espèces' }}</p>
        <p>Merci pour votre confiance. Ce document vaut règlement.<br>
        Signature & Cachet de l'École</p>
        <hr style="margin: 20px 0;">
        <small>Généré le {{ now()->format('d/m/Y H:i') }} via Système de Gestion Scolaire</small>
    </div>

    {{-- ✅ Boutons (MASQUÉS AUTOMATIQUE en PDF/impression) --}}
    <div class="no-print text-center mt-4">
        <button onclick="window.print()" class="btn btn-secondary me-3">🖨️ Imprimer</button>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">← Retour</a>
    </div>

    <script>
        // Auto-impression si ouvert directement (optionnel)
        if (window.matchMedia('print').matches) window.print();
    </script>
</body>
</html>