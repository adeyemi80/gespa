<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>
        Aperçu du contrat #{{ $contrat->id }}
    </title>

    <style>
        body {
            margin: 0;
            background: #e5e7eb;
        }

        .preview-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #212529;
            padding: 10px 15px;
            text-align: center;
        }

        .preview-toolbar a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .btn-pdf {
            background: #198754;
        }

        .btn-back {
            background: #6c757d;
        }

        .document-container {
            width: 210mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,.15);
        }

        @media print {
            body {
                background: white;
            }

            .preview-toolbar {
                display: none;
            }

            .document-container {
                width: 100%;
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <div class="preview-toolbar">

        <a href="{{ route('contrats-prestataires.index') }}"
           class="btn-back">
            ← Retour
        </a>

        <a href="{{ route('contrats-prestataires.pdf', $contrat->id) }}"
           class="btn-pdf">
            ↓ Télécharger PDF
        </a>

        <a href="javascript:window.print()"
           class="btn-pdf">
            🖨 Imprimer
        </a>

    </div>

    <div class="document-container">

        @include('contrats.prestataires.partials.document', [
            'contrat' => $contrat
        ])

    </div>

</body>
</html>