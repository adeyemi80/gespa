<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ticket de paiement</title>

<style>

/* RESET GLOBAL */
* {
    box-sizing: border-box;
}

/* FORMAT TICKET */
@page {
    size: 80mm auto;
    margin: 5mm;
}

/* BODY */
body {
    font-family: monospace;
    font-size: 12px;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
    color: #000;
    overflow-wrap: break-word;
    word-break: break-word;
}

/* CONTENEUR */
.ticket {
    border: 1px dashed #000;
    padding: 10px;
}

/* HEADER */
.header {
    text-align: center;
}

.logo img {
    width: 250px;
    height: auto;
}

/* TITRE */
.title {
    font-weight: bold;
    font-size: 14px;
    margin-top: 5px;
}

/* SEPARATEUR */
hr {
    border: none;
    border-top: 1px dashed #000;
    margin: 8px 0;
}

/* INFOS */
.info p {
    margin: 2px 0;
    word-break: break-word;
    white-space: normal;
}

/* TABLE */
table {
    width: 100%;
    table-layout: fixed;
    font-size: 12px;
}

td {
    padding: 2px 0;
    word-break: break-word;
}

.right {
    text-align: right;
}

/* TOTAL */
.total {
    font-weight: bold;
    border-top: 1px dashed #000;
    margin-top: 5px;
    padding-top: 5px;
}

/* STATUT */
.statut {
    text-align: center;
    font-weight: bold;
    margin-top: 5px;
}

/* FOOTER */
.footer {
    text-align: center;
    font-size: 11px;
    margin-top: 10px;
}

/* QR */
.footer img {
    margin-top: 5px;
}

/* PRINT */
@media print {
    .no-print {
        display: none;
    }
}

</style>
</head>

<body>

<div class="ticket">

    <!-- HEADER -->
    <div class="header">
        <div class="logo">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}" alt="Logo">
        </div>

        <div class="title">REÇU DE PAIEMENT</div>
        <small>N° {{ $paiement->numero_recu ?? $numeroLot }}</small>
    </div>

    <hr>

    <!-- INFOS ELEVE -->
    <div class="info">
        <p><strong>Élève :</strong><br>
            {{ $inscription->eleve->nom }} {{ $inscription->eleve->prenom }}
        </p>

        <p><strong>Classe :</strong> {{ $inscription->classe->nom }}</p>
        <p><strong>Année :</strong> {{ $inscription->annee->nom }}</p>
        <p><strong>Date :</strong> {{ optional($paiement->date_paiement)->format('d/m/Y') }}</p>
    </div>

    <hr>

    <!-- DETAILS -->
    <table>
        @foreach($details as $d)
        <tr>
            <td>{{ ucfirst($d['nom']) }}</td>
            <td class="right">{{ number_format($d['montant'], 0, ',', ' ') }}</td>
        </tr>
        @endforeach
    </table>

    <!-- TOTAL -->
    <div class="total">
        <p>Total versé : {{ number_format($total_ce_recu, 0, ',', ' ') }} FCFA</p>
        <p>Total payé : {{ number_format($total_paye, 0, ',', ' ') }} FCFA</p>
        <p>Reste : {{ number_format($reste, 0, ',', ' ') }} FCFA</p>
    </div>

    <hr>

    <!-- STATUT -->
    <div class="statut">
        {{ $reste > 0 ? '⏳ PARTIELLEMENET PAYE' : '✅ SOLDÉ' }}
    </div>

    <hr>

    <!-- FOOTER -->
    <div class="footer">
        <p>Mode : {{ $paiement->mode_paiement }}</p>
        <p>Merci 🙏</p>
        <small>{{ now()->format('d/m/Y H:i') }}</small>

        <!-- QR CODE -->
        <div style="margin-top:10px; text-align:center;">
            <img 
                src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $numeroLot }}" 
                alt="QR Code"
            >
            <p style="font-size:10px;">Scan pour vérifier</p>
        </div>
    </div>

</div>

<!-- BOUTONS (ne s’imprime pas) -->
<div class="no-print" style="text-align:center; margin-top:15px;">
    <button onclick="window.print()">🖨️ Imprimer</button>
</div>

<script>
// IMPRESSION AUTOMATIQUE FIABLE
window.addEventListener('load', function () {
    setTimeout(() => {
        window.print();
    }, 300);
});

// Optionnel : fermer après impression
window.addEventListener('afterprint', function () {
    // window.close();
});
</script>

</body>
</html>