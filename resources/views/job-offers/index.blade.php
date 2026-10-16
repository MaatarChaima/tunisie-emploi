<x-app-layout>
    <x-slot name="title">Offres d'emploi</x-slot>

    @php
        $typeMap = \App\Models\JobOffer::TYPES; // libellés et icônes partagés avec les pages de détail
        $levels = \App\Support\Education::LEVELS; // valeur en base => libellé affiché
        $savedJobIds = (Auth::check() && Auth::user()->isCandidate())
            ? Auth::user()->savedJobs->pluck('job_offer_id')->toArray()
            : [];
        $selCategory = $categories->firstWhere('id', request('category_id'));
        $activeFilters = array_filter([
            'search'          => request('search') ? '« ' . request('search') . ' »' : null,
            'location'        => request('location'),
            'category_id'     => $selCategory?->name,
            'education_level' => \App\Support\Education::label(request('education_level')),
            'type'            => $typeMap[request('type')]['label'] ?? null,
        ]);
        $nf = fn ($n) => number_format($n, 0, ',', "\u{202F}");
    @endphp

    <style>
        .jo-wrap { max-width:80rem; margin:0 auto; padding:1.5rem 1.5rem 3rem; }
        @media (max-width:640px) { .jo-wrap { padding:1rem 1rem 2rem; } }

        /* ── Hero ─────────────────────────────────────────── */
        .jo-hero { display:grid; grid-template-columns:minmax(0,1fr) 300px; gap:1.25rem 2rem; align-items:center; background:linear-gradient(135deg, var(--te-hero-from), var(--te-hero-to)); border:1px solid var(--te-hero-border); border-radius:var(--te-radius-lg); padding:2rem 2.25rem; margin-bottom:1.5rem; color:var(--te-hero-text); }
        @media (max-width:960px) { .jo-hero { grid-template-columns:1fr; } .jo-hero-art { display:none; } }
        @media (max-width:640px) { .jo-hero { padding:1.5rem 1.15rem; } }
        .jo-hero-title { font-size:clamp(1.6rem, 3.2vw, 2.25rem); font-weight:800; line-height:1.15; letter-spacing:-.025em; margin:0 0 .5rem; }
        .jo-hero-title em { font-style:normal; color:var(--te-hero-muted); }
        .jo-hero-sub { font-size:.95rem; color:var(--te-hero-muted); margin:0 0 1.25rem; max-width:34rem; line-height:1.55; }
        .jo-search { display:flex; gap:.4rem; background:var(--te-surface); border-radius:12px; padding:5px; max-width:40rem; }
        .jo-search-field { flex:1; display:flex; align-items:center; gap:.5rem; padding:0 .75rem; min-width:0; }
        .jo-search-field svg { color:var(--te-text-muted); flex-shrink:0; }
        .jo-search-field input { border:none; outline:none; background:transparent; font:inherit; font-size:.9rem; color:var(--te-text); width:100%; padding:.55rem 0; }
        .jo-search-field:focus-within { outline:2px solid var(--te-focus); outline-offset:3px; border-radius:8px; }
        /* Un seul indicateur de focus : celui du conteneur (ni contour ni anneau sur le champ lui-même) */
        .jo-search-field input:focus, .jo-search-field input:focus-visible { outline:none; box-shadow:none; }
        .jo-search-btn { display:inline-flex; align-items:center; gap:.4rem; background:var(--te-primary); color:var(--te-on-primary); border:none; border-radius:9px; padding:.6rem 1.15rem; font:inherit; font-size:.875rem; font-weight:700; cursor:pointer; }
        .jo-search-btn:hover { background:var(--te-primary-hover); }
        .jo-search-btn-ico { display:none; }
        .jo-popular { display:flex; align-items:center; flex-wrap:wrap; gap:.4rem; margin-top:.9rem; font-size:.8125rem; }
        .jo-popular-label { color:var(--te-hero-muted); margin-right:.15rem; }
        .jo-popular a { color:var(--te-hero-text); text-decoration:none; padding:.25rem .65rem; border-radius:99px; border:1px solid rgba(255,255,255,.22); background:rgba(255,255,255,.08); }
        .jo-popular a:hover { background:rgba(255,255,255,.16); }
        /* Chiffres clés : 3 colonnes égales sur toute la largeur, séparées par un trait vertical */
        .jo-figures { grid-column:1 / -1; display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); list-style:none; margin:0; padding:1rem 0 0; border-top:1px solid rgba(255,255,255,.14); }
        .jo-figures li { display:flex; align-items:center; gap:.75rem; padding:0 1.25rem; min-width:0; }
        .jo-figures li:first-child { padding-left:0; }
        .jo-figures li + li { border-left:1px solid rgba(255,255,255,.14); }
        .jo-fig-ico { width:38px; height:38px; border-radius:50%; flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.18); color:var(--te-hero-text); }
        .jo-fig-txt { min-width:0; }
        .jo-figures b { display:block; font-size:1.35rem; font-weight:800; line-height:1.2; }
        .jo-fig-txt span { font-size:.8125rem; color:var(--te-hero-muted); }

        /* ── Hero sur mobile (< 640 px) : compact, la liste reste visible sans défiler ── */
        @media (max-width:639.98px) {
            .jo-hero { padding:1.1rem 1rem 1rem; margin-bottom:1rem; gap:0; grid-template-columns:minmax(0, 1fr); }
            .jo-hero > div { min-width:0; }
            .jo-hero-title { font-size:1.45rem; margin-bottom:.35rem; }
            .jo-hero-sub { font-size:.875rem; line-height:1.45; margin-bottom:.85rem; }
            .jo-search { padding:4px; }
            .jo-search-field { padding:0 .5rem; }
            .jo-search-field input { padding:.5rem 0; }
            /* < 640 px : bouton réduit à une loupe, le texte d’aide tient en entier */
            .jo-search-btn { padding:.55rem .7rem; }
            .jo-search-btn-ico { display:block; }
            .jo-search-btn-text { display:none; }
            /* Recherches populaires : une seule ligne qui défile au doigt, barre masquée */
            .jo-popular { flex-wrap:nowrap; overflow-x:auto; margin:.75rem -1rem 0; padding:0 1rem 2px; scrollbar-width:none; -webkit-overflow-scrolling:touch; }
            .jo-popular::-webkit-scrollbar { display:none; }
            .jo-popular-label, .jo-popular a { flex-shrink:0; white-space:nowrap; }
            /* Chiffres clés : 3 colonnes compactes */
            .jo-figures { gap:.5rem; margin-top:.85rem; padding-top:.75rem; }
            .jo-figures li { padding:0; align-items:flex-start; }
            .jo-figures li + li { border-left:0; }
            .jo-fig-ico { display:none; }
            .jo-figures b { font-size:1.15rem; }
            .jo-fig-txt span { font-size:.75rem; line-height:1.3; display:block; }
        }
        .jo-hero-art { width:100%; height:auto; }

        /* ── Mise en page ─────────────────────────────────── */
        /* Bureau : filtres à gauche, barre de résultats puis résultats à droite.
           Mobile : barre (Filtres + Tri), panneau de filtres dépliable, résultats. */
        .jo-layout { display:grid; grid-template-columns:15rem minmax(0,1fr); grid-template-areas:"filters bar" "filters results"; grid-template-rows:auto 1fr; column-gap:1.5rem; align-items:start; }
        .jo-resbar { grid-area:bar; }
        .jo-filters { grid-area:filters; }
        .jo-main { grid-area:results; }
        @media (max-width:1024px) { .jo-layout { grid-template-columns:minmax(0,1fr); grid-template-areas:"bar" "filters" "results"; grid-template-rows:auto; } }

        /* ── Filtres ──────────────────────────────────────── */
        @media (max-width:1024px) { .jo-filters { position:static; } }
        @media (max-width:1024px) {
            .jo-filters { margin-bottom:.75rem; }
            .js .jo-filters:not(.is-open) { display:none; } /* sans JS, le panneau reste visible */
            .jo-filters-body { grid-template-columns:repeat(2, minmax(0,1fr)); padding-top:.85rem; gap:.75rem; }
            .jo-filters-body > .jo-field + .jo-field { margin-top:0; border-top:0; } /* grille : pas de séparateurs */
            .jo-filters-body > .jo-field, .jo-filters-body > .jo-filters-actions { padding-top:0; }
        }
        @media (max-width:520px) { .jo-filters-body { grid-template-columns:1fr; } }
        @media (max-width:1024px) { .jo-field-domain { grid-column:1 / -1; } .jo-field-domain .ui-lb-list { width:100%; } }
        @media (max-width:1024px) { .jo-field-types { grid-column:1 / -1; } .jo-types { grid-template-columns:repeat(4, minmax(0,1fr)); } }
        @media (max-width:520px) { .jo-types { grid-template-columns:repeat(2, minmax(0,1fr)); } }
        @media (max-width:1024px) { .jo-filters-actions { grid-column:1 / -1; } }

        /* ── Barre de résultats ───────────────────────────── */
        .jo-main { min-width:0; }
        .jo-resbar { display:flex; align-items:center; gap:.75rem; margin-bottom:.75rem; min-width:0; }
        .jo-count { font-size:.95rem; color:var(--te-text-2); margin:0 auto 0 0; }
        .jo-count strong { color:var(--te-text); font-weight:800; }
        /* Contrôles compacts de même hauteur : bouton Filtres (mobile) et tri */
        .jo-ctl { display:inline-flex; align-items:center; gap:.45rem; height:2.375rem; padding:0 .75rem; border:1px solid var(--te-input-border); border-radius:8px; background:var(--te-input-bg); color:var(--te-text); font:inherit; font-size:.8125rem; font-weight:600; min-width:0; }
        .jo-ctl > svg { flex-shrink:0; color:var(--te-text-muted); }
        .jo-filters-btn { display:none; cursor:pointer; }
        .jo-filters-btn:hover, .jo-sortform .ui-lb-btn:hover, .jo-dirbtn:hover { border-color:var(--te-border-strong); }
        .jo-sortgrp { display:flex; align-items:center; gap:.4rem; min-width:0; }
        .jo-sort-lbl { font-size:.8125rem; color:var(--te-text-muted); white-space:nowrap; }
        .jo-dirbtn { justify-content:center; width:2.375rem; padding:0; text-decoration:none; flex-shrink:0; }
        .jo-dirbtn > svg { color:var(--te-text); }
        .jo-dirbtn:focus-visible { outline:none; border-color:var(--te-primary); box-shadow:0 0 0 3px var(--te-primary-ring); }
        .jo-filters-btn:focus-visible { outline:none; border-color:var(--te-primary); box-shadow:0 0 0 3px var(--te-primary-ring); }
        .jo-filters-btn[aria-expanded="true"] { border-color:var(--te-primary-border); background:var(--te-primary-soft); color:var(--te-primary-fg); }
        /* Tri : liste du composant ui.listbox dans un formulaire (envoi sans JavaScript) */
        .jo-sortform { display:flex; align-items:center; gap:.4rem; margin:0; min-width:0; }
        .jo-sortform .ui-lb-native { width:auto; }
        .jo-sortform .ui-lb .ui-lb-btn { height:2.375rem; min-height:0; padding:.25rem 2rem .25rem .4rem; }
        @media (max-width:1024px) {
            .jo-resbar { display:grid; grid-template-columns:auto minmax(0,1fr); grid-template-areas:"filters sort" "count count"; row-gap:.65rem; }
            .js .jo-filters-btn { display:inline-flex; }
            .jo-filters-btn { grid-area:filters; }
            .jo-sortgrp { grid-area:sort; justify-self:end; max-width:100%; }
            .jo-sort-lbl { position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0 0 0 0); white-space:nowrap; } /* lu, pas affiché */
            .jo-count { grid-area:count; }
        }
        /* Filtres actifs : sur une ligne qui défile horizontalement sur mobile, « Tout effacer » à la fin */
        .jo-chips { display:flex; flex-wrap:wrap; gap:.4rem; align-items:center; margin-bottom:1rem; list-style:none; padding:0; }
        @media (max-width:640px) {
            .jo-chips { flex-wrap:nowrap; overflow-x:auto; padding-bottom:.35rem; margin-right:-1rem; padding-right:1rem; scrollbar-width:thin; }
            .jo-chips > li { flex-shrink:0; white-space:nowrap; }
        }
        .jo-chip { display:inline-flex; align-items:center; gap:.2rem; padding:.2rem .25rem .2rem .65rem; border-radius:99px; background:var(--te-primary-soft); border:1px solid var(--te-primary-border); color:var(--te-primary-fg); font-size:.8125rem; font-weight:600; }
        .jo-chip a { display:inline-flex; align-items:center; justify-content:center; width:1.4rem; height:1.4rem; border-radius:50%; color:inherit; }
        .jo-chip a:hover { background:var(--te-primary-border); }
        .jo-clear { font-size:.8125rem; font-weight:600; color:var(--te-text-2); text-decoration:underline; text-underline-offset:2px; margin-left:.25rem; }

        /* ── Cartes d'offres ──────────────────────────────── */
        .jo-cards { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:.9rem; list-style:none; margin:0; padding:0; }
        @media (max-width:1180px) { .jo-cards { grid-template-columns:1fr; } }
        .jo-card { position:relative; display:flex; flex-direction:column; gap:.7rem; height:100%; background:var(--te-surface); border:1px solid var(--te-border); border-radius:var(--te-radius); padding:1rem 1.1rem; transition:border-color var(--te-ease), box-shadow var(--te-ease); }
        .jo-card:hover { border-color:var(--te-primary-border); box-shadow:var(--te-shadow-md); }
        .jo-card:focus-within { border-color:var(--te-primary); }
        .jo-card-top { display:flex; gap:.85rem; align-items:flex-start; }
        .jo-card-head { flex:1; min-width:0; }
        .jo-card-title { font-size:1.02rem; font-weight:800; line-height:1.3; color:var(--te-text); margin:0; }
        .jo-card-title a { color:inherit; text-decoration:none; }
        .jo-card-title a::after { content:''; position:absolute; inset:0; border-radius:inherit; } /* toute la carte cliquable */
        .jo-card-title a:focus-visible { outline:none; }
        .jo-card:has(.jo-card-title a:focus-visible) { outline:2px solid var(--te-focus); outline-offset:2px; }
        .jo-card-company { font-size:.875rem; font-weight:600; color:var(--te-text-2); margin:.15rem 0 0; }
        /* Mobile : logo 48 px aligné en haut, titre et entreprise sur toute la largeur, type de contrat dessous */
        @media (max-width:639.98px) {
            .jo-card-top { display:grid; grid-template-columns:48px minmax(0,1fr); column-gap:.75rem; row-gap:.4rem; align-items:start; }
            .jo-card-top > :first-child { width:48px !important; height:48px !important; grid-row:1 / span 2; }
            .jo-card-head { grid-column:2; }
            .jo-card-top .jo-contract { grid-column:2; justify-self:start; }
        }
        .jo-contract { display:inline-flex; align-items:center; gap:.3rem; flex-shrink:0; padding:.2rem .55rem; border-radius:6px; background:var(--te-surface-3); color:var(--te-text-2); font-size:.75rem; font-weight:700; white-space:nowrap; }
        .jo-meta { display:flex; flex-wrap:wrap; gap:.3rem 1rem; list-style:none; margin:0; padding:0; font-size:.8125rem; color:var(--te-text-muted); }
        .jo-meta li { display:inline-flex; align-items:center; gap:.3rem; }
        .jo-meta svg { flex-shrink:0; }
        .jo-card-foot { display:flex; align-items:center; justify-content:space-between; gap:.75rem; margin-top:auto; padding-top:.7rem; border-top:1px solid var(--te-border-subtle); }
        .jo-salary { display:inline-flex; align-items:center; gap:.4rem; font-size:.95rem; font-weight:800; color:var(--te-text); }
        .jo-salary svg { flex-shrink:0; color:var(--te-text-muted); }
        .jo-salary small { font-size:.75rem; font-weight:500; color:var(--te-text-muted); }
        .jo-salary.none { font-size:.8125rem; font-weight:500; color:var(--te-text-muted); }
        .jo-foot-right { display:flex; align-items:center; gap:.5rem; position:relative; z-index:1; }
        .jo-date { font-size:.75rem; color:var(--te-text-muted); white-space:nowrap; }
        .jo-tag { display:inline-flex !important; align-items:center; gap:.35rem !important; padding:.15rem .6rem .15rem .45rem; border-radius:99px; background:var(--te-primary-soft); border:1px solid var(--te-primary-border); font-size:.75rem; font-weight:600; color:var(--te-primary-fg) !important; }
        .jo-tag .te-cat-ico { color:var(--te-primary-fg) !important; }
        .jo-icon-btn { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:8px; border:1px solid var(--te-border); background:var(--te-surface); color:var(--te-text-2); cursor:pointer; }
        .jo-icon-btn:hover { border-color:var(--te-primary); color:var(--te-primary-fg); }
        .jo-icon-btn.is-saved { background:var(--te-primary-soft); border-color:var(--te-primary-border); color:var(--te-primary-fg); }
        .jo-owner { display:flex; gap:.4rem; position:relative; z-index:1; flex-wrap:wrap; }
        .jo-btn-sm { padding:.3rem .65rem; font-size:.8125rem; border-radius:7px; }
        .jo-btn-danger { background:transparent; color:var(--te-danger-fg); border-color:var(--te-danger-border); }
        .jo-btn-success { background:var(--te-success-soft); color:var(--te-success-fg); border-color:var(--te-success-border); }
        .jo-status { font-size:.75rem; font-weight:700; padding:.15rem .5rem; border-radius:6px; border:1px solid; }
        .jo-status.is-warning { background:var(--te-warning-soft); color:var(--te-warning-fg); border-color:var(--te-warning-border); }
        .jo-status.is-danger  { background:var(--te-danger-soft);  color:var(--te-danger-fg);  border-color:var(--te-danger-border); }
        .jo-status.is-neutral { background:var(--te-surface-3);    color:var(--te-text-2);     border-color:var(--te-border); }

        /* ── État vide ────────────────────────────────────── */
        .jo-empty { grid-column:1 / -1; text-align:center; background:var(--te-surface); border:1px dashed var(--te-border-strong); border-radius:var(--te-radius); padding:2.5rem 1.5rem; }
        .jo-empty svg { margin:0 auto 1rem; display:block; }
        .jo-empty h2 { font-size:1.05rem; font-weight:800; color:var(--te-text); margin:0 0 .35rem; }
        .jo-empty p { font-size:.9rem; color:var(--te-text-muted); margin:0 auto 1.1rem; max-width:28rem; }

        .jo-pagination { margin-top:1.5rem; display:flex; justify-content:center; }
        .jo-loading { opacity:.55; pointer-events:none; transition:opacity .15s; }

        /* ── Fenêtre de confirmation (archivage) ─────────── */
        .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:var(--te-overlay); align-items:center; justify-content:center; padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal { background:var(--te-surface); border-radius:var(--te-radius-lg); width:100%; max-width:420px; border:1px solid var(--te-border); box-shadow:var(--te-shadow-lg); overflow:hidden; }
        .modal-head { padding:1.25rem 1.5rem; border-bottom:1px solid var(--te-border); }
        .modal-title { font-size:1rem; font-weight:800; color:var(--te-text); margin:0 0 .25rem; }
        .modal-msg { font-size:.875rem; color:var(--te-text-muted); line-height:1.55; margin:0; }
    </style>

    <div class="jo-wrap">

        {{-- ── Hero ────────────────────────────────────────── --}}
        <section class="jo-hero" aria-labelledby="jo-hero-title">
            <div>
                <h1 class="jo-hero-title" id="jo-hero-title">Trouvez votre <em>prochain défi</em></h1>
                <p class="jo-hero-sub">Des offres d'emploi vérifiées partout en Tunisie, publiées directement par les entreprises.</p>

                <form action="{{ route('job-offers.index') }}" method="GET" class="jo-search" id="hero-search-form" role="search">
                    <div class="jo-search-field">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <label for="hero-search-input" class="sr-only">Rechercher une offre</label>
                        <input type="search" name="search" id="hero-search-input" value="{{ request('search') }}"
                               placeholder="Métier, entreprise, mot-clé…" autocomplete="off">
                    </div>
                    <button type="submit" class="jo-search-btn" aria-label="Rechercher">
                        <svg class="jo-search-btn-ico" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="jo-search-btn-text">Rechercher</span>
                    </button>
                </form>

                @if($popularCategories->isNotEmpty() || $popularCities->isNotEmpty())
                    <div class="jo-popular" id="jo-popular">
                        <span class="jo-popular-label">Recherches populaires :</span>
                        @foreach($popularCategories as $cat)
                            <a href="{{ route('job-offers.index', ['category_id' => $cat->id]) }}">{{ $cat->name }}</a>
                        @endforeach
                        @foreach($popularCities as $city)
                            <a href="{{ route('job-offers.index', ['location' => $city]) }}">{{ $city }}</a>
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- Illustration : fiches d'offres et loupe (formes simples, couleurs du thème) --}}
            <svg class="jo-hero-art" viewBox="0 0 300 240" aria-hidden="true" focusable="false">
                <rect x="58" y="38" width="190" height="64" rx="12" fill="rgba(255,255,255,.07)" stroke="rgba(255,255,255,.18)"/>
                <rect x="40" y="88" width="200" height="70" rx="12" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.22)"/>
                <rect x="22" y="142" width="214" height="78" rx="12" fill="var(--te-surface)"/>
                <rect x="38" y="158" width="34" height="34" rx="8" fill="var(--te-primary-soft)"/>
                <path d="M48 175h14M55 168v14" stroke="var(--te-primary)" stroke-width="2.5" stroke-linecap="round"/>
                <rect x="84" y="160" width="96" height="9" rx="4.5" fill="var(--te-text)" opacity=".85"/>
                <rect x="84" y="176" width="64" height="7" rx="3.5" fill="var(--te-text-muted)" opacity=".7"/>
                <rect x="84" y="196" width="44" height="12" rx="6" fill="var(--te-surface-3)"/>
                <rect x="134" y="196" width="52" height="12" rx="6" fill="var(--te-surface-3)"/>
                <rect x="58" y="106" width="22" height="22" rx="6" fill="rgba(255,255,255,.18)"/>
                <rect x="90" y="108" width="90" height="7" rx="3.5" fill="rgba(255,255,255,.35)"/>
                <rect x="90" y="121" width="58" height="6" rx="3" fill="rgba(255,255,255,.2)"/>
                <rect x="74" y="54" width="18" height="18" rx="5" fill="rgba(255,255,255,.14)"/>
                <rect x="100" y="57" width="80" height="6" rx="3" fill="rgba(255,255,255,.25)"/>
                <circle cx="236" cy="96" r="34" fill="none" stroke="var(--te-hero-muted)" stroke-width="7"/>
                <circle cx="236" cy="96" r="25" fill="rgba(255,255,255,.08)"/>
                <path d="M260 121l22 22" stroke="var(--te-hero-muted)" stroke-width="10" stroke-linecap="round"/>
            </svg>
            {{-- Chiffres clés : toute la largeur du hero (sous le texte et l'illustration) --}}
            <ul class="jo-figures" aria-label="Chiffres clés">
                @foreach([
                    ['n' => $heroStats['offers'],    'label' => $heroStats['offers'] > 1 ? 'offres ouvertes' : 'offre ouverte',
                     'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['n' => $heroStats['companies'], 'label' => $heroStats['companies'] > 1 ? 'entreprises qui recrutent' : 'entreprise qui recrute',
                     'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['n' => $heroStats['cities'],    'label' => $heroStats['cities'] > 1 ? 'villes' : 'ville',
                     'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as $fig)
                    <li>
                        <span class="jo-fig-ico" aria-hidden="true"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $fig['icon'] }}"/></svg></span>
                        <span class="jo-fig-txt"><b>{{ $nf($fig['n']) }}</b><span>{{ $fig['label'] }}</span></span>
                    </li>
                @endforeach
            </ul>
        </section>

        <script>document.documentElement.classList.add('js');</script>
        @php
            // Tri : critère + ordre (libellés de l'ordre selon le critère)
            $sortCriteria = [
                'date'     => ['label' => 'Date de publication', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                               'desc' => 'Plus récentes d’abord', 'asc' => 'Plus anciennes d’abord'],
                'salary'   => ['label' => 'Salaire', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                               'desc' => 'Salaire décroissant', 'asc' => 'Salaire croissant'],
                'deadline' => ['label' => 'Date limite', 'icon' => 'M6 3h12M6 21h12M7 3c0 4 5 5 5 9s-5 5-5 9m10-18c0 4-5 5-5 9s5 5 5 9',
                               'asc' => 'Date limite la plus proche', 'desc' => 'Date limite la plus lointaine'],
            ];
            // Options des listes du composant ui.listbox (icône ronde devant chaque option)
            $domainOptions = [['value' => '', 'label' => 'Tous les domaines', 'plain' => true, 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'neutral' => true]];
            foreach ($categories as $category) {
                $n = (int) $category->open_offers_count;
                $domainOptions[] = ['value' => $category->id, 'label' => $category->name, 'icon' => $category->iconPath(), 'count' => $n,
                                    'sr' => ', ' . $n . ' offre' . ($n > 1 ? 's' : '') . ' ouverte' . ($n > 1 ? 's' : '')];
            }
            $cityOptions = [['value' => '', 'label' => 'Toutes les villes']];
            foreach ($cities as $city) {
                $n = (int) ($cityCounts[$city] ?? 0);
                $cityOptions[] = ['value' => $city, 'label' => $city, 'count' => $n,
                                  'sr' => ', ' . $n . ' offre' . ($n > 1 ? 's' : '') . ' ouverte' . ($n > 1 ? 's' : '')];
            }
            $levelOptions = [['value' => '', 'label' => 'Tous les niveaux', 'icon' => \App\Support\Education::NEUTRAL_ICON, 'neutral' => true, 'plain' => true]];
            foreach ($levels as $level => $levelLabel) {
                $n = (int) ($levelCounts[$level] ?? 0);
                $levelOptions[] = ['value' => $level, 'label' => $levelLabel, 'icon' => \App\Support\Education::icon($level), 'count' => $n,
                                   'sr' => ', ' . $n . ' offre' . ($n > 1 ? 's' : '') . ' ouverte' . ($n > 1 ? 's' : '')];
            }
            $sortOptions = [];
            foreach ($sortCriteria as $value => $c) {
                $sortOptions[] = ['value' => $value, 'label' => $c['label'], 'icon' => $c['icon'],
                                  'data' => ['icon' => $c['icon'], 'desc' => $c['desc'], 'asc' => $c['asc'], 'default' => \App\Http\Controllers\JobOfferController::SORT_DEFAULT_DIR[$value]]];
            }
            $dirIcons   = ['desc' => 'M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4', 'asc' => 'M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12'];
            $defaultDir = \App\Http\Controllers\JobOfferController::SORT_DEFAULT_DIR;
            $otherDir   = $dir === 'asc' ? 'desc' : 'asc';
            $dirUrl     = request()->fullUrlWithQuery(['dir' => $otherDir === $defaultDir[$sort] ? null : $otherDir, 'page' => null]);
            $nbSideFilters = count(array_diff_key($activeFilters, ['search' => 1]));
        @endphp
        <div class="jo-layout">

            {{-- ── Barre de résultats : nombre, Filtres (mobile), tri ── --}}
            <div class="jo-resbar">
                <p class="jo-count" id="jo-count" aria-live="polite">
                    <strong>{{ $nf($jobOffers->total()) }}</strong>
                    offre{{ $jobOffers->total() > 1 ? 's' : '' }} trouvée{{ $jobOffers->total() > 1 ? 's' : '' }}
                </p>
                <button type="button" class="jo-ctl jo-filters-btn" id="jo-filters-btn" aria-controls="jo-filters" aria-expanded="false">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8.5V19l-4 2v-8.5L3 4z"/></svg>
                    <span id="jo-filters-btn-label">{{ 'Filtres' . ($nbSideFilters ? ' (' . $nbSideFilters . ')' : '') }}</span>
                </button>
                <div class="jo-sortgrp">
                    <span class="jo-sort-lbl" id="jo-sort-lbl">Trier par</span>
                    {{-- Critère : même liste que Domaine ; formulaire pour l'envoi sans JavaScript --}}
                    <form method="GET" action="{{ route('job-offers.index') }}" class="jo-sortform" id="jo-sort-form">
                        @foreach(request()->except(['sort', 'dir', 'page']) as $k => $v)
                            @if(is_string($v) && $v !== '')
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach
                        <x-ui.listbox id="jo-sort" name="sort" :options="$sortOptions" :selected="$sort"
                                      labelledby="jo-sort-lbl" button-class="jo-ctl" align="right" class="jo-select" />
                        <noscript><button type="submit" class="jo-btn jo-btn-ghost jo-btn-sm">OK</button></noscript>
                    </form>
                    {{-- Ordre : inverse l'ordre du critère (lien : fonctionne aussi sans JavaScript) --}}
                    <a href="{{ $dirUrl }}" class="jo-ctl jo-dirbtn" id="jo-dir" role="button" data-dir="{{ $dir }}"
                       aria-label="Ordre : {{ $sortCriteria[$sort][$dir] }}. Activer pour afficher : {{ $sortCriteria[$sort][$otherDir] }}"
                       title="{{ $sortCriteria[$sort][$dir] }} (cliquer pour inverser)">
                        <svg id="jo-dir-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $dirIcons[$dir] }}"/></svg>
                    </a>
                </div>
            </div>

            {{-- ── Filtres ─────────────────────────────────── --}}
            <aside class="jo-filters" id="jo-filters" aria-label="Filtres">
                <div class="jo-filters-head">
                    <svg class="jo-filters-head-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8.5V19l-4 2v-8.5L3 4z"/></svg>
                    <h2 class="jo-filters-title">Filtres</h2>
                    <span id="jo-filters-head-count">@if($nbSideFilters)<span class="jo-badge-count" aria-label="{{ $nbSideFilters }} filtre{{ $nbSideFilters > 1 ? 's' : '' }} actif{{ $nbSideFilters > 1 ? 's' : '' }}">{{ $nbSideFilters }}</span>@endif</span>
                </div>

                <form method="GET" action="{{ route('job-offers.index') }}" id="sidebar-filter-form" class="jo-filters-body">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @foreach($sortParams as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach

                    <div class="jo-field jo-field-city">
                        <label for="f-location" id="f-location-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>Ville</label>
                        <x-ui.listbox id="f-location" name="location" :options="$cityOptions" :selected="request('location', '')"
                                      labelledby="f-location-label" button-class="jo-select" class="jo-select" />
                    </div>

                    {{-- Domaine : <x-ui.listbox> (icône, nom, nombre d'offres ouvertes) ; <select> natif sans JavaScript --}}
                    <div class="jo-field jo-field-domain">
                        <label for="f-category" id="f-category-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>Domaine</label>
                        <x-ui.listbox id="f-category" name="category_id" :options="$domainOptions" :selected="request('category_id', '')"
                                      labelledby="f-category-label" button-class="jo-select" class="jo-select" />
                    </div>

                    {{-- Type de contrat : boutons à bascule (vrais boutons radio, flèches du clavier natives),
                         mêmes icônes que les badges des cartes ($typeMap) --}}
                    <div class="jo-field jo-field-types">
                        <span class="jo-field-label" id="f-type-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>Type de contrat</span>
                        <div class="jo-types" role="radiogroup" aria-labelledby="f-type-label">
                            @foreach(['' => ['label' => 'Tous', 'icon' => 'M4 6h16M4 12h16M4 18h16']] + $typeMap as $value => $t)
                                <label class="jo-type">
                                    <input type="radio" name="type" value="{{ $value }}" @checked((string) request('type', '') === (string) $value)>
                                    <svg class="jo-type-ico" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/></svg>
                                    <span>{{ $t['label'] }}</span>
                                    <svg class="jo-type-check" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="jo-field jo-field-level">
                        <label for="f-education" id="f-education-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Niveau d'études</label>
                        <x-ui.listbox id="f-education" name="education_level" :options="$levelOptions" :selected="request('education_level', '')"
                                      labelledby="f-education-label" button-class="jo-select" class="jo-select" />
                    </div>

                    <div class="jo-filters-actions">
                        <button type="submit" class="jo-btn jo-btn-primary jo-apply">Appliquer</button>
                        {{-- Mobile : referme le panneau et fait défiler jusqu'aux résultats --}}
                        @php $total = $jobOffers->total(); @endphp
                        <button type="button" class="jo-btn jo-btn-primary jo-see" id="jo-see"><span id="jo-see-label">{{ $total === 0 ? 'Aucune offre' : ($total === 1 ? 'Voir l’offre' : 'Voir les ' . $nf($total) . ' offres') }}</span></button>
                        {{-- Visible seulement si un filtre est actif ; le tri choisi est conservé --}}
                        <a href="{{ route('job-offers.index', $sortParams) }}"
                           class="jo-btn jo-btn-ghost jo-reset" id="jo-reset"
                           title="Réinitialiser les filtres"
                           aria-label="Réinitialiser les filtres ({{ count($activeFilters) }} actif{{ count($activeFilters) > 1 ? 's' : '' }})"
                           @if(!$activeFilters) hidden @endif>
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v5h5"/></svg>
                            <span>Réinitialiser (<span data-reset-count>{{ count($activeFilters) }}</span>)</span>
                        </a>
                    </div>
                </form>
            </aside>

            {{-- ── Résultats (zone rechargée en AJAX) ─────────── --}}
            <div class="jo-main" id="jo-main">

                @if($activeFilters)
                    <ul class="jo-chips" aria-label="Filtres actifs">
                        @foreach($activeFilters as $key => $label)
                            <li class="jo-chip">
                                {{ $label }}
                                <a href="{{ request()->fullUrlWithoutQuery([$key, 'page']) }}" aria-label="Retirer le filtre : {{ $label }}">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            </li>
                        @endforeach
                        <li><a href="{{ route('job-offers.index', $sortParams) }}" class="jo-clear">Tout effacer</a></li>
                    </ul>
                @endif

                <ul class="jo-cards">
                    @forelse($jobOffers as $offer)
                        @php
                            $t        = $typeMap[$offer->type] ?? ['label' => ucfirst($offer->type), 'icon' => $typeMap['full-time']['icon']];
                            $deadline = $offer->expiration_date ? \Illuminate\Support\Carbon::parse($offer->expiration_date) : null;
                            $isSaved  = in_array($offer->id, $savedJobIds);
                            $isOwner  = Auth::check() && Auth::id() === $offer->user_id;
                        @endphp
                        <li>
                            <article class="jo-card">
                                <div class="jo-card-top">
                                    <x-company-logo :company="$offer->company" :size="56" />
                                    <div class="jo-card-head">
                                        <h2 class="jo-card-title"><a href="{{ route('job-offers.show', $offer) }}">{{ $offer->title }}</a></h2>
                                        <p class="jo-card-company">{{ $offer->company->name }}</p>
                                    </div>
                                    <span class="jo-contract" title="Type de contrat">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/></svg>
                                        {{ $t['label'] }}
                                    </span>
                                </div>

                                <ul class="jo-meta">
                                    @if($offer->location)
                                        <li><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>{{ $offer->location }}</li>
                                    @endif
                                    <li><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"/></svg>{{ \App\Support\Experience::label((int) $offer->experience_years, 'offer', 'd’expérience') }}</li>
                                    @if($offer->vacancies)
                                        <li><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>{{ $offer->vacanciesLabel() }}</li>
                                    @endif
                                    @if($deadline)
                                        <li><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>{{ $offer->deadlineLabel() }}</li>
                                    @endif
                                    @if($offer->offerCategory)
                                        <li class="jo-tag"><x-category-icon :category="$offer->offerCategory" :size="16" />{{ $offer->offerCategory->name }}</li>
                                    @endif
                                </ul>

                                <div class="jo-card-foot">
                                    @if($offer->salary)
                                        <span class="jo-salary"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>{{ $offer->salaryLabel() }}</span>
                                    @else
                                        <span class="jo-salary none">Salaire non précisé</span>
                                    @endif

                                    <div class="jo-foot-right">
                                        @if(($isOwner || (Auth::check() && Auth::user()->isAdmin())) && ($label = $offer->statusLabel()))
                                            <span class="jo-status {{ ['En attente' => 'is-warning', 'Archivée' => 'is-neutral', 'Clôturée' => 'is-neutral'][$label] ?? 'is-danger' }}">{{ $label }}</span>
                                        @endif
                                        <time class="jo-date" datetime="{{ $offer->created_at->toIso8601String() }}" title="Publiée le {{ $offer->created_at->translatedFormat('j F Y') }}">{{ $offer->created_at->diffForHumans() }}</time>
                                        @if(Auth::check() && Auth::user()->isCandidate())
                                            <form method="POST" action="{{ route('saved-jobs.toggle', $offer) }}" class="save-job-form">
                                                @csrf
                                                <button type="submit" class="jo-icon-btn save-job-btn {{ $isSaved ? 'is-saved' : '' }}"
                                                        aria-pressed="{{ $isSaved ? 'true' : 'false' }}"
                                                        aria-label="{{ $isSaved ? 'Retirer des favoris' : 'Sauvegarder l’offre' }} « {{ $offer->title }} »"
                                                        title="{{ $isSaved ? 'Retirer des favoris' : 'Sauvegarder l’offre' }}">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="{{ $isSaved ? 'currentColor' : 'none' }}" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                @if($isOwner && Auth::user()->isCompany())
                                    <div class="jo-owner">
                                        <a href="{{ route('job-offers.edit', $offer) }}" class="jo-btn jo-btn-ghost jo-btn-sm">Modifier</a>
                                        @if($offer->status === 'archived')
                                            <form method="POST" action="{{ route('job-offers.unarchive', $offer) }}" class="js-restore-form" data-title="{{ $offer->title }}">
                                                @csrf
                                                <button type="submit" class="jo-btn jo-btn-success jo-btn-sm" title="L'offre sera de nouveau visible par les candidats">Réactiver</button>
                                            </form>
                                        @elseif($offer->status === 'open')
                                            <form method="POST" action="{{ route('job-offers.archive', $offer) }}" class="js-archive-form" data-title="{{ $offer->title }}">
                                                @csrf
                                                <button type="submit" class="jo-btn jo-btn-danger jo-btn-sm" title="L'offre ne sera plus visible par les candidats">Archiver</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </article>
                        </li>
                    @empty
                        <li class="jo-empty">
                            {{-- Illustration : liste vide sous une loupe --}}
                            <svg width="140" height="100" viewBox="0 0 140 100" aria-hidden="true" focusable="false">
                                <rect x="16" y="14" width="78" height="72" rx="10" fill="var(--te-surface-2)" stroke="var(--te-border-strong)"/>
                                <rect x="28" y="28" width="16" height="16" rx="4" fill="var(--te-surface-3)"/>
                                <rect x="50" y="30" width="32" height="5" rx="2.5" fill="var(--te-border-strong)"/>
                                <rect x="50" y="39" width="22" height="4" rx="2" fill="var(--te-border)"/>
                                <rect x="28" y="54" width="54" height="5" rx="2.5" fill="var(--te-border)"/>
                                <rect x="28" y="65" width="38" height="5" rx="2.5" fill="var(--te-border)"/>
                                <circle cx="96" cy="56" r="20" fill="var(--te-surface)" stroke="var(--te-primary-400)" stroke-width="5"/>
                                <path d="M110 71l14 14" stroke="var(--te-primary-400)" stroke-width="7" stroke-linecap="round"/>
                                <path d="M89 56h14" stroke="var(--te-text-muted)" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                            <h2>Aucune offre ne correspond à votre recherche</h2>
                            <p>Essayez d'élargir vos critères : retirez un filtre, changez de ville ou utilisez un mot-clé plus général.</p>
                            <a href="{{ route('job-offers.index') }}" class="jo-btn jo-btn-primary">Voir toutes les offres</a>
                        </li>
                    @endforelse
                </ul>

                @if($jobOffers->hasPages())
                    <nav class="jo-pagination" aria-label="Pagination des offres">
                        {{ $jobOffers->links() }}
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <script>
    document.documentElement.classList.add('js');
    document.addEventListener('DOMContentLoaded', function () {
        var main      = document.getElementById('jo-main');
        var filters   = document.getElementById('sidebar-filter-form');
        var heroForm  = document.getElementById('hero-search-form');
        var heroInput = document.getElementById('hero-search-input');
        var baseUrl   = '{{ route('job-offers.index') }}';
        var KEY       = 'jo_index_url';
        var controller = null;

        // Synchronise les champs avec l'URL (après un clic sur une puce, la pagination, etc.)
        function syncFields(url) {
            var p = new URL(url).searchParams;
            ['location', 'category_id', 'education_level'].forEach(function (name) {
                var el = filters.querySelector('[name="' + name + '"]');
                if (el) el.value = p.get(name) || '';
            });
            document.dispatchEvent(new CustomEvent('ui-listbox-sync')); // listes du composant ui.listbox (Domaine, tri)
            // Type de contrat : boutons radio
            filters.querySelectorAll('input[name="type"]').forEach(function (r) { r.checked = r.value === (p.get('type') || ''); });
            var s = filters.querySelector('input[name="search"]');
            if (p.get('search')) {
                if (!s) { s = document.createElement('input'); s.type = 'hidden'; s.name = 'search'; filters.prepend(s); }
                s.value = p.get('search');
            } else if (s) { s.remove(); }
            var st = sortState(p);
            ['sort', 'dir'].forEach(function (name) {
                var keep = name === 'sort' ? (st.sort !== 'date' ? st.sort : '') : (st.dir !== st.defaultDir ? st.dir : '');
                var h = filters.querySelector('input[name="' + name + '"]');
                if (keep) {
                    if (!h) { h = document.createElement('input'); h.type = 'hidden'; h.name = name; filters.prepend(h); }
                    h.value = keep;
                } else if (h) { h.remove(); }
            });
            if (heroInput) heroInput.value = p.get('search') || '';

            // Tri : critère (libellé, icône) et bouton d'ordre (icône, libellés, lien sans JavaScript)
            renderSort(st, p);

            // Bouton « Réinitialiser » : nombre de filtres actifs (recherche comprise), tri conservé
            var reset = document.getElementById('jo-reset');
            var n = ['search', 'location', 'category_id', 'type', 'education_level'].filter(function (k) { return p.get(k); }).length;
            var resetUrl = new URL(baseUrl);
            if (st.sort !== 'date') resetUrl.searchParams.set('sort', st.sort);
            if (st.dir !== st.defaultDir) resetUrl.searchParams.set('dir', st.dir);
            reset.href = resetUrl.toString();
            reset.hidden = n === 0;
            reset.querySelector('[data-reset-count]').textContent = n;
            reset.setAttribute('aria-label', 'Réinitialiser les filtres (' + n + ' actif' + (n > 1 ? 's' : '') + ')');
        }

        // Critère et ordre de tri lus dans l'URL (mêmes règles que le serveur, anciennes URL comprises)
        var sortSel = document.getElementById('jo-sort');
        var dirBtn  = document.getElementById('jo-dir');
        var DIR_ICONS = @json($dirIcons);
        function sortState(p) {
            var legacy = { recent: ['date', 'desc'], salary_desc: ['salary', 'desc'], salary_asc: ['salary', 'asc'] };
            var raw = p.get('sort') || '';
            var opt = sortSel.querySelector('option[value="' + raw + '"]');
            var sort = opt ? raw : (legacy[raw] ? legacy[raw][0] : 'date');
            var def = sortSel.querySelector('option[value="' + sort + '"]').dataset.default;
            var dir = p.get('dir');
            if (dir !== 'asc' && dir !== 'desc') dir = legacy[raw] ? legacy[raw][1] : def;
            return { sort: sort, dir: dir, defaultDir: def };
        }
        function renderSort(st, p) {
            sortSel.value = st.sort;
            var o = sortSel.options[sortSel.selectedIndex];
            var other = st.dir === 'asc' ? 'desc' : 'asc';
            document.dispatchEvent(new CustomEvent('ui-listbox-sync'));
            document.querySelector('#jo-dir-ico path').setAttribute('d', DIR_ICONS[st.dir]);
            dirBtn.dataset.dir = st.dir;
            dirBtn.title = o.dataset[st.dir] + ' (cliquer pour inverser)';
            dirBtn.setAttribute('aria-label', 'Ordre : ' + o.dataset[st.dir] + '. Activer pour afficher : ' + o.dataset[other]);
            var u = new URL(location.href);
            if (other === st.defaultDir) u.searchParams.delete('dir'); else u.searchParams.set('dir', other);
            u.searchParams.delete('page');
            dirBtn.href = u.toString();
        }

        function buildUrl(extra) {
            var url = new URL(baseUrl);
            new FormData(filters).forEach(function (v, k) { if (v) url.searchParams.set(k, v); });
            Object.keys(extra || {}).forEach(function (k) {
                if (extra[k]) url.searchParams.set(k, extra[k]); else url.searchParams.delete(k);
            });
            return url.toString();
        }

        async function loadResults(url, push) {
            if (controller) controller.abort();
            controller = new AbortController();
            main.classList.add('jo-loading');
            try {
                var res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal });
                var doc  = new DOMParser().parseFromString(await res.text(), 'text/html');
                var next = doc.getElementById('jo-main');
                if (next) main.innerHTML = next.innerHTML;
                // Barre hors zone rechargée : nombre de résultats et pastille du bouton Filtres
                ['jo-count', 'jo-filters-btn-label', 'jo-filters-head-count', 'jo-see-label'].forEach(function (id) {
                    var cur = document.getElementById(id), nxt = doc.getElementById(id);
                    if (cur && nxt) cur.innerHTML = nxt.innerHTML;
                });
                syncFields(url);
                if (push !== false) history.pushState({}, '', url);
                sessionStorage.setItem(KEY, url);
                if (main.getBoundingClientRect().top < 0) main.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (e) {
                if (e.name !== 'AbortError') window.location.href = url; // repli : chargement classique
            } finally {
                main.classList.remove('jo-loading');
            }
        }

        // Filtres : application immédiate au changement
        filters.addEventListener('change', function () { loadResults(buildUrl({ page: null })); });
        filters.addEventListener('submit', function (e) { e.preventDefault(); loadResults(buildUrl({ page: null })); });
        document.getElementById('jo-reset').addEventListener('click', function (e) {
            e.preventDefault(); sessionStorage.removeItem(KEY); loadResults(this.href);
            (document.getElementById('f-location-btn') || document.getElementById('f-location')).focus({ preventScroll: true }); // le bouton disparaît : le focus ne doit pas se perdre
        });

        // Recherche du hero (envoi + saisie avec délai)
        heroForm.addEventListener('submit', function (e) {
            e.preventDefault(); loadResults(buildUrl({ search: heroInput.value.trim(), page: null }));
        });
        var timer = null;
        heroInput.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { loadResults(buildUrl({ search: heroInput.value.trim(), page: null })); }, 400);
        });

        // Tri : changer de critère remet son ordre par défaut ; le bouton d'ordre inverse l'ordre
        sortSel.addEventListener('change', function (e) {
            loadResults(buildUrl({ sort: e.target.value === 'date' ? null : e.target.value, dir: null, page: null }));
        });
        function toggleDir(e) {
            e.preventDefault();
            var st = sortState(new URL(location.href).searchParams);
            var next = st.dir === 'asc' ? 'desc' : 'asc';
            loadResults(buildUrl({ dir: next === st.defaultDir ? null : next, page: null }));
        }
        dirBtn.addEventListener('click', toggleDir);
        dirBtn.addEventListener('keydown', function (e) { if (e.key === ' ') toggleDir(e); }); // role="button" : Espace aussi

        // Mobile : le bouton Filtres déplie le panneau sous la barre
        var filtersBtn = document.getElementById('jo-filters-btn');
        var filtersBox = document.getElementById('jo-filters');
        // « Voir les N offres » : referme le panneau et amène aux résultats
        document.getElementById('jo-see').addEventListener('click', function () {
            filtersBox.classList.remove('is-open');
            filtersBtn.setAttribute('aria-expanded', 'false');
            document.querySelector('.jo-resbar').scrollIntoView({ behavior: 'smooth', block: 'start' });
            filtersBtn.focus({ preventScroll: true });
        });
        filtersBtn.addEventListener('click', function () {
            var open = !filtersBox.classList.contains('is-open');
            filtersBox.classList.toggle('is-open', open);
            filtersBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (open) (document.getElementById('f-location-btn') || document.getElementById('f-location')).focus({ preventScroll: true });
        });

        // Liens rechargés en AJAX : pagination, puces, « Tout effacer », recherches populaires, état vide
        document.addEventListener('click', function (e) {
            var link = e.target.closest('#jo-main .jo-pagination a, #jo-main .jo-chips a, #jo-main .jo-empty a, #jo-popular a');
            if (!link || e.ctrlKey || e.metaKey || e.shiftKey) return;
            e.preventDefault();
            loadResults(link.href);
        });

        // Favoris (délégation, la liste est rechargée)
        document.addEventListener('submit', function (e) {
            var form = e.target.closest('.save-job-form');
            if (!form) return;
            e.preventDefault();
            var btn = form.querySelector('.save-job-btn');
            var title = btn.getAttribute('aria-label').replace(/^.*?« /, '« ');
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            }).then(function (r) { return r.json(); }).then(function (data) {
                var saved = data.status === 'added';
                btn.classList.toggle('is-saved', saved);
                btn.setAttribute('aria-pressed', saved ? 'true' : 'false');
                btn.title = saved ? 'Retirer des favoris' : 'Sauvegarder l’offre';
                btn.setAttribute('aria-label', btn.title + ' ' + title);
                btn.querySelector('svg').setAttribute('fill', saved ? 'currentColor' : 'none');
            });
        });

        window.addEventListener('popstate', function () { loadResults(window.location.href, false); });

        // Retour sur la liste depuis une autre page : restaure les derniers filtres
        if (window.location.search) {
            sessionStorage.setItem(KEY, window.location.href);
        } else {
            var saved = sessionStorage.getItem(KEY);
            var fromOther = document.referrer && new URL(document.referrer).pathname !== window.location.pathname;
            if (saved && fromOther && new URL(saved).pathname === window.location.pathname) {
                history.replaceState({}, '', saved);
                loadResults(saved, false);
            }
        }
        window.addEventListener('pageshow', function (e) { if (e.persisted) loadResults(window.location.href, false); });
    });
    </script>

    {{-- Fenêtre de confirmation : archivage / réactivation (entreprise propriétaire) --}}
    @auth
        @if(Auth::user()->isCompany())
        <div id="archive-modal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="archive-modal-title">
            <div class="modal">
                <div class="modal-head">
                    <h2 class="modal-title" id="archive-modal-title">Archiver cette offre ?</h2>
                    <p class="modal-msg" id="archive-modal-msg">L'offre ne sera plus visible par les candidats. Vous pourrez la réactiver à tout moment.</p>
                </div>
                <div class="modal-foot">
                    <button type="button" class="jo-btn jo-btn-ghost" id="archive-modal-cancel">Annuler</button>
                    <button type="button" class="jo-btn jo-btn-primary" id="archive-modal-ok">Confirmer</button>
                </div>
            </div>
        </div>
        <script>
        (function () {
            var modal = document.getElementById('archive-modal');
            var ok    = document.getElementById('archive-modal-ok');
            var no    = document.getElementById('archive-modal-cancel');
            var title = document.getElementById('archive-modal-title');
            var msg   = document.getElementById('archive-modal-msg');
            var pending = null, lastFocus = null;

            function open(form, restore) {
                pending = form; lastFocus = document.activeElement;
                title.textContent = restore ? 'Réactiver cette offre ?' : 'Archiver cette offre ?';
                msg.textContent   = restore ? "L'offre sera de nouveau visible par les candidats."
                                            : "L'offre ne sera plus visible par les candidats. Vous pourrez la réactiver à tout moment.";
                ok.textContent    = restore ? 'Réactiver' : 'Archiver';
                ok.style.background = restore ? 'var(--te-primary)' : 'var(--te-danger-solid)';
                modal.classList.add('open'); document.body.style.overflow = 'hidden'; no.focus();
            }
            function close() { pending = null; modal.classList.remove('open'); document.body.style.overflow = ''; if (lastFocus) lastFocus.focus(); }

            no.addEventListener('click', close);
            modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
            ok.addEventListener('click', function () { if (pending) pending.submit(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && modal.classList.contains('open')) close(); });
            document.addEventListener('submit', function (e) {
                var a = e.target.closest('.js-archive-form'), r = e.target.closest('.js-restore-form');
                if (!a && !r) return;
                e.preventDefault(); open(a || r, !!r);
            });
        })();
        </script>
        @endif
    @endauth
</x-app-layout>
