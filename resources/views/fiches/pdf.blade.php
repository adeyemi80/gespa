<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px; /* un peu plus lisible */
            margin: 20px;
        }

        h3 {
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #eee;
            text-align: center;
        }

        /* Header (logo + infos) */
        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            text-align: left;
        }

        .logo img {
            width: 120px;
            height: auto;
        }

        .info {
            text-align: left;
            font-size: 13px;
        }

        .info strong {
            display: inline-block;
            width: 160px;
        }

        /* Petit espace avant le tableau principal */
        .table-container {
            margin-top: 20px;
        }

    </style>
</head>
<body>

    {{-- En-tête avec logo + infos --}}
    <table class="header-table">
        <tr>
            <td class="logo" style="width:50%;">
                <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            </td>
            <td class="info" style="width:50%;">
                <div><strong>Année scolaire :</strong> {{ $annee->nom }}</div>
                <div><strong>Classe :</strong> {{ $classe->nom }}</div>
                <div><strong>Matière :</strong> {{ $matiere->nom }}</div>
                <div><strong>Coefficient :</strong> {{ $matiere->coefficient }}</div>
                <div><strong>Nom et Prénom du Professeur :  </strong></div>
            </td>
        </tr>
    </table>
     <h3>
        FICHE DE NOTES DU {{ strtoupper($trimestre->nom) }}
    </h3>


    <div class="table-container">
        @include('fiches.table')
    </div>

</body>
</html>
