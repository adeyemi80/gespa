<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>État des paiements de scolarité</title>
    <style>
        @page { margin: 28px 24px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; }
        h1 { font-size: 15px; margin: 0 0 2px 0; color: #084298; }
        .sub { color: #666; margin-bottom: 8px; }
        .criteres { border: 1px solid #ccd; background: #f4f6fb; padding: 6px 8px; margin-bottom: 8px; }
        .criteres span { display: inline-block; margin-right: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #bbb; padding: 3px 4px; }
        th { background: #e9eef8; font-size: 8px; }
        .r { text-align: right; }
        .c { text-align: center; }
        .ok { color: #146c43; }
        .ko { color: #b02a37; }
        tfoot td { background: #e9eef8; font-weight: bold; }
        .stats { width: 100%; margin-bottom: 8px; }
        .stats td { border: 1px solid #bbb; padding: 5px 6px; width: 33%; vertical-align: top; }
        .big { font-size: 13px; font-weight: bold; }
        .foot { margin-top: 8px; color: #777; font-size: 8px; }
    </style>
</head>
<body>

    <h1>État des paiements de scolarité</h1>
    <div class="sub">Édité le {{ $dateEdition }}</div>

    <div class="criteres">
        <span><strong>Année :</strong> {{ $annee->nom ?? '—' }}</span>
        <span><strong>Cycle :</strong> {{ $cycle->nom ?? 'Tous' }}</span>
        <span><strong>Classe :</strong> {{ $classe->nom ?? 'Toutes' }}</span>
        <span><strong>Élève :</strong> {{ $eleve ? $eleve->nom . ' ' . $eleve->prenom : 'Tous' }}</span>
        <br>
        <span><strong>Taux de scolarité payée :</strong>
            @if($tauxMax === null) Tous
            @elseif($tauxMax == 0) 0 % (rien payé)
            @elseif($tauxMax == 99.9) Moins de 100 %
            @else Au plus {{ rtrim(rtrim(number_format($tauxMax, 1, ',', ''), '0'), ',') }} %
            @endif
        </span>
        <span><strong>Classement :</strong> {{ $libelleTri }}</span>
        <span><strong>Source :</strong> {{ $exportSelection ? 'Inscriptions sélectionnées' : 'Liste filtrée complète' }}</span>
    </div>

    {{-- RECOUVREMENT --}}
    <table class="stats">
        <tr>
            <td>
                Scolarité — recouvrement<br>
                <span class="big">{{ number_format($totaux['tauxScolarite'], 1, ',', ' ') }} %</span><br>
                Attendu : {{ number_format($totaux['attenduScolarite'], 0, ',', ' ') }}<br>
                Payé : {{ number_format($totaux['payeScolarite'], 0, ',', ' ') }}<br>
                Reste : {{ number_format($totaux['resteScolarite'], 0, ',', ' ') }}
            </td>
            <td>
                Frais d'inscription — recouvrement<br>
                <span class="big">{{ number_format($totaux['tauxInscription'], 1, ',', ' ') }} %</span><br>
                Attendu : {{ number_format($totaux['attenduInscription'], 0, ',', ' ') }}<br>
                Payé : {{ number_format($totaux['payeInscription'], 0, ',', ' ') }}<br>
                Reste : {{ number_format($totaux['resteInscription'], 0, ',', ' ') }}
            </td>
            <td>
                Global — recouvrement<br>
                <span class="big">{{ number_format($totaux['tauxGeneral'], 1, ',', ' ') }} %</span><br>
                Attendu : {{ number_format($totaux['attenduGeneral'], 0, ',', ' ') }}<br>
                Payé : {{ number_format($totaux['payeGeneral'], 0, ',', ' ') }}<br>
                Reste : {{ number_format($totaux['resteGeneral'], 0, ',', ' ') }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="c">N°</th>
                <th>Élève</th>
                <th>Matricule</th>
                <th>Classe</th>
                <th class="c">Type</th>
                <th class="r">Scolarité attendue</th>
                <th class="r">Scolarité payée</th>
                <th class="r">Reste scolarité</th>
                <th class="c">Taux</th>
                <th class="r">Inscr. attendue</th>
                <th class="r">Inscr. payée</th>
                <th class="r">Reste inscr.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $i => $l)
                <tr>
                    <td class="c">{{ $i + 1 }}</td>
                    <td>{{ $l['nom'] }}</td>
                    <td>{{ $l['matricule'] }}</td>
                    <td>{{ $l['cycle'] }} / {{ $l['classe'] }}</td>
                    <td class="c">{{ $l['est_nouveau'] ? 'Nouveau' : 'Ancien' }}</td>
                    <td class="r">{{ number_format($l['attendu_scolarite'], 0, ',', ' ') }}</td>
                    <td class="r ok">{{ number_format($l['paye_scolarite'], 0, ',', ' ') }}</td>
                    <td class="r ko">{{ number_format($l['reste_scolarite'], 0, ',', ' ') }}</td>
                    <td class="c">
                        {{ $l['taux_scolarite'] === null ? '—' : number_format($l['taux_scolarite'], 1, ',', ' ') . ' %' }}
                    </td>
                    <td class="r">{{ number_format($l['attendu_inscription'], 0, ',', ' ') }}</td>
                    <td class="r ok">{{ number_format($l['paye_inscription'], 0, ',', ' ') }}</td>
                    <td class="r ko">{{ number_format($l['reste_inscription'], 0, ',', ' ') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="c">Aucune inscription.</td>
                </tr>
            @endforelse
        </tbody>
        @if($lignes->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="5">TOTAL ({{ $totaux['effectif'] }} inscription(s))</td>
                    <td class="r">{{ number_format($totaux['attenduScolarite'], 0, ',', ' ') }}</td>
                    <td class="r">{{ number_format($totaux['payeScolarite'], 0, ',', ' ') }}</td>
                    <td class="r">{{ number_format($totaux['resteScolarite'], 0, ',', ' ') }}</td>
                    <td class="c">{{ number_format($totaux['tauxScolarite'], 1, ',', ' ') }} %</td>
                    <td class="r">{{ number_format($totaux['attenduInscription'], 0, ',', ' ') }}</td>
                    <td class="r">{{ number_format($totaux['payeInscription'], 0, ',', ' ') }}</td>
                    <td class="r">{{ number_format($totaux['resteInscription'], 0, ',', ' ') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="foot">
        Montants en FCFA. Les frais d'inscription et de réinscription ne sont retenus que pour les nouveaux inscrits.
    </div>

</body>
</html>