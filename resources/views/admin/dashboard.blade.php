<x-app-layout>
    <x-slot name="title">Tableau de bord</x-slot>

    @php
        // Mini-courbe SVG (6 points), sans JavaScript
        // Mini-courbe SVG (6 points), sans JavaScript. Une valeur null laisse un trou dans la ligne.
        $spark = function (array $values) {
            $w = 96; $h = 32; $pad = 3;
            $max = max(array_map(fn ($v) => $v ?? 0, $values)) ?: 1;
            $n = count($values);
            $runs = [[]]; $last = null;
            foreach ($values as $i => $v) {
                if ($v === null) { $runs[] = []; continue; }
                $x = round($i * ($w - 2 * $pad) / max($n - 1, 1) + $pad, 1);
                $y = round($h - $pad - ($v / $max) * ($h - 2 * $pad), 1);
                $runs[count($runs) - 1][] = "$x,$y";
                $last = [$x, $y];
            }
            $svg = '<svg class="db-spark" width="' . $w . '" height="' . $h . '" viewBox="0 0 ' . $w . ' ' . $h . '" aria-hidden="true" focusable="false">';
            foreach ($runs as $run) {
                if (count($run) > 1) {
                    $svg .= '<polyline points="' . implode(' ', $run) . '" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                } elseif (count($run) === 1) {
                    [$x, $y] = explode(',', $run[0]);
                    $svg .= '<circle cx="' . $x . '" cy="' . $y . '" r="1.8" fill="currentColor"/>';
                }
            }
            if ($last) {
                $svg .= '<circle cx="' . $last[0] . '" cy="' . $last[1] . '" r="2.5" fill="currentColor"/>';
            }
            return $svg . '</svg>';
        };
        $exportUrl = route('admin.stats.export', ['periode' => $period]);
    @endphp

    <x-slot name="header">
        <div class="db-head">
            <div>
                <h1 class="db-title">Tableau de bord</h1>
                <p class="db-date">{{ ucfirst(now()->translatedFormat('l j F Y')) }}</p>
            </div>
            <div class="db-head-actions">
                <nav class="db-period" aria-label="Période des indicateurs">
                    @foreach($periods as $key => $p)
                        <a href="{{ route('admin.dashboard', ['periode' => $key]) }}"
                           class="db-period-opt {{ $period === $key ? 'is-active' : '' }}"
                           aria-label="{{ $p['label'] }}"
                           @if($period === $key) aria-current="page" @endif><span class="db-lg" aria-hidden="true">{{ $p['label'] }}</span><span class="db-sm" aria-hidden="true">{{ $p['short'] }}</span></a>
                    @endforeach
                </nav>
                <a href="{{ $exportUrl }}" class="db-btn-primary">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Exporter CSV
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .db-head { display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
        .db-title { font-size:1.5rem; font-weight:800; color:var(--te-text); letter-spacing:-.02em; margin:0; }
        .db-date { font-size:.875rem; color:var(--te-text-muted); margin:.15rem 0 0; }
        .db-head-actions { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }

        .db-period { display:inline-flex; padding:3px; gap:2px; background:var(--te-surface-3); border:1px solid var(--te-border); border-radius:10px; }
        .db-period-opt { padding:.4rem .75rem; border-radius:7px; font-size:.8125rem; font-weight:600; color:var(--te-text-2); text-decoration:none; white-space:nowrap; }
        .db-period-opt:hover { color:var(--te-text); background:var(--te-surface); }
        .db-period-opt.is-active { background:var(--te-surface); color:var(--te-text); box-shadow:var(--te-shadow-sm); }
        .db-sm { display:none; }
        @media (max-width:640px) {
            .db-lg { display:none; } .db-sm { display:inline; }
            .db-head-actions { width:100%; justify-content:space-between; }
        }

        .db-btn-primary { display:inline-flex; align-items:center; gap:.45rem; padding:.55rem 1rem; border-radius:10px; background:var(--te-primary); color:var(--te-on-primary); font-size:.8125rem; font-weight:700; text-decoration:none; }
        .db-btn-primary:hover { background:var(--te-primary-hover); }

        .db-page { max-width:80rem; margin:0 auto; padding:1.5rem 1.5rem 3rem; display:grid; gap:1.25rem; }
        @media (max-width:640px) { .db-page { padding:1rem 1rem 2rem; } }

        .db-card { background:var(--te-surface); border:1px solid var(--te-border); border-radius:var(--te-radius); padding:1.25rem 1.35rem; min-width:0; }
        .db-card-title { font-size:1rem; font-weight:800; color:var(--te-text); margin:0; }
        .db-card-sub { font-size:.8125rem; color:var(--te-text-muted); margin:.2rem 0 1rem; }
        .db-grid-2 { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:1.25rem; align-items:start; }
        .db-response { margin:.75rem 0 0; padding-top:.75rem; border-top:1px solid var(--te-border-subtle); font-size:.8125rem; color:var(--te-text-2); }
        .db-response b { color:var(--te-text); }
        @media (max-width:767px) { .db-grid-2 { grid-template-columns:1fr; } }

        /* 1. À traiter */
        .db-todo { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; }
        .db-todo-title { font-size:1rem; font-weight:800; color:var(--te-text); margin:0 .5rem 0 0; }
        .db-todo-list { display:flex; gap:.75rem; flex-wrap:wrap; flex:1; list-style:none; margin:0; padding:0; }
        .db-todo-btn { display:flex; align-items:center; gap:.6rem; padding:.6rem .9rem; border-radius:10px; border:1px solid; text-decoration:none; font-size:.875rem; font-weight:600; color:var(--te-text); min-width:13rem; }
        .db-todo-btn .n { font-size:1.15rem; font-weight:800; }
        .db-todo-btn .chev { margin-left:auto; color:var(--te-text-muted); }
        .db-todo-btn.warning { background:var(--te-warning-soft); border-color:var(--te-warning-border); }
        .db-todo-btn.warning .n { color:var(--te-warning-fg); }
        .db-todo-btn.danger { background:var(--te-danger-soft); border-color:var(--te-danger-border); }
        .db-todo-btn.danger .n { color:var(--te-danger-fg); }
        /* Mobile : titre au-dessus, boutons en pleine largeur dessous */
        @media (max-width:640px) {
            .db-todo { flex-direction:column; align-items:stretch; gap:.75rem; }
            .db-todo-title { margin:0; }
            .db-todo-list { flex-direction:column; gap:.5rem; }
            .db-todo-btn { min-width:0; width:100%; }
        }
        .db-todo-btn:hover { box-shadow:var(--te-shadow-sm); }
        .db-todo-empty { display:flex; align-items:center; gap:.5rem; font-size:.9rem; color:var(--te-text-2); margin:0; }
        .db-todo-empty svg { color:var(--te-success-fg); }

        /* 2. Indicateurs */
        .db-tiles { display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:1.25rem; }
        @media (max-width:1100px) { .db-tiles { grid-template-columns:repeat(2, minmax(0, 1fr)); } }
        @media (max-width:520px) { .db-tiles { grid-template-columns:1fr; } }
        .db-tile-label { font-size:.875rem; font-weight:500; color:var(--te-text-muted); margin:0; }
        .db-tile-row { display:flex; align-items:flex-end; justify-content:space-between; gap:.75rem; margin:.35rem 0 .5rem; }
        .db-tile-value { font-size:2rem; font-weight:800; color:var(--te-text); line-height:1.1; letter-spacing:-.02em; }
        .db-spark { color:var(--te-primary-400); flex-shrink:0; }
        .db-tile-sub { font-size:.8125rem; color:var(--te-text-muted); margin:0; }
        .db-tile-delta { font-size:.8125rem; color:var(--te-text-2); margin:.2rem 0 0; display:flex; align-items:center; gap:.3rem; }

        /* 3. Activité mensuelle */
        .db-legend { display:flex; gap:1rem; flex-wrap:wrap; list-style:none; margin:0 0 .75rem; padding:0; font-size:.8125rem; color:var(--te-text-2); }
        .db-legend li { display:flex; align-items:center; gap:.4rem; }
        .db-swatch { width:12px; height:3px; border-radius:2px; display:inline-block; }
        .db-chart { position:relative; height:280px; }
        .db-empty { display:flex; align-items:center; justify-content:center; min-height:120px; border:1px dashed var(--te-border-strong); border-radius:var(--te-radius-sm); color:var(--te-text-muted); font-size:.875rem; text-align:center; padding:1rem; }

        /* 4-5. Barres horizontales */
        .db-bars { list-style:none; margin:0; padding:0; display:grid; gap:.55rem; }
        .db-bar { display:grid; grid-template-columns:minmax(7rem, 38%) 1fr; align-items:center; gap:.75rem; font-size:.8125rem; }
        .db-bar-label { color:var(--te-text-2); text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .db-bar-track { display:flex; align-items:center; gap:.5rem; min-width:0; }
        .db-bar-fill { height:16px; border-radius:3px; background:var(--te-primary); min-width:3px; }
        .db-bar-fill.is-others { background:var(--te-neutral-400); }
        .db-bar-value { color:var(--te-text-2); font-weight:600; font-variant-numeric:tabular-nums; }

        /* 6. Statuts */
        .db-stack { display:flex; height:26px; border-radius:6px; overflow:hidden; gap:2px; background:var(--te-surface-3); }
        .db-stack span { display:block; height:100%; }
        .tone-warning { background:var(--te-warning-solid); }
        .tone-success { background:var(--te-success-solid); }
        .tone-danger  { background:var(--te-danger-solid); }
        .tone-neutral { background:var(--te-neutral-solid); }
        .db-stack-legend { list-style:none; margin:.9rem 0 0; padding:0; display:flex; gap:.5rem 1.25rem; flex-wrap:wrap; font-size:.8125rem; color:var(--te-text-2); }
        .db-stack-legend li { display:flex; align-items:center; gap:.4rem; }
        .db-stack-legend i { width:10px; height:10px; border-radius:2px; display:inline-block; }
        .db-stack-legend b { color:var(--te-text); font-weight:700; }

        /* 7. Activité récente */
        .db-feed { list-style:none; margin:0 -.6rem; padding:0; }
        .db-feed li + li { border-top:1px solid var(--te-border-subtle); }
        .db-feed-item { display:flex; align-items:center; gap:.75rem; padding:.6rem; border-radius:10px; color:inherit; text-decoration:none; transition:background var(--te-transition, .15s); }
        a.db-feed-item:hover { background:var(--te-surface-2); }
        a.db-feed-item:hover .db-feed-title { color:var(--te-primary-fg); }
        a.db-feed-item:focus-visible { outline:2px solid var(--te-primary); outline-offset:-2px; }
        .db-feed-ico { width:34px; height:34px; border-radius:50%; flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; border:1px solid; }
        .db-feed-ico.application { background:var(--te-violet-soft);  color:var(--te-violet-fg);  border-color:var(--te-violet-border); }
        .db-feed-ico.report      { background:var(--te-danger-soft);  color:var(--te-danger-fg);  border-color:var(--te-danger-border); }
        .db-feed-ico.offer       { background:var(--te-warning-soft); color:var(--te-warning-fg); border-color:var(--te-warning-border); }
        .db-feed-ico.user        { background:var(--te-success-soft); color:var(--te-success-fg); border-color:var(--te-success-border); }
        .db-feed-text { flex:1; min-width:0; display:flex; flex-direction:column; }
        .db-feed-title { font-size:.875rem; font-weight:700; color:var(--te-text); }
        .db-feed-detail { font-size:.8125rem; color:var(--te-text-muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .db-feed time { align-self:flex-start; padding-top:.1rem; color:var(--te-text-muted); white-space:nowrap; font-size:.75rem; }
    </style>

    <div class="db-page">

        {{-- 1. À traiter --}}
        <section class="db-card db-todo" aria-labelledby="db-todo-title">
            <h2 class="db-todo-title" id="db-todo-title">À traiter</h2>
            @if(array_sum($todo) === 0)
                <p class="db-todo-empty">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Rien à traiter
                </p>
            @else
                <ul class="db-todo-list">
                    @foreach([
                        ['n' => $todo['offers'],    'tone' => 'warning', 'label' => $todo['offers'] > 1 ? 'offres à modérer' : 'offre à modérer',
                         'url' => route('admin.offers.moderation', ['status' => 'pending_validation'])],
                        ['n' => $todo['companies'], 'tone' => 'warning', 'label' => $todo['companies'] > 1 ? 'entreprises à valider' : 'entreprise à valider',
                         'url' => route('admin.users.moderation', ['role' => 'company', 'validated' => '0'])],
                        ['n' => $todo['reports'],   'tone' => 'danger',  'label' => $todo['reports'] > 1 ? 'signalements à traiter' : 'signalement à traiter',
                         'url' => route('admin.reports.index', ['statut' => 'non-traites'])],
                    ] as $t)
                        @if($t['n'] > 0)
                            <li>
                                <a href="{{ $t['url'] }}" class="db-todo-btn {{ $t['tone'] }}">
                                    <span class="n">{{ $t['n'] }}</span> {{ $t['label'] }}
                                    <svg class="chev" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- 2. Indicateurs --}}
        <section class="db-tiles" aria-label="Indicateurs clés">
            @foreach($tiles as $tile)
                <article class="db-card">
                    <h2 class="db-tile-label">{{ $tile['label'] }}</h2>
                    <div class="db-tile-row">
                        <span class="db-tile-value">{{ $tile['value'] }}</span>
                        @if(array_sum(array_map(fn ($v) => $v ?? 0, $tile['spark'])) > 0)
                            {!! $spark($tile['spark']) !!}
                        @endif
                    </div>
                    @if($tile['sub'])
                        <p class="db-tile-sub">{{ $tile['sub'] }}</p>
                    @endif
                    @if($tile['delta'])
                        <p class="db-tile-delta">
                            @if($tile['delta']['trend'] > 0)
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                            @elseif($tile['delta']['trend'] < 0)
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            @endif
                            {{ $tile['delta']['text'] }}
                        </p>
                    @endif
                </article>
            @endforeach
        </section>

        {{-- 3. Activité (suit la période choisie) --}}
        <section class="db-card" aria-labelledby="db-activity-title">
            <h2 class="db-card-title" id="db-activity-title">Activité</h2>
            <p class="db-card-sub">Offres publiées et candidatures reçues · {{ $periods[$period]['label'] }}</p>
            @if($activity['empty'])
                <div class="db-empty">Pas encore de données sur cette période</div>
            @else
                <ul class="db-legend">
                    <li><span class="db-swatch" style="background:var(--te-primary-fg)"></span>Candidatures</li>
                    <li><span class="db-swatch" style="background:var(--te-text-muted)"></span>Offres publiées</li>
                </ul>
                <div class="db-chart">
                    <canvas id="db-activity" role="img"
                            aria-label="Activité, {{ $periods[$period]['label'] }} : {{ collect($activity['full'])->map(fn($l, $i) => $l.' : '.$activity['offers'][$i].' offres, '.$activity['applications'][$i].' candidatures')->implode(' ; ') }}"></canvas>
                </div>
            @endif
        </section>

        {{-- 4-5. Offres par catégorie / par ville --}}
        <div class="db-grid-2">
            @foreach([
                ['id' => 'cat',  'title' => 'Offres par catégorie', 'data' => $byCategory],
                ['id' => 'city', 'title' => 'Offres par ville',     'data' => $byCity],
            ] as $block)
                <section class="db-card" aria-labelledby="db-{{ $block['id'] }}-title">
                    <h2 class="db-card-title" id="db-{{ $block['id'] }}-title">{{ $block['title'] }}</h2>
                    <p class="db-card-sub">
                        {{ $block['data']['total'] }} offre{{ $block['data']['total'] > 1 ? 's' : '' }} ouverte{{ $block['data']['total'] > 1 ? 's' : '' }} aujourd’hui
                    </p>
                    @if(empty($block['data']['rows']))
                        <div class="db-empty">Aucune offre ouverte</div>
                    @else
                        <ul class="db-bars">
                            @foreach($block['data']['rows'] as $row)
                                <li class="db-bar">
                                    <span class="db-bar-label" title="{{ $row['label'] }}">{{ $row['label'] }}</span>
                                    <span class="db-bar-track">
                                        <span class="db-bar-fill {{ !empty($row['others']) ? 'is-others' : '' }}" style="width:{{ max(1, round($row['total'] / $block['data']['max'] * 85)) }}%"></span>
                                        <span class="db-bar-value">{{ $row['total'] }}</span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            @endforeach
        </div>

        {{-- 6-7. Statuts / Activité récente --}}
        <div class="db-grid-2">
            <section class="db-card" aria-labelledby="db-status-title">
                <h2 class="db-card-title" id="db-status-title">Candidatures par statut</h2>
                <p class="db-card-sub">{{ $byStatus['total'] }} candidature{{ $byStatus['total'] > 1 ? 's' : '' }} · {{ $periods[$period]['label'] }}</p>
                @if($byStatus['total'] === 0)
                    <div class="db-empty">Aucune candidature sur cette période</div>
                @else
                    <div class="db-stack" aria-hidden="true">
                        @foreach($byStatus['segments'] as $seg)
                            @if($seg['count'] > 0)
                                <span class="tone-{{ $seg['tone'] }}" style="flex:{{ $seg['count'] }} 1 0" title="{{ $seg['label'] }} : {{ $seg['count'] }} ({{ $seg['pct'] }} %)"></span>
                            @endif
                        @endforeach
                    </div>
                    <ul class="db-stack-legend">
                        @foreach($byStatus['segments'] as $seg)
                            <li><i class="tone-{{ $seg['tone'] }}" aria-hidden="true"></i>{{ $seg['label'] }} <b>{{ $seg['count'] }}</b> ({{ $seg['pct'] }} %)</li>
                        @endforeach
                    </ul>
                    @if($byStatus['avg_response_days'] !== null)
                        @php
                            $d = $byStatus['avg_response_days'];
                            // < 24 h : « moins d'un jour » ; sinon « 1,8 jour », « 2,5 jours » (pluriel à partir de 2)
                            $dr = round($d, 1);
                            $delay = $d < 1 ? 'moins d’un jour'
                                : str_replace('.', ',', rtrim(rtrim(number_format($dr, 1, '.', ''), '0'), '.')) . ' jour' . ($dr >= 2 ? 's' : '');
                        @endphp
                        <p class="db-response">Délai moyen de réponse : <b>{{ $delay }}</b></p>
                    @endif
                @endif
            </section>

            <section class="db-card" aria-labelledby="db-feed-title">
                <h2 class="db-card-title" id="db-feed-title">Activité récente</h2>
                <p class="db-card-sub">5 derniers événements · {{ $periods[$period]['label'] }}</p>
                @if(empty($recent))
                    <div class="db-empty">Aucune activité récente</div>
                @else
                    @php
                        // Icônes par type (tracés 24 × 24, trait)
                        $feedIcons = [
                            'application' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'report'      => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z',
                            'offer'       => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                            'user'        => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        ];
                    @endphp
                    <ul class="db-feed">
                        @foreach($recent as $e)
                            <li>
                                <{{ $e['url'] ? 'a' : 'div' }} class="db-feed-item" @if($e['url']) href="{{ $e['url'] }}" @endif>
                                    <span class="db-feed-ico {{ $e['type'] }}" aria-hidden="true">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $feedIcons[$e['type']] }}"/></svg>
                                    </span>
                                    <span class="db-feed-text">
                                        <span class="db-feed-title">{{ $e['title'] }}</span>
                                        <span class="db-feed-detail" title="{{ $e['detail'] }}">{{ $e['detail'] }}</span>
                                    </span>
                                    <time datetime="{{ $e['at']->toIso8601String() }}" title="{{ $e['at']->translatedFormat('j F Y à H:i') }}">{{ $e['at']->diffForHumans() }}</time>
                                </{{ $e['url'] ? 'a' : 'div' }}>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>

@if(!$activity['empty'])
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const cssVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    const canvas = document.getElementById('db-activity');
    const fullLabels = @json($activity['full']);
    const tickEvery  = {{ $activity['tick_every'] }};
    const dense      = fullLabels.length > 12;
    if (!canvas || !window.Chart) return;

    // Libellé direct en bout de courbe, dans la couleur de sa courbe
    const endLabels = {
        id: 'endLabels',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            ctx.save();
            ctx.font = '600 12px ' + cssVar('--te-font');
            ctx.textBaseline = 'middle';
            // Positions des étiquettes ; si deux courbes finissent au même niveau, on les écarte
            const labels = chart.data.datasets.map((ds, i) => {
                const meta = chart.getDatasetMeta(i);
                const last = meta.data[meta.data.length - 1];
                return last && !meta.hidden ? { text: ds.label, color: ds.borderColor, x: last.x + 10, y: last.y } : null;
            }).filter(Boolean).sort((a, b) => a.y - b.y);
            const gap = 14;
            for (let i = 1; i < labels.length; i++) {
                const overlap = gap - (labels[i].y - labels[i - 1].y);
                if (overlap > 0) { labels[i - 1].y -= overlap / 2; labels[i].y += overlap / 2; }
            }
            labels.forEach((l) => { ctx.fillStyle = l.color; ctx.fillText(l.text, l.x, l.y); });
            ctx.restore();
        },
    };

    // Violet et gris lisibles en texte (≥ 4,5:1) dans les deux thèmes, car l'étiquette reprend la couleur
    const series = () => ({ apps: cssVar('--te-primary-fg'), offers: cssVar('--te-text-muted') });
    // Toutes les couleurs d'une courbe (trait, points, survol) : aucune couleur par défaut de Chart.js
    const paint = (color) => ({
        borderColor: color, backgroundColor: color,
        pointBackgroundColor: color, pointBorderColor: color,
        pointHoverBackgroundColor: color, pointHoverBorderColor: color,
    });
    Chart.defaults.font.family = cssVar('--te-font');
    const c0 = series();

    const chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: @json($activity['labels']),
            datasets: [
                { label: 'Candidatures', data: @json($activity['applications']),
                  borderWidth: 2.5, pointRadius: dense ? 2 : 3.5, pointHoverRadius: 5, tension: 0, ...paint(c0.apps) },
                { label: 'Offres publiées', data: @json($activity['offers']),
                  borderWidth: 2, pointRadius: dense ? 2 : 3, pointHoverRadius: 5, tension: 0, ...paint(c0.offers) },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { right: 120, top: 8 } },
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                colors: { enabled: false },
                tooltip: { padding: 10, cornerRadius: 8, boxPadding: 4, usePointStyle: true,
                           callbacks: { title: (items) => fullLabels[items[0].dataIndex] } },
            },
            scales: {
                // Libellés espacés en partant du dernier point (toujours libellé), si la largeur manque
                x: { grid: { display: false }, border: { display: false },
                     ticks: { autoSkip: false, maxRotation: 0,
                              callback(value, i) {
                                  const avail = Math.max(1, this.chart.width - 160);
                                  // largeur d'un libellé : environ 6,5 px par caractère (« 29 sept.–4 oct. » plus long que « lun. 12 »)
                                  const per   = Math.max(52, Math.max(...this.chart.data.labels.map((l) => String(l).length)) * 6.5);
                                  const step  = Math.max(tickEvery, Math.ceil(fullLabels.length * per / avail));
                                  return (fullLabels.length - 1 - i) % step === 0 ? this.getLabelForValue(value) : '';
                              } } },
                // Échelle calée sur le maximum de la période, graduations entières
                y: { beginAtZero: true, border: { display: false }, grid: {},
                     suggestedMax: Math.max(4, ...@json($activity['applications']), ...@json($activity['offers'])),
                     ticks: { precision: 0, maxTicksLimit: 6 } },
            },
        },
        plugins: [endLabels],
    });

    // Couleurs lues dans les tokens, réappliquées au changement de thème
    function applyTheme() {
        const c = series();
        Object.assign(chart.data.datasets[0], paint(c.apps));
        Object.assign(chart.data.datasets[1], paint(c.offers));
        chart.options.scales.y.grid.color = cssVar('--te-border-subtle');
        chart.options.scales.x.ticks.color = cssVar('--te-text-muted');
        chart.options.scales.y.ticks.color = cssVar('--te-text-muted');
        Object.assign(chart.options.plugins.tooltip, {
            backgroundColor: cssVar('--te-text'), titleColor: cssVar('--te-surface'), bodyColor: cssVar('--te-surface'),
        });
        chart.update(); // mise à jour complète : les points sont recalculés avec leurs nouvelles couleurs
    }
    applyTheme();
    new MutationObserver(applyTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
})();
</script>
@endpush
@endif
</x-app-layout>
