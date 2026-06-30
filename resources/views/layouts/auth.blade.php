<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login' }} — SI-PINTAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    <style>
        :root {
            --color-surface: #FFFFFF;
            --color-surface-soft: #F8FAFC;
            --color-surface-muted: #F4F7FC;
            --color-ink: #0f172a;
            --color-muted: #64748b;
            --color-text-muted: #94A3B8;
            --color-border: #e2e8f0;
            --color-border-soft: #e2e8f0;
            --color-accent: #146EF5;
            --color-accent-hover: #0F5CE0;
            --color-success: #00A65A;
            --color-warning: #FFAE13;
            --color-danger: #EE1D36;
            --color-support-blue: #2F6BFF;
            --color-support-purple: #7C5CFF;
            --color-support-green: #31B36B;
            --color-support-orange: #F59E0B;
            --shadow-soft: 0 24px 60px -36px rgb(15 23 42 / 0.18);
            --font-sans: 'Inter', system-ui, sans-serif;
        }

        .auth-login-page {
            color: #0f172a;
            background: #ffffff;
        }

        .auth-login-page .text-ink {
            color: #0f172a !important;
        }

        .auth-login-page .text-muted {
            color: #64748b !important;
        }

        .auth-login-page .text-text-muted,
        .auth-login-page .placeholder\:text-text-muted::placeholder {
            color: #94a3b8 !important;
            opacity: 1;
        }

        .auth-login-page .border-border,
        .auth-login-page .border-border-soft {
            border-color: #e2e8f0 !important;
        }

        .auth-login-page .bg-surface,
        .auth-login-page .bg-white {
            background-color: #ffffff !important;
        }

        .auth-login-page .bg-surface-soft {
            background-color: #f8fafc !important;
        }

        .auth-login-page .bg-surface-muted {
            background-color: #f4f7fc !important;
        }

        .auth-login-page [data-login-submit] {
            background-color: #146ef5 !important;
            color: #ffffff !important;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-login-page min-h-[100dvh] bg-surface font-sans text-ink antialiased">
    @yield('content')
</body>
</html>
