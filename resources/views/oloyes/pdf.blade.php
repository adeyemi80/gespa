<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<style>

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #212529;
}

/* Container Bootstrap */
.container {
    width: 100%;
    margin: auto;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 25px;
}

.header h1 {
    font-size: 22px;
    margin-bottom: 5px;
    color: #0d6efd;
}

.header h3 {
    font-size: 15px;
    font-weight: normal;
}


/* Card Bootstrap */
.card {

    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;

}


/* Tableau Bootstrap */

.table {

    width:100%;
    border-collapse:collapse;

}


.table th {

    background:#0d6efd;
    color:white;
    padding:8px;
    border:1px solid #dee2e6;
    text-align:center;

}


.table td {

    padding:7px;
    border:1px solid #dee2e6;

}


/* lignes alternées */

.table tbody tr:nth-child(even){

    background:#f8f9fa;

}


/* Montant */

.montant {

    text-align:right;
    font-weight:bold;

}


/* Total */

.total-card {

    margin-top:20px;
    padding:15px;
    border-radius:8px;
    border:1px solid #198754;
    background:#d1e7dd;

    text-align:right;

    font-size:16px;
    font-weight:bold;

    color:#146c43;

}


/* Footer */

.footer {

    margin-top:30px;
    text-align:center;
    font-size:10px;
    color:#6c757d;

}


</style>

</head>


<body>


<div class="container">


    <!-- En-tête -->

    <div class="header">

        <h1>
            🏠 OLOYE
        </h1>

        <h3>
            Etat des dépenses
        </h3>

        <p>
            Date d'édition :
            {{ date('d/m/Y') }}
        </p>

    </div>



    <div class="card">


        <table class="table">


            <thead>

                <tr>

                    <th>N°</th>

                    <th>Date</th>

                    <th>Libellé</th>

                    <th>Catégorie</th>

                    <th>Bénéficiaire</th>

                    <th>Montant (FCFA)</th>

                </tr>


            </thead>



            <tbody>


            @foreach($oloyes as $depense)


                <tr>


                    <td align="center">

                        {{ $loop->iteration }}

                    </td>


                    <td>

                        {{ $depense->date->format('d/m/Y') }}

                    </td>


                    <td>

                        {{ $depense->libelle }}

                    </td>


                    <td>

                        {{ $depense->categorie ?? '-' }}

                    </td>


                    <td>

                        {{ $depense->beneficiaire ?? '-' }}

                    </td>


                    <td class="montant">

                        {{ number_format($depense->montant,0,',',' ') }}

                    </td>


                </tr>


            @endforeach


            </tbody>


        </table>


    </div>



    <!-- Total -->

    <div class="total-card">


        TOTAL DES DÉPENSES :

        {{ number_format($total,0,',',' ') }}

        FCFA


    </div>



    <div class="footer">


        Document généré automatiquement par GESPA - Module OLOYE


    </div>


</div>


</body>

</html>