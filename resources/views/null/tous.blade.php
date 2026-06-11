<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletins</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

@foreach($eleves as $eleve)
    {{-- Inclure le template de bulletin --}}
    @include('bulletins.template', ['eleve' => $eleve, 'trimestre' => $trimestre])
    <div class="page-break"></div>
@endforeach

</body>
</html>
