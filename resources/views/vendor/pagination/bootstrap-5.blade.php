@if ($paginator->hasPages())
@once
<style>
/* ── Pagination – global design token alignment ─────────────── */
.pgn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-top:1px solid var(--te-border);
    flex-wrap: wrap;
}
.pgn-info {
    font-size: .78rem;
    font-weight: 500;
    color:var(--te-text-muted);
    white-space: nowrap;
}
.pgn-info strong {
    color:var(--te-text);
    font-weight: 700;
}
.pgn-nav {
    display: flex;
    align-items: center;
    gap: .3rem;
    flex-wrap: wrap;
}

/* ── Base button ─────────────────────────────────────────────── */
.pgn-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .3rem;
    min-width: 36px;
    height: 36px;
    padding: 0 .6rem;
    font-size: .8rem;
    font-weight: 600;
    color:var(--te-text-2);
    background:var(--te-surface);
    border:1.5px solid var(--te-border);
    border-radius:8px;
    text-decoration: none;
    cursor: pointer;
    font-family: inherit;
    transition: color .18s cubic-bezier(.4,0,.2,1),
                background .18s cubic-bezier(.4,0,.2,1),
                border-color .18s cubic-bezier(.4,0,.2,1),
                transform .18s cubic-bezier(.4,0,.2,1),
                box-shadow .18s cubic-bezier(.4,0,.2,1);
    white-space: nowrap;
    line-height: 1;
}
.pgn-btn:hover {
    color:var(--te-primary-fg);
    border-color:var(--te-primary);
    background:var(--te-primary-soft);
    transform: translateY(-1px);
    box-shadow:0 2px 8px rgba(79,70,229,.15);
}
.pgn-btn:active { transform: translateY(0); }

/* ── Active page ─────────────────────────────────────────────── */
.pgn-btn.pgn-active {
    background:var(--te-primary);
    color:var(--te-on-primary);
    border-color:var(--te-primary);
    font-weight: 700;
    box-shadow:0 2px 8px rgba(79,70,229,.3);
    cursor: default;
    pointer-events: none;
}

/* ── Disabled (prev/next on first/last page) ─────────────────── */
.pgn-btn.pgn-disabled,
.pgn-btn[disabled] {
    color:var(--te-text-muted);
    border-color:var(--te-border-subtle);
    background:var(--te-surface-2);
    cursor: not-allowed;
    opacity: 1;
    transform: none;
    box-shadow:none;
    pointer-events: none;
}

/* ── Ellipsis dots ───────────────────────────────────────────── */
.pgn-dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 36px;
    font-size: .85rem;
    color:var(--te-text-muted);
    cursor: default;
    letter-spacing: .05em;
}

/* ── Prev / Next labels (wider pill style) ───────────────────── */
.pgn-btn.pgn-edge {
    padding: 0 .85rem;
    font-size: .78rem;
}

/* ── Responsive ──────────────────────────────────────────────── */
@media (max-width: 600px) {
    .pgn {
        justify-content: center;
        padding: .85rem 1rem;
    }
    .pgn-info { display: none; }
    .pgn-btn, .pgn-dots { min-width: 32px; height: 32px; font-size: .75rem; }
    .pgn-btn.pgn-edge .pgn-edge-label { display: none; }
}
</style>
@endonce

<nav class="pgn" aria-label="Pagination">

    {{-- ── Info text ─────────────────────────────────────────── --}}
    <p class="pgn-info">
        Page <strong>{{ $paginator->currentPage() }}</strong>
        sur <strong>{{ $paginator->lastPage() }}</strong>
        &nbsp;·&nbsp;
        <strong>{{ $paginator->total() }}</strong> résultat{{ $paginator->total() > 1 ? 's' : '' }}
    </p>

    {{-- ── Navigation ──────────────────────────────────────────── --}}
    <div class="pgn-nav" role="list">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="pgn-btn pgn-edge pgn-disabled" aria-disabled="true">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="pgn-edge-label">Précédent</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pgn-btn pgn-edge" rel="prev" aria-label="Page précédente">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="pgn-edge-label">Précédent</span>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pgn-dots" aria-hidden="true">···</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pgn-btn pgn-active" aria-current="page" aria-label="Page {{ $page }}">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="pgn-btn" aria-label="Page {{ $page }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pgn-btn pgn-edge" rel="next" aria-label="Page suivante">
                <span class="pgn-edge-label">Suivant</span>
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="pgn-btn pgn-edge pgn-disabled" aria-disabled="true">
                <span class="pgn-edge-label">Suivant</span>
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif

    </div>
</nav>
@endif
