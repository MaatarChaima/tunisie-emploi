<x-app-layout>
    <x-slot name="title">{{ $candidate->name }}</x-slot>

    <x-slot name="header">
        <x-page-header
            :title="$candidate->name"
            subtitle="Profil du candidat"
            icon="user"
            :breadcrumbs="[
                ['label' => 'Candidats', 'url' => \App\Support\Breadcrumbs::parent('candidates.index')],
                ['label' => $candidate->name],
            ]"
        />
    </x-slot>

    @php
        $initial = $candidate->initials;
    @endphp

    <style>
    /* ══ Design tokens (aligned with job-offers/show) ═══════════ */
    :root {
            --r:       16px;
            --r-sm:    10px;
            --sh:      0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --sh-md:   0 4px 20px rgba(79,70,229,.1);
            --tr:      .18s cubic-bezier(.4,0,.2,1);
        }
        /* ── Breadcrumb ─────────────────────────────────────────────── */
    .show-bc-current { font-size:.8rem; font-weight:700; color:var(--te-text); }

    /* ── Page layout ────────────────────────────────────────────── */
    .show-page {
        max-width: 80rem; margin: 0 auto;
        padding: 1.75rem 1.5rem 3rem;
        display: grid; grid-template-columns: 1fr 320px;
        gap: 1.75rem; align-items: start;
    }
    @media (max-width: 960px) { .show-page { grid-template-columns: 1fr; padding: 1rem; } }

    /* ── Sticky sidebar ─────────────────────────────────────────── */

    /* ── Card base ──────────────────────────────────────────────── */
    .show-card-head h2 { font-size:.95rem; font-weight:800; color:var(--te-text); margin:0; }

    /* ── Hero ───────────────────────────────────────────────────── */
    .show-hero-banner {
        background:linear-gradient(135deg, var(--te-hero-from) 0%, var(--te-primary-hover) 45%, var(--te-primary) 75%, var(--te-primary-400) 100%);
        padding: 1.75rem 1.75rem 2.75rem; position:relative; overflow:hidden;
    }
    .show-hero-banner::before {
        content:''; position:absolute; inset:0;
        background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events:none;
    }
    .show-hero-logo-ph {
        width:72px; height:72px; border-radius:16px; flex-shrink:0;
        background:rgba(255,255,255,.15); border:3px solid rgba(255,255,255,.25);
        display:flex; align-items:center; justify-content:center;
        font-size:1.75rem; font-weight:900; color:var(--te-on-primary);
        box-shadow:0 8px 24px rgba(0,0,0,.2); backdrop-filter:blur(4px);
    }
    @media(max-width:600px) { .show-hero-title { font-size:1.2rem; } }

    /* ── Tags ───────────────────────────────────────────────────── */

    /* ── Section title ──────────────────────────────────────────── */
    .show-section-title { display:flex; align-items:center; gap:.5rem; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--te-text-muted); margin:0 0 1rem; }

    /* ── Detail chips ───────────────────────────────────────────── */
    .show-details-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:.85rem; }
    @media(max-width:600px){ .show-details-grid { grid-template-columns:1fr; } }
    .show-detail-chip-icon.green  { background:var(--te-success-soft); }
    .show-detail-chip-icon.green  svg { color:var(--te-success-fg); }
    .show-detail-chip-icon.slate  { background:var(--te-surface-3); }
    .show-detail-chip-icon.slate  svg { color:var(--te-text-muted); }

    /* ── Bio prose ──────────────────────────────────────────────── */
    .show-prose { font-size:.9rem; color:var(--te-text-2); line-height:1.8; white-space:pre-line; margin:0; }
    .show-prose-empty { font-size:.875rem; color:var(--te-text-muted); font-style:italic; margin:0; }
    .show-hero-bio { font-size:.82rem; color:rgba(255,255,255,.78); line-height:1.65; margin:.5rem 0 0; white-space:pre-line; }

    /* ── Sidebar stat rows ──────────────────────────────────────── */
    .sb-stat-row { display:flex; align-items:center; justify-content:space-between; padding:.6rem 0; border-bottom:1px solid var(--te-border-subtle); }
    .sb-stat-row:last-child { border-bottom:none; }
    .sb-stat-label { font-size:.78rem; font-weight:600; color:var(--te-text-muted); }
    .sb-stat-val { font-size:.85rem; font-weight:800; color:var(--te-text); }

    /* ── Buttons ────────────────────────────────────────────────── */
    .show-btn-solid {
        display:inline-flex; align-items:center; justify-content:center; gap:.45rem;
        width:100%; padding:.65rem 1rem; border-radius:var(--r-sm);
        background:var(--te-primary); color:var(--te-on-primary); border:none;
        font-family:inherit; font-size:.85rem; font-weight:700;
        text-decoration:none; cursor:pointer;
        box-shadow:0 2px 8px rgba(79,70,229,.25);
        transition:background var(--tr),transform var(--tr),box-shadow var(--tr);
    }
    .show-btn-solid:hover { background:var(--te-primary-hover); transform:translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,.35); }

    .show-btn-outline {
        display:inline-flex; align-items:center; justify-content:center; gap:.45rem;
        width:100%; padding:.63rem 1rem; border-radius:var(--r-sm);
        background:var(--te-surface); color:var(--te-primary-fg); border:1.5px solid var(--te-primary-border);
        font-family:inherit; font-size:.85rem; font-weight:700;
        text-decoration:none; cursor:pointer; transition:all var(--tr);
    }
    .show-btn-outline:hover { background:var(--te-primary-soft); border-color:var(--te-primary); }

    /* ── Report banner ──────────────────────────────────────────── */
    .show-report-btn {
        display:inline-flex; align-items:center; gap:.35rem;
        padding:.4rem .875rem; border-radius:8px;
        font-size:.8rem; font-weight:700; color:var(--te-danger-fg);
        background:var(--te-surface); border:1.5px solid var(--te-danger-border);
        text-decoration:none; white-space:nowrap;
        transition:all var(--tr);
    }

    /* ── Animations ─────────────────────────────────────────────── */
    @keyframes fadeUp { from{opacity:0;transform:translateY(14px);} to{opacity:1;transform:none;} }
    .show-anim    { animation:fadeUp .35s ease both; }
    </style>

    <div class="show-page">

        {{-- ══════════════  LEFT COLUMN  ══════════════ --}}
        <div>

            {{-- ── Hero Banner ── --}}
            <div class="show-hero show-anim">
                <div class="show-hero-banner">
                    <div class="show-hero-top">
                        <div class="show-hero-logo-ph" aria-hidden="true">{{ $initial }}</div>
                        <div class="show-hero-info">
                            @if($candidate->domain)
                                <div class="show-hero-subtitle">{{ $candidate->domain }}</div>
                            @else
                                <div class="show-hero-subtitle">Candidat</div>
                            @endif
                            <h1 class="show-hero-title">{{ $candidate->name }}</h1>
                            @if($candidate->bio)
                                <p class="show-hero-bio">{{ $candidate->bio }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="show-hero-tags">
                        @if($candidate->city)
                        <span class="show-tag show-tag-hero">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $candidate->city }}
                        </span>
                        @endif
                        @if($candidate->experience_years !== null)
                        <span class="show-tag show-tag-hero">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            {{ \App\Support\Experience::label($candidate->experience_years, 'candidate', 'd’exp.') }}
                        </span>
                        @endif
                        @if($candidate->education_level)
                        <span class="show-tag show-tag-hero"><x-education-icon :level="$candidate->education_level" :size="11" />
                            {{ \App\Support\Education::label($candidate->education_level) }}
                        </span>
                        @endif
                        @if($candidate->cv_path)
                        <span class="show-tag show-tag-hero">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            CV disponible
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Pull-up meta row --}}
                <div class="show-hero-pullup">
                    <div class="show-hero-meta">
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $candidate->email }}</span>
                        </div>
                        @if($candidate->phone)
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $candidate->phone }}</span>
                        </div>
                        @endif
                        @if($candidate->linkedin_url)
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>
                            </svg>
                            <a href="{{ $candidate->linkedin_url }}" target="_blank" style="color:var(--te-primary-fg);font-weight:700;text-decoration:none;">LinkedIn</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Informations personnelles ── --}}
            <div class="show-card show-anim show-anim-d1">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-primary-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2>Informations personnelles</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        @if($candidate->birth_date)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Date de naissance</div>
                                <div class="show-detail-chip-value">{{ \Carbon\Carbon::parse($candidate->birth_date)->translatedFormat('d/m/Y') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->city)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Ville</div>
                                <div class="show-detail-chip-value">{{ $candidate->city }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Email</div>
                                <div class="show-detail-chip-value" style="font-size:.78rem;word-break:break-all;">{{ $candidate->email }}</div>
                            </div>
                        </div>

                        @if($candidate->phone)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Téléphone</div>
                                <div class="show-detail-chip-value">{{ $candidate->phone }}</div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- ── Parcours professionnel ── --}}
            <div class="show-card show-anim show-anim-d2">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-info-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-info-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2>Parcours professionnel</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        @if($candidate->education_level)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue"><x-education-icon :level="$candidate->education_level" :size="16" />
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Niveau d'études</div>
                                <div class="show-detail-chip-value">{{ \App\Support\Education::label($candidate->education_level) }}</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->experience_years !== null)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Expérience</div>
                                <div class="show-detail-chip-value">{{ \App\Support\Experience::label($candidate->experience_years, 'candidate') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->domain)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Domaine</div>
                                <div class="show-detail-chip-value">{{ $candidate->domain }}</div>
                            </div>
                        </div>
                        @endif

                        @if($candidate->linkedin_url)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">LinkedIn</div>
                                <a href="{{ $candidate->linkedin_url }}" target="_blank"
                                   style="font-size:.85rem;color:var(--te-primary-fg);font-weight:700;text-decoration:none;">
                                    Voir le profil →
                                </a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
        {{-- end left column --}}

        {{-- ══════════════  SIDEBAR  ══════════════ --}}
        <aside class="show-sidebar show-anim show-anim-d2">

            {{-- Actions card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-primary-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2>Actions</h2>
                </div>
                <div class="show-card-body" style="display:flex;flex-direction:column;gap:.65rem;padding-top:1rem;padding-bottom:1rem;">
                    @if($candidate->cv_path)
                    <a href="{{ route('applications.download-cv-candidate', $candidate) }}" class="show-btn-solid">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Télécharger le CV
                    </a>
                    @endif
                    <a href="mailto:{{ $candidate->email }}" class="show-btn-outline">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Contacter par email
                    </a>
                </div>
            </div>

            {{-- Quick stats card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-success-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-success-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2>Aperçu</h2>
                </div>
                <div class="show-card-body" style="padding-top:1rem;padding-bottom:1rem;">

                    @if($candidate->experience_years !== null)
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Expérience</span>
                        <span class="sb-stat-val">{{ \App\Support\Experience::label($candidate->experience_years, 'candidate') }}</span>
                    </div>
                    @endif

                    @if($candidate->education_level)
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Niveau d'études</span>
                        <span class="sb-stat-val" style="font-size:.78rem;text-align:right;max-width:140px;">{{ \App\Support\Education::label($candidate->education_level) }}</span>
                    </div>
                    @endif

                    @if($candidate->city)
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Localisation</span>
                        <span class="sb-stat-val">{{ $candidate->city }}</span>
                    </div>
                    @endif

                    <div class="sb-stat-row">
                        <span class="sb-stat-label">CV disponible</span>
                        @if($candidate->cv_path)
                            <span style="display:inline-flex;align-items:center;gap:.3rem;font-size:.82rem;font-weight:700;color:var(--te-success-fg);">
                                <span style="width:7px;height:7px;border-radius:50%;background:var(--te-success-solid);"></span>
                                Oui
                            </span>
                        @else
                            <span style="font-size:.82rem;font-weight:700;color:var(--te-text-muted);">Non</span>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Report card (company only, if applied) --}}
            @php
                $hasAppliedToSomeOffer = false;
                if(Auth::user()->isCompany()) {
                    $hasAppliedToSomeOffer = \App\Models\Application::where('user_id', $candidate->id)
                        ->whereHas('jobOffer', function($q) {
                            $q->where('user_id', Auth::id());
                        })->exists();
                }
            @endphp

            @if(Auth::user()->isCompany() && $hasAppliedToSomeOffer)
            <div class="show-report-card">
                <div>
                    <div style="font-size:.83rem;font-weight:800;color:var(--te-danger-fg);">Un problème avec ce candidat ?</div>
                    <div style="font-size:.75rem;color:var(--te-danger-fg);margin-top:.15rem;">Signalez-le à notre équipe de modération.</div>
                </div>
                <a href="{{ route('reports.create', $candidate) }}" class="show-report-btn">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    Signaler
                </a>
            </div>
            @endif

        </aside>

    </div>

@push('scripts')
<script>
document.querySelectorAll('.show-anim').forEach(el => {
    el.style.willChange = 'opacity, transform';
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.style.opacity = '1'; e.target.style.transform = 'none'; obs.unobserve(e.target); }
        });
    }, { threshold: 0.08 });
    obs.observe(el);
});
</script>
@endpush

</x-app-layout>
