<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login' }} — SI-PINTAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    <style>
        @theme {
            --color-primary: #080808;
            --color-info: #146EF5;
            --color-success: #00A65A;
            --color-warning: #FFAE13;
            --color-danger: #EE1D36;
            --color-border: #D8D8D8;
            --color-text-secondary: #5A5A5A;
            --font-sans: 'Inter', system-ui, sans-serif;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-white font-sans antialiased">
    @yield('content')
</body>
</html>
