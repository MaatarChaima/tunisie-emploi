<x-app-layout>
    <x-slot name="title">{{ $user->name }}</x-slot>

    <x-slot name="header">
        <x-page-header
            :title="$user->name"
            :subtitle="$user->isCompany() ? 'Entreprise' : ($user->isAdmin() ? 'Administrateur' : 'Candidat')"
            icon="user"
            :breadcrumbs="[
                ['label' => 'Utilisateurs', 'url' => \App\Support\Breadcrumbs::parent('admin.users.moderation')],
                ['label' => $user->name],
            ]"
        />
    </x-slot>

    @php
        $roleLabel  = $user->isAdmin() ? 'Administrateur' : ($user->isCompany() ? 'Entreprise' : 'Candidat');
        $roleColors = [
            'admin'     => ['bg'=>'var(--te-violet-soft)','color'=>'var(--te-violet-fg)','border'=>'var(--te-primary-border)','dot'=>'var(--te-violet-solid)'],
            'company'   => ['bg'=>'var(--te-info-soft)','color'=>'var(--te-info-fg)','border'=>'var(--te-info-border)','dot'=>'var(--te-info-solid)'],
            'candidate' => ['bg'=>'var(--te-primary-soft)','color'=>'var(--te-primary-fg)','border'=>'var(--te-primary-border)','dot'=>'var(--te-primary)'],
        ];
        $rc = $roleColors[$user->role] ?? $roleColors['candidate'];
        $initial = $user->initials;
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
    .show-card-head h2 { font-size:.95rem; font-weight:800; color:var(--te-text); margin:0; display:flex; align-items:center; gap:.5rem; }

    /* ── Hero banner ────────────────────────────────────────────── */
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
    .show-hero-logo {
        width:72px; height:72px; border-radius:16px; object-fit:cover;
        border:3px solid rgba(255,255,255,.25); box-shadow:0 8px 24px rgba(0,0,0,.25);
        background:var(--te-surface); flex-shrink:0;
    }

    /* ── Tags ───────────────────────────────────────────────────── */

    /* ── Detail chips ───────────────────────────────────────────── */
    .show-details-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:.85rem; }
    @media (max-width:600px) { .show-details-grid { grid-template-columns:1fr; } }
    .show-detail-chip-icon.green  { background:var(--te-success-soft); }
    .show-detail-chip-icon.green  svg { color:var(--te-success-fg); }
    .show-detail-chip-icon.slate  { background:var(--te-surface-3); }
    .show-detail-chip-icon.slate  svg { color:var(--te-text-muted); }

    /* ── Section title ──────────────────────────────────────────── */
    .show-section-title {
        display:flex; align-items:center; gap:.5rem;
        font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
        color:var(--te-text-muted); margin:0 0 1rem;
    }

    /* ── Mini list rows ─────────────────────────────────────────── */
    .mini-row { display:flex; align-items:center; gap:.75rem; padding:.75rem 0; border-bottom:1px solid var(--te-border-subtle); }
    .mini-row:last-child { border-bottom:none; }
    .mini-thumb { width:36px; height:36px; border-radius:9px; object-fit:cover; border:1px solid var(--te-border); flex-shrink:0; background:var(--te-primary-soft); display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:800; color:var(--te-primary-fg); }
    .mini-title { font-size:.85rem; font-weight:700; color:var(--te-text); line-height:1.3; }
    .mini-sub { font-size:.75rem; color:var(--te-text-muted); margin-top:.1rem; }
    .mini-badge { display:inline-flex; align-items:center; padding:.2rem .6rem; border-radius:99px; font-size:.75rem; font-weight:700; border:1px solid; flex-shrink:0; margin-left:auto; }

    /* ── Action buttons ─────────────────────────────────────────── */
    .show-btn-solid {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        background:var(--te-primary); color:var(--te-on-primary); font-size:.875rem; font-weight:700;
        text-decoration:none; box-shadow:0 2px 8px rgba(79,70,229,.25);
        transition:all var(--tr); border:none; cursor:pointer; font-family:inherit; white-space:nowrap;
    }
    .show-btn-solid:hover { background:var(--te-primary-hover); box-shadow:0 4px 12px rgba(79,70,229,.35); transform:translateY(-1px); }

    .show-btn-ghost {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        border:1.5px solid var(--te-border-strong); background:var(--te-surface);
        color:var(--te-text-2); font-size:.875rem; font-weight:600;
        text-decoration:none; transition:all var(--tr);
        cursor:pointer; font-family:inherit; white-space:nowrap;
    }

    .show-btn-danger {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        background:var(--te-danger-soft); color:var(--te-danger-fg); border:1.5px solid var(--te-danger-border);
        font-size:.875rem; font-weight:700; cursor:pointer; font-family:inherit;
        transition:all var(--tr); white-space:nowrap;
    }
    .show-btn-danger:hover { background:var(--te-danger-soft); border-color:var(--te-danger-solid); }

    .show-btn-success {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.6rem 1.25rem; border-radius:8px;
        background:var(--te-success-soft); color:var(--te-success-fg); border:1.5px solid var(--te-success-border);
        font-size:.875rem; font-weight:700; cursor:pointer; font-family:inherit;
        transition:all var(--tr); white-space:nowrap;
    }
    .show-btn-success:hover { background:var(--te-success-soft); border-color:var(--te-success-solid); }

    /* Action row in sidebar card */
    .act-row { display:flex; align-items:center; justify-content:space-between; padding:.875rem 0; border-bottom:1px solid var(--te-border-subtle); }
    .act-row:last-child { border-bottom:none; }
    .act-label { font-size:.875rem; font-weight:700; color:var(--te-text); }
    .act-sub { font-size:.75rem; color:var(--te-text-muted); margin-top:.1rem; }

    /* ── Sidebar stat row ───────────────────────────────────────── */
    .sb-stat-row { display:flex; align-items:center; justify-content:space-between; padding:.6rem 0; border-bottom:1px solid var(--te-border-subtle); }
    .sb-stat-row:last-child { border-bottom:none; }
    .sb-stat-label { font-size:.78rem; font-weight:600; color:var(--te-text-muted); }
    .sb-stat-val { font-size:.85rem; font-weight:800; color:var(--te-text); }

    /* ── Modal ──────────────────────────────────────────────────── */
    .modal-bg.open { display:flex; }
    .modal-box { background:var(--te-surface); border-radius:var(--r); width:100%; max-width:420px; border:1.5px solid var(--te-border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
    .modal-title { font-size:1rem; font-weight:800; color:var(--te-text); margin-bottom:.2rem; }
    .modal-msg { font-size:.85rem; color:var(--te-text-muted); line-height:1.55; margin:0; }
    .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--te-border); background:var(--te-surface); font-size:.85rem; font-weight:600; color:var(--te-text-muted); cursor:pointer; transition:all var(--tr); font-family:inherit; }
    .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:var(--te-on-primary); cursor:pointer; font-family:inherit; transition:opacity var(--tr); }

    /* ── Animations ─────────────────────────────────────────────── */
    @keyframes fadeUp { from{opacity:0;transform:translateY(14px);} to{opacity:1;transform:none;} }
    .show-anim   { animation:fadeUp .35s ease both; }
    </style>

    <div class="show-page">

        {{-- ══════════════  LEFT COLUMN  ══════════════ --}}
        <div>

            {{-- ── Hero Banner ── --}}
            <div class="show-hero show-anim">
                <div class="show-hero-banner">
                    <div class="show-hero-top">
                        @if($user->isCompany())
                            <x-company-logo :company="$user" :size="72" />
                        @else
                            <div class="show-hero-logo-ph" aria-hidden="true">{{ $initial }}</div>
                        @endif
                        <div class="show-hero-info">
                            <div class="show-hero-subtitle">{{ $roleLabel }}</div>
                            <h1 class="show-hero-title">{{ $user->name }}</h1>
                        </div>
                    </div>
                    <div class="show-hero-tags">
                        {{-- Role badge --}}
                        @if($user->isAdmin())
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Administrateur
                            </span>
                        @elseif($user->isCompany())
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                Entreprise
                            </span>
                        @else
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Candidat
                            </span>
                        @endif
                        {{-- Validation badge --}}
                        @if($user->is_validated)
                            <span class="show-tag show-tag-hero">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Compte validé
                            </span>
                        @else
                            <span class="show-tag" style="background:rgba(251,191,36,.2);border-color:rgba(251,191,36,.4);color:var(--te-hero-accent);">
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                En attente de validation
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
                            <span>{{ $user->email }}</span>
                        </div>
                        @if($user->city)
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>{{ $user->city }}</span>
                        </div>
                        @endif
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Inscrit le {{ $user->created_at->translatedFormat('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Profile Info Card ── --}}
            <div class="show-card show-anim show-anim-d1">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-primary-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2>Informations du profil</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Nom complet</div>
                                <div class="show-detail-chip-value">{{ $user->name }}</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Email</div>
                                <div class="show-detail-chip-value" style="font-size:.78rem;word-break:break-all;">{{ $user->email }}</div>
                            </div>
                        </div>

                        @if($user->phone)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Téléphone</div>
                                <div class="show-detail-chip-value">{{ $user->phone }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->city)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Ville</div>
                                <div class="show-detail-chip-value">{{ $user->city }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCompany() && $user->company_size)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon amber">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Taille entreprise</div>
                                <div class="show-detail-chip-value">{{ $user->company_size }} employés</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCandidate() && $user->experience_years !== null)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Expérience</div>
                                <div class="show-detail-chip-value">{{ \App\Support\Experience::label($user->experience_years, 'candidate') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCandidate() && $user->education_level)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue"><x-education-icon :level="$user->education_level" :size="16" />
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Niveau d'études</div>
                                <div class="show-detail-chip-value">{{ \App\Support\Education::label($user->education_level) }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->isCandidate() && $user->domain)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Domaine</div>
                                <div class="show-detail-chip-value">{{ $user->domain }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon slate">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Inscription</div>
                                <div class="show-detail-chip-value">{{ $user->created_at->translatedFormat('d/m/Y') }}</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon slate">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Mise à jour</div>
                                <div class="show-detail-chip-value">{{ $user->updated_at->translatedFormat('d/m/Y') }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Recent Offers (company) ── --}}
            @if($user->isCompany() && $recentOffers->count() > 0)
            <div class="show-card show-anim show-anim-d2">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-info-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-info-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2>Offres récentes <span style="font-size:.75rem;font-weight:600;color:var(--te-text-muted);padding:.15rem .5rem;background:var(--te-surface-2);border:1px solid var(--te-border);border-radius:99px;">{{ $user->job_offers_count }}</span></h2>
                </div>
                <div class="show-card-body">
                    @foreach($recentOffers as $offer)
                    @php $stMap = ['open'=>['var(--te-success-soft)', 'var(--te-success-fg)', 'var(--te-success-border)','Ouverte'],'closed'=>['var(--te-danger-soft)', 'var(--te-danger-fg)', 'var(--te-danger-border)','Clôturée'],'pending_validation'=>['var(--te-warning-soft)', 'var(--te-warning-fg)', 'var(--te-warning-border)','En attente'],'refused'=>['var(--te-danger-soft)', 'var(--te-danger-fg)', 'var(--te-danger-border)','Refusée']]; $st = $stMap[$offer->status] ?? ['var(--te-surface-2)', 'var(--te-text-2)', 'var(--te-border)',ucfirst($offer->status)]; @endphp
                    <div class="mini-row">
                        <x-category-icon :category="$offer->offerCategory" :size="18" :round="36" />
                        <div style="flex:1;min-width:0;">
                            <div class="mini-title">{{ $offer->title }}</div>
                            <div class="mini-sub">{{ $offer->applications_count }} candidature(s) · {{ $offer->created_at->translatedFormat('d/m/Y') }}</div>
                        </div>
                        <span class="mini-badge" style="background:{{ $st[0] }};color:{{ $st[1] }};border-color:{{ $st[2] }};">{{ $st[3] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Recent Applications (candidate) ── --}}
            @if($user->isCandidate() && $recentApplications->count() > 0)
            <div class="show-card show-anim show-anim-d2">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-info-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-info-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2>Candidatures récentes <span style="font-size:.75rem;font-weight:600;color:var(--te-text-muted);padding:.15rem .5rem;background:var(--te-surface-2);border:1px solid var(--te-border);border-radius:99px;">{{ $user->applications_count }}</span></h2>
                </div>
                <div class="show-card-body">
                    @foreach($recentApplications as $app)
                    @php $aMap = ['pending'=>['var(--te-warning-soft)', 'var(--te-warning-fg)', 'var(--te-warning-border)','En attente'],'accepted'=>['var(--te-success-soft)', 'var(--te-success-fg)', 'var(--te-success-border)','Acceptée'],'rejected'=>['var(--te-danger-soft)', 'var(--te-danger-fg)', 'var(--te-danger-border)','Refusée']]; $am = $aMap[$app->status] ?? ['var(--te-surface-2)', 'var(--te-text-2)', 'var(--te-border)',ucfirst($app->status)]; @endphp
                    <div class="mini-row">
                        <x-company-logo :company="$app->jobOffer->company" :size="36" />
                        <div style="flex:1;min-width:0;">
                            <div class="mini-title">{{ $app->jobOffer->title }}</div>
                            <div class="mini-sub">{{ $app->jobOffer->company->name }} · {{ $app->created_at->translatedFormat('d/m/Y') }}</div>
                        </div>
                        <span class="mini-badge" style="background:{{ $am[0] }};color:{{ $am[1] }};border-color:{{ $am[2] }};">{{ $am[3] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
        {{-- end left column --}}

        {{-- ══════════════  SIDEBAR  ══════════════ --}}
        <aside class="show-sidebar show-anim show-anim-d2">

            {{-- Statistics card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-primary-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h2>Statistiques</h2>
                </div>
                <div class="show-card-body" style="padding-top:1rem;padding-bottom:1rem;">
                    @if($user->isCompany())
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Offres publiées</span>
                        <span class="sb-stat-val" style="color:var(--te-primary-fg);">{{ $user->job_offers_count }}</span>
                    </div>
                    @endif
                    @if($user->isCandidate())
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Candidatures</span>
                        <span class="sb-stat-val" style="color:var(--te-primary-fg);">{{ $user->applications_count }}</span>
                    </div>
                    @endif
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Validation</span>
                        <span class="sb-stat-val" style="color:{{ $user->is_validated ? 'var(--te-success-fg)' : 'var(--te-warning-fg)' }};">
                            {{ $user->is_validated ? 'Validé' : 'Non validé' }}
                        </span>
                    </div>
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Rôle</span>
                        <span class="sb-stat-val">{{ $roleLabel }}</span>
                    </div>
                    <div class="sb-stat-row">
                        <span class="sb-stat-label">Membre depuis</span>
                        <span class="sb-stat-val">{{ $user->created_at->translatedFormat('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions card (non-admins only) --}}
            @if(!$user->isAdmin())
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-success-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-success-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    <h2>Actions</h2>
                </div>
                <div class="show-card-body" style="display:flex;flex-direction:column;gap:.75rem;padding-top:1rem;padding-bottom:1rem;">

                    @if(!$user->is_validated)
                    <div class="act-row">
                        <div>
                            <div class="act-label">Validation du compte</div>
                            <div class="act-sub">Activer l'accès à la plateforme</div>
                        </div>
                        <form method="POST" action="{{ route('admin.users.status', $user) }}"
                              class="js-user-form" data-variant="validate" data-name="{{ $user->name }}"
                              style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_validated" value="1">
                            <button type="submit" class="show-btn-solid" style="padding:.45rem .9rem;font-size:.78rem;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                Valider
                            </button>
                        </form>
                    </div>
                    @endif

                    <div class="act-row">
                        <div>
                            <div class="act-label">{{ $user->is_blocked ? 'Compte bloqué' : 'Accès' }}</div>
                            <div class="act-sub">{{ $user->is_blocked ? 'Réactiver l\'accès' : 'Empêcher la connexion' }}</div>
                        </div>
                        <form method="POST" action="{{ route('admin.users.status', $user) }}"
                              class="js-user-form"
                              data-variant="{{ $user->is_blocked ? 'unblock' : 'block' }}"
                              data-name="{{ $user->name }}"
                              style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_blocked" value="{{ $user->is_blocked ? '0' : '1' }}">
                            @if($user->is_blocked)
                                <button type="submit" class="show-btn-success" style="padding:.45rem .9rem;font-size:.78rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                    Débloquer
                                </button>
                            @else
                                <button type="submit" class="show-btn-danger" style="padding:.45rem .9rem;font-size:.78rem;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    Bloquer
                                </button>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
            @endif

        </aside>

    </div>

    {{-- Confirm modal --}}
    <div id="us-modal" class="modal-bg" role="dialog" aria-modal="true">
        <div class="modal-box">
            <div class="modal-hd">
                <div class="modal-ico" id="us-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="us-modal-title"></h3>
                    <p class="modal-msg" id="us-modal-msg"></p>
                </div>
            </div>
            <div class="modal-ft">
                <button class="modal-cancel" id="us-modal-cancel">Annuler</button>
                <button class="modal-ok" id="us-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var modal=document.getElementById('us-modal'),ico=document.getElementById('us-modal-ico'),
            title=document.getElementById('us-modal-title'),msg=document.getElementById('us-modal-msg'),
            btnOk=document.getElementById('us-modal-ok'),btnNo=document.getElementById('us-modal-cancel'),
            pending=null;
        var configs={
            validate:{color:'var(--te-primary-fg)',bg:'var(--te-primary-soft)',icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',title:'Valider ce compte ?',msg:'L\'utilisateur pourra accéder à la plateforme.',btn:'Valider'},
            block:{color:'var(--te-danger-fg)',bg:'var(--te-danger-soft)',icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',title:'Bloquer cet utilisateur ?',msg:'Il ne pourra plus se connecter à la plateforme.',btn:'Bloquer'},
            unblock:{color:'var(--te-success-fg)',bg:'var(--te-success-soft)',icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',title:'Débloquer cet utilisateur ?',msg:'Il pourra de nouveau accéder à la plateforme.',btn:'Débloquer'},
        };
        function open(form,variant,name){
            pending=form;var c=configs[variant]||configs.block;
            ico.style.background=c.bg;
            ico.innerHTML='<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + c.color + '" stroke-width="2" viewBox="0 0 24 24">'+c.icon+'</svg>';
            title.textContent=c.title;msg.textContent='"'+name+'" : '+c.msg;
            btnOk.textContent=c.btn;btnOk.style.background=c.color;
            modal.classList.add('open');document.body.style.overflow='hidden';
        }
        function close(){pending=null;modal.classList.remove('open');document.body.style.overflow='';}
        btnNo.onclick=close;btnOk.onclick=function(){if(pending)pending.submit();};
        modal.onclick=function(e){if(e.target===modal)close();};
        document.addEventListener('keydown',function(e){if(e.key==='Escape')close();});
        document.querySelectorAll('.js-user-form').forEach(function(f){
            f.onsubmit=function(e){e.preventDefault();open(f,f.dataset.variant,f.dataset.name);};
        });
    })();
    </script>
</x-app-layout>
