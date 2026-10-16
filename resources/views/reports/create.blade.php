<x-app-layout>
    <x-slot name="title">Signaler {{ $user->name }}</x-slot>

    <x-slot name="header">
        @php
            // Un candidat signale une entreprise à laquelle il a postulé ; une entreprise signale un candidat depuis sa fiche
            $rptBreadcrumbs = $user->isCompany()
                ? [['label' => 'Mes candidatures', 'url' => \App\Support\Breadcrumbs::parent('applications.index')]]
                : [['label' => 'Candidats', 'url' => \App\Support\Breadcrumbs::parent('candidates.index')],
                   ['label' => Str::limit($user->name, 25), 'url' => route('candidates.show', $user)]];
            $rptBreadcrumbs[] = ['label' => 'Signaler'];
        @endphp
        <x-page-header
            :title="'Signaler ' . ($user->isCompany() ? 'une entreprise' : 'un candidat')"
            :subtitle="$user->name"
            icon="flag"
            :breadcrumbs="$rptBreadcrumbs"
        />
    </x-slot>

    <style>
    :root {
            --r:      16px;
            --r-sm:   10px;
            --tr:     .18s cubic-bezier(.4,0,.2,1);
        }
        .rpt-wrap {
        max-width: 80rem;
        margin: 2rem auto;
        padding: 0 1.5rem 3rem;
    }

    /* Target card */
    .rpt-target {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem 1.25rem;
        background:var(--te-surface); border:1.5px solid var(--te-border);
        border-radius:var(--r); margin-bottom: 1.25rem;
        box-shadow:0 1px 3px rgba(0,0,0,.05);
    }
    .rpt-target-avatar {
        width: 44px; height: 44px; border-radius:50%;
        background:var(--te-danger-soft); border:2px solid var(--te-danger-border);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; font-weight: 800; color:var(--te-danger-fg);
        flex-shrink: 0;
    }
    .rpt-target-name { font-size: .95rem; font-weight: 800; color:var(--te-text); }
    .rpt-target-meta { font-size: .78rem; color:var(--te-text-muted); margin-top: .1rem; }

    /* Form card */
    .rpt-card {
        background:var(--te-surface); border:1.5px solid var(--te-border);
        border-radius:var(--r);
        box-shadow:0 1px 3px rgba(0,0,0,.05);
        overflow: hidden;
    }
    .rpt-card-head {
        display: flex; align-items: center; gap: .65rem;
        padding: 1rem 1.5rem;
        border-bottom:1px solid var(--te-border-subtle);
        background:var(--te-surface-2);
    }
    .rpt-card-icon {
        width: 32px; height: 32px; border-radius:8px;
        background:var(--te-danger-soft); border:1px solid var(--te-danger-border);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .rpt-card-title { font-size: .9rem; font-weight: 800; color:var(--te-text); }
    .rpt-card-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }

    /* Fields */
    .rpt-label { display: block; font-size: .78rem; font-weight: 700; color:var(--te-text-muted); margin-bottom: .35rem; text-transform: uppercase; letter-spacing: .04em; }
    .rpt-sel {
        width: 100%; padding: .6rem .85rem;
        border:1.5px solid var(--te-border); border-radius:var(--r-sm);
        font-size: .85rem; font-family: inherit; color:var(--te-text);
        background:var(--te-surface-2); outline:none;
        appearance: none; cursor: pointer;
        transition: border-color var(--tr), box-shadow var(--tr);
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat:no-repeat;
        background-position:right .75rem center;
        padding-right: 2.25rem;
    }
    .rpt-sel:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
    .rpt-textarea {
        width: 100%; padding: .65rem .85rem;
        border:1.5px solid var(--te-border); border-radius:var(--r-sm);
        font-size: .85rem; font-family: inherit; color:var(--te-text);
        background:var(--te-surface); outline:none;
        resize: vertical; min-height: 110px;
        transition: border-color var(--tr), box-shadow var(--tr);
        box-sizing: border-box; line-height: 1.6;
    }
    .rpt-textarea:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
    .rpt-hint { font-size: .75rem; color:var(--te-text-muted); margin-top: .3rem; line-height: 1.5; }

    /* Error */
    .rpt-error { font-size: .78rem; color:var(--te-danger-fg); margin-top: .3rem; }

    /* Actions */
    .rpt-actions { display: flex; align-items: center; gap: .75rem; padding: 1.25rem 1.5rem; border-top:1px solid var(--te-border-subtle); background:var(--te-surface-2); }
    .rpt-btn-submit {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.35rem; border-radius:var(--r-sm);
        background:var(--te-danger-solid); color:var(--te-on-primary);
        border:none; font-family: inherit;
        font-size: .85rem; font-weight: 700; cursor: pointer;
        transition: background var(--tr), box-shadow var(--tr);
    }
    .rpt-btn-submit:hover { background:var(--te-danger-solid); box-shadow:0 4px 12px rgba(220,38,38,.25); }
    .rpt-btn-cancel {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.1rem; border-radius:var(--r-sm);
        background:var(--te-surface); color:var(--te-text-muted);
        border:1.5px solid var(--te-border); font-family: inherit;
        font-size: .85rem; font-weight: 600; cursor: pointer;
        text-decoration: none; transition: all var(--tr);
    }
    .rpt-btn-cancel:hover { background:var(--te-surface-2); border-color:var(--te-border-strong); color:var(--te-text); }
    </style>

    @php
        $isEditing = !is_null($existingReport);
        // Parse existing reason: "Type\n\nDetails" or just "Type"
        $existingType = '';
        $existingDesc = '';
        if ($isEditing) {
            $parts = explode("\n\n", $existingReport->reason, 2);
            $existingType = $parts[0] ?? '';
            $existingDesc = $parts[1] ?? '';
        }
    @endphp

    <div class="rpt-wrap">

        {{-- Target card --}}
        <div class="rpt-target">
            <div class="rpt-target-avatar">{{ $user->initials }}</div>
            <div>
                <div class="rpt-target-name">{{ $user->name }}</div>
                <div class="rpt-target-meta">
                    @if($user->isCompany())
                        Entreprise @if($user->sector) · {{ $user->sector }}@endif @if($user->city) · {{ $user->city }}@endif
                    @else
                        Candidat @if($user->city) · {{ $user->city }}@endif
                    @endif
                </div>
            </div>
            @if($isEditing)
            <span style="margin-left:auto;font-size:.75rem;font-weight:700;color:var(--te-warning-fg);background:var(--te-warning-soft);border:1.5px solid var(--te-warning-border);border-radius:6px;padding:.2rem .55rem;white-space:nowrap;">
                Déjà signalé
            </span>
            @endif
        </div>

        @if($isEditing)
        <div style="display:flex;align-items:flex-start;gap:.6rem;padding:.85rem 1.1rem;background:var(--te-warning-soft);border:1.5px solid var(--te-warning-border);border-radius:12px;margin-bottom:1.25rem;font-size:.82rem;color:var(--te-warning-fg);line-height:1.55;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="color:var(--te-warning-fg);flex-shrink:0;margin-top:.1rem;">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
            </svg>
            <span>Vous avez déjà signalé cet utilisateur. Vous pouvez modifier votre signalement ci-dessous — les administrateurs seront notifiés de la mise à jour.</span>
        </div>
        @endif

        {{-- Form card --}}
        <div class="rpt-card">
            <div class="rpt-card-head">
                <div class="rpt-card-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="color:var(--te-danger-fg)">
                        <path d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <span class="rpt-card-title">{{ $isEditing ? 'Modifier le signalement' : 'Envoyer un signalement' }}</span>
                @if($isEditing)
                <span style="margin-left:auto;font-size:.75rem;color:var(--te-text-muted);">Signalé le {{ $existingReport->created_at->translatedFormat('d/m/Y') }}</span>
                @endif
            </div>

            <form id="rptForm" method="POST" action="{{ route('reports.store', $user) }}">
                @csrf

                {{-- Hidden field that carries the combined reason --}}
                <input type="hidden" name="reason" id="rptReason">

                <div class="rpt-card-body">
                    <div>
                        <label class="rpt-label" for="rptType">Motif du signalement</label>
                        <select id="rptType" class="rpt-sel" required>
                            <option value="" disabled {{ $existingType ? '' : 'selected' }}>Choisissez un motif…</option>
                            @if($user->isCompany())
                                <option value="Offre frauduleuse" {{ $existingType === 'Offre frauduleuse' ? 'selected' : '' }}>Offre frauduleuse</option>
                                <option value="Entreprise inexistante" {{ $existingType === 'Entreprise inexistante' ? 'selected' : '' }}>Entreprise inexistante</option>
                                <option value="Contenu inapproprié" {{ $existingType === 'Contenu inapproprié' ? 'selected' : '' }}>Contenu inapproprié</option>
                                <option value="Harcèlement" {{ $existingType === 'Harcèlement' ? 'selected' : '' }}>Harcèlement</option>
                            @else
                                <option value="Profil frauduleux" {{ $existingType === 'Profil frauduleux' ? 'selected' : '' }}>Profil frauduleux</option>
                                <option value="Fausses informations" {{ $existingType === 'Fausses informations' ? 'selected' : '' }}>Fausses informations</option>
                                <option value="Contenu inapproprié" {{ $existingType === 'Contenu inapproprié' ? 'selected' : '' }}>Contenu inapproprié</option>
                                <option value="Spam" {{ $existingType === 'Spam' ? 'selected' : '' }}>Spam</option>
                            @endif
                            <option value="Autre" {{ $existingType === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('reason')<p class="rpt-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="rpt-label" for="rptDesc">Détails <span style="font-weight:400;text-transform:none;letter-spacing:0;">(facultatif)</span></label>
                        <textarea id="rptDesc" class="rpt-textarea"
                                  placeholder="Décrivez le problème avec précision pour aider notre équipe de modération…">{{ $existingDesc }}</textarea>
                        <p class="rpt-hint">Soyez le plus explicite possible — cela aide les administrateurs dans leur décision.</p>
                    </div>
                </div>

                <div class="rpt-actions">
                    <button type="submit" class="rpt-btn-submit">
                        @if($isEditing)
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Mettre à jour le signalement
                        @else
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        Envoyer le signalement
                        @endif
                    </button>
                    <a href="{{ url()->previous() }}" class="rpt-btn-cancel">Annuler</a>
                </div>
            </form>
        </div>

    </div>

@push('scripts')
<script>
document.getElementById('rptForm').addEventListener('submit', function(e) {
    const type = document.getElementById('rptType').value;
    const desc = document.getElementById('rptDesc').value.trim();
    if (!type) { e.preventDefault(); return; }
    document.getElementById('rptReason').value = desc ? type + '\n\n' + desc : type;
});
</script>
@endpush

</x-app-layout>
