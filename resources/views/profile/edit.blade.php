<x-app-layout>
    <x-slot name="title">Mon profil</x-slot>

    <x-slot name="header">
        <x-page-header title="Mon profil" subtitle="Gérez vos informations personnelles" icon="user"
           
        />
    </x-slot>

    <style>
        /* ── Page wrapper ─────────────────────────────── */
        .pe-page {
            max-width:80rem; margin:0 auto; padding:2rem 1.5rem;
            display:flex; flex-direction:column; gap:1.5rem;
        }
        @media(max-width:640px) { .pe-page { padding:1rem; gap:1rem; } }

        /* ── Hero card ───────────────────────────────── */
        .pe-hero {
            background:var(--te-surface); border-radius:18px;
            border:1px solid var(--te-border);
            box-shadow:0 6px 28px rgba(79,70,229,.09), 0 1px 4px rgba(0,0,0,.05);
            overflow:hidden;
        }
        .pe-hero-banner {
            height:100px;
            background:linear-gradient(130deg, var(--te-primary-hover) 0%, var(--te-primary) 45%, var(--te-primary-400) 80%, var(--te-primary-400) 100%);
            position:relative; overflow:hidden;
        }
        .pe-hero-banner::before {
            content:''; position:absolute; border-radius:50%;
            width:240px; height:240px;
            background:rgba(255,255,255,.07);
            top:-110px; right:70px;
        }
        .pe-hero-banner::after {
            content:''; position:absolute; border-radius:50%;
            width:150px; height:150px;
            background:rgba(255,255,255,.05);
            bottom:-75px; left:50px;
        }
        /* subtle grid pattern */
        .pe-hero-banner-grid {
            position:absolute; inset:0;
            background-image:radial-gradient(rgba(255,255,255,.15) 1px, transparent 1px);
            background-size:20px 20px;
        }

        .pe-hero-body {
            padding:0 2rem 1.75rem;
            display:flex; align-items:flex-end; gap:1.25rem; flex-wrap:wrap;
        }
        @media(max-width:600px) { .pe-hero-body { padding:0 1rem 1.25rem; gap:1rem; } }

        /* ── Avatar ──────────────────────────────────── */
        .pe-avatar-wrap { margin-top:-40px; position:relative; flex-shrink:0; }
        .pe-avatar {
            width:82px; height:82px; border-radius:50%;
            background:linear-gradient(135deg, var(--te-primary), var(--te-primary-400));
            display:flex; align-items:center; justify-content:center;
            color:var(--te-on-primary); font-size:2rem; font-weight:800;
            box-shadow:0 4px 18px rgba(79,70,229,.35);
            border:4px solid var(--te-surface); overflow:hidden; position:relative;
        }
        .pe-avatar img { width:100%; height:100%; object-fit:cover; }
        .pe-avatar-wrap { cursor:pointer; }
        .pe-avatar-edit { position:absolute; inset:0; border-radius:50%; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.38); opacity:0; transition:opacity .2s; pointer-events:none; }
        .pe-avatar-wrap:hover .pe-avatar-edit { opacity:1; }

        /* ── Hero info ───────────────────────────────── */
        .pe-hero-info { flex:1; padding-top:1.1rem; min-width:0; }
        .pe-hero-name {
            font-size:1.15rem; font-weight:800; color:var(--te-text);
            letter-spacing:-.025em; line-height:1.25;
        }
        .pe-hero-email {
            font-size:.78rem; color:var(--te-text-muted); margin-top:.2rem;
            display:inline-flex; align-items:center; gap:.35rem;
        }
        .pe-hero-meta { display:flex; align-items:center; gap:.5rem; margin-top:.65rem; flex-wrap:wrap; }

        .pe-role-chip {
            display:inline-flex; align-items:center; gap:.35rem;
            font-size:.75rem; font-weight:700; padding:.25rem .7rem;
            border-radius:99px; letter-spacing:.02em; flex-shrink:0;
        }
        .pe-role-chip.candidate { background:var(--te-primary-soft); color:var(--te-primary-fg); border:1px solid var(--te-primary-border); }
        .pe-role-chip.company   { background:var(--te-success-soft); color:var(--te-success-fg); border:1px solid var(--te-success-border); }
        .pe-role-chip.admin     { background:var(--te-warning-soft); color:var(--te-warning-fg); border:1px solid var(--te-warning-border); }
        .pe-role-dot { width:5px; height:5px; border-radius:50%; background:currentColor; }

        .pe-meta-pill {
            display:inline-flex; align-items:center; gap:.3rem;
            font-size:.75rem; color:var(--te-text-muted);
            background:var(--te-surface-2); border:1px solid var(--te-border-subtle);
            padding:.2rem .65rem; border-radius:99px;
        }

        /* ── Tabs card ───────────────────────────────── */
        .pe-tabs-card {
            background:var(--te-surface); border-radius:18px;
            border:1px solid var(--te-border);
            box-shadow:0 2px 8px rgba(0,0,0,.05), 0 1px 3px rgba(0,0,0,.04);
            overflow:hidden;
        }
        .pe-tabs {
            display:flex; border-bottom:1px solid var(--te-border-subtle);
            overflow-x:auto; padding:0 1rem;
            scrollbar-width:none;
        }
        .pe-tabs::-webkit-scrollbar { display:none; }

        .pe-tab {
            display:flex; align-items:center; gap:.5rem;
            padding:.75rem .9rem; border:none; border-bottom:2.5px solid transparent;
            background:none; font-size:.85rem; font-weight:600; color:var(--te-text-muted);
            cursor:pointer; font-family:inherit; white-space:nowrap;
            margin-bottom:-1px; transition:color .15s, border-color .15s;
        }
        .pe-tab-icon {
            width:26px; height:26px; border-radius:7px;
            display:inline-flex; align-items:center; justify-content:center;
            background:var(--te-surface-3); color:var(--te-text-muted);
            transition:background .15s, color .15s; flex-shrink:0;
        }
        .pe-tab:hover:not(.active) { color:var(--te-text-2); }
        .pe-tab:hover:not(.active) .pe-tab-icon { background:var(--te-border); color:var(--te-text-muted); }
        .pe-tab.active { color:var(--te-primary-fg); border-bottom-color:var(--te-primary); }
        .pe-tab.active .pe-tab-icon { background:var(--te-primary-soft); color:var(--te-primary-fg); }

        /* ── Panel ───────────────────────────────────── */
        .pe-panel { display:none; padding:2rem; }
        @media(max-width:640px) { .pe-panel { padding:1.25rem 1rem; } }
        .pe-panel.active { display:block; }

        /* Zone de suppression du compte, séparée du formulaire */
        .pe-danger-zone {
            margin-top:2.25rem; padding:1.25rem 1.5rem;
            border:1.5px solid var(--te-danger-border); border-radius:var(--r-sm, 10px);
            background:var(--te-surface);
        }
        @media(max-width:640px) { .pe-danger-zone { padding:1rem; } }
    </style>

    <div class="pe-page">

        {{-- ── Hero ─────────────────────────────────────── --}}
        <div class="pe-hero">
            <div class="pe-hero-banner">
                <div class="pe-hero-banner-grid"></div>
            </div>
            <div class="pe-hero-body">
                <div class="pe-avatar-wrap" @if(Auth::user()->isCompany()) onclick="document.getElementById('logo')?.click()" title="Changer le logo" @endif>
                    <div class="pe-avatar">
                        @if(Auth::user()->isCompany() && Auth::user()->logo_path)
                            <img src="{{ Auth::user()->logo_url }}" alt="Logo de {{ Auth::user()->name }}">
                        @else
                            {{ Auth::user()->initials }}
                        @endif
                        @if(Auth::user()->isCompany())
                            <div class="pe-avatar-edit">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-on-primary)">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-1.414a2 2 0 01.586-1.414z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="pe-hero-info">
                    <div class="pe-hero-name">{{ Auth::user()->name }}</div>
                    <div class="pe-hero-email">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ Auth::user()->email }}
                    </div>
                    <div class="pe-hero-meta">
                        @if(Auth::user()->isCandidate())
                            <span class="pe-role-chip candidate">
                                <span class="pe-role-dot"></span>Candidat
                            </span>
                            @if(Auth::user()->city)
                                <span class="pe-meta-pill">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ Auth::user()->city }}
                                </span>
                            @endif
                            @if(Auth::user()->domain)
                                <span class="pe-meta-pill">{{ Auth::user()->domain }}</span>
                            @endif
                            @if(Auth::user()->experience_years !== null)
                                <span class="pe-meta-pill">{{ \App\Support\Experience::label(Auth::user()->experience_years, 'candidate', 'd’expérience') }}</span>
                            @endif
                        @elseif(Auth::user()->isCompany())
                            <span class="pe-role-chip company">
                                <span class="pe-role-dot"></span>Entreprise
                            </span>
                            @if(Auth::user()->sector)
                                <span class="pe-meta-pill">{{ Auth::user()->sector }}</span>
                            @endif
                            @if(Auth::user()->city)
                                <span class="pe-meta-pill">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ Auth::user()->city }}
                                </span>
                            @endif
                        @elseif(Auth::user()->isAdmin())
                            <span class="pe-role-chip admin">
                                <span class="pe-role-dot"></span>Administrateur
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tabs + Panels ─────────────────────────────── --}}
        <div class="pe-tabs-card">
            <div class="pe-tabs" id="pe-tabs">
                <button class="pe-tab active" onclick="switchTab('profile', this)" type="button">
                    <span class="pe-tab-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    Informations
                </button>
                <button class="pe-tab" onclick="switchTab('password', this)" type="button">
                    <span class="pe-tab-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    Mot de passe
                </button>
            </div>

            <div class="pe-panel active" id="tab-profile">
                @include('profile.partials.update-profile-information-form')

                {{-- Suppression du compte : pas pour l'administrateur (seul compte admin) --}}
                @unless(Auth::user()->isAdmin())
                    <div class="pe-danger-zone">
                        @include('profile.partials.delete-user-form')
                    </div>
                @endunless
            </div>
            <div class="pe-panel" id="tab-password">
                @include('profile.partials.update-password-form')
            </div>
        </div>

    </div>

    <script>
        function switchTab(name, btn) {
            document.querySelectorAll('.pe-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.pe-panel').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + name).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if($errors->updatePassword->any())
                switchTab('password', document.querySelectorAll('.pe-tab')[1]);
            @endif
        });
    </script>
</x-app-layout>
