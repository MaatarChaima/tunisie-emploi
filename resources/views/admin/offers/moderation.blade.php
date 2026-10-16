<x-app-layout>
    <x-slot name="title">Modération des offres</x-slot>

    <x-slot name="header">
        <x-page-header title="Modération des offres" subtitle="Gérez l'ensemble des offres publiées sur la plateforme" icon="briefcase">
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:var(--te-warning-soft);color:var(--te-warning-fg);border:1.5px solid var(--te-warning-border);font-size:.75rem;font-weight:700;">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--te-warning-solid);flex-shrink:0;"></span>
                {{ $pendingCount }} en attente
            </span>
        </x-page-header>
    </x-slot>

    <style>
        :root {
            --pr:   rgba(79,70,229,.12);
            --r:    12px;
            --shd:  0 1px 3px rgba(0,0,0,.07), 0 2px 4px rgba(0,0,0,.04);
            --ease: .18s cubic-bezier(.4,0,.2,1);
        }
        .pg { max-width:80rem; margin:0 auto; padding:2rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }


        /* ── toolbar ── */
        .tbl-toolbar {
            display:flex; align-items:center; justify-content:space-between;
            padding:.85rem 1.25rem; border-bottom:1px solid var(--te-border);
            background:linear-gradient(135deg,var(--te-info-soft) 0%,var(--te-surface-2) 100%);
            flex-wrap:wrap; gap:.65rem;
        }
        .tbl-count { font-size:.92rem; font-weight:800; color:var(--te-text); white-space:nowrap; }
        .tbl-count strong { color:var(--te-primary-fg); }

        /* column dropdown */
        .th-col { position:relative; }
        .th-col-btn { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; background:none; border:none; font:inherit; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--te-primary-fg); padding:0; user-select:none; white-space:nowrap; }
        .th-col-btn:hover { color:var(--te-primary-fg); }
        .th-col-btn svg { opacity:.5; transition:opacity var(--ease); }
        .th-col-btn:hover svg, .th-col-btn.active svg { opacity:1; }
        .col-drop { display:none; position:absolute; top:calc(100% + 4px); left:0; z-index:200; background:var(--te-surface); border:1.5px solid var(--te-border); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.12); min-width:160px; overflow:hidden; }
        .col-drop.open { display:block; }
        .col-drop a { display:flex; align-items:center; gap:.55rem; padding:.6rem .9rem; font-size:.8rem; font-weight:600; color:var(--te-text); text-decoration:none; transition:background var(--ease); }
        .col-drop a:hover { background:var(--te-surface-2); }
        .col-drop a.active { background:var(--te-primary-soft); color:var(--te-primary-fg); font-weight:700; }
        .col-drop .rd-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .col-drop-sep { height:1px; background:var(--te-border); margin:0; }

        /* ── table ── */
        .tbl { width:100%; border-collapse:collapse; min-width:760px; }
        .tbl thead tr { border-bottom:1px solid var(--te-border); background:var(--te-info-soft); }
        .tbl th { padding:.85rem 1rem; text-align:left; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--te-primary-fg); white-space:nowrap; }
        .tbl tbody tr { border-bottom:1px solid var(--te-border); transition:background var(--ease); cursor:pointer; }
        .tbl tbody tr:hover { background:var(--te-info-soft); }
        .tbl td { padding:.9rem 1rem; vertical-align:middle; font-size:.9rem; color:var(--te-text); }

        /* sortable column header */
        .th-sort {
            display:inline-flex; align-items:center; gap:.35rem;
            text-decoration:none; color:var(--te-primary-fg);
            font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
            transition:opacity var(--ease);
        }
        .th-sort:hover { opacity:.75; }
        .th-sort svg { flex-shrink:0; }

        .tbl-title { font-weight:800; color:var(--te-text); text-decoration:none; display:block; transition:color var(--ease); font-size:.95rem; }
        .tbl-sub { font-size:.78rem; color:var(--te-text-muted); margin-top:.2rem; display:flex; align-items:center; gap:.3rem; }

        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.32rem .85rem; border-radius:99px; font-size:.75rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .co-logo { width:34px;height:34px;border-radius:9px;border:1.5px solid var(--te-border);object-fit:cover;flex-shrink:0;background:var(--te-surface); }

        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem .95rem; background:var(--te-primary); color:var(--te-on-primary); border:none; border-radius:8px; font-family:inherit; font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease),box-shadow var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--te-primary-hover); transform:translateY(-1px); box-shadow:0 4px 12px var(--pr); }
        .btn-ok { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem; background:var(--te-success-soft); color:var(--te-success-fg); border:1.5px solid var(--te-success-border); border-radius:8px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; transition:all var(--ease); white-space:nowrap; }
        .btn-rd { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem; background:var(--te-danger-soft); color:var(--te-danger-fg); border:1.5px solid var(--te-danger-border); border-radius:8px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; transition:all var(--ease); white-space:nowrap; }

        .uc-cat { display:inline-flex;align-items:center;gap:.3rem;padding:.12rem .55rem .12rem .2rem;border-radius:99px;font-size:.75rem;font-weight:700;background:var(--te-primary-soft);color:var(--te-primary-fg);border:1px solid var(--te-primary-border);letter-spacing:.02em; }

        .empty-box { padding:4rem 2rem; text-align:center; }
        .empty-ico { width:52px;height:52px;border-radius:14px;background:var(--te-primary-soft);margin:0 auto .875rem;display:flex;align-items:center;justify-content:center; }
        .empty-box p { font-size:.85rem;color:var(--te-text-muted);max-width:300px;margin:0 auto 1.25rem;line-height:1.6; }

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

    @php
        /* helpers to build sort URLs */
        function sortUrl(string $col, string $currentSort, string $currentDir, string $currentStatus): string {
            $newDir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
            $params = array_filter(['sort' => $col, 'dir' => $newDir, 'status' => $currentStatus]);
            return request()->url() . '?' . http_build_query($params);
        }
        function sortIcon(string $col, string $currentSort, string $currentDir): string {
            if ($currentSort !== $col) {
                return '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="opacity:.45"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>';
            }
            if ($currentDir === 'asc') {
                return '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>';
            }
            return '<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>';
        }
        function statusFilterUrl(string $statusVal, string $currentSort, string $currentDir): string {
            $params = array_filter(['sort' => $currentSort !== 'date' ? $currentSort : null, 'dir' => $currentDir !== 'desc' ? $currentDir : null, 'status' => $statusVal ?: null]);
            return request()->url() . ($params ? '?' . http_build_query($params) : '');
        }

        $statusLabels = [
            'pending_validation' => ['label' => 'En attente',  'bg' => 'var(--te-warning-soft)', 'color' => 'var(--te-warning-fg)', 'border' => 'var(--te-warning-border)', 'dot' => 'var(--te-warning-solid)'],
            'open'               => ['label' => 'Validée',     'bg' => 'var(--te-success-soft)', 'color' => 'var(--te-success-fg)', 'border' => 'var(--te-success-border)', 'dot' => 'var(--te-success-solid)'],
            'refused'            => ['label' => 'Refusée',     'bg' => 'var(--te-danger-soft)', 'color' => 'var(--te-danger-fg)', 'border' => 'var(--te-danger-border)', 'dot' => 'var(--te-danger-solid)'],
            'closed'             => ['label' => 'Fermée',      'bg' => 'var(--te-surface-3)', 'color' => 'var(--te-text-2)', 'border' => 'var(--te-border-strong)', 'dot' => 'var(--te-neutral-400)'],
        ];
    @endphp

    <div class="pg">

        @if(session('success'))
            <div style="display:flex;align-items:center;gap:.65rem;padding:.85rem 1.1rem;background:var(--te-success-soft);border:1.5px solid var(--te-success-border);border-radius:10px;color:var(--te-success-fg);font-size:.875rem;font-weight:600;margin-bottom:1.25rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="tbl-card">

            {{-- toolbar --}}
            <div class="tbl-toolbar">
                <p class="tbl-count">
                    <strong>{{ $offers->total() }}</strong>
                    offre{{ $offers->total() > 1 ? 's' : '' }}
                    @if($status)
                        @php $stLbl = $statusLabels[$status] ?? null; @endphp
                        @if($stLbl)
                            · <span style="color:{{ $stLbl['color'] }};font-weight:700;">{{ $stLbl['label'] }}</span>
                        @endif
                    @endif
                </p>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ sortUrl('title', $sort, $dir, $status) }}" class="th-sort">
                                    Titre de l'offre
                                    {!! sortIcon('title', $sort, $dir) !!}
                                </a>
                            </th>
                            <th>
                                <a href="{{ sortUrl('company', $sort, $dir, $status) }}" class="th-sort">
                                    Entreprise
                                    {!! sortIcon('company', $sort, $dir) !!}
                                </a>
                            </th>
                            <th class="th-col">
                                <button type="button" class="th-col-btn {{ $status ? 'active' : '' }}" onclick="document.getElementById('status-drop').classList.toggle('open');event.stopPropagation();">
                                    Statut
                                    @if($status && isset($statusLabels[$status]))
                                        <span style="width:6px;height:6px;border-radius:50%;background:{{ $statusLabels[$status]['dot'] }};display:inline-block;"></span>
                                    @endif
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="col-drop" id="status-drop">
                                    <a href="{{ statusFilterUrl('', $sort, $dir) }}" class="{{ $status === '' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:var(--te-neutral-400);"></span> Tous
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ statusFilterUrl('pending_validation', $sort, $dir) }}" class="{{ $status === 'pending_validation' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:var(--te-warning-solid);"></span> En attente
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ statusFilterUrl('open', $sort, $dir) }}" class="{{ $status === 'open' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:var(--te-success-solid);"></span> Validée
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ statusFilterUrl('refused', $sort, $dir) }}" class="{{ $status === 'refused' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:var(--te-danger-solid);"></span> Refusée
                                    </a>
                                </div>
                            </th>
                            <th>
                                <a href="{{ sortUrl('date', $sort, $dir, $status) }}" class="th-sort">
                                    Date
                                    {!! sortIcon('date', $sort, $dir) !!}
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            @php
                                $stCfg = $statusLabels[$offer->status] ?? ['label' => $offer->status, 'bg' => 'var(--te-surface-3)', 'color' => 'var(--te-text-2)', 'border' => 'var(--te-border-strong)', 'dot' => 'var(--te-neutral-400)'];
                                $isPending = $offer->status === 'pending_validation';
                            @endphp
                            <tr onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('admin.offers.show', $offer) }}'">

                                {{-- Titre --}}
                                <td>
                                    <a href="{{ route('admin.offers.show', $offer) }}" class="tbl-title">
                                        {{ $offer->title }}
                                    </a>
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
                                </td>

                                {{-- Entreprise --}}
                                <td>
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <x-company-logo :company="$offer->company" :size="36" />
                                        <div>
                                            <div style="font-size:.85rem;font-weight:700;color:var(--te-text);line-height:1.2;">{{ $offer->company->name }}</div>
                                            @if($offer->company->city)
                                                <div style="font-size:.75rem;color:var(--te-text-muted);margin-top:.1rem;">{{ $offer->company->city }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Statut --}}
                                <td>
                                    <span class="stb" style="background:{{ $stCfg['bg'] }};color:{{ $stCfg['color'] }};border-color:{{ $stCfg['border'] }};">
                                        <span class="stb-dot" style="background:{{ $stCfg['dot'] }};"></span>
                                        {{ $stCfg['label'] }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td>
                                    <span style="font-size:.82rem;color:var(--te-text-muted);font-weight:500;">
                                        {{ $offer->created_at->translatedFormat('d/m/Y') }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div style="display:inline-flex;align-items:center;gap:.5rem;justify-content:flex-end;">
                                        <a href="{{ route('admin.offers.show', $offer) }}" class="btn-g">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            Voir
                                        </a>

                                        @if($isPending)
                                            <form method="POST" action="{{ route('admin.offers.status', $offer) }}"
                                                  class="js-validate-form"
                                                  data-variant="validate"
                                                  data-title="{{ $offer->title }}"
                                                  style="display:contents;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="open">
                                                <button type="submit" class="btn-ok">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Valider
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.offers.status', $offer) }}"
                                                  class="js-refuse-form"
                                                  data-variant="refuse"
                                                  data-title="{{ $offer->title }}"
                                                  style="display:contents;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="refused">
                                                <button type="submit" class="btn-rd">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Refuser
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
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <h3>Aucune offre trouvée</h3>
                                        <p>Aucune offre ne correspond aux critères sélectionnés.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($offers->hasPages())
                <div style="padding:1.25rem 1.5rem; border-top:1px solid var(--te-border);">
                    {{ $offers->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Confirm modal --}}
    <div id="mod-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="mod-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="mod-modal-title"></h3>
                    <p class="modal-msg" id="mod-modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="mod-modal-cancel">Annuler</button>
                <button type="button" class="modal-ok" id="mod-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('click', function() {
        document.querySelectorAll('.col-drop').forEach(function(d){ d.classList.remove('open'); });
    });
    </script>
    <script>
    (function () {
        var modal   = document.getElementById('mod-modal');
        var ico     = document.getElementById('mod-modal-ico');
        var title   = document.getElementById('mod-modal-title');
        var msg     = document.getElementById('mod-modal-msg');
        var btnOk   = document.getElementById('mod-modal-ok');
        var btnNo   = document.getElementById('mod-modal-cancel');
        var pending = null;

        function open(form, variant, offerTitle) {
            pending = form;
            var isValidate = variant === 'validate';
            var color = isValidate ? 'var(--te-success-fg)' : 'var(--te-danger-fg)';
            var bg    = isValidate ? 'var(--te-success-soft)' : 'var(--te-danger-soft)';
            ico.style.background = bg;
            ico.innerHTML = isValidate
                ? '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + color + '" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'
                : '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + color + '" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
            title.textContent = isValidate ? 'Valider cette offre ?' : 'Refuser cette offre ?';
            msg.textContent   = isValidate
                ? "L'offre \"" + offerTitle + "\" sera rendue visible aux candidats."
                : "L'offre \"" + offerTitle + "\" sera rejetée et l'entreprise en sera notifiée.";
            btnOk.textContent      = isValidate ? 'Valider' : 'Refuser';
            btnOk.style.background = color;
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

        document.querySelectorAll('.js-validate-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'validate', f.dataset.title); };
        });
        document.querySelectorAll('.js-refuse-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'refuse', f.dataset.title); };
        });
    })();
    </script>
</x-app-layout>
