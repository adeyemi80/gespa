<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Classement par classe</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .logo img {
            height: 120px;
        }

        .info {
            text-align: right;
            font-size: 13px;
        }

        h2 {
            text-align: center;
            margin: 10px 0;
        }

        h3 {
            margin-top: 20px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }

         td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .good {
            color: green;
        }

        .bad {
            color: red;
        }
    </style>
</head>

<body>

{{-- 🔝 HEADER --}}
<table class="header-table">
    <tr>
        <th class="logo" style="width:50%;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/entete_lg.png'))) }}" alt="Logo">
        </th>

        <th class="info" style="width:50%;">
            <div><strong>Année scolaire :</strong> {{ $annee->nom ?? '' }}</div>
        </th>
    </tr>
</table>

{{-- 🏫 TITRE --}}
<h2>
    Résultats du {{ $trimestre->nom ?? '' }}
</h2>

{{-- 📊 CLASSEMENTS --}}
@foreach($classes as $data)

    <h3>{{ $data['classe']->nom ?? 'Classe' }}</h3>

    <table>
        <thead>
            <tr>
                <th>Rang</th>
                <th>Élève</th>
                <th>Moyenne</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data['eleves'] as $i => $ins)
            <tr>

                {{-- RANG --}}
                <td>
                    {{ $i + 1 }}
                </td>

                {{-- ÉLÈVE --}}
                <td>
                    {{ $ins->eleve->nom ?? '' }} {{ $ins->eleve->prenom ?? '' }}
                </td>

                {{-- MOYENNE --}}
                <td class="{{ ($ins->moyenne ?? 0) >= 10 ? 'good' : 'bad' }}">
                    {{ $ins->moyenne ?? 0 }}
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

@endforeach

</body>
</html>