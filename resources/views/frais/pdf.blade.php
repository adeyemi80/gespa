<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>
        Frais par classe et année
    </title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            margin-bottom: 0;
        }

        ul {
            margin: 0;
            padding-left: 16px;
        }

    </style>

</head>

<body>

    <h2>
        Récapitulatif des frais par classe et année
    </h2>

    <p>
        Généré le :
        {{ now()->format('d/m/Y à H:i') }}
    </p>

    <table>

        <thead>

            <tr>

                <th>Classe(s)</th>
                <th>Année(s)</th>
                <th>Nom du frais</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Échéances</th>

            </tr>

        </thead>

        <tbody>

            @forelse($frais as $f)

                <tr>

                    {{-- CLASSES --}}
                    <td>

                        @php
                            $classes = $f->anneeClasseFrais
                                ->pluck('classe.nom')
                                ->filter()
                                ->unique();
                        @endphp

                        @if($classes->isNotEmpty())

                            {{ $classes->implode(', ') }}

                        @else

                            —

                        @endif

                    </td>

                    {{-- ANNEES --}}
                    <td>

                        @php
                            $annees = $f->anneeClasseFrais
                                ->pluck('annee.nom')
                                ->filter()
                                ->unique();
                        @endphp

                        @if($annees->isNotEmpty())

                            {{ $annees->implode(', ') }}

                        @else

                            —

                        @endif

                    </td>

                    {{-- NOM --}}
                    <td>

                        {{ $f->nom }}

                    </td>

                    {{-- DESCRIPTION --}}
                    <td>

                        {{ $f->description ?? '—' }}

                    </td>

                    {{-- MONTANT --}}
                    <td>

                        @php
                            $montant = $f->anneeClasseFrais
                                ->first()?->montant;
                        @endphp

                        {{ number_format($montant ?? 0, 2, ',', ' ') }} F

                    </td>

                    {{-- ÉCHÉANCES --}}
                    <td>

                        @if($f->echeances->isNotEmpty())

                            <ul>

                                @foreach($f->echeances as $e)

                                    <li>

                                        {{ $e->nom }}

                                        :

                                        {{ number_format($e->montant, 2, ',', ' ') }} F

                                        (

                                        {{ $e->date_limite
                                            ? \Carbon\Carbon::parse($e->date_limite)->format('d/m/Y')
                                            : '—' }}

                                        )

                                    </li>

                                @endforeach

                            </ul>

                        @else

                            —

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" style="text-align: center;">

                        Aucun frais trouvé

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>

</html>