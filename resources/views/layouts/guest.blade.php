<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('partials.theme-script')

        <title>{{ isset($title) && trim(strip_tags($title)) !== '' ? trim(strip_tags($title)).' – ' : '' }}{{ config('app.name', 'TunisieEmploi') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            /* ── Fond : dégradé du hero, identique dans les deux thèmes ── */
            body {
                font-family: var(--te-font);
                color:var(--te-text);
                min-height: 100vh;
                background:linear-gradient(135deg, var(--te-hero-from), var(--te-hero-to));
            }

            /* ── Page wrapper ─────────────────────────────────────── */
            .auth-page {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 3rem 1.25rem;
            }

            /* ── Brand above card ─────────────────────────────────── */
            .auth-brand {
                display: flex;
                align-items: center;
                gap: .65rem;
                margin-bottom: 1.75rem;
                text-decoration: none;
            }
            .auth-brand-name {
                font-size: 1.5rem;
                font-weight: 800;
                color:var(--te-hero-text);
                letter-spacing: -.03em;
            }
            .auth-brand-name span { color:var(--te-hero-muted); }

            .auth-flash { width:100%; max-width:440px; margin:0 0 1rem; padding:.75rem 1rem; border-radius:10px; font-size:.875rem; font-weight:600; border:1px solid; background:var(--te-surface); }
            .auth-flash.is-error   { color:var(--te-danger-fg);  border-color:var(--te-danger-border); }
            .auth-flash.is-info    { color:var(--te-primary-fg); border-color:var(--te-primary-border); }
            .auth-flash.is-success { color:var(--te-success-fg); border-color:var(--te-success-border); }
            .auth-footer { margin:1.5rem 0 0; font-size:.75rem; color:var(--te-hero-muted); text-align:center; }

            /* ── Carte du formulaire ──────────────────────────────── */
            .auth-card {
                width: 100%;
                background:var(--te-surface);
                border-radius:var(--te-radius-lg);
                padding: 2.75rem 2.5rem;
                box-shadow:var(--te-shadow-lg);
                border:1px solid var(--te-hero-border);
            }

            @media (max-width: 640px) {
                .auth-card  { padding: 2rem 1.5rem; }
                .auth-page  { padding: 2rem 1rem; }
            }

        </style>
    </head>
    <body>

        <div class="auth-page">

            <!-- Brand -->
            <a href="{{ url('/') }}" class="auth-brand">
                <span class="auth-brand-name">Tunisie<span>Emploi</span></span>
            </a>

            {{-- Messages (ex. session expirée, déconnexion) --}}
            @foreach(['error' => 'is-error', 'info' => 'is-info', 'success' => 'is-success'] as $key => $cls)
                @if(session($key))
                    <div class="auth-flash {{ $cls }}" role="{{ $key === 'error' ? 'alert' : 'status' }}">{{ session($key) }}</div>
                @endif
            @endforeach

            <!-- Slot — each page renders its own .auth-card wrapper -->
            {{ $slot }}

            <p class="auth-footer">Données de démonstration fictives</p>
        </div>

    </body>
</html>
