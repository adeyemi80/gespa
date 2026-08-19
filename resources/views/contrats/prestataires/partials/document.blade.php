<style>
    /* ============================================================
       FORMAT A4 — 2 PAGES MAXIMUM
       POLICES CONSERVÉES
    ============================================================ */

    @page {
        size: A4 portrait;
        margin: 8mm 8mm 8mm 8mm;
    }

    * {
        box-sizing: border-box;
    }

    :root {
        --or: #b8860b;
        --or-fonce: #8f6500;
        --or-clair: #d4af37;
        --texte: #252525;
        --gris: #666;
        --bordure: #d8d8d8;
    }

    body {
        margin: 0;
        padding: 0;
        background: #f1f3f5;
        color: var(--texte);

        /* POLICE INCHANGÉE */
        font-family: "DejaVu Serif", Georgia, "Times New Roman", serif;
        font-size: 10.2pt;
        line-height: 1.30;
    }

    .contrat-wrapper {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }

    .page {
        width: 100%;
        max-width: 100%;
        background: #fff;
        overflow: visible;
    }


    /* ============================================================
       LOGO / EN-TÊTE ÉTABLISSEMENT
    ============================================================ */

    .logo {
        width: 100%;
        max-width: 100%;
        margin: 0 0 4px 0;
        padding: 0;
        text-align: center;

        page-break-inside: avoid;
        break-inside: avoid;
    }

    .logo img {
        display: block;

        width: 100%;
        max-width: 100%;
        height: auto;

        margin: 0 auto;
    }


    /* ============================================================
       EN-TÊTE
    ============================================================ */

    .header {
        width: 100%;
        text-align: center;
        margin-bottom: 5px;
    }

    .header h1 {
        margin: 0;

        color: var(--or-fonce);

        /* POLICE ET TAILLE CONSERVÉES */
        font-family: "DejaVu Serif", Georgia, serif;
        font-size: 16pt;
        font-weight: bold;

        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .header .subtitle {
        margin-top: 1px;

        color: #444;

        /* CONSERVÉ */
        font-size: 10.5pt;
        font-weight: bold;
        font-style: italic;
    }

    .header .line {
        width: 100%;
        height: 2px;

        margin-top: 3px;

        background: var(--or);
    }


    /* ============================================================
       PARTIES
    ============================================================ */

    .parties-title {
        color: var(--or-fonce);

        text-align: center;
        font-size: 11pt;
        font-weight: bold;

        margin: 3px 0;
    }

    .partie {
        width: 100%;
        max-width: 100%;

        margin-bottom: 3px;

        /*
         * On évite seulement les coupures lorsque le bloc est petit.
         * Cela permet au navigateur/PDF de mieux remplir les pages.
         */
        page-break-inside: auto;
        break-inside: auto;
    }

    .partie-title {
        color: var(--or-fonce);

        font-size: 10.8pt;
        font-weight: bold;

        text-transform: uppercase;

        border-bottom: 1px solid var(--or);

        padding-bottom: 1px;
        margin-bottom: 1px;
    }

    .partie p {
        margin: 1px 0;
        padding: 0;
    }


    /* ============================================================
       ARTICLES
    ============================================================ */

    .article {
        width: 100%;
        max-width: 100%;

        margin-top: 3px;
        margin-bottom: 2px;

        text-align: justify;

        /*
         * IMPORTANT :
         * on ne bloque pas complètement les coupures.
         * Cela évite qu'un article entier parte sur la page suivante
         * et crée un espace vide.
         */
        page-break-inside: auto;
        break-inside: auto;
    }

    .article-title {
        color: var(--or-fonce);

        font-size: 10.8pt;
        font-weight: bold;

        text-transform: uppercase;

        margin-bottom: 1px;

        border-bottom: 1px solid rgba(184, 134, 11, .45);

        padding-bottom: 1px;

        page-break-after: avoid;
        break-after: avoid;
    }

    .article p {
        margin: 1.5px 0;
        padding: 0;

        text-align: justify;

        orphans: 2;
        widows: 2;
    }

    .article ul {
        margin-top: 1px;
        margin-bottom: 1px;

        padding-left: 16px;
    }

    .article li {
        margin-bottom: 0.5px;

        text-align: justify;

        orphans: 2;
        widows: 2;
    }


    /* ============================================================
       OBJET DU CONTRAT
    ============================================================ */

    .objet-contrat {
        display: block;

        width: 100%;
        max-width: 100%;

        /*
         * Empêche le texte de sortir de la marge.
         */
        white-space: normal;
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: normal;

        line-height: 1.30;
    }


    /* ============================================================
       MONTANTS
    ============================================================ */

    .amount-box {
        width: 100%;
        max-width: 100%;

        margin: 2px 0;
        padding: 2px 6px;

        border-left: 3px solid var(--or);

        border-top: 1px solid #ddd;
        border-right: 1px solid #ddd;
        border-bottom: 1px solid #ddd;

        background: #fffdf6;

        /*
         * Uniquement pour les petits blocs.
         */
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .amount-label {
        color: #555;
        font-weight: bold;
    }

    .amount-value {
        color: var(--or-fonce);

        font-size: 11pt;
        font-weight: bold;
    }


    /* ============================================================
       TEXTES
    ============================================================ */

    .italic {
        font-style: italic;
    }

    .small {
        font-size: 9pt;
    }

    strong {
        font-weight: bold;
    }


    /* ============================================================
       LIEU / DATE
    ============================================================ */

    .signature-intro {
        margin-top: 5px;

        text-align: right;
    }

    .signature-date {
        margin-top: 1px;

        text-align: right;
    }

    .signature-intro strong,
    .signature-date strong {
        color: var(--or-fonce);
    }


    /* ============================================================
       SIGNATURES
    ============================================================ */

    .signature-section {
        width: 100%;
        max-width: 100%;

        margin-top: 4px;

        page-break-inside: avoid;
        break-inside: avoid;
    }

    .signatures {
        display: table;

        width: 100%;
        max-width: 100%;

        table-layout: fixed;

        margin-top: 3px;

        page-break-inside: avoid;
        break-inside: avoid;
    }

    .signature-column {
        display: table-cell;

        width: 50%;

        vertical-align: top;
        text-align: center;

        padding: 0 6px;
    }

    .signature-title {
        color: var(--or-fonce);

        font-size: 10pt;
        font-weight: bold;

        text-transform: uppercase;
    }

    .signature-function {
        color: #333;

        font-size: 9.8pt;
        font-weight: bold;

        text-transform: uppercase;

        margin-top: 1px;
    }

    .signature-name {
        color: #333;

        font-size: 9.8pt;
        font-weight: bold;

        margin-top: 1px;
    }

    .signature-space {
        height: 15px;
    }


    /* ============================================================
       DÉCHARGE DU PRESTATAIRE
    ============================================================ */

    .prestataire-decharge {
        width: 100%;
        max-width: 100%;

        margin-top: 4px;

        text-align: justify;

        font-size: 9.7pt;

        page-break-inside: avoid;
        break-inside: avoid;
    }

    .prestataire-decharge p {
        margin: 1.5px 0;
        padding: 0;
    }


    /* ============================================================
       MENTION MANUSCRITE
    ============================================================ */

    .mention-title {
        color: var(--or-fonce);

        font-weight: bold;

        margin-top: 2px;
    }

    .mention {
        min-height: 18px;

        margin-top: 1px;

        padding: 2px 5px;

        border-bottom: 1px dotted #777;

        font-style: italic;
    }


    /* ============================================================
       LU ET APPROUVÉ
    ============================================================ */

    .lu-approuve {
        margin-top: 3px;

        padding: 2px;

        text-align: center;

        color: var(--or-fonce);

        font-weight: bold;
        font-style: italic;

        border-top: 1px solid rgba(184, 134, 11, .35);
        border-bottom: 1px solid rgba(184, 134, 11, .35);

        page-break-inside: avoid;
        break-inside: avoid;
    }


    /* ============================================================
       SIGNATURE FINALE
    ============================================================ */

    .signature-final {
        margin-top: 3px;

        text-align: center;

        page-break-inside: avoid;
        break-inside: avoid;
    }

    .signature-final .label {
        color: var(--or-fonce);

        font-weight: bold;

        text-transform: uppercase;
    }

    .signature-final .signature-space {
        height: 14px;
    }


    /* ============================================================
       PIED DE PAGE
    ============================================================ */

    .footer-page {
        width: 100%;
        max-width: 100%;

        margin-top: 4px;

        padding-top: 2px;

        border-top: 1px solid #ddd;

        text-align: center;

        color: #777;

        font-family: Arial, sans-serif;
        font-size: 7.5pt;
    }


    /* ============================================================
       PROTECTION DES BLOCS
    ============================================================ */

    .avoid-break {
        page-break-inside: avoid;
        break-inside: avoid;
    }


    /* ============================================================
       IMPRESSION
    ============================================================ */

    @media print {

        body {
            background: white;
        }

        .page {
            background: white;
        }

        .footer-page {
            color: #666;
        }
    }
</style>


<div class="contrat-wrapper">

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


        {{-- ======================================================
             EN-TÊTE
        ======================================================= --}}

        <div class="header">

            <h1>
                Contrat de prestation de services et décharge
            </h1>

            <div class="subtitle">
                Entre les soussignés
            </div>

            <div class="line"></div>

        </div>


        {{-- ======================================================
             ÉTABLISSEMENT
        ======================================================= --}}

        <div class="partie">

            <div class="partie-title">
                L'Établissement
            </div>

            <p>
                <strong>Dénomination :</strong>
                {{ $contrat->etablissement }}
            </p>

            <p>
                <strong>Adresse :</strong>
                {{ $contrat->adresse_etablissement }}
            </p>

            <p>
                <strong>Représenté par :</strong>
                M.  {{ $contrat->representant }}
            </p>

            <p>
                <strong>Fonction :</strong>
                {{ $contrat->fonction }}
            </p>

            <p class="small">
                Ci-après dénommé « l'Établissement », d'une part.
            </p>

        </div>


        {{-- ======================================================
             PRESTATAIRE
        ======================================================= --}}

        <div class="partie">

            <div class="partie-title">
                Le Prestataire
            </div>

            <p>
                <strong>Nom et prénom :</strong>
                {{ $contrat->prestataire_nom }}
            </p>

            <p>
                <strong>Adresse :</strong>
                {{ $contrat->prestataire_adresse }}
            </p>

            <p>
                <strong>Téléphone :</strong>
                {{ $contrat->telephone }}
            </p>

            <p>
                <strong>NPI / IFU :</strong>
                {{ $contrat->ifu ?? 'Non fourni' }}
            </p>

            <p class="small">
                Ci-après dénommé « le Prestataire », d'autre part.
            </p>

        </div>


        {{-- ======================================================
             ARTICLE 1
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 1 : Objet
            </div>

            <p>
                Le présent document tient lieu de contrat et de décharge
                relative à la prestation suivante :
            </p>

            <p>
                <strong class="objet-contrat">
                    {{ $contrat->objet_contrat }}
                </strong>
            </p>

            <p>
                au profit de l'Établissement.
            </p>

        </div>


        {{-- ======================================================
             ARTICLE 2
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 2 : Montant du marché
            </div>

            <p>
                Les parties conviennent que le coût total de la prestation
                est fixé à :
            </p>

            <div class="amount-box">

                <span class="amount-label">
                    Montant total :
                </span>

                <span class="amount-value">
                    {{ number_format($contrat->montant_total, 0, ',', ' ') }}
                    F CFA
                </span>

                <br>

                <span class="small">
                    (En lettres :
                    {{ ucfirst($contrat->montant_total_lettre) }})
                </span>

            </div>

        </div>


        {{-- ======================================================
             ARTICLE 3
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 3 : Acompte reçu
            </div>

            <p>
                Le Prestataire reconnaît avoir reçu de l'Établissement
                la somme de :
            </p>

            <div class="amount-box">

                <span class="amount-value">
                    {{ number_format($contrat->acompte, 0, ',', ' ') }}
                    F CFA
                </span>

                <br>

                <span class="small">
                    (En lettres :
                    {{ ucfirst($contrat->acompte_lettre) }})
                </span>

            </div>

            <p>
                à titre d'acompte sur le montant total du marché.
            </p>

            <p>
                Par la présente, le Prestataire donne bonne et valable
                décharge à l'Établissement pour cette somme.
            </p>

        </div>


        {{-- ======================================================
             ARTICLE 4
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 4 : Reliquat à payer
            </div>

            <p>
                Après déduction de l'acompte versé,
                le solde restant dû s'élève à :
            </p>

            <div class="amount-box">

                <span class="amount-value">
                    {{ number_format($contrat->reliquat, 0, ',', ' ') }}
                    F CFA
                </span>

                <br>

                <span class="small">
                    (En lettres :
                    {{ ucfirst($contrat->reliquat_lettre) }})
                </span>

            </div>

            <p>
                Ce montant sera payé au Prestataire après la livraison
                complète et après constat de leur conformité
                par l'Établissement.
            </p>

        </div>


        {{-- ======================================================
             ARTICLE 5
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 5 : Engagement du prestataire
            </div>

            <p>
                Le Prestataire s'engage à :
            </p>

            <ul>

                <li>
                    faire les réalisations conformément aux
                    caractéristiques convenues ;
                </li>

                <li>
                    respecter les normes de solidité et de qualité
                    requises pour un usage scolaire ;
                </li>

                <li>
                    livrer l'intégralité de la commande au plus tard le
                    {{ $contrat->date_limite_livraison->format('d/m/Y') }} ;
                </li>

                <li>
                    remplacer ou réparer à ses frais tout ouvrage reconnu
                    défectueux à la réception.
                </li>

            </ul>

        </div>


        {{-- ======================================================
             ARTICLE 6
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 6 : Réception et paiement du solde
            </div>

            <p>
                La réception sera effectuée par l'Établissement
                après vérification :
            </p>

            <ul>

                <li>
                    de la quantité commandée ;
                </li>

                <li>
                    de la qualité de fabrication ;
                </li>

                <li>
                    de la conformité aux spécifications convenues.
                </li>

            </ul>

            <p>
                Le paiement du reliquat ne pourra intervenir
                qu'après cette réception.
            </p>

            <p>
                En cas de défauts majeurs ou de livraison incomplète,
                l'Établissement se réserve le droit de différer
                le paiement du solde jusqu'à la correction
                des insuffisances constatées.
            </p>

        </div>


        {{-- ======================================================
             ARTICLE 7
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 7 : Défaut de livraison
            </div>

            <p>
                En cas de non-livraison ou d'abandon du marché
                par le Prestataire, celui-ci s'engage à rembourser
                à l'Établissement l'acompte perçu, sans préjudice
                des autres recours que l'Établissement pourrait exercer.
            </p>

        </div>


        {{-- ======================================================
             ARTICLE 8
        ======================================================= --}}

        <div class="article">

            <div class="article-title">
                Article 8 : Règlement des litiges
            </div>

            <p>
                Les parties s'efforceront de régler à l'amiable
                tout différend relatif à l'exécution du présent engagement.
            </p>

            <p>
                À défaut d'accord amiable, les juridictions compétentes
                seront saisies.
            </p>

        </div>


        {{-- ======================================================
             LIEU ET DATE
        ======================================================= --}}

        <div class="signature-intro">

            Fait à
            <strong>
                {{ $contrat->lieu_signature }}
            </strong>

        </div>

        <div class="signature-date">

            Le
            <strong>
                {{ $contrat->date_signature->format('d/m/Y') }}
            </strong>

        </div>


        {{-- ======================================================
             SIGNATURES
        ======================================================= --}}

        <div class="signature-section">

            <div class="signatures">

                {{-- ÉTABLISSEMENT --}}

                <div class="signature-column">

                    <div class="signature-title">
                        Pour l'Établissement
                    </div>

                    <div class="signature-function">
                        Le Directeur
                    </div>

                    <div class="signature-space"></div>

                    <div class="signature-name">
                        YESSOUFOU Affissou A.
                    </div>

                </div>


                {{-- PRESTATAIRE --}}

                <div class="signature-column"> </div>

            </div>


            {{-- ==================================================
                 DÉCHARGE
            =================================================== --}}

            <div class="prestataire-decharge">

                <div class="signature-title">
                    Le Prestataire
                </div>

                <p>
                    Je soussigné
                    <strong>
                        M. {{ $contrat->prestataire_nom }}
                    </strong>,
                    reconnais avoir reçu la somme de
                    <strong>
                        {{ number_format($contrat->acompte, 0, ',', ' ') }}
                        F CFA
                    </strong>
                    à titre d'acompte pour
                    <strong class="objet-contrat">
                        {{ $contrat->objet_contrat }}
                    </strong>
                    et m'engage à exécuter la commande conformément
                    aux dispositions du présent document.
                </p>


                <div class="mention-title">
                    Mention manuscrite :
                </div>

                <div class="signature-space"></div>

                <div class="lu-approuve">
                    « Lu et approuvé – Bon pour réception de l'acompte
                    et engagement de prestation. »
                </div>


                <div class="signature-final">

                    <div class="label">
                        Signature
                    </div>

                    <div class="signature-space"></div>

                    <div>
                        <strong>
                            Nom et Prénom :
                        </strong>

                        {{ $contrat->prestataire_nom }}
                    </div>

                </div>

            </div>

        </div>


        {{-- ======================================================
             PIED DE PAGE
        ======================================================= --}}

        <div class="footer-page">
            Contrat de prestation de services — Document contractuel
        </div>

    </div>

</div>