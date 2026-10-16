<x-app-layout>
    <x-slot name="title">Modération des utilisateurs</x-slot>

    <x-slot name="header">
        <x-page-header title="Gestion des utilisateurs" subtitle="Validez et gérez les comptes de la plateforme" icon="users"
        >
            <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .85rem;border-radius:99px;background:var(--te-primary-soft);color:var(--te-primary-fg);border:1.5px solid var(--te-primary-border);font-size:.75rem;font-weight:700;">
                {{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }}
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
        .pg { max-width:80rem; margin:0 auto; padding:1.75rem 1.5rem; }
        @media(max-width:640px){ .pg { padding:1rem; } }
        .tbl-card-head { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid var(--te-border); background:linear-gradient(135deg,var(--te-surface-2) 0%,var(--te-info-soft) 100%); flex-wrap:wrap; gap:.5rem; }
        .tbl-card-count { font-size:.92rem; font-weight:800; color:var(--te-text); }
        .tbl { width:100%; border-collapse:collapse; min-width:720px; }
        .tbl thead tr { border-bottom:1px solid var(--te-border); background:var(--te-info-soft); }
        .tbl th { padding:.85rem 1rem; text-align:left; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--te-primary-fg); white-space:nowrap; }
        .th-sort { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; text-decoration:none; color:inherit; white-space:nowrap; user-select:none; }
        .th-sort:hover { color:var(--te-primary-fg); }
        .th-sort svg { opacity:.5; transition:opacity var(--ease); flex-shrink:0; }
        .th-sort:hover svg, .th-sort.active svg { opacity:1; }
        .th-role { position:relative; }
        .th-role-btn { display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; background:none; border:none; font:inherit; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:var(--te-primary-fg); padding:0; user-select:none; white-space:nowrap; }
        .th-role-btn:hover { color:var(--te-primary-fg); }
        .th-role-btn svg { opacity:.5; transition:opacity var(--ease); }
        .th-role-btn:hover svg, .th-role-btn.active svg { opacity:1; }
        .col-drop { display:none; position:absolute; top:calc(100% + 4px); left:0; z-index:200; background:var(--te-surface); border:1.5px solid var(--te-border); border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.12); min-width:160px; overflow:hidden; }
        .col-drop.open { display:block; }
        .col-drop a { display:flex; align-items:center; gap:.55rem; padding:.6rem .9rem; font-size:.8rem; font-weight:600; color:var(--te-text); text-decoration:none; transition:background var(--ease); }
        .col-drop a:hover { background:var(--te-surface-2); }
        .col-drop a.active { background:var(--te-primary-soft); color:var(--te-primary-fg); font-weight:700; }
        .col-drop a .rd-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .col-drop-sep { height:1px; background:var(--te-border); margin:0; }
        .tbl tbody tr { border-bottom:1px solid var(--te-border); transition:background var(--ease); cursor:pointer; }
        .tbl tbody tr:hover { background:var(--te-info-soft); }
        .tbl td { padding:.9rem 1rem; vertical-align:middle; font-size:.9rem; color:var(--te-text); }
        .tbl-title { font-weight:800; color:var(--te-text); text-decoration:none; display:block; transition:color var(--ease); font-size:.95rem; }
        .tbl-sub { font-size:.78rem; color:var(--te-text-muted); margin-top:.2rem; }
        .u-avatar { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:800; flex-shrink:0; }
        .u-logo { width:34px; height:34px; border-radius:9px; object-fit:cover; border:1.5px solid var(--te-border); flex-shrink:0; }
        .stb { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .75rem; border-radius:99px; font-size:.75rem; font-weight:700; border:1.5px solid; white-space:nowrap; }
        .stb-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .btn-p { display:inline-flex; align-items:center; gap:.4rem; padding:.4rem .9rem; background:var(--te-primary); color:var(--te-on-primary); border:none; border-radius:8px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; text-decoration:none; transition:background var(--ease),transform var(--ease); white-space:nowrap; }
        .btn-p:hover { background:var(--te-primary-hover); transform:translateY(-1px); }
        .btn-ok { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem; background:var(--te-success-soft); color:var(--te-success-fg); border:1.5px solid var(--te-success-border); border-radius:8px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; transition:all var(--ease); white-space:nowrap; }
        .btn-rd { display:inline-flex; align-items:center; gap:.35rem; padding:.38rem .85rem; background:var(--te-danger-soft); color:var(--te-danger-fg); border:1.5px solid var(--te-danger-border); border-radius:8px; font-family:inherit; font-size:.75rem; font-weight:700; cursor:pointer; transition:all var(--ease); white-space:nowrap; }
        .pgn { padding:1.25rem 1.5rem; border-top:1px solid var(--te-border); }

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
        <div class="tbl-card">
            <div class="tbl-card-head">
                <p class="tbl-card-count">
                    <strong>{{ $users->total() }}</strong>
                    utilisateur{{ $users->total() > 1 ? 's' : '' }}
                </p>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        @php
                            $nextDir  = fn($col) => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc';
                            $baseParams = array_filter(['sort' => $sort, 'dir' => $dir, 'role' => $role, 'validated' => $validated], fn($v) => $v !== null && $v !== '');
                            $sortUrl  = fn($col) => route('admin.users.moderation', array_merge($baseParams, ['sort' => $col, 'dir' => $nextDir($col)]));
                            $sortIco  = function($col) use ($sort, $dir) {
                                if ($sort !== $col) return '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                return $dir === 'asc'
                                    ? '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>'
                                    : '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>';
                            };
                            $roleUrl = fn($r) => route('admin.users.moderation', array_filter(array_merge($baseParams, ['role' => $r === $role ? null : $r]), fn($v) => $v !== null && $v !== ''));
                            $validatedUrl = fn($v) => route('admin.users.moderation', array_filter(array_merge($baseParams, ['validated' => $v === $validated ? null : $v]), fn($x) => $x !== null && $x !== ''));
                        @endphp
                        <tr>
                            <th>
                                <a href="{{ $sortUrl('name') }}" class="th-sort {{ $sort === 'name' ? 'active' : '' }}">
                                    Utilisateur {!! $sortIco('name') !!}
                                </a>
                            </th>
                            <th class="th-role">
                                <button type="button" class="th-role-btn {{ $role ? 'active' : '' }}" onclick="toggleDrop('role-drop');event.stopPropagation();">
                                    Rôle
                                    @if($role)
                                        <span style="width:6px;height:6px;border-radius:50%;background:{{ $role === 'company' ? 'var(--te-info-solid)' : 'var(--te-primary)' }};display:inline-block;"></span>
                                    @endif
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="col-drop" id="role-drop">
                                    <a href="{{ route('admin.users.moderation', array_filter(['sort' => $sort, 'dir' => $dir, 'validated' => $validated], fn($v) => $v !== null && $v !== '')) }}" class="{{ !$role ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:var(--te-neutral-400);"></span> Tous
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ $roleUrl('company') }}" class="{{ $role === 'company' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:var(--te-info-solid);"></span> Entreprise
                                    </a>
                                    <div class="col-drop-sep"></div>
                                    <a href="{{ $roleUrl('candidate') }}" class="{{ $role === 'candidate' ? 'active' : '' }}">
                                        <span class="rd-dot" style="background:var(--te-primary);"></span> Candidat
                                    </a>
                                </div>
                            </th>
                            <th>Validation</th>
                            <th>
                                <a href="{{ $sortUrl('date') }}" class="th-sort {{ $sort === 'date' ? 'active' : '' }}">
                                    Inscription {!! $sortIco('date') !!}
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @php
                                $avBg  = $user->isAdmin() ? 'var(--te-violet-soft)' : ($user->isCompany() ? 'var(--te-info-soft)' : 'var(--te-primary-soft)');
                                $avClr = $user->isAdmin() ? 'var(--te-violet-fg)' : ($user->isCompany() ? 'var(--te-info-fg)' : 'var(--te-primary-fg)');
                            @endphp
                            <tr onclick="if(!event.target.closest('a')&&!event.target.closest('button')&&!event.target.closest('form')) window.location.href='{{ route('admin.users.show', $user) }}'">
                                {{-- User --}}
                                <td>
                                    <div style="display:flex;align-items:center;gap:.75rem;">
                                        @if($user->isCompany())
                                            <x-company-logo :company="$user" :size="36" />
                                        @else
                                            <div class="u-avatar" style="background:{{ $avBg }};color:{{ $avClr }};">{{ $user->initials }}</div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.users.show', $user) }}" class="tbl-title">{{ $user->name }}</a>
                                            <div class="tbl-sub">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Role --}}
                                <td>
                                    @if($user->isAdmin())
                                        <span class="stb" style="background:var(--te-violet-soft);color:var(--te-violet-fg);border-color:var(--te-primary-border);">
                                            <span class="stb-dot" style="background:var(--te-violet-solid);"></span>
                                            Admin
                                        </span>
                                    @elseif($user->isCompany())
                                        <span class="stb" style="background:var(--te-info-soft);color:var(--te-info-fg);border-color:var(--te-info-border);">
                                            <span class="stb-dot" style="background:var(--te-info-solid);"></span>
                                            Entreprise
                                        </span>
                                    @else
                                        <span class="stb" style="background:var(--te-primary-soft);color:var(--te-primary-fg);border-color:var(--te-primary-border);">
                                            <span class="stb-dot" style="background:var(--te-primary);"></span>
                                            Candidat
                                        </span>
                                    @endif
                                </td>

                                {{-- Validation --}}
                                <td>
                                    @if($user->is_validated)
                                        <span class="stb" style="background:var(--te-success-soft);color:var(--te-success-fg);border-color:var(--te-success-border);">
                                            <span class="stb-dot" style="background:var(--te-success-solid);"></span>
                                            Validé
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.status', $user) }}"
                                              class="js-validate-user-form"
                                              data-name="{{ $user->name }}"
                                              data-variant="validate"
                                              style="display:contents;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_validated" value="1">
                                            <button type="submit" class="btn-p">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Valider
                                            </button>
                                        </form>
                                    @endif
                                </td>

                                {{-- Inscription --}}
                                <td>
                                    <span style="font-size:.78rem;color:var(--te-text-muted);">{{ $user->created_at->translatedFormat('d/m/Y') }}</span>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    @if($user->isAdmin())
                                        <span style="font-size:.78rem;color:var(--te-text-muted);font-style:italic;">Administrateur</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.status', $user) }}"
                                              class="{{ $user->is_blocked ? 'js-unblock-form' : 'js-block-form' }}"
                                              data-name="{{ $user->name }}"
                                              data-variant="{{ $user->is_blocked ? 'unblock' : 'block' }}"
                                              style="display:contents;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_blocked" value="{{ $user->is_blocked ? '0' : '1' }}">
                                            @if($user->is_blocked)
                                                <button type="submit" class="btn-ok">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Débloquer
                                                </button>
                                            @else
                                                <button type="submit" class="btn-rd">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                    Bloquer
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                {{ $users->links() }}
            @endif
        </div>
    </div>

    {{-- Confirm modal --}}
    <div id="user-modal" class="modal-backdrop" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <div class="modal-ico" id="user-modal-ico"></div>
                <div>
                    <h3 class="modal-title" id="user-modal-title"></h3>
                    <p class="modal-msg" id="user-modal-msg"></p>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="modal-cancel" id="user-modal-cancel">Annuler</button>
                <button type="button" class="modal-ok" id="user-modal-ok"></button>
            </div>
        </div>
    </div>

    <script>
    function toggleDrop(id) {
        ['role-drop','validated-drop'].forEach(function(d) {
            var el = document.getElementById(d);
            if (el) el.classList[d === id ? 'toggle' : 'remove']('open');
        });
    }
    document.addEventListener('click', function() {
        document.querySelectorAll('.col-drop').forEach(function(d){ d.classList.remove('open'); });
    });
    </script>
    <script>
    (function () {
        var modal   = document.getElementById('user-modal');
        var ico     = document.getElementById('user-modal-ico');
        var title   = document.getElementById('user-modal-title');
        var msg     = document.getElementById('user-modal-msg');
        var btnOk   = document.getElementById('user-modal-ok');
        var btnNo   = document.getElementById('user-modal-cancel');
        var pending = null;

        function open(form, variant, userName) {
            pending = form;

            var configs = {
                validate: {
                    color: 'var(--te-primary-fg)', bg: 'var(--te-primary-soft)',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
                    titleText: 'Valider ce compte ?',
                    msgText: 'Le compte de "' + userName + '" sera active et l\'utilisateur pourra accéder à la plateforme.',
                    btnText: 'Valider'
                },
                block: {
                    color: 'var(--te-danger-fg)', bg: 'var(--te-danger-soft)',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
                    titleText: 'Bloquer cet utilisateur ?',
                    msgText: '"' + userName + '" ne pourra plus se connecter à la plateforme.',
                    btnText: 'Bloquer'
                },
                unblock: {
                    color: 'var(--te-success-fg)', bg: 'var(--te-success-soft)',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
                    titleText: 'Débloquer cet utilisateur ?',
                    msgText: '"' + userName + '" pourra de nouveau accéder à la plateforme.',
                    btnText: 'Débloquer'
                }
            };

            var c = configs[variant] || configs.block;
            ico.style.background = c.bg;
            ico.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + c.color + '" stroke-width="2" viewBox="0 0 24 24">' + c.icon + '</svg>';
            title.textContent      = c.titleText;
            msg.textContent        = c.msgText;
            btnOk.textContent      = c.btnText;
            btnOk.style.background = c.color;
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

        var selectors = [
            { sel: '.js-validate-user-form', variant: 'validate' },
            { sel: '.js-block-form',         variant: 'block'    },
            { sel: '.js-unblock-form',        variant: 'unblock'  }
        ];

        selectors.forEach(function (s) {
            document.querySelectorAll(s.sel).forEach(function (f) {
                f.onsubmit = function (e) {
                    e.preventDefault();
                    open(f, s.variant, f.dataset.name);
                };
            });
        });
    })();
    </script>
</x-app-layout>
