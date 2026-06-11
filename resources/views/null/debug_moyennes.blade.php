@extends('classes.layout')

@section('title', 'Bienvenue')

@section('content')
<h1>Debug Calcul des Moyennes Annuelles</h1>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Trim. 1</th>
            <th>Trim. 2</th>
            <th>Trim. 3</th>
            <th>Moyenne Annuelle</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resultats as $res)
            <tr>
                <td>{{ $res['nom'] }}</td>
                <td>{{ $res['prenom'] }}</td>
                @foreach ($res['moyennes'] as $m)
                    <td>{{ $m ?? 'N/A' }}</td>
                @endforeach
                <td>{{ $res['moyenne_annuelle'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="6">
                    <strong>Détails par trimestre :</strong>
                    @foreach ($res['details'] as $tri => $details)
                        <h4>Trimestre {{ $tri }}</h4>
                        <table>
                            <tr>
                                <th>Matière</th>
                                <th>Moyenne matière</th>
                                <th>Coef</th>
                                <th>Moyenne × Coef</th>
                            </tr>
                            @foreach ($details['matieres'] as $m)
                                <tr>
                                    <td>{{ $m->matiere_nom }}</td>
                                    <td>{{ $m->moyenne_matiere ?? 'N/A' }}</td>
                                    <td>{{ $m->coefficient }}</td>
                                    <td>
                                        @if (!is_null($m->moyenne_matiere))
                                            {{ $m->moyenne_matiere * $m->coefficient }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
