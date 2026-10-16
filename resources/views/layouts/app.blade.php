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

            body {
                font-family:var(--te-font);
                background:var(--te-bg);
                color:var(--te-text);
                min-height: 100vh;
            }

            /* ── Page header ──────────────────────────────────────── */
            .page-header {
                background:var(--surface-2);
                border-bottom:1px solid var(--border);
                padding: 1.25rem 2rem;
                box-shadow:var(--shadow-sm);
            }

            .page-header-inner {
                max-width: 80rem;
                margin: 0 auto;
            }

            .page-header h1 {
                font-size: 1.25rem;
                font-weight: 700;
                color:var(--text-main);
            }

            /* ── Page Header Component (x-page-header) ─────────── */
            .ph-wrap { display: flex; flex-direction: column; gap: .2rem; }
            .ph-bc {
                display: flex; align-items: center; gap: .3rem; flex-wrap: wrap;
                margin-bottom: .3rem;
            }
            .ph-bc-link {
                font-size: .75rem; font-weight: 600; color:var(--text-muted);
                text-decoration: none; transition: color var(--transition);
                display: inline-flex; align-items: center; gap: .2rem;
            }
            .ph-bc-link:hover { color:var(--te-primary-fg); }
            .ph-bc-sep { color:var(--te-border-strong); flex-shrink: 0; }
            .ph-bc-current {
                font-size: .75rem; font-weight: 600; color:var(--text-main);
                max-width: 36ch; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            }
            .ph-row {
                display: flex; align-items: center; justify-content: space-between;
                flex-wrap: wrap; gap: .75rem;
            }
            .ph-left { display: flex; align-items: center; gap: .7rem; }
            .ph-icon-wrap {
                width: 38px; height: 38px;
                border-radius:10px;
                background:var(--te-primary-soft);
                color:var(--te-primary-fg);
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }
            .ph-title {
                font-size: 1.2rem; font-weight: 800; color:var(--te-text);
                margin: 0; letter-spacing: -.025em; line-height: 1.25;
            }
            .ph-sub { font-size: .8rem; color:var(--te-text-muted); margin: .2rem 0 0; line-height: 1.45; }
            .ph-actions { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }

            /* ── Main content ─────────────────────────────────────── */
            .main-content {
                max-width: 80rem;
                margin: 2rem auto;
                padding: 0 1.5rem;
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background:var(--surface); }
            ::-webkit-scrollbar-thumb { background:var(--border); border-radius:99px; }
            ::-webkit-scrollbar-thumb:hover { background:var(--te-primary); }

            /* Selection */
            ::selection { background:var(--te-primary); color:var(--te-on-primary); }

            /* ── Toast / Flash messages ───────────────────────────── */
            .flash-container {
                position: fixed;
                bottom: 1.25rem;
                right: 1.25rem;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: .5rem;
            }
            .flash {
                padding: .75rem 1.25rem;
                border-radius:var(--radius);
                font-size: .875rem;
                font-weight: 600;
                box-shadow:var(--shadow-md);
                animation: slideIn .3s ease forwards;
                display:flex; align-items:flex-start; gap:.55rem;
            }
            .flash-ico { flex-shrink:0; margin-top:.1rem; }
            .flash-success { background:var(--te-surface); color:var(--te-success-fg); border:1px solid var(--te-success-border); border-left:4px solid var(--te-success-solid); }
            .flash-error   { background:var(--te-surface); color:var(--te-danger-fg); border:1px solid var(--te-danger-border); border-left:4px solid var(--te-danger-solid); }
            .flash-info    { background:var(--te-surface); color:var(--te-primary-fg); border:1px solid var(--te-primary-border); border-left:4px solid var(--te-primary); }

            .flash-sticky { display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; }
            .flash-action { color:var(--te-danger-fg); font-weight:800; text-decoration:underline; text-underline-offset:2px; }
            .flash-close { margin-left:auto; background:none; border:none; font-size:1.2rem; line-height:1; color:var(--te-text-muted); cursor:pointer; padding:0 .2rem; }

            @keyframes slideIn {
                from { opacity: 0; transform: translateY(1rem); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ── Pied de page ─────────────────────────────────── */
            .te-footer { border-top:1px solid var(--te-border); padding:1rem 1.5rem; text-align:center; }
            .te-footer p { margin:0; font-size:.75rem; color:var(--te-text-muted); }

            /* ── Main content wrapper ─────────────────────────── */
            .te-main-content {
                width: 100%;
                min-height: calc(100vh - 64px - 3.25rem);
            }

            .te-content-container {
                width: 100%;
            }

        </style>
    </head>
    <body>
        <div class="min-h-screen" style="background:var(--te-bg)">

            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <div class="page-header">
                    <div class="page-header-inner">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Flash Messages -->
            @if(session('success') || session('error') || session('info'))
                <div class="flash-container">
                    @if(session('success'))
                        <div class="flash flash-success" role="status"><svg class="flash-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span>{{ session('success') }}</span></div>
                    @endif
                    @if(session('error'))
                        @if(session('error_action'))
                            <div class="flash flash-error flash-sticky" role="alert">
                                <svg class="flash-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg><span>{{ session('error') }}</span>
                                <a href="{{ session('error_action')['url'] }}" class="flash-action">{{ session('error_action')['label'] }}</a>
                                <button type="button" class="flash-close" aria-label="Fermer le message" onclick="this.parentElement.remove()">×</button>
                            </div>
                        @else
                            <div class="flash flash-error" role="alert"><svg class="flash-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg><span>{{ session('error') }}</span></div>
                        @endif
                    @endif
                    @if(session('info'))
                        <div class="flash flash-info" role="status"><svg class="flash-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>{{ session('info') }}</span></div>
                    @endif
                </div>
                <script>
                    setTimeout(() => {
                        document.querySelectorAll('.flash:not(.flash-sticky)').forEach(el => {
                            el.style.transition = 'opacity .4s';
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 400);
                        });
                    }, 4000);
                </script>
            @endif

        <!-- Page Content -->
        <main class="te-main-content">
            <div class="te-content-container">
                {{ $slot }}
            </div>
        </main>

        <footer class="te-footer">
            <p>TunisieEmploi · Données de démonstration fictives</p>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
