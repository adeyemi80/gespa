<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin - {{ $classe->nom }}</title>
    <style>
        /* Taille du papier et marges pour DomPDF */
        @page { 
            size: A4 portrait; 
            margin: 10mm; 
        }

        /* Corps */
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 0; padding: 0; height: 100%; }

        /* Header & Footer */
        .header, .footer { text-align: center; width: 100%; }
        .school-name { font-size: 14px; font-weight: bold; }

        /* Bande lumineuse */
        .bande-lumiere {
            background: blue;
            padding: 3px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: white;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Bande lumineuse alternative */
        .bande-lumineuse {
            background: midnightblue;
            padding: 3px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: white;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th, td { border: 1px solid #000; padding: 4px 3px; text-align: center; font-size: 10px; height: 24px; line-height: 20px; }
        .table th { background-color: #dcf0f1ff; }
        .signatures td { text-align: center; padding: 3px; }

        /* Supprimer les bordures de l'entête */
        .entete, .entete td, .entete th { border: none !important; }

        /* Footer fixé en bas */
        .footer {
            position: fixed;
            bottom: 5mm;
            width: 100%;
            text-align: center;
            font-size: 8px;
        }

        
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <table class="entete" width="100%">
        <tr>
            <td width="20%">
                <img src="file://{{ public_path('images/logo_benin.png') }}" width="160" height="60" alt="Logo Benin">
            </td>
            <td width="40%" class="school-name" style="text-align:center; vertical-align:middle;">
               <h3> COLLEGE LE GLORIEUX <br>
                Année scolaire : {{ $annee_scolaire }}</h3>
            </td>
            <td width="40%">
                <table class="entete" width="100%">
                    <tr>
                        <td width="30%">
                            <img src="file://{{ public_path('images/logo.jpg') }}" width="80" height="60" alt="Logo">
                        </td>
                        <td style="line-height: 1;">
                            <h5>Cotonou AKPAKPA-AYELAWADJE,<br>
                            1ère rue après ZOMM SERVICE <br>
                            en venant de SACRE COEUR
                            Tel: (+229) 0197189327 / 0197521637</h5>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <hr>
</div>

<div class="bande-lumiere">BULLETIN DE NOTES</div>

<!-- Infos élève -->
<table class="table">
    <tr>
        <th>Nom</th><td>{{ $eleve->nom }}</td>
        <th>Prénom</th><td>{{ $eleve->prenom }}</td>
          <th></th><td></td>
    </tr>
    <tr>
        <th>Classe</th><td>{{ $classe->nom }}</td>
        <th>Matricule</th><td>{{ $eleve->matricule }}</td>
         <th>Sexe</th><td>{{ $eleve->sexe }}</td>
    </tr>
    <tr>
        <th>Statut</th><td>{{ $eleve->statut ?? '' }}</td>
        <th>Effectif</th><td>{{ $total_eleves }}</td>
          <th></th><td></td>
    </tr>
    <tr>
        <th>Trimestre</th><td>{{ $trimestre->nom }}</td>
        <th>Date</th><td>{{ now()->format('d/m/Y') }}</td>
          <th></th><td></td>
    </tr>
</table>

<!-- Notes -->
<div class="bande-lumineuse">Performance Académique par Matière</div>
<table class="table">
    <thead>
        <tr>
            <th>Matière</th>
            <th>Coef</th>
            <th>Moyenne Interro</th>
            <th>Devoir 1</th>
            <th>Devoir 2</th>
            <th>Moyenne Matière</th>
            <th>Moyenne Coef</th>
            <th>Appréciation</th>
        </tr>
    </thead>
    <tbody>
        @foreach($classe->matieres as $matiere)
            @php
                $note = $notes->get($matiere->id);
                $coef = $matiere->coefficient ?? 1;
                $moyenne_coeff = $note ? $coef * $note->moyenne_matiere : 0;
                $appreciation_matiere = $note ? appreciation($note->moyenne_matiere) : '';
            @endphp
            <tr>
                <td><strong>{{ $matiere->nom ?? 'Nom inconnu' }}</strong></td>
                <td>{{ $coef }}</td>
                <td>{{ $note ? number_format($note->moyenne_interro,2) : '' }}</td>
                <td>{{ $note ? number_format($note->devoir1,2) : '' }}</td>
                <td>{{ $note ? number_format($note->devoir2,2) : '' }}</td>
                <td>{{ $note ? number_format($note->moyenne_matiere,2) : '' }}</td>
                <td>{{ $note ? number_format($moyenne_coeff,2) : '' }}</td>
                <td>{{ $appreciation_matiere }}</td>
            </tr>
        @endforeach
        <tr>
             <td><strong>Conduite</strong></td>
            <td>1</td>
            <td colspan="3"></td>
            <td>{{ $noteConduite !== null ? number_format($noteConduite,2) : '' }}</td>
            <td>{{ $noteConduite !== null ? number_format($noteConduite,2) : '' }}</td>
            <td>{{ $noteConduite !== null ? appreciationConduite($noteConduite) : '' }}</td>
        </tr>
    </tbody>
</table>

<!-- Bilan -->
<div class="bande-lumineuse">Bilan</div>
<table class="table">
    <tr>
        <th>Moyenne Trimestrielle</th><td>{{ number_format($moyenne_trimestre,2) ?? 'N/A' }}</td>
        <th>Moyenne Scientifique</th><td>{{ $moyenne_scientifique ?? 'N/A' }}</td>
        <th>Plus faible Moyenne</th><td>{{ $plusFaibleMoyenne ?? 'N/A'  }}</td>
    </tr>
    <tr>
        <th>Moyenne Litteraire</th><td>{{ $moyenne_litteraire ?? 'N/A' }}</td>
        <th></th><td></td>
         <th>Plus forte Moyenne</th><td>{{ $plusForteMoyenne ?? 'N/A'  }}</td>
    </tr>
    <tr>
        <th>Autre Bilan</th><td>Autre Bilan</td>
        <th>Autre Bilan</th><td>Valeur / Mention</td>
        <th>Autre Bilan</th><td>Valeur / Mention</td>
    </tr>
</table>

<!-- Signatures -->

<table class="table signatures">
  <tr>
   @php
    $bulletin = $bulletins[0] ?? null; // prend le premier bulletin si existant
@endphp

@if($bulletin)
<td class="text-start">
    <div>
    @foreach(['FELICITATION', 'TABLEAU D’HONNEUR', 'ENCOURAGEMENT', 'AVERTISSEMENT', 'BLAME'] as $mention)
        <span style="font-weight: bold; color: {{ $bulletin['mention'] === $mention ? '#28a745' : '#6c757d' }};">
            {{ $bulletin['mention'] === $mention ? '✓' : '☐' }} {{ $mention }}
        </span><br>
    @endforeach
</div>

</div>

</td>
@else
<td colspan="5">Aucun bulletin trouvé pour cet élève.</td>
@endif

        <td><strong>Appréciation générale</strong><br><br><strong>{{ $appreciation_generale }}</strong></td>
        <td>
            <img src="file://{{ public_path('images/cache.png') }}" width="180" height="100" alt="Cachet">
        </td>
    </tr>
</table>

<!-- FOOTER -->
<div class="footer">
    Réalisé par Kolatresor, TEL: +229 0197521637/0141906354
</div>

</body>
</html>

