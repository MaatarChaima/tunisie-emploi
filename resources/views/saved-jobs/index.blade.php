<x-app-layout>
    <x-slot name="title">Offres sauvegardées</x-slot>

    <x-slot name="header">
        <x-page-header title="Mes offres sauvegardées" subtitle="Retrouvez toutes vos offres mises de côté" icon="bookmark"
        >
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:var(--te-primary);color:var(--te-on-primary);font-size:.75rem;font-weight:700;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                {{ $savedJobs->total() }} offre{{ $savedJobs->total() > 1 ? 's' : '' }}
            </span>
        </x-page-header>
    </x-slot>

    <style>
        /* === UNIFIED DESIGN TOKENS === */
        :root {
            --pr:   rgba(79,70,229,.12);
            --r:    12px;
            --shd:  0 1px 3px rgba(0,0,0,.07), 0 2px 4px rgba(0,0,0,.04);
            --shd-h:0 8px 30px rgba(79,70,229,.11);
            --ease: .18s cubic-bezier(.4,0,.2,1);
        }
        .pg { max-width:80rem; margin:0 auto; padding:2rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }
        .pg-2col { display:flex; gap:2rem; align-items:flex-start; }
        @media(max-width:1024px){ .pg-2col { flex-direction:column; } }
        .flt-sidebar { width:244px; flex-shrink:0; position:sticky; top:80px; z-index:10; }
        @media(max-width:1024px){ .flt-sidebar { width:100%; position:static; } }
        .flt-card { background:var(--te-surface); border:1px solid var(--te-border); border-radius:var(--r); overflow:hidden; box-shadow:var(--shd); }
        .flt-head { display:flex; align-items:center; justify-content:space-between; padding:.875rem 1rem; background:var(--te-primary); }
        .flt-head-l { display:flex; align-items:center; gap:.5rem; }
        .flt-head-l svg { color:var(--te-on-primary); flex-shrink:0; }
        .flt-head-title { font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.95); }
        .flt-count { font-size:.75rem; font-weight:800; padding:.15rem .5rem; border-radius:99px; background:rgba(255,255,255,.2); color:var(--te-on-primary); }
        .flt-count.on { background:var(--te-surface); color:var(--te-primary-fg); }
        .flt-active-tags { display:flex; flex-wrap:wrap; gap:.3rem; padding:.6rem 1rem .2rem; border-top:1px solid var(--te-border-subtle); }
        .flt-tag { display:inline-flex; align-items:center; gap:.25rem; padding:.18rem .55rem; border-radius:99px; font-size:.75rem; font-weight:600; background:var(--te-primary-soft); color:var(--te-primary-fg); border:1px solid var(--te-primary-border); }
        .flt-tag a { color:inherit; opacity:.6; text-decoration:none; }
        .flt-tag a:hover { opacity:1; }
        .flt-body { padding:.875rem 1rem; display:flex; flex-direction:column; gap:0; }
        .flt-field { padding:.6rem 0; border-bottom:1px solid var(--te-border-subtle); }
        .flt-field:last-of-type { border-bottom:none; }
        .flt-lbl { display:flex; align-items:center; gap:.35rem; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--te-text-muted); margin-bottom:.4rem; }
        .flt-lbl svg { color:var(--te-text-muted); flex-shrink:0; }
        .flt-sel, .flt-inp { width:100%; padding:.48rem .7rem; border:1.5px solid var(--te-border); border-radius:8px; font-size:.8rem; font-family:inherit; font-weight:500; color:var(--te-text); background:var(--te-surface-2); outline:none; appearance:none; cursor:pointer; transition:border-color var(--ease),box-shadow var(--ease),background var(--ease); }
        .flt-inp { appearance:auto; cursor:text; }
        .flt-sel:focus,.flt-inp:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px var(--pr); background:var(--te-surface); }
        .flt-sel.on,.flt-inp.on { border-color:var(--te-primary-border); background:var(--te-primary-soft); color:var(--te-primary-fg); font-weight:600; }
        .flt-acts { display:flex; flex-direction:column; gap:.4rem; padding-top:.7rem; }
        .flt-apply { display:flex; align-items:center; justify-content:center; gap:.4rem; padding:.58rem; background:var(--te-primary); color:var(--te-on-primary); border:none; border-radius:8px; font-family:inherit; font-size:.8rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease); }
        .flt-apply:hover { background:var(--te-primary-hover); transform:translateY(-1px); }
        .flt-reset { display:flex; align-items:center; justify-content:center; gap:.4rem; padding:.53rem; background:var(--te-surface); color:var(--te-text-muted); border:1.5px solid var(--te-border); border-radius:8px; font-family:inherit; font-size:.8rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all var(--ease); }
        .flt-reset:hover { border-color:var(--te-primary); color:var(--te-primary-fg); background:var(--te-primary-soft); }
        .pg-main { flex:1; min-width:0; }
        .res-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:.75rem; }
        .res-count { font-size:.875rem; font-weight:600; color:var(--te-text-muted); }
        .res-count strong { color:var(--te-primary-fg); font-weight:800; }
        .chip-row { display:flex; flex-wrap:wrap; gap:.4rem; }
        .chip-active { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .7rem; border-radius:99px; font-size:.75rem; font-weight:600; background:var(--te-primary-soft); color:var(--te-primary-fg); border:1px solid var(--te-primary-border); }
        .chip-active a { color:inherit; text-decoration:none; opacity:.6; }
        .chip-active a:hover { opacity:1; }
        .grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
        @media(max-width:860px){ .grid-2 { grid-template-columns:1fr; } }
        .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:1.125rem; }
        @media(max-width:1100px){ .grid-3 { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px){ .grid-3 { grid-template-columns:1fr; } }
        .uc-logo { width:46px; height:46px; border-radius:11px; border:1.5px solid var(--te-border); object-fit:cover; flex-shrink:0; background:var(--te-surface); }
        .uc-logo-ph { width:46px; height:46px; border-radius:11px; background:var(--te-primary-soft); border:1.5px solid var(--te-primary-border); display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:800; color:var(--te-primary-fg); flex-shrink:0; }
        .uc-co-row { display:flex; align-items:flex-start; gap:.875rem; margin-bottom:.75rem; }
        .uc-co-info { flex:1; min-width:0; }
        .uc-co-name { font-size:.8rem; font-weight:700; color:var(--te-primary-fg); line-height:1.25; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .uc-co-loc { display:flex; align-items:center; gap:.3rem; font-size:.75rem; color:var(--te-text-muted); margin-top:.2rem; }
        .uc-co-loc svg { flex-shrink:0; }
        .uc-title { font-size:.975rem; font-weight:800; color:var(--te-text); text-decoration:none; display:block; line-height:1.4; transition:color var(--ease); margin:.5rem 0; }
        .uc-title:hover { color:var(--te-primary-fg); }
        span.uc-title:hover { color:var(--te-text); }
        .uc-unavailable { display:inline-block; margin:-.1rem 0 .5rem; padding:.15rem .55rem; border-radius:6px; background:var(--te-surface-3); border:1px solid var(--te-border); color:var(--te-text-2); font-size:.75rem; font-weight:700; }
        .uc-desc { font-size:.78rem; color:var(--te-text-muted); line-height:1.55; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; margin:.25rem 0; }
        .uc-pill.gr { background:var(--te-success-soft); color:var(--te-success-fg); border-color:var(--te-success-border); }
        .uc-pill.rd { background:var(--te-danger-soft); color:var(--te-danger-fg); border-color:var(--te-danger-border); }
        .uc-date { font-size:.75rem; color:var(--te-text-muted); display:flex; align-items:center; gap:.25rem; }
        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--te-primary); color:var(--te-on-primary); border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--te-primary-hover); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .btn-d { display:inline-flex; align-items:center; gap:.4rem; padding:.43rem .875rem; background:var(--te-surface); color:var(--te-danger-fg); border:1.5px solid var(--te-danger-border); border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .btn-d:hover { background:var(--te-danger-soft); border-color:var(--te-danger-solid); }
        .btn-i.ok:hover { border-color:var(--te-success-border); color:var(--te-success-fg); background:var(--te-success-soft); }
        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .75rem; border-radius:99px; font-size:.75rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .empty-box { grid-column:1/-1; background:var(--te-surface); border:2px dashed var(--te-border); border-radius:var(--r); padding:3.5rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--te-primary-soft); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box p { font-size:.85rem; color:var(--te-text-muted); margin-bottom:1.25rem; max-width:300px; margin-left:auto; margin-right:auto; line-height:1.6; }
        .tbl-card-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-bottom:1px solid var(--te-border); background:var(--te-surface-2); flex-wrap:wrap; gap:.5rem; }
        .tbl-card-count { font-size:.875rem; font-weight:700; color:var(--te-text-muted); }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl thead tr { border-bottom:1px solid var(--te-border); background:var(--te-surface-2); }
        .tbl th { padding:.875rem 1.25rem; text-align:left; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); white-space:nowrap; }
        .tbl tbody tr { border-bottom:1px solid var(--te-border); transition:background var(--ease); }
        .tbl tbody tr:hover { background:var(--te-primary-soft); }
        .tbl td { padding:1rem 1.25rem; vertical-align:middle; font-size:.875rem; color:var(--te-text); }
        .tbl-title { font-weight:800; color:var(--te-text); text-decoration:none; display:block; transition:color var(--ease); font-size:.9rem; }
        .tbl-sub { font-size:.78rem; color:var(--te-text-muted); margin-top:.2rem; display:flex; align-items:center; gap:.3rem; }
        .stats-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:1rem; margin-bottom:1.5rem; }
        .stat-c { background:var(--te-surface); border:1px solid var(--te-border); border-radius:var(--r); padding:1.25rem 1.5rem; box-shadow:var(--shd); }
        .stat-l { font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); margin-bottom:.3rem; }
        .stat-v { font-size:1.75rem; font-weight:800; color:var(--te-primary-fg); line-height:1; }
        .pgn { padding:1.25rem 1.5rem; border-top:1px solid var(--te-border); }
        .cand-avatar { width:44px; height:44px; border-radius:10px; background:var(--te-primary-soft); border:1.5px solid var(--te-primary-border); display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:800; color:var(--te-primary-fg); flex-shrink:0; }
        .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal { background:var(--te-surface); border-radius:16px; width:100%; max-width:420px; border:1.5px solid var(--te-border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
        .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--te-border); }
        .modal-title { font-size:1rem; font-weight:800; color:var(--te-text); margin-bottom:.2rem; }
        .modal-msg { font-size:.85rem; color:var(--te-text-muted); line-height:1.55; margin:0; }
        .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--te-border); background:var(--te-surface); font-size:.85rem; font-weight:600; color:var(--te-text-muted); cursor:pointer; transition:all var(--ease); font-family:inherit; }
        .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:var(--te-on-primary); cursor:pointer; transition:opacity var(--ease); font-family:inherit; }

        /* Toast */
        .sv-toast { position:fixed; bottom:24px; right:24px; background:var(--te-success-solid); color:var(--te-on-primary); padding:.75rem 1.25rem; border-radius:12px; font-size:.85rem; font-weight:600; box-shadow:0 8px 20px rgba(0,0,0,.15); z-index:999; display:flex; align-items:center; gap:.6rem; opacity:0; transform:translateY(12px); transition:all .3s; pointer-events:none; }
        .sv-toast.show { opacity:1; transform:translateY(0); }
        .uc-cat { display:inline-flex;align-items:center;gap:.3rem;padding:.12rem .55rem .12rem .2rem;border-radius:99px;font-size:.75rem;font-weight:700;background:var(--te-primary-soft);color:var(--te-primary-fg);border:1px solid var(--te-primary-border);letter-spacing:.02em; }
    </style>

    <div class="pg">

        {{-- Results bar --}}
        <div class="res-bar">
            <p class="res-count">
                <strong>{{ $savedJobs->total() }}</strong>
                offre{{ $savedJobs->total() > 1 ? 's' : '' }} sauvegardée{{ $savedJobs->total() > 1 ? 's' : '' }}
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid-3" id="saved-grid">
            @forelse($savedJobs as $savedJob)
                @php
                    $typeLabels = [
                        'full-time'  => ['label' => 'Temps plein',   'cls' => 'gr'],
                        'part-time'  => ['label' => 'Temps partiel', 'cls' => 'am'],
                        'remote'     => ['label' => 'Télétravail',   'cls' => 'hi'],
                        'freelance'  => ['label' => 'Freelance',     'cls' => 'hi'],
                        'internship' => ['label' => 'Stage',         'cls' => ''],
                    ];
                    $tm = $typeLabels[$savedJob->jobOffer->type] ?? ['label' => ucfirst($savedJob->jobOffer->type), 'cls' => ''];
                    $initial = strtoupper(substr($savedJob->jobOffer->company->name, 0, 1));
                    // Offre archivée, clôturée, refusée ou expirée : plus de lien, badge « Plus disponible »
                    $available = $savedJob->jobOffer->isPubliclyVisible();
                @endphp

                <div class="uc {{ $available ? '' : 'is-unavailable' }}" data-job-id="{{ $savedJob->jobOffer->id }}"
                     @if($available)
                     style="cursor:pointer;"
                     onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('job-offers.show', $savedJob->jobOffer) }}'"
                     @endif>
                    <div class="uc-body">
                        <div class="uc-co-row">
                            <x-company-logo :company="$savedJob->jobOffer->company" :size="44" />
                            <div class="uc-co-info">
                                <div class="uc-co-name">{{ $savedJob->jobOffer->company->name }}</div>
                                <div class="uc-co-loc">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $savedJob->jobOffer->location }}
                                </div>
                            </div>
                        </div>

                        @if($available)
                            <a href="{{ route('job-offers.show', $savedJob->jobOffer) }}" class="uc-title">
                                {{ $savedJob->jobOffer->title }}
                            </a>
                        @else
                            <span class="uc-title">{{ $savedJob->jobOffer->title }}</span>
                            <span class="uc-unavailable">Plus disponible</span>
                        @endif

                        @if($savedJob->jobOffer->description)
                            <div class="uc-desc">
                                {{ Str::limit(strip_tags($savedJob->jobOffer->description), 110) }}
                            </div>
                        @endif

                        <div class="uc-meta">
                            @if(optional($savedJob->jobOffer->offerCategory)->name)
                                <span class="uc-cat">
                                    <x-category-icon :category="$savedJob->jobOffer->offerCategory" :size="13" class="uc-cat-ico" />
                                    {{ $savedJob->jobOffer->offerCategory->name }}
                                </span>
                            @endif
                            <span class="uc-pill {{ $tm['cls'] }}">
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $tm['label'] }}
                            </span>
                            @if($savedJob->jobOffer->salary)
                                <span class="uc-pill hi">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $savedJob->jobOffer->salaryLabel() }}
                                </span>
                            @endif
                            @if($savedJob->jobOffer->expiration_date)
                                <span class="uc-pill">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $savedJob->jobOffer->deadlineLabel() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="uc-foot">
                        <span class="uc-date">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $savedJob->created_at->translatedFormat('d/m/Y') }}
                        </span>
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            @if($available)
                            <a href="{{ route('job-offers.show', $savedJob->jobOffer) }}" class="btn-p">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Voir l'offre
                            </a>
                            @endif
                            <form method="POST" action="{{ route('saved-jobs.toggle', $savedJob->jobOffer) }}"
                                  class="unsave-form" style="display:contents;">
                                @csrf
                                <button type="submit" class="btn-d">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="3 6 5 6 21 6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 6l-1 14H6L5 6"/>
                                    </svg>
                                    Retirer des favoris
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <div class="empty-box">
                    <div class="empty-ico">
                        <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </div>
                    <h3>Aucune offre sauvegardée</h3>
                    <p>Commencez à sauvegarder les offres qui vous intéressent pour les retrouver facilement.</p>
                    <a href="{{ route('job-offers.index') }}" class="btn-p" style="display:inline-flex;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
                        </svg>
                        Découvrir les offres
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($savedJobs->hasPages())
            <div style="margin-top:2rem;display:flex;justify-content:center;">
                {{ $savedJobs->links() }}
            </div>
        @endif
    </div>

    {{-- Toast --}}
    <div id="sv-toast" class="sv-toast">
        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Offre retirée des favoris
    </div>

    <script>
    (function () {
        function showToast(msg) {
            const t = document.getElementById('sv-toast');
            t.childNodes[t.childNodes.length - 1].textContent = ' ' + msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        document.querySelectorAll('.unsave-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const card  = this.closest('.uc');
                const token = this.querySelector('[name="_token"]').value;
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.status === 'removed') {
                        card.style.transition = 'all .3s cubic-bezier(.4,0,.2,1)';
                        card.style.opacity    = '0';
                        card.style.transform  = 'translateY(-8px) scale(.97)';
                        setTimeout(function () {
                            card.remove();
                            const grid = document.getElementById('saved-grid');
                            if (!grid.querySelector('.uc')) {
                                grid.innerHTML = '<div class="empty-box"><div class="empty-ico"><svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-primary-fg)"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg></div><h3>Aucune offre sauvegardée</h3><p>Parcourez les offres disponibles pour en sauvegarder de nouvelles.</p><a href="{{ route('job-offers.index') }}" class="btn-p" style="display:inline-flex;margin-top:.5rem;">Découvrir les offres</a></div>';
                            }
                            showToast('Offre retirée des favoris');
                        }, 300);
                    }
                })
                .catch(function () { showToast('Une erreur est survenue'); });
            });
        });
    })();
    </script>
</x-app-layout>
