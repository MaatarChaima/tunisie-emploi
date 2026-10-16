<x-app-layout>
    <x-slot name="title">Mes offres</x-slot>

    <x-slot name="header">
        <x-page-header title="Mes offres d'emploi" subtitle="Gérez vos offres publiées" icon="briefcase"
        >
            <a href="{{ route('job-offers.create') }}"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.1rem;background:var(--te-primary);color:var(--te-on-primary);border-radius:8px;font-size:.82rem;font-weight:700;text-decoration:none;transition:background .15s;"
               onmouseover="this.style.background='var(--te-primary-hover)'" onmouseout="this.style.background='var(--te-primary)'">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Publier une offre
            </a>
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
        .kpi-band { list-style:none; margin:0 0 1.25rem; padding:0; display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); background:var(--te-surface); border:1px solid var(--te-border); border-radius:var(--r); overflow:hidden; }
        .kpi-band li + li { border-left:1px solid var(--te-border); }
        .kpi { display:flex; align-items:baseline; gap:.6rem; padding:.85rem 1.25rem; text-decoration:none; height:100%; }
        .kpi:hover { background:var(--te-surface-2); }
        .kpi-v { font-size:1.5rem; font-weight:800; color:var(--te-text); line-height:1; font-variant-numeric:tabular-nums; }
        .kpi-l { font-size:.8125rem; font-weight:600; color:var(--te-text-muted); }
        @media (max-width:767px) {
            .kpi-band { grid-template-columns:repeat(2, minmax(0,1fr)); }
            .kpi-band li:nth-child(3) { border-left:none; }
            .kpi-band li:nth-child(n+3) { border-top:1px solid var(--te-border); }
        }
        /* Infobulle des boutons d'action (survol et clavier) */
        .tip { position:relative; }
        .tip::after { content:attr(data-tip); position:absolute; top:50%; right:calc(100% + 8px); z-index:20; width:max-content; max-width:16rem; padding:.35rem .55rem; border-radius:6px; background:var(--te-text); color:var(--te-surface); font-size:.75rem; font-weight:600; line-height:1.35; white-space:normal; text-align:left; opacity:0; pointer-events:none; transform:translate(4px,-50%); transition:opacity .12s, transform .12s; }
        .tip:hover::after, .tip:focus-visible::after { opacity:1; transform:translate(0,-50%); }
        .btn-txt { display:inline-flex; align-items:center; gap:.35rem; height:32px; padding:0 .7rem; border-radius:8px; border:1.5px solid var(--te-success-border); background:var(--te-success-soft); color:var(--te-success-fg); font:inherit; font-size:.8125rem; font-weight:700; cursor:pointer; }
        .btn-txt:hover { border-color:var(--te-success-solid); }
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
        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .75rem; border-radius:99px; font-size:.75rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .co-logo { width:34px;height:34px;border-radius:9px;border:1.5px solid var(--te-border);object-fit:cover;flex-shrink:0;background:var(--te-surface); }
        .co-logo-ph { width:34px;height:34px;border-radius:9px;background:var(--te-primary-soft);border:1.5px solid var(--te-primary-border);display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:800;color:var(--te-primary-fg);flex-shrink:0; }
        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--te-primary); color:var(--te-on-primary); border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--te-primary-hover); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .btn-i.ok { border-color:var(--te-info-border); color:var(--te-info-fg); background:var(--te-info-soft); }
        .btn-i.ok:hover { border-color:var(--te-info-solid); color:var(--te-info-fg); background:var(--te-info-soft); }
        .pgn { padding:1.25rem 1.5rem; border-top:1px solid var(--te-border); }
        .empty-box { padding:4rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--te-primary-soft); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box p { font-size:.85rem; color:var(--te-text-muted); margin-bottom:1.25rem; max-width:300px; margin-left:auto; margin-right:auto; line-height:1.6; }
        .uc-cat { display:inline-flex;align-items:center;gap:.3rem;padding:.12rem .55rem .12rem .2rem;border-radius:99px;font-size:.75rem;font-weight:700;background:var(--te-primary-soft);color:var(--te-primary-fg);border:1px solid var(--te-primary-border);letter-spacing:.02em; }

        /* app badge */
        .app-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .75rem; border-radius:99px; font-size:.75rem; font-weight:700; background:var(--te-primary-soft); color:var(--te-primary-fg); border:1px solid var(--te-primary-border); text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .app-badge:hover { background:var(--te-primary); color:var(--te-on-primary); border-color:var(--te-primary); }

        /* Sortable headers */
        .th-sort { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; text-decoration:none; color:inherit; white-space:nowrap; user-select:none; }
        .th-sort:hover { color:var(--te-primary-fg); }
        .th-sort svg { opacity:.45; transition:opacity var(--ease); flex-shrink:0; }
        .th-sort:hover svg, .th-sort.active svg { opacity:1; }

        /* Status dropdown */
        .th-status { position:relative; }
        .th-status-btn { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; background:none; border:none; font:inherit; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); padding:0; user-select:none; white-space:nowrap; }
        .th-status-btn:hover { color:var(--te-primary-fg); }
        .th-status-btn svg { opacity:.45; transition:opacity var(--ease); }
        .th-status-btn:hover svg, .th-status-btn.active svg { opacity:1; }
        .status-drop { display:none; position:absolute; top:calc(100% + 4px); right:0; z-index:200; background:var(--te-surface); border:1.5px solid var(--te-border); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.12); min-width:160px; overflow:hidden; }
        .status-drop.open { display:block; }
        .status-drop a { display:flex; align-items:center; gap:.55rem; padding:.58rem .9rem; font-size:.8rem; font-weight:600; color:var(--te-text); text-decoration:none; transition:background var(--ease); white-space:nowrap; }
        .status-drop a:hover { background:var(--te-surface-2); }
        .status-drop a.active { background:var(--te-primary-soft); color:var(--te-primary-fg); font-weight:700; }
        .status-drop-sep { height:1px; background:var(--te-border); }
        .sd-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }

        /* Modal */
        .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal { background:var(--te-surface); border-radius:16px; width:100%; max-width:420px; border:1.5px solid var(--te-border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
        .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--te-border); }
        .modal-title { font-size:1rem; font-weight:800; color:var(--te-text); margin-bottom:.2rem; }
        .modal-msg { font-size:.85rem; color:var(--te-text-muted); line-height:1.55; margin:0; }
        .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--te-border); background:var(--te-surface); font-size:.85rem; font-weight:600; color:var(--te-text-muted); cursor:pointer; transition:all var(--ease); font-family:inherit; }
        .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:var(--te-on-primary); cursor:pointer; transition:opacity var(--ease); font-family:inherit; }
    </style>

    <div class="pg">

        {{-- Indicateurs --}}
        <ul class="kpi-band" aria-label="Résumé de vos offres">
            @foreach([
                ['n' => $summaryStats['active_count'],       'label' => 'Actives',                    'url' => route('job-offers.my-offers', ['status' => 'open'])],
                ['n' => $summaryStats['pending_count'],      'label' => 'En attente de validation',   'url' => route('job-offers.my-offers', ['status' => 'pending_validation'])],
                ['n' => $summaryStats['archived_count'],     'label' => 'Archivées',                  'url' => route('job-offers.my-offers', ['status' => 'archived'])],
                ['n' => $summaryStats['applications_total'], 'label' => 'Candidatures reçues',        'url' => route('applications.index')],
            ] as $k)
                <li>
                    <a href="{{ $k['url'] }}" class="kpi">
                        <span class="kpi-v">{{ $k['n'] }}</span>
                        <span class="kpi-l">{{ $k['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Table card --}}
        <div class="tbl-card">
            <div class="tbl-card-head">
                <p class="tbl-card-count">
                    <strong>{{ $jobOffers->total() }}</strong>
                    offre{{ $jobOffers->total() > 1 ? 's' : '' }} publiée{{ $jobOffers->total() > 1 ? 's' : '' }}
                </p>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        @php
                            $nextDir  = fn($col) => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                            $sortUrl  = fn($col) => route('job-offers.my-offers', array_filter(['sort' => $col, 'dir' => $nextDir($col), 'status' => $status]));
                            $sortIco  = function($col) use ($sort, $dir) {
                                if ($sort !== $col) return '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                return $dir === 'asc'
                                    ? '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>'
                                    : '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>';
                            };
                            $statusUrl = fn($s) => route('job-offers.my-offers', array_filter(['sort' => $sort, 'dir' => $dir, 'status' => $s === $status ? null : $s]));
                            $statusOptions = [
                                null              => ['label' => 'Tous',       'dot' => 'var(--te-neutral-400)'],
                                'pending_validation' => ['label' => 'En attente', 'dot' => 'var(--te-warning-solid)'],
                                'refused'         => ['label' => 'Refusée',    'dot' => 'var(--te-danger-solid)'],
                                'open'            => ['label' => 'Ouverte',    'dot' => 'var(--te-success-solid)'],
                                'archived'        => ['label' => 'Archivée',   'dot' => 'var(--te-neutral-400)'],
                            ];
                        @endphp
                        <tr>
                            <th>
                                <a href="{{ $sortUrl('title') }}" class="th-sort {{ $sort === 'title' ? 'active' : '' }}">
                                    Titre de l'offre {!! $sortIco('title') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ $sortUrl('date') }}" class="th-sort {{ $sort === 'date' ? 'active' : '' }}">
                                    Publication {!! $sortIco('date') !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ $sortUrl('applications') }}" class="th-sort {{ $sort === 'applications' ? 'active' : '' }}">
                                    Candidatures {!! $sortIco('applications') !!}
                                </a>
                            </th>
                            <th class="th-status">
                                <button type="button" class="th-status-btn {{ $status ? 'active' : '' }}" onclick="document.getElementById('status-drop').classList.toggle('open');event.stopPropagation();">
                                    Statut
                                    @if($status)
                                        <span class="sd-dot" style="background:{{ $statusOptions[$status]['dot'] ?? 'var(--te-neutral-400)' }};"></span>
                                    @endif
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="status-drop" id="status-drop">
                                    @foreach($statusOptions as $val => $opt)
                                        @if(!is_null($val))
                                            <div class="status-drop-sep"></div>
                                        @endif
                                        <a href="{{ $statusUrl($val) }}" class="{{ $status === $val || ($val === null && !$status) ? 'active' : '' }}">
                                            <span class="sd-dot" style="background:{{ $opt['dot'] }};"></span>
                                            {{ $opt['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobOffers as $offer)
                            @php
                                $sc = [
                                    'open'               => ['label'=>'Ouverte',    'dot'=>'var(--te-success-solid)', 'bg'=>'var(--te-success-soft)', 'color'=>'var(--te-success-fg)', 'bd'=>'var(--te-success-border)'],
                                    'closed'             => ['label'=>'Clôturée',   'dot'=>'var(--te-danger-solid)', 'bg'=>'var(--te-danger-soft)', 'color'=>'var(--te-danger-fg)', 'bd'=>'var(--te-danger-border)'],
                                    'archived'           => ['label'=>'Archivée',   'dot'=>'var(--te-neutral-400)', 'bg'=>'var(--te-surface-2)', 'color'=>'var(--te-text-2)', 'bd'=>'var(--te-border)'],
                                    'pending_validation' => ['label'=>'En attente', 'dot'=>'var(--te-warning-solid)', 'bg'=>'var(--te-warning-soft)', 'color'=>'var(--te-warning-fg)', 'bd'=>'var(--te-warning-border)'],
                                    'refused'            => ['label'=>'Refusée',    'dot'=>'var(--te-danger-solid)', 'bg'=>'var(--te-danger-soft)', 'color'=>'var(--te-danger-fg)', 'bd'=>'var(--te-danger-border)'],
                                ][$offer->status] ?? ['label'=>ucfirst($offer->status),'dot'=>'var(--te-neutral-400)','bg'=>'var(--te-surface-2)','color'=>'var(--te-text-2)','bd'=>'var(--te-border)'];
                                // Offre ouverte mais date limite dépassée : n'apparaît plus dans la liste publique
                                if ($offer->status === 'open' && $offer->isExpired()) {
                                    $sc = ['label'=>'Expirée', 'dot'=>'var(--te-danger-solid)', 'bg'=>'var(--te-danger-soft)', 'color'=>'var(--te-danger-fg)', 'bd'=>'var(--te-danger-border)'];
                                }
                                $isArchived = $offer->status === 'archived';
                            @endphp
                            <tr style="cursor:pointer;"
                                onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('applications.index', ['job_offer_id' => $offer->id]) }}'">
                                <td>
                                    <div style="display:flex;align-items:flex-start;gap:.75rem;">
                                        {{-- Logo entreprise --}}
                                        <x-company-logo :company="$offer->company" :size="40" style="margin-top:.1rem;" />
                                        <div>
                                            <a href="{{ route('job-offers.show', $offer) }}"
                                               class="tbl-title">{{ $offer->title }}</a>
                                            <div class="tbl-sub">
                                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $offer->location }}
                                            </div>
                                            @if(optional($offer->offerCategory)->name)
                                                <span class="uc-cat" style="margin-top:.3rem;">
                                                    <x-category-icon :category="$offer->offerCategory" :size="13" class="uc-cat-ico" />
                                                    {{ $offer->offerCategory->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span style="font-size:.82rem;color:var(--te-text-muted);font-weight:500;">
                                        {{ $offer->created_at->translatedFormat('d/m/Y') }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('applications.index', ['job_offer_id' => $offer->id]) }}"
                                       class="app-badge">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                        {{ $offer->applications_count }} reçue{{ $offer->applications_count > 1 ? 's' : '' }}
                                    </a>
                                </td>

                                <td>
                                    <span class="stb"
                                          style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border-color:{{ $sc['bd'] }};">
                                        <span class="stb-dot" style="background:{{ $sc['dot'] }};"></span>
                                        {{ $sc['label'] }}
                                    </span>
                                </td>

                                <td>
                                    <div style="display:inline-flex;align-items:center;gap:.4rem;">
                                        <a href="{{ route('job-offers.show', $offer) }}"
                                           class="btn-i tip" aria-label="Voir l'offre « {{ $offer->title }} »" data-tip="Voir l'offre telle que la voient les candidats">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('job-offers.edit', $offer) }}"
                                           class="btn-i tip" aria-label="Modifier l'offre « {{ $offer->title }} »" data-tip="Modifier l'offre">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>

                                        @if($isArchived)
                                            <form method="POST" action="{{ route('job-offers.unarchive', $offer) }}"
                                                  class="js-restore-form" data-title="{{ $offer->title }}" style="display:contents;">
                                                @csrf
                                                <button type="submit" class="btn-txt tip" data-tip="Réactiver : l'offre sera de nouveau visible par les candidats">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    Réactiver
                                                </button>
                                            </form>
                                        @elseif($offer->status === 'open')
                                            <form method="POST" action="{{ route('job-offers.archive', $offer) }}"
                                                  class="js-archive-form" data-title="{{ $offer->title }}" style="display:contents;">
                                                @csrf
                                                <button type="submit" class="btn-i dg tip" aria-label="Archiver l'offre « {{ $offer->title }} »" data-tip="Archiver : l'offre ne sera plus visible par les candidats">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/>
                                                        <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                                        <line x1="10" y1="12" x2="14" y2="12" stroke-width="2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-box">
                                        <div class="empty-ico">
                                            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        @if($status)
                                            <h3>Aucune offre ne correspond aux filtres sélectionnés</h3>
                                        @else
                                            <h3>Aucune offre publiée</h3>
                                            <p>Commencez par créer votre première offre d'emploi.</p>
                                            <a href="{{ route('job-offers.create') }}" class="btn-p" style="display:inline-flex;">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Créer ma première offre
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jobOffers->hasPages())
                {{ $jobOffers->links() }}
            @endif
        </div>
    </div>

    {{-- Confirmation modal --}}
    <div id="confirm-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="modal-title"></h3>
                    <p class="modal-msg" id="modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="modal-cancel">Annuler</button>
                <button type="button" class="modal-ok" id="modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('click', function() {
        var d = document.getElementById('status-drop');
        if (d) d.classList.remove('open');
    });
    </script>
    <script>
    (function () {
        var modal   = document.getElementById('confirm-modal');
        var ico     = document.getElementById('modal-ico');
        var title   = document.getElementById('modal-title');
        var msg     = document.getElementById('modal-msg');
        var btnOk   = document.getElementById('modal-ok');
        var btnNo   = document.getElementById('modal-cancel');
        var pending = null;

        function open(form, variant) {
            pending = form;
            var isArchive = variant === 'archive';
            var color = isArchive ? 'var(--te-danger-fg)' : 'var(--te-primary-fg)';
            var bg    = isArchive ? 'var(--te-danger-soft)' : 'var(--te-primary-soft)';

            ico.style.background = bg;
            ico.innerHTML = isArchive
                ? '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + color + '" stroke-width="2" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>'
                : '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + color + '" stroke-width="2" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><polyline stroke-linecap="round" stroke-linejoin="round" points="10 12 12 14 14 12"/><line x1="12" y1="8" x2="12" y2="14"/></svg>';

            title.textContent = isArchive ? 'Archiver cette offre ?' : 'Réactiver cette offre ?';
            msg.textContent   = isArchive
                ? "L'offre sera masquée des résultats de recherche. Vous pourrez la réactiver à tout moment."
                : "L'offre sera de nouveau visible par les candidats.";

            btnOk.textContent       = isArchive ? 'Archiver' : 'Réactiver';
            btnOk.style.background  = color;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function close() {
            pending = null;
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }

        btnNo.onclick  = close;
        modal.onclick  = function (e) { if (e.target === modal) close(); };
        btnOk.onclick  = function () { if (pending) pending.submit(); };
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

        document.querySelectorAll('.js-archive-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'archive'); };
        });
        document.querySelectorAll('.js-restore-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'restore'); };
        });
    })();
    </script>
    <script>
    (function () {
        var KEY = 'my_offers_url';
        if (window.location.search) {
            sessionStorage.setItem(KEY, window.location.href);
        } else {
            var referrerPath = null;
            try { referrerPath = document.referrer ? new URL(document.referrer).pathname : null; } catch(e) {}
            if (referrerPath && referrerPath !== window.location.pathname) {
                var saved = sessionStorage.getItem(KEY);
                if (saved) { try { if (new URL(saved).pathname === window.location.pathname) window.location.replace(saved); } catch(e) {} }
            }
        }
    })();
    </script>
</x-app-layout>
