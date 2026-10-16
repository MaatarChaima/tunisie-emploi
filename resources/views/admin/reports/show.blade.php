<x-app-layout>
    <x-slot name="title">Signalement n° {{ $report->id }}</x-slot>

    <x-slot name="header">
        <x-page-header
            :title="'Signalement n° ' . $report->id"
            :subtitle="'Soumis le ' . $report->created_at->translatedFormat('j F Y à H:i')"
            icon="flag"
            :breadcrumbs="[
                ['label' => 'Signalements', 'url' => \App\Support\Breadcrumbs::parent('admin.reports.index')],
                ['label' => 'Signalement n° ' . $report->id],
            ]"
        />
    </x-slot>

    <style>
        :root {
            --r:12px;
            --shd:0 1px 3px rgba(0,0,0,.07),0 2px 4px rgba(0,0,0,.04);
            --ease:.18s cubic-bezier(.4,0,.2,1);
        }
        .pg { max-width:80rem; margin:0 auto; padding:1.75rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }

        .grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
        @media(max-width:680px){ .grid2 { grid-template-columns:1fr; } }

        .card { background:var(--te-surface); border:1px solid var(--te-border); border-radius:var(--r);
                box-shadow:var(--shd); overflow:hidden; }
        .card-head { padding:.85rem 1.25rem; border-bottom:1px solid var(--te-border);
                     background:linear-gradient(135deg,var(--te-surface-2),var(--te-info-soft));
                     display:flex; align-items:center; gap:.5rem; }
        .card-head-label { font-size:.75rem; font-weight:800; text-transform:uppercase;
                           letter-spacing:.08em; color:var(--te-primary-fg); }
        .card-body { padding:1.25rem; }

        .profile-row { display:flex; align-items:center; gap:1rem; margin-bottom:1.1rem; }
        .profile-av { width:52px; height:52px; border-radius:12px; display:flex; align-items:center;
                      justify-content:center; font-size:1.2rem; font-weight:800; flex-shrink:0; }
        .profile-logo { width:52px; height:52px; border-radius:12px; object-fit:cover;
                        border:1.5px solid var(--te-border); flex-shrink:0; }
        .profile-name { font-size:1rem; font-weight:800; color:var(--te-text); }
        .profile-email { font-size:.78rem; color:var(--te-text-muted); margin-top:.1rem; }

        .meta-row { display:flex; align-items:center; justify-content:space-between;
                    padding:.5rem 0; border-bottom:1px solid var(--te-border-subtle); font-size:.82rem; }
        .meta-row:last-child { border-bottom:none; padding-bottom:0; }
        .meta-label { color:var(--te-text-muted); font-weight:600; }
        .meta-val { color:var(--te-text); font-weight:700; text-align:right; }

        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .7rem;
               border-radius:99px; font-size:.75rem; font-weight:700; border:1.5px solid; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }

        .reason-box { background:var(--te-surface-2); border:1.5px solid var(--te-border); border-radius:10px;
                      padding:1.25rem; font-size:.9rem; color:var(--te-text); line-height:1.65;
                      white-space:pre-wrap; word-break:break-word; }

        .action-strip { display:flex; gap:.75rem; align-items:center; flex-wrap:wrap;
                        padding:1.1rem 1.25rem; background:var(--te-surface-2); border-top:1px solid var(--te-border); }
        .action-label { font-size:.78rem; color:var(--te-text-muted); font-weight:600; flex:1; }

        .btn-ok { display:inline-flex; align-items:center; gap:.45rem; padding:.55rem 1.25rem;
                  background:var(--te-success-soft); color:var(--te-success-fg); border:1.5px solid var(--te-success-border); border-radius:9px;
                  font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer;
                  transition:all var(--ease); white-space:nowrap; }
        .btn-rd { display:inline-flex; align-items:center; gap:.45rem; padding:.55rem 1.25rem;
                  background:var(--te-danger-soft); color:var(--te-danger-fg); border:1.5px solid var(--te-danger-border); border-radius:9px;
                  font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer;
                  transition:all var(--ease); white-space:nowrap; }
        .btn-user { display:inline-flex; align-items:center; gap:.45rem; padding:.55rem 1.25rem;
                    background:var(--te-primary-soft); color:var(--te-primary-fg); border:1.5px solid var(--te-primary-border); border-radius:9px;
                    font-family:inherit; font-size:.82rem; font-weight:700; cursor:pointer;
                    text-decoration:none; transition:all var(--ease); white-space:nowrap; }
        .btn-user:hover { background:var(--te-primary-soft); border-color:var(--te-primary); }

        /* Modal */
        .modal-backdrop { display:none; position:fixed; inset:0; z-index:500;
                          background:rgba(15,23,42,.45); backdrop-filter:blur(4px);
                          align-items:center; justify-content:center; padding:1rem; }
        .modal-backdrop.open { display:flex; }
        .modal { background:var(--te-surface); border-radius:16px; width:100%; max-width:420px;
                 border:1.5px solid var(--te-border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
        .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--te-border); }
        .modal-title { font-size:1rem; font-weight:800; color:var(--te-text); margin-bottom:.2rem; }
        .modal-msg  { font-size:.85rem; color:var(--te-text-muted); line-height:1.55; margin:0; }
        .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--te-border);
                        background:var(--te-surface); font-size:.85rem; font-weight:600; color:var(--te-text-muted);
                        cursor:pointer; transition:all var(--ease); font-family:inherit; }
        .modal-ok   { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem;
                      font-weight:700; color:var(--te-on-primary); cursor:pointer; transition:opacity var(--ease); font-family:inherit; }
            .rp-treat { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; padding:.9rem 1.1rem; margin-bottom:1.25rem; border-radius:12px; border:1px solid; }
        .rp-treat.is-todo { background:var(--te-warning-soft); border-color:var(--te-warning-border); }
        .rp-treat.is-done { background:var(--te-success-soft); border-color:var(--te-success-border); }
        .rp-treat strong { display:block; font-size:.9rem; color:var(--te-text); }
        .rp-treat span { font-size:.8125rem; color:var(--te-text-2); }
        .rp-treat-btn { padding:.5rem 1rem; border-radius:8px; border:1px solid transparent; background:var(--te-primary); color:var(--te-on-primary); font:inherit; font-size:.8125rem; font-weight:700; cursor:pointer; }
        .rp-treat-btn:hover { background:var(--te-primary-hover); }
        .rp-treat-btn.is-secondary { background:var(--te-surface); color:var(--te-text-2); border-color:var(--te-border); }
    </style>

    <div class="pg">

        @if(session('success'))
            <div style="display:flex;align-items:center;gap:.65rem;padding:.85rem 1.1rem;background:var(--te-success-soft);
                        border:1.5px solid var(--te-success-border);border-radius:10px;color:var(--te-success-fg);font-size:.875rem;
                        font-weight:600;margin-bottom:1.25rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Statut de traitement --}}
        <div class="rp-treat {{ $report->isTreated() ? 'is-done' : 'is-todo' }}">
            <div>
                <strong>{{ $report->isTreated() ? 'Signalement traité' : 'Signalement à traiter' }}</strong>
                @if($report->isTreated())
                    <span>le {{ $report->treated_at->translatedFormat('j F Y à H:i') }}{{ $report->treatedBy ? ' par ' . $report->treatedBy->name : '' }}</span>
                @else
                    <span>Examinez les deux comptes puis marquez le signalement comme traité.</span>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.reports.treated', $report) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="rp-treat-btn {{ $report->isTreated() ? 'is-secondary' : '' }}">
                    {{ $report->isTreated() ? 'Remettre à traiter' : 'Marquer comme traité' }}
                </button>
            </form>
        </div>

        {{-- Two profile cards --}}
        <div class="grid2">

            {{-- Reporter card --}}
            <div class="card">
                <div class="card-head">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="card-head-label">Signalé par</span>
                </div>
                <div class="card-body">
                    @php
                        $reporter = $report->user;
                        $repBg  = $reporter->isCompany() ? 'var(--te-info-soft)' : 'var(--te-primary-soft)';
                        $repClr = $reporter->isCompany() ? 'var(--te-info-fg)' : 'var(--te-primary-fg)';
                    @endphp
                    <div class="profile-row">
                        @if($reporter->isCompany())
                            <x-company-logo :company="$reporter" :size="48" />
                        @else
                            <div class="profile-av" style="background:{{ $repBg }};color:{{ $repClr }};">
                                {{ $reporter->initials }}
                            </div>
                        @endif
                        <div>
                            <div class="profile-name">{{ $reporter->name }}</div>
                            <div class="profile-email">{{ $reporter->email }}</div>
                        </div>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Rôle</span>
                        <span class="meta-val">
                            @if($reporter->isCompany())
                                <span class="stb" style="background:var(--te-info-soft);color:var(--te-info-fg);border-color:var(--te-info-border);">
                                    <span class="stb-dot" style="background:var(--te-info-solid);"></span>Entreprise
                                </span>
                            @else
                                <span class="stb" style="background:var(--te-primary-soft);color:var(--te-primary-fg);border-color:var(--te-primary-border);">
                                    <span class="stb-dot" style="background:var(--te-primary);"></span>Candidat
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Inscrit le</span>
                        <span class="meta-val">{{ $reporter->created_at->translatedFormat('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Statut</span>
                        <span class="meta-val">
                            @if($reporter->is_blocked)
                                <span class="stb" style="background:var(--te-danger-soft);color:var(--te-danger-fg);border-color:var(--te-danger-border);">
                                    <span class="stb-dot" style="background:var(--te-danger-solid);"></span>Bloqué
                                </span>
                            @else
                                <span class="stb" style="background:var(--te-success-soft);color:var(--te-success-fg);border-color:var(--te-success-border);">
                                    <span class="stb-dot" style="background:var(--te-success-solid);"></span>Actif
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="action-strip">
                    <span class="action-label"></span>
                    <a href="{{ route('admin.users.show', $reporter) }}" class="btn-user">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Voir le profil
                    </a>
                </div>
            </div>

            {{-- Reported user card --}}
            <div class="card">
                <div class="card-head">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-danger-fg)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H11l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    <span class="card-head-label" style="color:var(--te-danger-fg);">Utilisateur signalé</span>
                </div>
                <div class="card-body">
                    @php
                        $reported = $report->reportedUser;
                        $rpdBg  = $reported->isCompany() ? 'var(--te-info-soft)' : 'var(--te-primary-soft)';
                        $rpdClr = $reported->isCompany() ? 'var(--te-info-fg)' : 'var(--te-primary-fg)';
                    @endphp
                    <div class="profile-row">
                        @if($reported->isCompany())
                            <x-company-logo :company="$reported" :size="48" />
                        @else
                            <div class="profile-av" style="background:{{ $rpdBg }};color:{{ $rpdClr }};">
                                {{ $reported->initials }}
                            </div>
                        @endif
                        <div>
                            <div class="profile-name">{{ $reported->name }}</div>
                            <div class="profile-email">{{ $reported->email }}</div>
                        </div>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Rôle</span>
                        <span class="meta-val">
                            @if($reported->isCompany())
                                <span class="stb" style="background:var(--te-info-soft);color:var(--te-info-fg);border-color:var(--te-info-border);">
                                    <span class="stb-dot" style="background:var(--te-info-solid);"></span>Entreprise
                                </span>
                            @else
                                <span class="stb" style="background:var(--te-primary-soft);color:var(--te-primary-fg);border-color:var(--te-primary-border);">
                                    <span class="stb-dot" style="background:var(--te-primary);"></span>Candidat
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Inscrit le</span>
                        <span class="meta-val">{{ $reported->created_at->translatedFormat('d/m/Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Statut actuel</span>
                        <span class="meta-val">
                            @if($reported->is_blocked)
                                <span class="stb" style="background:var(--te-danger-soft);color:var(--te-danger-fg);border-color:var(--te-danger-border);">
                                    <span class="stb-dot" style="background:var(--te-danger-solid);"></span>Bloqué
                                </span>
                            @else
                                <span class="stb" style="background:var(--te-success-soft);color:var(--te-success-fg);border-color:var(--te-success-border);">
                                    <span class="stb-dot" style="background:var(--te-success-solid);"></span>Actif
                                </span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Action strip --}}
                <div class="action-strip">
                    <span class="action-label">Action de modération :</span>
                    <a href="{{ route('admin.users.show', $reported) }}" class="btn-user">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Voir le profil
                    </a>

                    @if(!$reported->is_blocked)
                        <form method="POST" action="{{ route('admin.users.status', $reported) }}"
                              id="block-form" style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_blocked" value="1">
                            <button type="submit" class="btn-rd" id="btn-block">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Bloquer ce compte
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.status', $reported) }}"
                              id="unblock-form" style="display:contents;">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_blocked" value="0">
                            <button type="submit" class="btn-ok" id="btn-unblock">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Débloquer ce compte
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Full reason --}}
        @php
            $reasonParts = explode("\n\n", $report->reason, 2);
            $reasonType  = $reasonParts[0] ?? '';
            $reasonMsg   = $reasonParts[1] ?? '';
        @endphp
        <div class="card">
            <div class="card-head">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="card-head-label">Raison du signalement</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;">
                {{-- Type badge --}}
                <div style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--te-text-muted);white-space:nowrap;">Motif du signalement</span>
                    <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .85rem;border-radius:99px;background:var(--te-danger-soft);color:var(--te-danger-fg);border:1.5px solid var(--te-danger-border);font-size:.78rem;font-weight:700;">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H11l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        {{ $reasonType ?: '—' }}
                    </span>
                </div>
                {{-- Message --}}
                <div>
                    <div style="font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:var(--te-text-muted);margin-bottom:.5rem;">Détails</div>
                    @if($reasonMsg)
                        <div class="reason-box">{{ $reasonMsg }}</div>
                    @else
                        <div style="font-size:.85rem;color:var(--te-text-muted);font-style:italic;padding:.75rem 1rem;background:var(--te-surface-2);border:1.5px dashed var(--te-border);border-radius:10px;">
                            Aucun message complémentaire fourni.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Confirm modal --}}
    <div id="rpt-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="rpt-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="rpt-modal-title"></h3>
                    <p class="modal-msg"  id="rpt-modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="rpt-modal-cancel">Annuler</button>
                <button type="button" class="modal-ok"     id="rpt-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    (function(){
        var modal   = document.getElementById('rpt-modal');
        var ico     = document.getElementById('rpt-modal-ico');
        var title   = document.getElementById('rpt-modal-title');
        var msg     = document.getElementById('rpt-modal-msg');
        var btnOk   = document.getElementById('rpt-modal-ok');
        var btnNo   = document.getElementById('rpt-modal-cancel');
        var pending = null;

        var cfgs = {
            block: {
                color:'var(--te-danger-fg)', bg:'var(--te-danger-soft)',
                icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
                titleText:'Bloquer ce compte ?',
                msgText:'Cet utilisateur ne pourra plus se connecter à la plateforme.',
                btnText:'Bloquer'
            },
            unblock: {
                color:'var(--te-success-fg)', bg:'var(--te-success-soft)',
                icon:'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
                titleText:'Débloquer ce compte ?',
                msgText:"Cet utilisateur pourra de nouveau accéder à la plateforme.",
                btnText:'Débloquer'
            }
        };

        function open(form, variant){
            pending = form;
            var c = cfgs[variant];
            ico.style.background = c.bg;
            ico.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + c.color + '" stroke-width="2" viewBox="0 0 24 24">'+c.icon+'</svg>';
            title.textContent = c.titleText;
            msg.textContent   = c.msgText;
            btnOk.textContent = c.btnText;
            btnOk.style.background = c.color;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function close(){
            pending = null;
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }

        btnNo.onclick = close;
        modal.onclick = function(e){ if(e.target===modal) close(); };
        btnOk.onclick = function(){ if(pending) pending.submit(); };
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') close(); });

        var bf = document.getElementById('block-form');
        var uf = document.getElementById('unblock-form');
        if(bf) bf.onsubmit = function(e){ e.preventDefault(); open(bf,'block'); };
        if(uf) uf.onsubmit = function(e){ e.preventDefault(); open(uf,'unblock'); };
    })();
    </script>
</x-app-layout>
