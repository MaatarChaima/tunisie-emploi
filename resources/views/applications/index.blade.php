<x-app-layout>
    <x-slot name="title">Mes candidatures</x-slot>

    <x-slot name="header">
        <x-page-header title="Mes candidatures" subtitle="Suivez l'avancement de toutes vos candidatures" icon="document"
        >
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:var(--te-primary);color:var(--te-on-primary);font-size:.75rem;font-weight:700;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                </svg>
                {{ $applications->total() }} candidature{{ $applications->total() > 1 ? 's' : '' }}
            </span>
        </x-page-header>
    </x-slot>

    <style>
        :root {
            --r:      14px;
            --r-sm:   8px;
            --t:      .18s cubic-bezier(.4,0,.2,1);
        }
        *, *::before, *::after { box-sizing: border-box; }

        .ac-page {
            max-width: 80rem;
            margin: 0 auto;
            padding: 1.75rem 1.5rem 5rem;
            font-family: var(--te-font);
        }
        @media (max-width:640px) { .ac-page { padding: 1rem 1rem 4rem; } }

        /* ── Stats strip ────────────────────────────── */
        .ac-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .75rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width:700px) { .ac-stats { grid-template-columns: repeat(2, 1fr); } }

        .ac-stat {
            background:var(--te-surface);
            border:1px solid var(--te-border);
            border-radius:var(--r);
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            cursor: pointer;
            transition: border-color var(--t), box-shadow var(--t), transform var(--t);
        }
        .ac-stat:hover { border-color:var(--te-info-border); transform: translateY(-1px); box-shadow:0 4px 14px rgba(37,99,235,.07); }
        .ac-stat.active { border-color:var(--te-info-solid); box-shadow:0 0 0 3px rgba(37,99,235,.1); }

        .ac-stat-icon {
            width: 36px; height: 36px; border-radius:9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .ac-stat-icon.all      { background:var(--te-primary); }
        .ac-stat-icon.pending  { background:var(--te-warning-soft); border:1px solid var(--te-warning-border); }
        .ac-stat-icon.accepted { background:var(--te-success-soft); border:1px solid var(--te-success-border); }
        .ac-stat-icon.rejected { background:var(--te-danger-soft); border:1px solid var(--te-danger-border); }

        .ac-stat-num {
            font-family: var(--te-font);
            font-size: 1.35rem; font-weight: 800;
            color:var(--te-primary-fg); line-height: 1;
        }
        .ac-stat-lbl {
            font-size: .75rem; font-weight: 600;
            color:var(--te-text-muted); margin-top: .15rem;
            white-space: nowrap;
        }

        /* ── Grid ───────────────────────────────────── */
        .ac-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0,1fr));
            gap: 1.25rem;
        }
        @media (max-width:1100px) { .ac-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width:640px)  { .ac-grid { grid-template-columns: 1fr; } }

        /* ── Card ───────────────────────────────────── */
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .ac-card {
            background:var(--te-surface);
            border:1px solid var(--te-border);
            border-radius:var(--r);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: cardIn .25s ease both;
            transition: border-color var(--t), box-shadow var(--t), transform var(--t);
            position: relative;
        }
        .ac-card:hover {
            border-color:var(--te-info-border);
            box-shadow:0 6px 22px rgba(37,99,235,.09), 0 1px 4px rgba(0,0,0,.04);
            transform: translateY(-2px);
        }
        .ac-card:nth-child(1) { animation-delay:.03s; }
        .ac-card:nth-child(2) { animation-delay:.07s; }
        .ac-card:nth-child(3) { animation-delay:.11s; }
        .ac-card:nth-child(4) { animation-delay:.15s; }
        .ac-card:nth-child(5) { animation-delay:.19s; }
        .ac-card:nth-child(6) { animation-delay:.23s; }

        /* Status top accent line */
        .ac-card-accent {
            height: 3px;
            width: 100%;
        }
        .ac-card.pending  .ac-card-accent { background:var(--te-warning-solid); }
        .ac-card.accepted .ac-card-accent { background:var(--te-success-solid); }
        .ac-card.rejected .ac-card-accent { background:var(--te-danger-solid); }

        /* Card body */
        .ac-card-body { padding: 1.25rem 1.25rem 1rem; flex: 1; }

        /* Header: logo + company + badge */
        .ac-card-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .ac-logo {
            width: 42px; height: 42px; border-radius:10px;
            border:1px solid var(--te-border); object-fit: cover;
            background:var(--te-surface); flex-shrink: 0;
        }
        .ac-logo-ph {
            width: 42px; height: 42px; border-radius:10px;
            background:var(--te-primary);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--te-font); font-size: .9rem; font-weight: 800;
            color:var(--te-on-primary); flex-shrink: 0;
        }

        .ac-company-name {
            font-size: .85rem; font-weight: 700;
            color:var(--te-info-fg); line-height: 1.3;
        }
        .ac-company-loc {
            display: flex; align-items: center; gap: .3rem;
            font-size: .75rem; color:var(--te-text-muted); margin-top: .25rem;
        }

        /* Status badge */
        .ac-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .2rem .6rem; border-radius:99px;
            font-size: .75rem; font-weight: 700;
            white-space: nowrap; border:1px solid; margin-left: auto;
            flex-shrink: 0;
        }
        .ac-badge-dot { width: 5px; height: 5px; border-radius:50%; }
        .ac-badge.pending  { background:var(--te-warning-soft);  color:var(--te-warning-fg);  border-color:var(--te-warning-border); }
        .ac-badge.accepted { background:var(--te-success-soft); color:var(--te-success-fg); border-color:var(--te-success-border); }
        .ac-badge.rejected { background:var(--te-danger-soft); color:var(--te-danger-fg); border-color:var(--te-danger-border); }
        .ac-badge.pending  .ac-badge-dot { background:var(--te-warning-solid); }
        .ac-badge.accepted .ac-badge-dot { background:var(--te-success-solid); }
        .ac-badge.rejected .ac-badge-dot { background:var(--te-danger-solid); }

        /* Job title */
        .ac-job-title {
            font-family: var(--te-font);
            font-size: 1.05rem; font-weight: 800;
            color:var(--te-primary-fg); text-decoration: none;
            display: block; line-height: 1.4;
            margin-bottom: .85rem;
            transition: color var(--t);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .ac-job-title:hover { color:var(--te-info-fg); }

        /* Meta pills */
        .ac-meta { display: flex; flex-wrap: wrap; gap: .3rem; margin-bottom: .5rem; }
        .ac-pill {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .25rem .65rem; border-radius:8px;
            font-size: .75rem; font-weight: 600;
            background:var(--te-surface-2); color:var(--te-primary-fg);
            border:1px solid var(--te-border);
        }
        .ac-pill svg { width: 10px; height: 10px; color:var(--te-text-muted); flex-shrink: 0; }
        .ac-pill.salary { background:var(--te-primary-soft); color:var(--te-info-fg); border-color:var(--te-info-border); font-weight: 700; }
        .ac-pill.salary svg { color:var(--te-info-fg); }

        /* Company comment */
        .ac-comment {
            padding: .7rem 1.25rem .8rem;
            background:var(--te-surface-2);
            border-top:1px solid var(--te-border);
        }
        .ac-comment-label {
            font-size: .75rem; font-weight: 700; color:var(--te-text-muted); margin-bottom: .2rem;
        }
        .ac-comment-text { font-size: .8rem; color:var(--te-text); line-height: 1.6; white-space: pre-line; overflow-wrap: anywhere; }

        /* Status banner */
        .ac-status-banner {
            display: flex; align-items: center; gap: .6rem;
            padding: .6rem 1.25rem;
            font-size: .78rem; font-weight: 600;
            border-top:1px solid transparent;
        }
        .ac-status-banner.accepted { background:var(--te-success-soft); border-color:var(--te-success-border); color:var(--te-success-fg); }
        .ac-status-banner.rejected { background:var(--te-danger-soft); border-color:var(--te-danger-border); color:var(--te-danger-fg); }

        /* Card footer */
        .ac-card-foot {
            display: flex; align-items: center; justify-content: space-between;
            padding: .65rem 1.25rem;
            border-top:1px solid var(--te-border-subtle);
            background:var(--te-surface-2);
            gap: .5rem;
        }

        .ac-date {
            display: flex; align-items: center; gap: .3rem;
            font-size: .75rem; color:var(--te-text-muted); font-weight: 500;
        }

        .ac-btn-view {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .45rem 1rem; border-radius:var(--r-sm);
            background:var(--te-primary); color:var(--te-on-primary); border:none;
            font-family: var(--te-font); font-size: .8rem; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: background var(--t), transform var(--t);
        }
        .ac-btn-view:hover { background:var(--te-primary-hover); transform: translateY(-1px); }

        /* ── Empty state ────────────────────────────── */
        .ac-empty {
            grid-column: 1 / -1;
            background:var(--te-surface);
            border:1px solid var(--te-border);
            border-radius:var(--r);
            padding: 4rem 2rem;
            text-align: center;
        }
        .ac-empty-icon {
            width: 56px; height: 56px; border-radius:14px;
            background:var(--te-primary);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .ac-empty h3 { font-family: var(--te-font); font-size: 1.05rem; font-weight: 800; color:var(--te-primary-fg); margin: 0 0 .4rem; }
        .ac-empty p  { font-size: .8rem; color:var(--te-text-muted); margin: 0 auto 1.5rem; max-width: 320px; line-height: 1.6; }
        .ac-empty-btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .8rem 1.5rem; border-radius:10px;
            background:var(--te-primary); color:var(--te-on-primary);
            font-family: var(--te-font); font-size: .9rem; font-weight: 800;
            text-decoration: none; transition: background var(--t);
        }
        .ac-empty-btn:hover { background:var(--te-primary-hover); }
            .ac-closed { display:inline-block; margin:.15rem 0 .35rem; padding:.12rem .5rem; border-radius:6px; background:var(--te-surface-3); border:1px solid var(--te-border); color:var(--te-text-2); font-size:.75rem; font-weight:700; }
        span.ac-job-title { cursor:default; }
    </style>

    <div class="ac-page">

        {{-- ── Stats strip / filter ─────────────────── --}}
        @php
            $totalCount    = $applications->total();
            $pendingCount  = $applications->getCollection()->where('status','pending')->count();
            $acceptedCount = $applications->getCollection()->where('status','accepted')->count();
            $rejectedCount = $applications->getCollection()->where('status','rejected')->count();
        @endphp

        <div class="ac-stats">
            <div class="ac-stat active" onclick="filterCards('all', this)" data-filter="all">
                <div class="ac-stat-icon all">
                    <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $totalCount }}</div>
                    <div class="ac-stat-lbl">Total</div>
                </div>
            </div>

            <div class="ac-stat" onclick="filterCards('pending', this)" data-filter="pending">
                <div class="ac-stat-icon pending">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-warning-fg)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $pendingCount }}</div>
                    <div class="ac-stat-lbl">En attente</div>
                </div>
            </div>

            <div class="ac-stat" onclick="filterCards('accepted', this)" data-filter="accepted">
                <div class="ac-stat-icon accepted">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-success-fg)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $acceptedCount }}</div>
                    <div class="ac-stat-lbl">Acceptées</div>
                </div>
            </div>

            <div class="ac-stat" onclick="filterCards('rejected', this)" data-filter="rejected">
                <div class="ac-stat-icon rejected">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-danger-fg)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <div class="ac-stat-num">{{ $rejectedCount }}</div>
                    <div class="ac-stat-lbl">Refusées</div>
                </div>
            </div>
        </div>

        {{-- ── Grid ────────────────────────────────── --}}
        <div class="ac-grid" id="ac-grid">

            @forelse($applications as $application)
                @php
                    $statusMap = [
                        'pending'  => ['label' => 'En attente', 'cls' => 'pending'],
                        'accepted' => ['label' => 'Acceptée',   'cls' => 'accepted'],
                        'rejected' => ['label' => 'Refusée',    'cls' => 'rejected'],
                    ];
                    $s = $statusMap[$application->status] ?? ['label' => ucfirst($application->status), 'cls' => 'pending'];
                    $typeMap = ['full-time'=>'Temps plein','part-time'=>'Temps partiel','remote'=>'Télétravail','freelance'=>'Freelance','internship'=>'Stage'];
                @endphp

                @php $canView = $application->jobOffer->isViewableBy(Auth::user()); @endphp
                <div class="ac-card {{ $s['cls'] }}" data-status="{{ $application->status }}"
                     @if($canView)
                     style="cursor:pointer;"
                     onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('job-offers.show', $application->jobOffer) }}'"
                     @endif>

                    {{-- Top accent --}}
                    <div class="ac-card-accent"></div>

                    <div class="ac-card-body">

                        {{-- Header: logo + company + badge --}}
                        <div class="ac-card-header">
                            <x-company-logo :company="$application->jobOffer->company" :size="44" />

                            <div style="flex:1;min-width:0;">
                                <div class="ac-company-name">{{ $application->jobOffer->company->name }}</div>
                                <div class="ac-company-loc">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $application->jobOffer->location }}
                                </div>
                            </div>

                            <span class="ac-badge {{ $s['cls'] }}">
                                <span class="ac-badge-dot"></span>
                                {{ $s['label'] }}
                            </span>
                        </div>

                        {{-- Job title --}}
                        @if($canView)
                            <a href="{{ route('job-offers.show', $application->jobOffer) }}" class="ac-job-title">
                                {{ $application->jobOffer->title }}
                            </a>
                        @else
                            <span class="ac-job-title">{{ $application->jobOffer->title }}</span>
                        @endif
                        @if($application->jobOffer->closedBadge())
                            <span class="ac-closed">{{ $application->jobOffer->closedBadge() }}</span>
                        @endif

                        {{-- Meta --}}
                        <div class="ac-meta">
                            <span class="ac-pill">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $typeMap[$application->jobOffer->type] ?? $application->jobOffer->type }}
                            </span>
                            @if($application->jobOffer->salary)
                                <span class="ac-pill salary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $application->jobOffer->salaryLabel() }}
                                </span>
                            @endif
                        </div>

                    </div>

                    {{-- Status banner --}}
                    @if($application->status === 'accepted')
                        <div class="ac-status-banner accepted">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:var(--te-success-fg)">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Félicitations ! Votre candidature a été acceptée.
                        </div>
                    @elseif($application->status === 'rejected')
                        <div class="ac-status-banner rejected">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="color:var(--te-danger-fg)">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cette candidature n'a pas été retenue.
                        </div>
                    @endif

                    {{-- Commentaire laissé par l'entreprise avec sa décision --}}
                    @if(in_array($application->status, ['accepted', 'rejected'], true) && filled($application->company_comment))
                        <div class="ac-comment">
                            <p class="ac-comment-label">Message de l'entreprise :</p>
                            <p class="ac-comment-text">{{ $application->company_comment }}</p>
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="ac-card-foot">
                        <span class="ac-date">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
                                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2" stroke-linecap="round"/>
                                <line x1="8"  y1="2" x2="8"  y2="6" stroke-width="2" stroke-linecap="round"/>
                                <line x1="3"  y1="10" x2="21" y2="10" stroke-width="2"/>
                            </svg>
                            {{ $application->created_at->translatedFormat('d/m/Y') }}
                        </span>
                        @if($canView)
                        <a href="{{ route('job-offers.show', $application->jobOffer) }}" class="ac-btn-view">
                            Voir l'offre
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>

            @empty
                <div class="ac-empty">
                    <div class="ac-empty-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-info-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3>Aucune candidature pour le moment</h3>
                    <p>Explorez les offres disponibles et postulez dès maintenant pour commencer votre parcours.</p>
                    <a href="{{ route('job-offers.index') }}" class="ac-empty-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Découvrir les offres
                    </a>
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        @if($applications->hasPages())
            {{ $applications->links() }}
        @endif

    </div>

    <script>
        function filterCards(status, el) {
            document.querySelectorAll('.ac-stat').forEach(s => s.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.ac-card').forEach(card => {
                card.style.display = (status === 'all' || card.dataset.status === status) ? 'flex' : 'none';
            });
        }
    </script>

</x-app-layout>