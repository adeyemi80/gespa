{{-- resources/views/paiements/recu.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reçu Paiement</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; background: #f8f9fa; }
        .recu { width: 320px; margin: 30px auto; border: 1px solid #000; padding: 15px; background: #fff; }
        h2 { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 8px; }
        .info strong { display: inline-block; width: 110px; }
        .reste { color: red; font-weight: bold; }
        .statut { font-weight: bold; color: green; }
        .statut.solde { color: blue; }
    </style>
</head>
<body>
<div class="recu">
    <h2>Reçu de paiement</h2>

    {{-- Infos élève --}}
    <div class="info"><strong>Élève:</strong> {{ $paiement->inscription->eleve->nom }} {{ $paiement->inscription->eleve->prenom }}</div>
    <div class="info"><strong>Classe:</strong> {{ $paiement->inscription->classe->nom }}</div>
    <div class="info"><strong>Année:</strong> {{ $paiement->inscription->annee->nom }}</div>

    {{-- Frais --}}
    <div class="info"><strong>Frais:</strong> {{ $paiement->frais->description }}</div>
    <div class="info"><strong>Montant versé:</strong> {{ number_format($paiement->montant_verse,0,' ',' ') }} FCFA</div>

    {{-- Ligne inscription_frais --}}
    <div class="info"><strong>Total payé:</strong> {{ number_format($ligne->montant_paye ?? 0,0,' ',' ') }} FCFA</div>
    <div class="info"><strong>Reste à payer:</strong> 
        <span class="reste">{{ number_format($ligne->reste ?? 0,0,' ',' ') }} FCFA</span>
    </div>

    <div class="info"><strong>Statut:</strong> 
        <span class="statut {{ ($ligne->reste ?? 0) == 0 ? 'solde' : '' }}">
            {{ ($ligne->reste ?? 0) == 0 ? 'Soldé' : 'Partiellement payé' }}
        </span>
    </div>

    {{-- Infos paiement --}}
    <div class="info"><strong>Date:</strong> {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</div>
    <div class="info"><strong>Reçu n°:</strong> {{ $paiement->numero_recu }}</div>
</div>

<script>
    // Ouvre automatiquement la fenêtre d'impression
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>