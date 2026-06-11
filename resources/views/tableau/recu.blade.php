<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reçu')</title>

    <!-- CSS léger, juste pour le reçu -->
    <style>
        body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 420px;
            margin: 20px auto;
            padding: 10px;
            background: #fff;
            border: 1px solid #000;
        }
        .no-print {
            display: block;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>