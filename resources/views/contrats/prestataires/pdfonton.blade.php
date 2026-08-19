<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat #{{ $contrat->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            background: white;
        }

        .page {
            width: 21cm;
            height: 29.7cm;
            padding: 10mm;
            margin: 0 auto;
            background: white;
            page-break-after: always;
            position: relative;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* En-tête du document */
        .header {
            text-align: center;
            margin-bottom: 5mm;
            border-bottom: 2px solid #8B4513;
            padding-bottom: 6mm;
        }

        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 5mm;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Sections */
        .section {
            margin-bottom: 8mm;
        }

        .section-title {
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            color: #8B4513;
            margin-bottom: 5mm;
            padding-bottom: 2mm;
            border-bottom: 1px solid #CCC;
            letter-spacing: 0.5px;
        }

        .section-content {
            margin-left: 8mm;
            font-size: 10.5pt;
        }

        .field {
            margin-bottom: 3mm;
            display: flex;
            justify-content: space-between;
        }

        .field-label {
            font-weight: bold;
            color: #555;
            min-width: 120px;
        }

        .field-value {
            color: #333;
            flex: 1;
            padding-left: 10mm;
        }

        .article {
            margin-bottom: 6mm;
            font-size: 10.5pt;
        }

        .article-title {
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 2mm;
        }

        .article-content {
            margin-left: 5mm;
            text-align: justify;
        }

        .article ul {
            margin-left: 5mm;
            list-style-position: inside;
        }

        .article li {
            margin-bottom: 1.5mm;
        }

        /* Montants en boîtes */
        .amounts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8mm;
            margin: 8mm 0;
        }

        .amount-box {
            border: 1px solid #DDD;
            padding: 6mm;
            text-align: center;
            background: #F9F9F9;
            border-radius: 3px;
        }

        .amount-label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 2mm;
        }

        .amount-value {
            font-size: 12pt;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 2mm;
        }

        .amount-letters {
            font-size: 9pt;
            color: #666;
            font-style: italic;
        }

        /* Signatures */
        .signatures-section {
            margin-top: 10mm;
            page-break-inside: avoid;
        }

        .signature-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5mm;
            margin-top: 5mm;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 100%;
            height: 40mm;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 2mm;
        }

        .signature-label {
            font-weight: bold;
            font-size: 10pt;
            margin-top: 2mm;
            text-transform: uppercase;
            color: #8B4513;
        }

        .signature-sublabel {
            font-size: 9pt;
            color: #666;
            margin-top: 1mm;
        }

        /* Mentionné */
        .mention-box {
            margin-top: 8mm;
            font-size: 9.5pt;
            font-style: italic;
            color: #666;
            background: #F0F0F0;
            padding: 5mm;
            border-radius: 3px;
        }

        /* Dates et lieux */
        .date-location {
            margin-top: 5mm;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10mm;
            font-size: 10.5pt;
        }

        .date-location-item {
            line-height: 1.8;
        }

        .date-location-label {
            font-weight: bold;
            color: #555;
        }

        .date-location-value {
            border-bottom: 1px solid #CCC;
            min-height: 8mm;
        }

        /* Approbation finale */
        .approval {
            margin-top: 8mm;
            text-align: center;
            font-style: italic;
            font-size: 10pt;
            color: #8B4513;
            font-weight: bold;
        }

        /* Pagination et numéros de page */
        .page-number {
            position: absolute;
            bottom: 5mm;
            right: 5mm;
            font-size: 9pt;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }

        /* Tableau pour les infos */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5mm 0;
        }

        table tr {
            border-bottom: 1px solid #EEE;
        }

        table td {
            padding: 3mm 5mm;
            font-size: 10.5pt;
        }

        table td.label {
            font-weight: bold;
            color: #555;
            width: 40%;
            vertical-align: top;
        }

        /* Styles d'impression */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .page {
                margin: 0;
                box-shadow: none;
                page-break-after: always;
            }
        }
        .logo {
    width: 50%;
    max-width: 50%;
    margin: 0 0 6px 0;
    text-align: center;

    page-break-inside: avoid;
    break-inside: avoid;
}

.logo img {
    display: block;

    width: 50%;
    max-width: 50%;
    height: auto;

    margin: 0 auto;
}
    </style>
</head>
<body>

<!-- PAGE 1 -->
<div class="page">
    {{-- ======================================================
             IMAGE / EN-TÊTE DE L'ÉTABLISSEMENT
        ======================================================= --}}

        <div class="logo">
            <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}"
                alt="En-tête de l'établissement"
            >
        </div>

    <div class="header">
        <h1>CONTRAT DE PRESTATION DE SERVICES ET DÉCHARGE</h1>
    </div>

    <!-- Parties du contrat -->
    <div class="section">
        <div class="section-title">L'ÉTABLISSEMENT</div>
        <div class="section-content">
            <div class="field">
                <div class="field-label">Dénomination :</div>
                <div class="field-value">{{ $contrat->etablissement }}</div>
            </div>
            <div class="field">
                <div class="field-label">Adresse :</div>
                <div class="field-value">{{ $contrat->adresse_etablissement }}</div>
            </div>
            <div class="field">
                <div class="field-label">Représenté par :</div>
                <div class="field-value">{{ $contrat->representant }}</div>
            </div>
            <div class="field">
                <div class="field-label">Fonction :</div>
                <div class="field-value">{{ $contrat->fonction }}</div>
            </div>
            <p style="margin-top: 4mm; font-style: italic; color: #666;">Ci-après dénommé « l'Établissement »,</p>
            <p style="margin-top: 2mm; font-weight: bold;">D'une part,</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">LE PRESTATAIRE</div>
        <div class="section-content">
            <div class="field">
                <div class="field-label">Nom et prénom :</div>
                <div class="field-value">{{ $contrat->prestataire_nom }}</div>
            </div>
            <div class="field">
                <div class="field-label">Adresse :</div>
                <div class="field-value">{{ $contrat->prestataire_adresse }}</div>
            </div>
            <div class="field">
                <div class="field-label">Téléphone :</div>
                <div class="field-value">{{ $contrat->telephone }}</div>
            </div>
            <div class="field">
                <div class="field-label">NPI / IFU :</div>
                <div class="field-value">{{ $contrat->ifu ?? 'Non fourni' }}</div>
            </div>
            <p style="margin-top: 4mm; font-style: italic; color: #666;">Ci-après dénommé « le Prestataire »,</p>
            <p style="margin-top: 2mm; font-weight: bold;">D'autre part.</p>
        </div>
    </div>

    <!-- Articles du contrat -->
    <div class="section">
        <div class="article">
            <div class="article-title">ARTICLE 1 : OBJET</div>
            <div class="article-content">
                Le présent document tient lieu de contrat et de décharge relative à :
                <p style="margin: 3mm 0; font-weight: bold;">{{ $contrat->objet_contrat }}</p>
                au profit de l'Établissement.
            </div>
        </div>

        <div class="article">
            <div class="article-title">ARTICLE 2 : MONTANT DU MARCHÉ</div>
            <div class="article-content">
                Les parties conviennent que le coût total de la prestation est fixé à :
            </div>
            <div class="amounts-grid">
              
                    <div class="amount-label">MONTANT TOTAL</div>
                    <div class="amount-value">{{ number_format($contrat->montant_total, 0, ',', ' ') }} FCFA</div>
                    <div class="amount-letters">{{ ucfirst($contrat->montant_total_lettre) }}</div>
              
                
                    <div class="amount-label">ACOMPTE REÇU</div>
                    <div class="amount-value">{{ number_format($contrat->acompte, 0, ',', ' ') }} FCFA</div>
                    <div class="amount-letters">{{ ucfirst($contrat->acompte_lettre) }}</div>
             
                    <div class="amount-label">RELIQUAT À PAYER</div>
                    <div class="amount-value">{{ number_format($contrat->reliquat, 0, ',', ' ') }} FCFA</div>
                    <div class="amount-letters">{{ ucfirst($contrat->reliquat_lettre) }}</div>
               
            </div>
        </div>

        <div class="article">
            <div class="article-title">ARTICLE 3 : ACOMPTE REÇU</div>
            <div class="article-content">
                Le Prestataire reconnaît avoir reçu de l'Établissement la somme de <strong>{{ number_format($contrat->acompte, 0, ',', ' ') }} FCFA</strong> (en lettres : <strong>{{ ucfirst($contrat->acompte_lettre) }}</strong>) à titre d'acompte sur le montant total du marché.
                <p style="margin-top: 3mm;">Par la présente, le Prestataire donne bonne et valable décharge à l'Établissement pour cette somme.</p>
            </div>
        </div>

        <div class="article">
            <div class="article-title">ARTICLE 4 : RELIQUAT À PAYER</div>
            <div class="article-content">
                Après déduction de l'acompte versé, le solde restant dû s'élève à <strong>{{ number_format($contrat->reliquat, 0, ',', ' ') }} FCFA</strong> (en lettres : <strong>{{ ucfirst($contrat->reliquat_lettre) }}</strong>).
                <p style="margin-top: 3mm;">Ce montant sera payé au Prestataire après la livraison complète et après constat de leur conformité par l'Établissement.</p>
            </div>
        </div>

        <div class="article">
            <div class="article-title">ARTICLE 5 : ENGAGEMENT DU PRESTATAIRE</div>
            <div class="article-content">
                Le Prestataire s'engage à :
                <ul>
                    <li>faire les réalisations conformément aux caractéristiques convenues ;</li>
                    <li>respecter les normes de solidité et de qualité requises pour un usage scolaire ;</li>
                    <li>livrer l'intégralité de la commande au plus tard le {{ $contrat->date_limite_livraison->format('d/m/Y') }} ;</li>
                    <li>remplacer ou réparer à ses frais tout ouvrage reconnu défectueux à la réception.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-number">Page 1 sur 2</div>
</div>

<!-- PAGE 2 -->
<div class="page">
    <!-- Articles suite -->
    <div class="section">
        <div class="article">
            <div class="article-title">ARTICLE 6 : RÉCEPTION ET PAIEMENT DU SOLDE</div>
            <div class="article-content">
                La réception sera effectuée par l'Établissement après vérification :
                <ul>
                    <li>de la quantité commandée ;</li>
                    <li>de la qualité de fabrication ;</li>
                    <li>de la conformité aux spécifications convenues.</li>
                </ul>
                <p style="margin-top: 3mm;">Le paiement du reliquat ne pourra intervenir qu'après cette réception.</p>
                <p style="margin-top: 3mm;">En cas de défauts majeurs ou de livraison incomplète, l'Établissement se réserve le droit de différer le paiement du solde jusqu'à la correction des insuffisances constatées.</p>
            </div>
        </div>

        <div class="article">
            <div class="article-title">ARTICLE 7 : DÉFAUT DE LIVRAISON</div>
            <div class="article-content">
                En cas de non-livraison ou d'abandon du marché par le Prestataire, celui-ci s'engage à rembourser à l'Établissement l'acompte perçu, sans préjudice des autres recours que l'Établissement pourrait exercer.
            </div>
        </div>

        <div class="article">
            <div class="article-title">ARTICLE 8 : RÈGLEMENT DES LITIGES</div>
            <div class="article-content">
                Les parties s'efforceront de régler à l'amiable tout différend relatif à l'exécution du présent engagement.
                <p style="margin-top: 3mm;">À défaut d'accord amiable, les juridictions compétentes seront saisies.</p>
            </div>
        </div>

        @if ($contrat->mention_manuelle)
            <div class="article">
                <div class="article-title">MENTIONS ADDITIONNELLES</div>
                <div class="article-content">
                    {{ $contrat->mention_manuelle }}
                </div>
            </div>
        @endif
    </div>

    <!-- Dates et lieu -->
    <div class="date-location">
        <div class="date-location-item">
            <div class="date-location-label">FAIT À :</div>
            <div class="date-location-value">{{ $contrat->lieu_signature }}</div>
        </div>
        <div class="date-location-item">
            <div class="date-location-label">LE :</div>
            <div class="date-location-value">{{ $contrat->date_signature->format('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Signatures -->
    <div class="signatures-section">
        <div class="signature-container">
            <!-- Signature Établissement -->
            <div class="signature-box">
                <div style="font-weight: bold; margin-bottom: 3mm; text-transform: uppercase; font-size: 10pt;">POUR L'ÉTABLISSEMENT</div>
                <div style="font-size: 9.5pt; margin-bottom: 8mm;">LE DIRECTEUR</div>
                <div class="signature-label">Signature</div>
                <div class="signature-sublabel">Nom et Prénom</div>
                <div class="signature-line"></div>
            </div>

            <!-- Signature Prestataire -->
            <div class="signature-box">
                <div style="font-weight: bold; margin-bottom: 3mm; text-transform: uppercase; font-size: 10pt;">LE PRESTATAIRE</div>
                 <!-- Déclaration du Prestataire -->
        <div class="mention-box">
            <p style="margin-bottom: 3mm;">Je soussigné(e) <strong>________________________</strong>, reconnais avoir reçu la somme de <strong>{{ number_format($contrat->acompte, 0, ',', ' ') }} FCFA</strong> à titre d'acompte pour la prestation susmentionnée et m'engage à exécuter la commande conformément aux dispositions du présent document.</p>
            <p style="margin-bottom: 3mm;">Mention manuscrite : ________________________________</p>
        </div>
         <!-- Approbation finale -->
        <div class="approval">
            « Lu et approuvé – Bon pour réception de l'acompte et engagement de prestation. »
        </div>
                <div class="signature-label">Signature</div>
                <div class="signature-sublabel">Nom et Prénom</div>
            </div>
        </div>
    </div>

    <div class="page-number">Page 2 sur 2</div>
</div>

</body>
</html>