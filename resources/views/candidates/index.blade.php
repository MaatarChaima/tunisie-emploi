<x-app-layout>
    <x-slot name="title">Candidats</x-slot>

    <x-slot name="header">
        <x-page-header title="Rechercher des candidats" subtitle="Trouvez les profils qui correspondent à vos besoins" icon="search"
             />
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
        .flt-sidebar { width:15rem; flex-shrink:0; }
        @media(max-width:1024px){ .flt-sidebar { width:100%; position:static; } }
        /* Mobile : panneau replié derrière le bouton « Filtres (n) » (toujours visible sans JavaScript) */
        .cand-filters-btn { display:none; }
        @media(max-width:1024px){
            .js .cand-filters-btn { display:inline-flex; align-items:center; gap:.45rem; height:2.375rem; padding:0 .85rem; border:1px solid var(--te-input-border); border-radius:8px; background:var(--te-input-bg); color:var(--te-text); font:inherit; font-size:.8125rem; font-weight:600; cursor:pointer; align-self:flex-start; }
            .cand-filters-btn[aria-expanded="true"] { border-color:var(--te-primary-border); background:var(--te-primary-soft); color:var(--te-primary-fg); }
            .cand-filters-btn:focus-visible { outline:none; border-color:var(--te-primary); box-shadow:0 0 0 3px var(--te-primary-ring); }
            .js .flt-sidebar:not(.is-open) { display:none; }
            .pg-2col { gap:1rem; }
        }
        .flt-sidebar .jo-filters-body { display:grid; }
        .cand-exp .jo-type { min-height:2.4rem; }
        .chip-active a { display:inline-flex; align-items:center; justify-content:center; width:1.25rem; height:1.25rem; margin-right:-.3rem; border-radius:50%; color:inherit; text-decoration:none; }
        .chip-active a:hover { background:var(--te-primary-border); }
        .chip-active a:focus-visible { outline:2px solid var(--te-primary); outline-offset:1px; }
        .pg-main { flex:1; min-width:0; }
        .res-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:.75rem; }
        .res-count { font-size:.875rem; font-weight:600; color:var(--te-text-muted); }
        .res-count strong { color:var(--te-primary-fg); font-weight:800; }
        .chip-row { display:flex; flex-wrap:wrap; gap:.4rem; }
        .chip-active { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .7rem; border-radius:99px; font-size:.75rem; font-weight:600; background:var(--te-primary-soft); color:var(--te-primary-fg); border:1px solid var(--te-primary-border); }
        .grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
        @media(max-width:860px){ .grid-2 { grid-template-columns:1fr; } }
        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--te-primary); color:var(--te-on-primary); border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--te-primary-hover); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .cand-avatar { width:44px; height:44px; border-radius:10px; background:var(--te-primary-soft); border:1.5px solid var(--te-primary-border); display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:800; color:var(--te-primary-fg); flex-shrink:0; }
        .empty-box { grid-column:1/-1; background:var(--te-surface); border:2px dashed var(--te-border); border-radius:var(--r); padding:3.5rem 2rem; text-align:center; }
        .empty-ico { width:52px; height:52px; border-radius:14px; background:var(--te-primary-soft); margin:0 auto .875rem; display:flex; align-items:center; justify-content:center; }
        .empty-box p { font-size:.85rem; color:var(--te-text-muted); margin-bottom:1.25rem; max-width:300px; margin-left:auto; margin-right:auto; line-height:1.6; }
    </style>

    <div class="pg">
        <div class="pg-2col">

            {{-- Filtres : même panneau que la liste des offres (styles partagés dans app.css) --}}
            @php
                $activeCount = collect(['city','category_id','education_level','experience_years'])
                    ->filter(fn($k) => request($k))->count();
                $plural = fn (int $n, string $w) => $n . ' ' . $w . ($n > 1 ? 's' : '');
                $cityOptions = [['value' => '', 'label' => 'Toutes les villes']];
                foreach (['Tunis','Ariana','Ben Arous','Manouba','Nabeul','Sousse','Monastir','Mahdia','Sfax','Bizerte','Kairouan','Gafsa','Gabès','Djerba'] as $city) {
                    $n = (int) ($cityCounts[$city] ?? 0);
                    $cityOptions[] = ['value' => $city, 'label' => $city, 'count' => $n, 'sr' => ', ' . $plural($n, 'candidat')];
                }
                $domainOptions = [['value' => '', 'label' => 'Tous les domaines', 'plain' => true, 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'neutral' => true]];
                foreach ($categories as $category) {
                    $n = (int) ($domainCounts[$category->id] ?? 0);
                    $domainOptions[] = ['value' => $category->id, 'label' => $category->name, 'icon' => $category->iconPath(), 'count' => $n, 'sr' => ', ' . $plural($n, 'candidat')];
                }
                $levelOptions = [['value' => '', 'label' => 'Tous les niveaux', 'icon' => \App\Support\Education::NEUTRAL_ICON, 'neutral' => true, 'plain' => true]];
                foreach (\App\Support\Education::LEVELS as $level => $levelLabel) {
                    $n = (int) ($levelCounts[$level] ?? 0);
                    $levelOptions[] = ['value' => $level, 'label' => $levelLabel, 'icon' => \App\Support\Education::icon($level), 'count' => $n, 'sr' => ', ' . $plural($n, 'candidat')];
                }
                $expTiles = ['' => 'Toutes', '1' => '1 an et +', '3' => '3 ans et +', '5' => '5 ans et +', '10' => '10 ans et +'];
            @endphp

            <button type="button" class="cand-filters-btn" id="cand-filters-btn" aria-controls="cand-filters" aria-expanded="false">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8.5V19l-4 2v-8.5L3 4z"/></svg>
                {{ 'Filtres' . ($activeCount ? ' (' . $activeCount . ')' : '') }}
            </button>

            <aside class="flt-sidebar jo-filters" id="cand-filters" aria-label="Filtres">
                <div class="jo-filters-head">
                    <svg class="jo-filters-head-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18l-7 8.5V19l-4 2v-8.5L3 4z"/></svg>
                    <h2 class="jo-filters-title">Filtres</h2>
                    @if($activeCount > 0)
                        <span class="jo-badge-count" aria-label="{{ $activeCount }} filtre{{ $activeCount > 1 ? 's' : '' }} actif{{ $activeCount > 1 ? 's' : '' }}">{{ $activeCount }}</span>
                    @endif
                </div>

                {{-- Formulaire GET classique : envoyé à chaque changement avec JavaScript, par « Appliquer » sans --}}
                <form method="GET" action="{{ route('candidates.index') }}" class="jo-filters-body" id="cand-filter-form">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="jo-field jo-field-city">
                        <label for="f-city" id="f-city-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>Ville</label>
                        <x-ui.listbox id="f-city" name="city" :options="$cityOptions" :selected="request('city', '')"
                                      labelledby="f-city-label" button-class="jo-select" class="jo-select" />
                    </div>

                    <div class="jo-field jo-field-domain">
                        <label for="f-category" id="f-category-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>Domaine</label>
                        <x-ui.listbox id="f-category" name="category_id" :options="$domainOptions" :selected="request('category_id', '')"
                                      labelledby="f-category-label" button-class="jo-select" class="jo-select" />
                    </div>

                    <div class="jo-field jo-field-level">
                        <label for="f-education" id="f-education-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Niveau d'études</label>
                        <x-ui.listbox id="f-education" name="education_level" :options="$levelOptions" :selected="request('education_level', '')"
                                      labelledby="f-education-label" button-class="jo-select" class="jo-select" />
                    </div>

                    {{-- Expérience minimale : tuiles à bascule (vrais boutons radio, flèches du clavier natives) --}}
                    <div class="jo-field jo-field-types">
                        <span class="jo-field-label" id="f-exp-label"><svg class="jo-lbl-ico" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Expérience minimale</span>
                        <div class="jo-types cand-exp" role="radiogroup" aria-labelledby="f-exp-label">
                            @foreach($expTiles as $value => $label)
                                <label class="jo-type">
                                    <input type="radio" name="experience_years" value="{{ $value }}" id="f-exp-{{ $value === '' ? 'all' : $value }}"
                                           @checked((string) request('experience_years', '') === (string) $value)>
                                    <span>{{ $label }}</span>
                                    <svg class="jo-type-check" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="jo-filters-actions">
                        <button type="submit" class="jo-btn jo-btn-primary jo-apply">Appliquer</button>
                        @php $total = $candidates->total(); @endphp
                        <button type="button" class="jo-btn jo-btn-primary jo-see" id="cand-see">{{ $total === 0 ? 'Aucun candidat' : ($total === 1 ? 'Voir le candidat' : 'Voir les ' . $total . ' candidats') }}</button>
                        @if($activeCount > 0)
                            <a href="{{ route('candidates.index') }}" class="jo-btn jo-btn-ghost jo-reset" id="cand-reset"
                               title="Réinitialiser les filtres" aria-label="Réinitialiser les filtres ({{ $activeCount }} actif{{ $activeCount > 1 ? 's' : '' }})">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v5h5"/></svg>
                                <span>Réinitialiser ({{ $activeCount }})</span>
                            </a>
                        @endif
                    </div>
                </form>
            </aside>

            {{-- Main --}}
            <div class="pg-main">

                {{-- Results bar --}}
                <div class="res-bar">
                    <p class="res-count">
                        <strong>{{ $candidates->total() }}</strong>
                        candidat{{ $candidates->total() > 1 ? 's' : '' }} trouvé{{ $candidates->total() > 1 ? 's' : '' }}
                    </p>

                    @if(request()->hasAny(['city','category_id','education_level','experience_years']))
                        <div class="chip-row">
                            @if(request('city'))
                                <span class="chip-active">
                                    {{ request('city') }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['city', 'page']) }}" aria-label="Retirer le filtre : {{ request('city') }}"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></a>
                                </span>
                            @endif
                            @if(request('category_id'))
                                @php $catN = $categories->firstWhere('id', request('category_id'))?->name ?? ''; @endphp
                                @if($catN)
                                    <span class="chip-active">
                                        {{ $catN }}
                                        <a href="{{ request()->fullUrlWithoutQuery(['category_id', 'page']) }}" aria-label="Retirer le filtre : {{ $catN }}"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></a>
                                    </span>
                                @endif
                            @endif
                            @if(request('education_level'))
                                <span class="chip-active">
                                    {{ \App\Support\Education::label(request('education_level')) }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['education_level', 'page']) }}" aria-label="Retirer le filtre : {{ \App\Support\Education::label(request('education_level')) }}"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></a>
                                </span>
                            @endif
                            @if(request('experience_years'))
                                <span class="chip-active">
                                    {{ \App\Support\Experience::years((int) request('experience_years')) }} et +
                                    <a href="{{ request()->fullUrlWithoutQuery(['experience_years', 'page']) }}" aria-label="Retirer le filtre : {{ \App\Support\Experience::years((int) request('experience_years')) . ' et +' }}"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Cards grid --}}
                <div class="grid-2">
                    @forelse($candidates as $candidate)
                        <div class="uc" style="cursor:pointer;"
                             onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('candidates.show', $candidate) }}'">
                            <div class="uc-body">
                                <div style="display:flex;align-items:flex-start;gap:.875rem;margin-bottom:.75rem;">
                                    <div class="cand-avatar">
                                        {{ $candidate->initials }}
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <a href="{{ route('candidates.show', $candidate) }}"
                                           style="font-size:.9rem;font-weight:800;color:var(--te-text);text-decoration:none;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;transition:color var(--ease);"
                                           onmouseover="this.style.color='var(--te-primary-fg)'" onmouseout="this.style.color='var(--te-text)'">
                                            {{ $candidate->name }}
                                        </a>
                                        @if($candidate->domain)
                                            <div style="font-size:.78rem;font-weight:700;color:var(--te-primary-fg);margin-top:.2rem;">
                                                {{ $candidate->domain }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="uc-meta">
                                    @if($candidate->city)
                                        <span class="uc-pill">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <circle cx="12" cy="11" r="3"/>
                                            </svg>
                                            {{ $candidate->city }}
                                        </span>
                                    @endif
                                    @if(isset($candidate->experience_years))
                                        <span class="uc-pill hi">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \App\Support\Experience::label($candidate->experience_years, 'candidate', 'd’exp.') }}
                                        </span>
                                    @endif
                                    @if($candidate->education_level)
                                        <span class="uc-pill am">
                                            <x-education-icon :level="$candidate->education_level" :size="11" />
                                            {{ \App\Support\Education::label($candidate->education_level) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="uc-foot">
                                <span></span>
                                <a href="{{ route('candidates.show', $candidate) }}" class="btn-p">
                                    Voir le profil
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            <div class="empty-ico">
                                <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3>Aucun candidat trouvé</h3>
                            <p>Aucun candidat ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('candidates.index') }}" class="btn-p" style="display:inline-flex;">
                                Voir tous les candidats
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($candidates->hasPages())
                    <div style="margin-top:2rem;">
                        {{ $candidates->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@push('scripts')
<script>
(function () {
    var KEY = 'candidates_index_url';
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
    // Filtres : envoi immédiat (léger délai : les flèches du clavier parcourent les tuiles)
    var form = document.getElementById('cand-filter-form');
    var FOCUS_KEY = 'candidates_filter_focus';
    if (form) {
        var timer = null;
        form.addEventListener('change', function (e) {
            var t = e.target;
            var focusId = t.type === 'radio' ? t.id : (t.id ? t.id + '-btn' : '');
            clearTimeout(timer);
            timer = setTimeout(function () {
                try { sessionStorage.setItem(FOCUS_KEY, focusId); } catch (err) {}
                form.requestSubmit ? form.requestSubmit() : form.submit();
            }, t.type === 'radio' ? 350 : 0);
        });
        // URL propre : pas de paramètres vides (?city=&…)
        form.addEventListener('submit', function () {
            Array.prototype.forEach.call(form.elements, function (el) {
                if (el.name && el.value === '' && (el.type !== 'radio' || el.checked)) el.disabled = true;
            });
        });
        // Mobile : bouton « Filtres (n) », panneau rouvert après un rechargement dû à un filtre
        var fBtn = document.getElementById('cand-filters-btn'), fBox = document.getElementById('cand-filters');
        var OPEN_KEY = 'candidates_filters_open';
        function setOpen(open) {
            fBox.classList.toggle('is-open', open);
            fBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            try { open ? sessionStorage.setItem(OPEN_KEY, '1') : sessionStorage.removeItem(OPEN_KEY); } catch (err) {}
        }
        try { if (sessionStorage.getItem(OPEN_KEY)) setOpen(true); } catch (err) {}
        fBtn.addEventListener('click', function () { setOpen(!fBox.classList.contains('is-open')); });
        document.getElementById('cand-see').addEventListener('click', function () {
            setOpen(false);
            document.querySelector('.res-bar').scrollIntoView({ behavior: 'smooth', block: 'start' });
            fBtn.focus({ preventScroll: true });
        });

        var again = null;
        try { again = sessionStorage.getItem(FOCUS_KEY); sessionStorage.removeItem(FOCUS_KEY); } catch (err) {}
        if (again) {
            var el = document.getElementById(again);
            if (el) setTimeout(function () { el.focus({ preventScroll: true }); }, 60);
        }
    }

    // Clear on explicit reset link
    document.querySelectorAll('a[href="{{ route('candidates.index') }}"]').forEach(function(a) {
        a.addEventListener('click', function() { sessionStorage.removeItem(KEY); });
    });
})();
</script>
@endpush

</x-app-layout>
