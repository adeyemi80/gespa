<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans; font-size: 11px }
        table { width: 100%; border-collapse: collapse }
        th, td { border: 1px solid #000; padding: 4px }
        th { background: #f0f0f0 }
        .title { text-align: center; margin-bottom: 10px }
        .total { font-weight: bold; margin-top: 10px }
    </style>
</head>
<body>

<div class="title">
    <h3>💰 Liste des paiements</h3>
    <p>
        Généré le {{ now()->format('d/m/Y H:i') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Élève</th>
            <th>Classe</th>
            <th>Année Scolaire</th>
            <th>Frais</th>
            <th>Montant Payé</th>
            <th>Mode</th>
            <th>Date</th>
            <th>Reçu</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paiements as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->inscription->eleve->nom ?? '' }} {{ $p->inscription->eleve->prenom ?? '' }}</td>
                <td>{{ $p->inscription->classe->nom ?? '' }}</td>
                <td>{{ $p->inscription->annee->nom ?? '' }}</td>
                <td>{{ $p->frais->description ?? '' }}</td>
                <td>{{ number_format($p->montant_verse, 0, ' ', ' ') }} FCFA</td>
                <td>{{ $p->mode_paiement }}</td>
                <td>{{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</td>
                <td>{{ $p->numero_recu }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p class="total">
    Total encaissé : {{ number_format($total, 0, ' ', ' ') }} FCFA
</p>

</body>
</html>