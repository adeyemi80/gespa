<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>
        Contrat de prestation #{{ $contrat->id }}
    </title>
</head>

<body>

    @include('contrats.prestataires.partials.document', [
        'contrat' => $contrat
    ])

</body>
</html>