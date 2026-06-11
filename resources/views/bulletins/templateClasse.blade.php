<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin - {{ $classe->nom ?? 'Classe' }}</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 0; padding: 0; }

        .header, .footer { text-align: center; width: 100%; }
        .school-name { font-size: 14px; font-weight: bold; }

        .bande-lumiere {
            padding: 3px; text-align: center;
            font-size: 16px; font-weight: bold; color: white;
            letter-spacing: 1px; text-transform: uppercase;
        }
        .bande-lumi, .bande-lumineuse {
            padding: 3px; text-align: center;
            font-size: 16px; font-family: cursive; color: white;
            letter-spacing: 1px; text-transform: uppercase;
        }
        .bande-lumiere { background: blue; }
        .bande-lumi { background: skyblue; }
        .bande-lumineuse { background: midnightblue; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th, td { border: 1px solid #6d9b84ff; padding: 4px 3px; text-align: center; font-size: 10px; height: 24px; line-height: 18px; }
        .table th { background-color: #dcf0f1ff; }
        .signatures td { text-align: center; padding: 2px; }

        .entete, .entete td, .entete th { border: none !important; }
        .footer { position: fixed; bottom: 5mm; width: 100%; font-size: 8px; }

        /* Styles pour la mention */
        .mention-felicitation { color: #28a745; font-weight: bold; }
        .mention-tableau { color: #007bff; font-weight: bold; }
        .mention-encouragement { color: #ffc107; font-weight: bold; }
        .mention-avertissement { color: #17a2b8; font-weight: bold; }
        .mention-blame { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

@foreach($bulletins as $bulletin)

@php
    $inscription            = $bulletin['inscription'];
    $classe                 = $bulletin['classe'];
    $annee                  = $bulletin['annee'];
    $trimestre              = $bulletin['trimestre'];

    $notes                  = $bulletin['notes'] ?? [];

    $noteConduite           = $bulletin['noteConduite'] ?? 0;
    $appreciationConduite   = $bulletin['appreciationConduite'];

    $moyenne_trimestre      = $bulletin['moyenne_trimestre'];
    $moyenne_scientifique   = $bulletin['moyenne_scientifique'];
    $moyenne_litteraire     = $bulletin['moyenne_litteraire'];

    $moyenne_annuelle       = $bulletin['moyenne_annuelle'];

    $rang_trimestre         = $bulletin['rang_trimestre'];
    $rang_annuel            = $bulletin['rang_annuel'];

    $mention                = $bulletin['mention'];
    $appreciation           = $bulletin['appreciation'];

    $total_eleves           = $bulletin['total_eleves'];

    $plusFaibleMoyenne      = $bulletin['plusFaibleMoyenne'];
    $plusForteMoyenne       = $bulletin['plusForteMoyenne'];

    $moyenneT1              = $bulletin['moyenneT1'];
    $moyenneT2              = $bulletin['moyenneT2'];
    $moyenneT3              = $bulletin['moyenneT3'];

    $decision               = $bulletin['decision'];

    // Fonction helper pour afficher un tiret
    $afficherTiret = function($valeur) {
        return $valeur !== null
            && $valeur !== ''
            && $valeur != 0
                ? number_format($valeur, 2)
                : '-';
    };

    // Couleur de la mention
    $mentionClasse = match($mention ?? '') {
        'FÉLICITATION'       => 'mention-felicitation',
        'TABLEAU D\'HONNEUR' => 'mention-tableau',
        'ENCOURAGEMENT'      => 'mention-encouragement',
        'AVERTISSEMENT'      => 'mention-avertissement',
        'BLAME'              => 'mention-blame',
        default              => 'text-muted'
    };
@endphp


<!-- HEADER -->
<div class="header">
    <table class="entete" width="100%">
        <tr>
            <td width="20%">
                <img src="{{ public_path('images/logo_benin.png') }}" width="160" height="60" alt="Logo Bénin">
            </td>
            <td width="40%" class="school-name" style="text-align:center;">
                <h3>COLLÈGE LE GLORIEUX <br> Année scolaire : {{ $annee->nom ?? '' }}</h3>
            </td>
            <td width="40%">
                <table class="entete">
                    <tr>
                        <td width="30%">
                            <img src="{{ public_path('images/logo_lg.png') }}" width="60" height="60" alt="Logo">
                        </td>
                        <td style="line-height: 1;">
                            <h5>Cotonou AKPAKPA-AYELAWADJE,<br>
                            1ène rue après ZOM SERVICE <br>
                            en venant de SACRÉ CŒUR <br>
                            Tel: (+229) 0197189324 / 0197521637</h5>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <hr>
</div>


<div class="bande-lumiere">BULLETIN DE NOTES</div>


<!-- INFOS ÉLÈVE -->
<table class="table">
    <tr>
        <th>Nom</th><td>{{ $inscription->eleve->nom ?? '' }}</td>
        <th>Matricule</th><td>{{ $inscription->eleve->matricule ?? '' }}</td>
        <th>Classe</th><td>{{ $inscription->classe->nom ?? '' }}</td>
        <td rowspan="3" style="text-align:center; vertical-align:middle;">
            @if($inscription->eleve->photo)
                <img 
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/' . $inscription->eleve->photo))) }}"
                    width="100"
                    height="100"
                    style="border-radius: 50%; border: 2px solid #1dd4e9;"
                >
            @endif
        </td>
    </tr>
    <tr>
        <th>Prénom</th><td>{{ $inscription->eleve->prenom ?? '' }}</td>
        <th>Sexe</th><td>{{ $inscription->eleve->sexe ?? '' }}</td>
        <th>Effectif</th><td>{{ $total_eleves ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Statut</th><td>{{ $inscription->eleve->statut ?? '' }}</td>
        <th>Trimestre</th><td>{{ $trimestre->nom ?? '' }}</td>
        <th>N° EducMaster</th><td>{{ $inscription->eleve->numeducmaster ?? '' }}</td>
    </tr>
</table>


<!-- NOTES -->
<div class="bande-lumineuse">Performance Académique par Matière</div>
<table class="table">
    <thead>
        <tr>
            <th>Matière</th>
            <th>Coef</th>
            <th>Moy. d'Interro</th>
            <th>Devoir 1</th>
            <th>Devoir 2</th>
            <th>Moy. Matière</th>
            <th>Moy. Coef</th>
            <th>Appréciation</th>
        </tr>
    </thead>
    <tbody>
        @foreach($classe->matieres as $matiere)
            @php
                $note = $notes[$matiere->id] ?? [];
                $coef = $matiere->coefficient ?? 1;
                $moyenne_matiere = $note['moyenne_matiere'] ?? null;
                $moyenne_coeff = $moyenne_matiere !== null ? $coef * $moyenne_matiere : null;
            @endphp
            <tr>
                <th>{{ $matiere->nom }}</th>
                <td>{{ $matiere->coefficient }}</td>
                <td>{{ $afficherTiret($note['moyenne_interro'] ?? null) }}</td>
                <td>{{ $afficherTiret($note['devoir1'] ?? null) }}</td>
                <td>{{ $afficherTiret($note['devoir2'] ?? null) }}</td>
                <td>{{ $afficherTiret($moyenne_matiere) }}</td>
                <td>{{ $afficherTiret($moyenne_coeff) }}</td>
                <td>{{ $note['appreciation'] ?? '-' }}</td>
            </tr>
        @endforeach

        {{-- Conduite --}}
        <tr>
            <th>Conduite</th>
            <td>1</td>
            <td colspan="3">-</td>
            <td>{{ $afficherTiret($noteConduite) }}</td>
            <td>{{ $afficherTiret($noteConduite) }}</td>
            <td>{{ $appreciationConduite ?? '-' }}</td>
        </tr>
    </tbody>
</table>


<!-- BILAN TRIMESTRIEL -->
<div class="bande-lumineuse">Bilan Trimestriel</div>
<table class="table">
    <tr>
        <th>Moyenne Trimestrielle</th>
        <td>{{ number_format($moyenne_trimestre ?? 0, 2) }}</td>
        <th>Moyenne Scientifique</th>
        <td>{{ number_format($moyenne_scientifique ?? 0, 2) }}</td>
        <th>Moyenne Littéraire</th>
        <td>{{ number_format($moyenne_litteraire ?? 0, 2) }}</td>
    </tr>
    <tr>
        <th>Rang Trimestre</th>
        <td>{{ $rang_trimestre ?? '' }}</td>
        <th>Plus faible Moyenne</th>
        <td>{{ number_format($plusFaibleMoyenne ?? 0, 2) }}</td>
        <th>Plus forte Moyenne</th>
        <td>{{ number_format($plusForteMoyenne ?? 0, 2) }}</td>
    </tr>
</table>


@if(isset($trimestre) && $trimestre->id == 3)
    <div class="bande-lumi">Bilan Annuel</div>
    <table class="table">
        <tr>
            <th>1er Trimestre</th>
            <td>{{ $afficherTiret($moyenneT1) }}</td>
            <th>2ème Trimestre</th>
            <td>{{ $afficherTiret($moyenneT2) }}</td>
            <th>3ème Trimestre</th>
            <td>{{ $afficherTiret($moyenneT3) }}</td>
        </tr>
        <tr>
            <th>Moyenne Annuelle</th>
            <td>{{ number_format($moyenne_annuelle ?? 0, 2) }}</td>
            <th>Rang Annuel</th>
            <td>{{ $rang_annuel ?? '-' }}</td>
            <th>Décision du Conseil</th>
            <td>{{ $decision ?? '' }}</td>
        </tr>
    </table>
@endif

<!-- SIGNATURES / MENTIONS -->
<table class="table signatures">
    <tr>
        <td style="width: 33%;">
            <strong>MENTION</strong><br><br>
            
            {{-- FÉLICITATION --}}
            @if(($mention ?? '') === 'FÉLICITATION')
                <span class="mention-felicitation">✓ {{ $mention }}</span><br>
            @else
                <span style="color: #d3d3d3; font-weight: normal;">☐ FÉLICITATION</span><br>
            @endif
            
            {{-- TABLEAU D'HONNEUR --}}
            @if(($mention ?? '') === 'TABLEAU D\'HONNEUR')
                <span class="mention-tableau">✓ {{ $mention }}</span><br>
            @else
                <span style="color: #d3d3d3; font-weight: normal;">☐ TABLEAU D'HONNEUR</span><br>
            @endif
            
            {{-- ENCOURAGEMENT --}}
            @if(($mention ?? '') === 'ENCOURAGEMENT')
                <span class="mention-encouragement">✓ {{ $mention }}</span><br>
            @else
                <span style="color: #d3d3d3; font-weight: normal;">☐ ENCOURAGEMENT</span><br>
            @endif
            
            {{-- AVERTISSEMENT --}}
            @if(($mention ?? '') === 'AVERTISSEMENT')
                <span class="mention-avertissement">✓ {{ $mention }}</span><br>
            @else
                <span style="color: #d3d3d3; font-weight: normal;">☐ AVERTISSEMENT</span><br>
            @endif
            
            {{-- BLAME --}}
            @if(($mention ?? '') === 'BLAME')
                <span class="mention-blame">✓ {{ $mention }}</span>
            @else
                <span style="color: #d3d3d3; font-weight: normal;">☐ BLAME</span>
            @endif
        </td>

        <td style="width: 34%;">
            <strong>Appréciation générale</strong><br><br>
            <strong>{{ $appreciation ?? '-' }}</strong>
        </td>
        <td style="width: 33%;">
            <img src="{{ public_path('images/cache_lg_dir.jpeg') }}" width="100" height="100" alt="Cachet">
            <h4><strong>YESSOUFOU A. Affisou</strong></h4>
        </td>
    </tr>
</table>


<div class="footer">
    Réalisé par Kolatresor.TIC, TEL: +229 0197521637 / 0141906354
</div>

@endforeach
</body>
</html>