<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Classement Annuel par Classe</title>

    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        h3 {
            background: #f2f2f2;
            padding: 8px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #333;
            color: white;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .danger {
            color: red;
            font-weight: bold;
        }

        .empty {
            padding: 10px;
            text-align: center;
            color: #777;
            font-style: italic;
        }

        .header-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .logo img {
            max-width: 160px;
            height: auto;
        }
    </style>
</head>

<body>

{{-- 🔝 HEADER --}}
<table class="header-table">
    <tr>
        <td class="logo" style="width:150%;">
            <img 
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}"
                style="max-width: 740px; max-height: 100px; width: auto; height: auto;"
                alt="Logo"
            >
        </td>

        <td style="width:50%; text-align:right;">
            <strong>Année scolaire :</strong> {{ $annee->nom ?? '' }}
        </td>
    </tr>
</table>

<h2>
    RESULTATS ANNUELS 
</h2>

{{-- 🏫 CLASSES --}}
@foreach($classesData as $data)

    <h3>
        {{ $data['classe']->nom }}
    </h3>

    {{-- ❌ CAS VIDE --}}
    @if($data['eleves']->count() === 0)

        <div class="empty">
            Aucun élève dans cette classe
        </div>

    @else

        <table>

            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Élève</th>
                    <th>Moyenne</th>
                    <th>Mention</th>
                    <th>Passage</th>
                </tr>
            </thead>

            <tbody>

                @foreach($data['eleves'] as $i => $moyenne)

                    @php
                        $m = $moyenne->moyenne_annuelle ?? 0;

                        $classeActuelle = $moyenne->inscription->classe ?? null;

                        $classeSuivante = $classeActuelle
                            ? \App\Models\Classe::where('ordre', $classeActuelle->ordre + 1)->first()
                            : null;

                        $mention = $m >= 16 ? 'Très bien' :
                                   ($m >= 14 ? 'Bien' :
                                   ($m >= 12 ? 'Assez bien' :
                                   ($m >= 10 ? 'Passable' : 'Échoué')));
                    @endphp

                    <tr>

                        <td>
                            {{ $i + 1 }}
                        </td>

                        <td>
                            {{ $moyenne->inscription->eleve->nom ?? '' }}
                            {{ $moyenne->inscription->eleve->prenom ?? '' }}
                        </td>

                        <td class="{{ $m >= 10 ? 'success' : 'danger' }}">
                            {{ number_format($m, 2) }}
                        </td>

                        <td>
                            {{ $mention }}
                        </td>

                        <td>
                            @if($m >= 10)
                                 Passe en {{ $classeSuivante->nom ?? 'Fin de cycle' }}
                            @else
                                 Redouble {{ $classeActuelle->nom ?? '' }}
                            @endif
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif

@endforeach

</body>
</html>