<x-app-layout>
    <x-slot name="title">Notifications</x-slot>

    <x-slot name="header">
        <x-page-header title="Mes notifications" subtitle="Restez informé des dernières activités" icon="bell"
            
        />
    </x-slot>

    <style>
        :root {
            --r:12px;
            --shadow:0 1px 3px rgba(0,0,0,.07);
            --shadow-hover:0 4px 16px rgba(79,70,229,.1);
        }
        .notif-page { max-width:80rem; margin:0 auto; padding:2rem 1.5rem; }
        @media(max-width:640px) { .notif-page { padding:1rem; } }

        /* Card wrapper */
        .notif-card {
            background:var(--te-surface); border-radius:var(--r);
            border:1px solid var(--te-border); box-shadow:var(--shadow); overflow:hidden;
        }

        /* Item */
        .notif-item {
            display:flex; align-items:flex-start; gap:1rem;
            padding:1rem 1.25rem; border-bottom:1px solid var(--te-border-subtle);
            text-decoration:none; color:inherit; transition:background .12s;
            position:relative;
        }
        .notif-item:last-child { border-bottom:none; }
        .notif-item:hover { background:var(--te-surface-2); }
        .notif-item.unread { background:var(--te-surface-2); }
        .notif-item.unread:hover { background:var(--te-primary-soft); }

        /* Unread dot */
        .notif-dot {
            position:absolute; left:.5rem; top:50%; transform:translateY(-50%);
            width:6px; height:6px; border-radius:50%; background:var(--te-primary);
        }
        .notif-item.unread { padding-left:1.6rem; }

        /* Icon */
        .notif-icon {
            width:38px; height:38px; border-radius:10px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
        }

        /* Content */
        .notif-content { flex:1; min-width:0; }
        .notif-title { font-size:.875rem; font-weight:700; color:var(--te-text); margin-bottom:.2rem; }
        .notif-msg { font-size:.8rem; color:var(--te-text-muted); line-height:1.5; }
        .notif-link {
            display:inline-flex; align-items:center; gap:.3rem;
            font-size:.75rem; font-weight:700; color:var(--te-primary-fg);
            text-decoration:none; margin-top:.4rem;
            transition:color .15s;
        }
        .notif-link:hover { color:var(--te-primary-fg); }

        /* Meta */
        .notif-meta { flex-shrink:0; text-align:right; }
        .notif-time { font-size:.75rem; color:var(--te-text-muted); white-space:nowrap; }
        .notif-unread-pill {
            display:inline-block; margin-top:.35rem;
            padding:.15rem .5rem; border-radius:99px;
            font-size:.75rem; font-weight:700;
            background:var(--te-primary-soft); color:var(--te-primary-fg);
        }

        /* Empty state */
        .notif-empty { padding:5rem 2rem; text-align:center; }
        .notif-empty-icon {
            width:64px; height:64px; border-radius:16px;
            background:var(--te-primary-soft); margin:0 auto 1.25rem;
            display:flex; align-items:center; justify-content:center;
        }
        .notif-empty h3 { font-size:1rem; font-weight:800; color:var(--te-text); margin-bottom:.5rem; }
        .notif-empty p { font-size:.875rem; color:var(--te-text-muted); }

        /* Pagination */
        .notif-pag { padding:1rem 1.25rem; border-top:1px solid var(--te-border-subtle); }
    </style>

    <div class="notif-page">
        <div class="notif-card">

            @forelse($notifications as $notification)
                @php
                    $isUnread = !$notification->read_at;
                    $type = $notification->data['type'] ?? 'default';

                    // Icon & color per notification type
                    $iconConfig = match($type) {
                        'application'  => ['bg'=>'var(--te-primary-soft)','color'=>'var(--te-primary-fg)','path'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        'accepted'     => ['bg'=>'var(--te-success-soft)','color'=>'var(--te-success-fg)','path'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'rejected'     => ['bg'=>'var(--te-danger-soft)','color'=>'var(--te-danger-fg)','path'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'message'      => ['bg'=>'var(--te-info-soft)','color'=>'var(--te-info-fg)','path'=>'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                        'offer'        => ['bg'=>'var(--te-warning-soft)','color'=>'var(--te-warning-fg)','path'=>'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        default        => ['bg'=>'var(--te-surface-3)','color'=>'var(--te-text-muted)','path'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                    };
                @endphp

                <div class="notif-item {{ $isUnread ? 'unread' : '' }}">
                    @if($isUnread)
                        <span class="notif-dot"></span>
                    @endif

                    <div class="notif-icon" style="background:{{ $iconConfig['bg'] }}">
                        <svg width="18" height="18" fill="none" stroke="{{ $iconConfig['color'] }}" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconConfig['path'] }}"/>
                        </svg>
                    </div>

                    <div class="notif-content">
                        <div class="notif-title">{{ $notification->data['title'] }}</div>
                        <div class="notif-msg">{{ $notification->data['message'] }}</div>

                        @if(isset($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="notif-link">
                                Voir les détails
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div class="notif-meta">
                        <div class="notif-time">{{ $notification->created_at->diffForHumans() }}</div>
                        @if($isUnread)
                            <span class="notif-unread-pill">Nouveau</span>
                        @endif
                    </div>
                </div>

            @empty
                <div class="notif-empty">
                    <div class="notif-empty-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3>Aucune notification</h3>
                    <p>Vous n'avez pas encore de notifications. Elles apparaîtront ici.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                {{ $notifications->links() }}
            @endif
        </div>
    </div>
</x-app-layout>
