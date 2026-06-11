<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin - {{ $eleve->nom }}{{ $eleve->prenom }} </title>
    <style>
        @page { margin: 30px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            position: relative;
        }
         .bulletin {
        page-break-after: always;
    }
    /* Évite un saut après le dernier */
    .bulletin:last-child {
        page-break-after: auto;
    }

        /* FILIGRANE */
        body::before {
            content: "";
            position: fixed;
            top: 30%;
            left: 20%;
            width: 400px;
            height: 400px;
            background: url('{{ public_path('images/logo.png') }}') no-repeat center;
            background-size: contain;
            opacity: 0.08;
            z-index: -1;
        }

        .header, .footer { text-align: center; }
        .school-name { font-size: 18px; font-weight: bold; }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .table th {
            background-color: #dcf0f1ff;
        }
        .signatures {
            margin-top: 40px;
        }
        .signatures td {
            text-align: center;
        }
        hr { margin: 8px 0; }
        .summary {
            background-color: #f9f9f9;
            padding: 5px;
            border: 1px solid #ccc;
        }
        
        .bande-lumineuse {
            background: midnightblue;
            padding: 10px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: white;
            letter-spacing: 2px;
            text-transform: uppercase;
            animation: neonGlow 1.5s infinite alternate;
        }

        @keyframes neonGlow {
            0% {
                text-shadow: 
                    0 0 5px #fff,
                    0 0 10px #ff00de,
                    0 0 20px #ff00de,
                    0 0 40px #ff00de;
            }
            100% {
                text-shadow: 
                    0 0 10px #fff,
                    0 0 20px #ff00de,
                    0 0 30px #ff00de,
                    0 0 50px #f51ed8ff;
            }
        }
    </style>
</head>
<body>



<!-- HEADER -->
<div class="header">
  <table width="100%" style="border-spacing: 0; border-collapse: collapse;">
    <tr>
        <td width="20%" style="padding: 2px;">
            <img src="{{ asset('images/logo_benin.png') }}" width="250" height="80" alt="Logo Benin">
        </td>
        <td width="40%" class="school-name" style="text-align: center; vertical-align: middle; padding: 2px;">
            COLLEGE LE GLORIEUX <br>
            <small>Année scolaire : {{ $annee_scolaire }}</small>
        </td>
        <td width="40%" style="padding: 2px;">
            <table style="border-spacing: 0; border-collapse: collapse;">
                <tr>
                    <td style="padding: 2px;">
                        <img src="{{ asset('images/logo.jpg') }}" width="100" height="80" alt="Logo">
                    </td>
                    <td style="vertical-align: middle; padding-left: 5px; font-size: 14px; font-weight: bold;">
                        Cotonou Akpakpa-AYELAWADJE,<br>
                        1ère rue après ZOOM SERVICE en venant de SACRÉ-<br>
                        Tel: (+229) 0197189327 / 0197521637 / 0148878041 / 0141906354
                    </td>
                </tr> 
            </table>
        </td>
    </tr>
</table>

    <hr>
</div>

<div class="bande-lumineuse">BULLETIN DE NOTES</div>

<!-- INFOS ÉLÈVE -->
<table class="table">
    <tr>
        <th>Nom</th><td>{{ $eleve->nom }}</td>
        <th>Prénom</th><td>{{ $eleve->prenom }}</td>
    </tr>
    <tr>
        <th>Classe</th><td>{{ $classe->nom }}</td>
        <th>Matricule</th><td>{{ $eleve->matricule }}</td>
    </tr>
    <tr>
        <th>Statut</th><td>{{ $eleve->statut }}</td>
        <th>Effectif</th><td>{{ $total_eleves }}</td>
    </tr>
    <tr>
        <th>Trimestre</th><td>{{ $trimestre->nom }}</td>
        <th>Date</th><td>{{ now()->format('d/m/Y') }}</td>
    </tr>
</table>

<!-- TABLEAU DES NOTES -->
<table class="table">
    <thead>
        <tr>
            <th>Matière</th>
            <th>Coef</th>
            <th>Moyenne d'Interrogation</th>
            <th>Devoir 1</th>
            <th>Devoir 2</th>
            <th>Moyenne Matière</th>
            <th>Moyenne Coefficientée</th>
            <th>Appréciation</th>
        </tr>
    </thead>
    <tbody>
    @foreach($matieres as $matiere)
        @php
            $note = $notes->get($matiere->id);
            $coef = $matiere->coefficient ?? 1;
            $moyenne_coeff = $note ? $coef * $note->moyenne_matiere : 0;
            $appreciation_matiere = $note ? appreciation($note->moyenne_matiere) : '';
        @endphp
        <tr>
            <td>{{ $matiere->nom }}</td>
            <td style="text-align:center">{{ $coef }}</td>
            <td style="text-align:center">{{ $note ? number_format($note->moyenne_interro, 2) : '' }}</td>
            <td style="text-align:center">{{ $note ? number_format($note->devoir1, 2) : '' }}</td>
            <td style="text-align:center">{{ $note ? number_format($note->devoir2, 2) : '' }}</td>
            <td style="text-align:center">{{ $note ? number_format($note->moyenne_matiere, 2) : '' }}</td>
            <td style="text-align:center">{{ $note ? number_format($moyenne_coeff, 2) : '' }}</td>
            <td>{{ $appreciation_matiere }}</td>
        </tr>
    @endforeach

    <!-- Conduite -->
    <tr>
        <td>Conduite</td>
        <td style="text-align:center">1</td>
        <td></td>
         <td></td>
          <td></td>
        <td style="text-align:center">{{ $noteConduite !== null ? number_format($noteConduite, 2) : '' }}</td>
         <td style="text-align:center">{{ $noteConduite !== null ? number_format($noteConduite, 2) : '' }}</td>
        <td colspan="2">{{ $noteConduite !== null ? appreciationConduite($noteConduite) : '' }}</td>
    </tr>
    </tbody>
</table>

<!-- BILAN -->
<div class="bande-lumineuse">Bilan </div>
<table class="table">
    <tr>
    <th>Moyenne Trimestrielle</th>
    <td>{{ number_format($moyenne_trimestre, 2) ?? 'N/A' }}</td>
   <th>Moyenne Scientifique</th><td>{{ $moyenne_scientifique ?? 'N/A' }}</td>
</tr>

    <tr>
        <th>Autre Bilan</th><td></td>
        <th>Moyenne Litteraire</th><td>{{ $moyenne_litteraire ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Autre Bilan</th><td></td>
        <th>Autre Bilan</th><td></td>
    </tr>
    <tr>
        <th>Autre Bilan</th><td></td>
        <th>Autre Bilan</th><td></td>
    </tr>
    <tr>
        <th> Autre Bilan</th><td> </td>
        <th>Autre Bilan </th><td></td>
    </tr>
</table>

<!-- SIGNATURES -->
<table width="100%" class="signatures">
    <tr>
        <td><h2>Professeur Principal</h2><br><br>____________________</td>
        <td><h2>Directeur</h2><br><br>____________________</td>
    </tr>
</table>

</body>
</html>
