<x-app-layout>
    <x-slot name="title">{{ $jobOffer->title }}</x-slot>

    <x-slot name="header">
        @php
            // Hiérarchie : l'entreprise propriétaire passe par « Mes offres », les autres par la liste publique
            $navL0 = (Auth::check() && Auth::id() === $jobOffer->user_id)
                ? ['url' => \App\Support\Breadcrumbs::parent('job-offers.my-offers'), 'label' => 'Mes offres']
                : ['url' => \App\Support\Breadcrumbs::parent('job-offers.index'), 'label' => 'Offres d\'emploi'];
            $showBreadcrumbs = [
                ['label' => $navL0['label'], 'url' => $navL0['url']],
                ['label' => Str::limit($jobOffer->title, 42)],
            ];
        @endphp
        {{-- Fil d'Ariane seul : titre et entreprise figurent dans la bannière (h1) --}}
        <x-page-header :breadcrumbs="$showBreadcrumbs" />
    </x-slot>

    @php
        $hasAppliedToCompany = false;
        if (Auth::user() && Auth::user()->isCandidate()) {
            $hasAppliedToCompany = \App\Models\Application::where('user_id', Auth::id())
                ->whereHas('jobOffer', fn($q) => $q->where('user_id', $jobOffer->user_id))
                ->exists();
        }

        $tb = $jobOffer->typeMeta(); // mêmes icônes que les cartes (JobOffer::TYPES)

        $expDate   = \Carbon\Carbon::parse($jobOffer->expiration_date);
        $daysLeft  = now()->diffInDays($expDate, false);
        $urgentExpiry = $daysLeft <= 7 && $daysLeft >= 0;
        $expired = $daysLeft < 0;
        if (!$expired && $urgentExpiry) {
            $daysInt  = (int) floor($daysLeft);
            $hoursInt = (int) floor(now()->diffInHours($expDate, false));
            $minsInt  = (int) floor(now()->diffInMinutes($expDate, false));
            if ($daysInt >= 1)       $expiryText = $daysInt . 'j';
            elseif ($hoursInt >= 1)  $expiryText = $hoursInt . 'h';
            else                     $expiryText = max(0, $minsInt) . 'min';
        }

        $categoryName = $jobOffer->offerCategory->name ?? null;
        $categoryImg  = $jobOffer->offerCategory->image_src ?? null;
        $companyInitial = strtoupper(substr($jobOffer->company->name, 0, 1));
    @endphp

    <style>
    /* ══ Design tokens ══════════════════════════════════════════ */
    :root {
            --r:       16px;
            --r-sm:    10px;
            --sh:      0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --sh-md:   0 4px 20px rgba(79,70,229,.1);
            --sh-lg:   0 8px 32px rgba(79,70,229,.14);
            --tr:      .18s cubic-bezier(.4,0,.2,1);
        }
        /* ══ Breadcrumb ═════════════════════════════════════════════ */
    .show-bc-current {
        font-size:.8rem; font-weight:700; color:var(--te-text);
        max-width:40ch; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
    }

    /* ══ Page layout ════════════════════════════════════════════ */
    .show-page {
        max-width: 80rem;
        margin: 0 auto;
        padding: 1.75rem 1.5rem 3rem;
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.75rem;
        align-items: start;
    }
    @media (max-width: 960px) { .show-page { grid-template-columns: 1fr; padding: 1rem; } }

    /* Bandeau de modération (pleine largeur, au-dessus des deux colonnes) */
    .show-mod-banner { grid-column:1 / -1; display:flex; align-items:center; gap:.85rem; flex-wrap:wrap; padding:.9rem 1.1rem; border:1px solid; border-left-width:4px; border-radius:var(--r-sm, 10px); }
    .show-mod-banner.is-warning { background:var(--te-warning-soft); border-color:var(--te-warning-border); border-left-color:var(--te-warning-solid); }
    .show-mod-banner.is-neutral { background:var(--te-surface-2); border-color:var(--te-border); border-left-color:var(--te-border-strong); }
    .show-mod-banner.is-info    { background:var(--te-primary-soft); border-color:var(--te-primary-border); border-left-color:var(--te-primary); }
    .show-mod-banner.is-danger  { background:var(--te-danger-soft); border-color:var(--te-danger-border); border-left-color:var(--te-danger-solid); }
    .is-danger .show-mod-ico, .is-danger .show-mod-text h2 { color:var(--te-danger-fg); }
    .show-mod-ico { display:inline-flex; flex-shrink:0; }
    .is-warning .show-mod-ico { color:var(--te-warning-fg); }
    .is-neutral .show-mod-ico { color:var(--te-text-muted); }
    .is-info .show-mod-ico    { color:var(--te-primary-fg); }
    .show-mod-text { flex:1; min-width:14rem; }
    .show-mod-text h2 { margin:0; font-size:.95rem; font-weight:800; color:var(--te-text); }
    .show-mod-text p { margin:.15rem 0 0; font-size:.8125rem; color:var(--te-text-2); line-height:1.5; }
    .show-mod-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
    .show-mod-actions form { margin:0; }
    .show-mod-btn { display:inline-flex; align-items:center; gap:.35rem; padding:.5rem .95rem; border-radius:8px; font:inherit; font-size:.8125rem; font-weight:700; cursor:pointer; border:1px solid; }
    .show-mod-btn.is-ok { background:var(--te-success-solid); border-color:var(--te-success-solid); color:#fff; }
    .show-mod-btn.is-ok:hover { filter:brightness(.95); }
    .show-mod-btn.is-no { background:var(--te-surface); border-color:var(--te-danger-border); color:var(--te-danger-fg); }
    .show-mod-btn.is-no:hover { background:var(--te-danger-soft); }
    .show-mod-btn:focus-visible { outline:2px solid var(--te-focus); outline-offset:2px; }
    @media (max-width: 640px) { .show-mod-actions { width:100%; } .show-mod-actions form { flex:1; } .show-mod-btn { width:100%; justify-content:center; } }

    /* ══ Sticky sidebar ═════════════════════════════════════════ */

    /* ══ Card base ══════════════════════════════════════════════ */
    .show-card-head h2 {
        font-size:.95rem; font-weight:800; color:var(--te-text); margin:0;
        display:flex; align-items:center; gap:.5rem;
    }

    /* ══ Hero banner ════════════════════════════════════════════ */
    .show-hero-banner {
        /* Voile sombre (couleurs du hero) sur l'image de la catégorie : texte lisible quelle que soit la photo */
        background:
            linear-gradient(120deg, color-mix(in srgb, var(--te-hero-from) 88%, transparent) 0%, color-mix(in srgb, var(--te-hero-from) 78%, transparent) 45%, color-mix(in srgb, var(--te-hero-to) 55%, transparent) 100%),
            var(--banner-img, none) center / cover no-repeat,
            var(--te-hero-from);
        padding: 1.75rem 1.75rem 2.75rem;
        position: relative;
        overflow: hidden;
    }
    .show-hero-banner::before { content:none; }

    .show-hero-logo-wrap { flex-shrink: 0; }
    .show-hero-logo {
        width: 72px; height: 72px; border-radius:16px;
        object-fit: cover; border:3px solid rgba(255,255,255,.25);
        box-shadow:0 8px 24px rgba(0,0,0,.25);
        background:var(--te-surface);
    }
    .show-hero-logo-ph {
        width: 72px; height: 72px; border-radius:16px;
        background:rgba(255,255,255,.15);
        border:3px solid rgba(255,255,255,.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem; font-weight: 900; color:var(--te-on-primary);
        box-shadow:0 8px 24px rgba(0,0,0,.2);
        backdrop-filter: blur(4px);
    }
    @media (max-width: 600px) { .show-hero-title { font-size: 1.2rem; } }


    /* ══ Tags / Badges ══════════════════════════════════════════ */
    /* Light tags (card context) */
    /* Dark/glass tags (hero context) */
    .show-tag-urgency {
        background:var(--te-warning-soft); border-color:var(--te-warning-border); color:var(--te-warning-fg);
        animation: pulse-urgency 2s infinite;
    }
    @keyframes pulse-urgency { 0%,100%{ box-shadow:0 0 0 0 rgba(245,158,11,.3);} 50%{ box-shadow:0 0 0 4px rgba(245,158,11,0);} }
    .show-tag-expired { background:var(--te-danger-solid); border-color:transparent; color:var(--te-on-primary); }

    /* ══ Hero pull-up card ══════════════════════════════════════ */

    /* ══ Save / share buttons ═══════════════════════════════════ */
    .show-action-row { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
    .show-btn-outline {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .45rem .9rem; border-radius:var(--r-sm);
        font-size: .78rem; font-weight: 700;
        border:1.5px solid var(--te-border-strong); background:var(--te-surface);
        color:var(--te-text-muted); cursor: pointer; text-decoration: none;
        transition: all var(--tr);
    }
    .show-btn-outline:hover { border-color:var(--te-primary); color:var(--te-primary-fg); background:var(--te-primary-soft); }

    /* ══ Description prose ══════════════════════════════════════ */
    .show-section-title {
        display: flex; align-items: center; gap: .5rem;
        font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em;
        color:var(--te-text-muted); margin: 0 0 .9rem;
    }

    .show-prose {
        font-size: .9rem; color:var(--te-text-2); line-height: 1.8;
        white-space: pre-line;
    }

    /* ══ Details grid ═══════════════════════════════════════════ */
    .show-details-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .85rem;
    }
    @media (max-width: 700px) { .show-details-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 420px) { .show-details-grid { grid-template-columns: 1fr; } }

    .show-detail-chip-icon.green  { background:var(--te-success-soft); }
    .show-detail-chip-icon.green  svg { color:var(--te-success-fg); }
    .show-detail-chip-icon.red    { background:var(--te-danger-soft); }
    .show-detail-chip-icon.red    svg { color:var(--te-danger-fg); }
    .show-detail-chip-value.red   { color:var(--te-danger-fg); }

    /* ══ Keywords / tags row ════════════════════════════════════ */
    .show-keyword {
        display: inline-flex; align-items: center; gap: .25rem;
        padding: .25rem .7rem; border-radius:99px;
        font-size: .75rem; font-weight: 600;
        background:var(--te-surface-3); border:1px solid var(--te-border); color:var(--te-text-2);
        transition: background var(--tr), color var(--tr);
    }
    .show-keyword:hover { background:var(--te-primary-soft); color:var(--te-primary-fg); border-color:var(--te-primary-border); }

    /* ══ Application form ═══════════════════════════════════════ */
    .show-form-group { margin-bottom: 1.25rem; }
    .show-label {
        display: flex; align-items: center; gap: .35rem;
        font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em;
        color:var(--te-text-muted); margin-bottom: .5rem;
    }
    .show-textarea {
        width: 100%; padding: .75rem 1rem;
        border:1.5px solid var(--te-border-strong); border-radius:var(--r-sm);
        font-size: .875rem; font-family: inherit; color:var(--te-text);
        resize: vertical; outline:none; background:var(--te-surface-2);
        transition: border-color var(--tr), box-shadow var(--tr), background var(--tr);
        min-height: 120px;
    }
    .show-textarea:focus { border-color:var(--te-primary); background:var(--te-surface); box-shadow:0 0 0 3px rgba(79,70,229,.1); }

    /* Char counter */
    .show-char-counter { font-size: .75rem; color:var(--te-text-muted); text-align: right; margin-top: .3rem; }
    .show-char-counter.warn { color:var(--te-warning-fg); }
    .show-char-counter.ok   { color:var(--te-success-fg); }

    /* CV choice cards */
    .show-cv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; margin-bottom: .75rem; }
    @media (max-width: 420px) { .show-cv-grid { grid-template-columns: 1fr; } }

    .show-cv-card {
        border:2px solid var(--te-border); border-radius:var(--r-sm);
        padding: 1rem; cursor: pointer;
        transition: border-color var(--tr), background var(--tr), box-shadow var(--tr);
        display: flex; flex-direction: column; gap: .3rem;
    }
    .show-cv-card:has(input:checked) {
        border-color:var(--te-primary); background:var(--te-primary-soft);
        box-shadow:0 0 0 3px rgba(79,70,229,.1);
    }
    .show-cv-card-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .show-cv-card-title { font-size: .85rem; font-weight: 800; color:var(--te-text); }
    .show-cv-card-sub   { font-size: .75rem; color:var(--te-text-muted); }
    .show-cv-avail { font-size: .75rem; font-weight: 700; color:var(--te-success-fg); background:var(--te-success-soft); padding:.1rem .45rem; border-radius:99px; display:flex; align-items:center; gap:.2rem; }
    .show-cv-none  { font-size: .75rem; font-weight: 700; color:var(--te-danger-fg); }

    /* Upload zone */
    .show-upload-zone {
        border:2px dashed var(--te-border-strong); border-radius:var(--r-sm);
        padding: 1.25rem; background:var(--te-surface-2); text-align: center;
        font-size: .8rem; color:var(--te-text-muted); margin-bottom: .75rem;
        transition: border-color var(--tr), background var(--tr);
    }
    .show-upload-zone:hover { border-color:var(--te-primary); background:var(--te-primary-soft); }
    .show-upload-zone:focus-visible { outline:none; border-color:var(--te-primary); box-shadow:0 0 0 3px var(--te-primary-ring); }

    /* Fichier choisi : nom, taille et bouton « Retirer » */
    .show-file-chip {
        display:flex; align-items:center; gap:.7rem; margin-bottom:.75rem;
        padding:.7rem .8rem; border:1.5px solid var(--te-success-border); border-radius:var(--r-sm);
        background:var(--te-success-soft);
    }
    .show-file-chip[hidden] { display:none; }
    .show-file-chip > svg { flex-shrink:0; color:var(--te-success-fg); }
    .show-file-meta { flex:1; min-width:0; display:flex; flex-direction:column; }
    .show-file-name { font-size:.82rem; font-weight:700; color:var(--te-text); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .show-file-size { font-size:.75rem; color:var(--te-text-muted); }
    .show-file-remove {
        flex-shrink:0; display:inline-flex; align-items:center; gap:.3rem;
        padding:.35rem .6rem; border-radius:6px; border:1px solid var(--te-border-strong);
        background:var(--te-surface); color:var(--te-text-2); font-size:.75rem; font-weight:700; cursor:pointer;
        transition:border-color var(--tr), color var(--tr), background var(--tr);
    }
    .show-file-remove:hover { border-color:var(--te-danger-border); color:var(--te-danger-fg); background:var(--te-danger-soft); }
    .show-file-remove:focus-visible { outline:2px solid var(--te-primary); outline-offset:2px; }

    /* Submit button */
    .show-submit-btn {
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        width: 100%; padding: .85rem 1.5rem;
        background:linear-gradient(135deg, var(--te-primary), var(--te-primary-400));
        color:var(--te-on-primary); border:none; border-radius:var(--r-sm);
        font-size: .9rem; font-weight: 800; font-family: inherit; letter-spacing: .02em;
        cursor: pointer;
        box-shadow:0 4px 14px rgba(79,70,229,.35);
        transition: opacity var(--tr), transform var(--tr), box-shadow var(--tr);
    }
    .show-submit-btn:hover { opacity: .93; transform: translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,.4); }
    .show-submit-btn:active { transform: translateY(0); }

    /* ══ Guest CTA card ═════════════════════════════════════════ */
    .show-guest-cta {
        text-align: center; padding: 2.25rem 1.5rem;
    }
    .show-guest-icon {
        width: 56px; height: 56px; border-radius:16px;
        background:var(--te-primary-soft); border:1.5px solid var(--te-primary-border);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
    }
    .show-guest-title { font-size: 1.05rem; font-weight: 800; color:var(--te-text); margin: 0 0 .4rem; }
    .show-guest-sub   { font-size: .85rem; color:var(--te-text-muted); margin: 0 0 1.25rem; line-height: 1.55; }
    .show-guest-actions { display: flex; align-items: center; justify-content: center; gap: .75rem; flex-wrap: wrap; }
    .show-btn-ghost {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.25rem; border-radius:8px;
        border:1.5px solid var(--te-border-strong); background:var(--te-surface);
        color:var(--te-text-2); font-size: .875rem; font-weight: 600;
        text-decoration: none; transition: all var(--tr);
    }
    .show-btn-solid {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .6rem 1.25rem; border-radius:8px;
        background:var(--te-primary); color:var(--te-on-primary);
        font-size: .875rem; font-weight: 700;
        text-decoration: none;
        box-shadow:0 2px 8px rgba(79,70,229,.25);
        transition: all var(--tr);
    }
    .show-btn-solid:hover { background:var(--te-primary-hover); box-shadow:0 4px 12px rgba(79,70,229,.35); }

    /* ══ Application status banner ══════════════════════════════ */
    .show-status-banner { border-radius:var(--r-sm); overflow: hidden; border:1.5px solid; }
    .show-status-head {
        display: flex; align-items: center; gap: .75rem;
        padding: .875rem 1rem; border-bottom:1px solid rgba(0,0,0,.06);
    }
    .show-status-icon { width: 36px; height: 36px; border-radius:8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .show-status-body { padding: .875rem 1rem; display: flex; align-items: center; justify-content: space-between; gap: .5rem; flex-wrap: wrap; }
    .show-status-dot { width: 8px; height: 8px; border-radius:50%; display: inline-block; margin-right: .4rem; flex-shrink: 0; }
    .show-status-pill { display: inline-flex; align-items: center; padding: .25rem .85rem; border-radius:99px; font-size: .75rem; font-weight: 800; border:1.5px solid; }
    .show-status-foot { display: flex; align-items: center; gap: .4rem; padding: .5rem 1rem; font-size: .75rem; font-weight: 500; border-top:1px solid rgba(0,0,0,.06); }

    /* ══ Company card sidebar ═══════════════════════════════════ */
    .show-company-name { font-size: 1rem; font-weight: 800; color:var(--te-primary-fg); }
    .show-company-link {
        display: inline-flex; align-items: center; gap: .35rem; margin-top: .85rem;
        font-size: .8rem; font-weight: 700; color:var(--te-primary-fg);
        text-decoration: none; transition: color var(--tr);
        padding: .4rem .85rem; border-radius:8px; border:1.5px solid var(--te-primary-border);
        background:var(--te-primary-soft); width: 100%; justify-content: center;
    }
    .show-company-link:hover { background:var(--te-primary); color:var(--te-on-primary); border-color:var(--te-primary); }

    /* ══ Progress bar (similar offers) ═════════════════════════ */

    /* ══ Company actions card ══════════════════════════════════ */
    .show-actions-card {
        background:var(--te-surface); border:1px solid var(--te-border);
        border-radius:var(--r); box-shadow:var(--sh); overflow: hidden;
    }
    .show-actions-head {
        display: flex; align-items: center; gap: .65rem;
        padding: 1rem 1.5rem; border-bottom:1px solid var(--te-border-subtle); background:var(--te-surface-2);
    }
    .show-actions-body { padding: 1rem 1.25rem; display: flex; flex-direction: column; gap: .6rem; }
    .show-act-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
        width: 100%; padding: .55rem 1rem; border-radius:8px;
        font-size: .82rem; font-weight: 700; font-family: inherit;
        cursor: pointer; text-decoration: none; border:1.5px solid;
        transition: all var(--tr);
    }
    .show-act-edit {
        background:var(--te-primary-soft); color:var(--te-primary-fg); border-color:var(--te-primary-border);
    }
    .show-act-edit:hover { background:var(--te-primary); color:var(--te-on-primary); border-color:var(--te-primary); }
    .show-act-archive {
        background:var(--te-danger-soft); color:var(--te-danger-fg); border-color:var(--te-danger-border);
    }
    .show-act-archive:hover { background:var(--te-danger-soft); border-color:var(--te-danger-solid); }
    .show-act-unarchive {
        background:var(--te-info-soft); color:var(--te-info-fg); border-color:var(--te-info-border);
    }
    .show-act-unarchive:hover { background:var(--te-info-soft); border-color:var(--te-info-solid); }

    /* ══ Modal ══════════════════════════════════════════════════ */
    .modal-backdrop { display:none; position:fixed; inset:0; z-index:500; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
    .modal-backdrop.open { display:flex; }
    .modal { background:var(--te-surface); border-radius:16px; width:100%; max-width:420px; border:1.5px solid var(--te-border); box-shadow:0 20px 60px rgba(0,0,0,.18); overflow:hidden; }
    .modal-head { display:flex; align-items:flex-start; gap:1rem; padding:1.25rem 1.5rem; border-bottom:1px solid var(--te-border); }
    .modal-title { font-size:1rem; font-weight:800; color:var(--te-text); margin-bottom:.2rem; }
    .modal-msg { font-size:.85rem; color:var(--te-text-muted); line-height:1.55; margin:0; }
    .modal-cancel { padding:.53rem 1.2rem; border-radius:8px; border:1.5px solid var(--te-border); background:var(--te-surface); font-size:.85rem; font-weight:600; color:var(--te-text-muted); cursor:pointer; transition:all var(--tr); font-family:inherit; }
    .modal-ok { padding:.53rem 1.2rem; border-radius:8px; border:none; font-size:.85rem; font-weight:700; color:var(--te-on-primary); cursor:pointer; transition:opacity var(--tr); font-family:inherit; }

    /* ══ Entreprise et offres similaires ═════════════════════════ */
    .show-company-sub { font-size:.8125rem; color:var(--te-text-muted); margin-top:.1rem; }
    .show-company-facts { list-style:none; margin:0 0 .9rem; padding:0; display:grid; gap:.35rem; font-size:.8125rem; color:var(--te-text); }
    .show-company-facts li { display:flex; align-items:center; gap:.5rem; min-width:0; }
    .show-fact-ico { flex-shrink:0; color:var(--te-text-muted); }
    .show-fact-link { color:var(--te-primary-fg); text-decoration:underline; text-underline-offset:2px; overflow-wrap:anywhere; }
    .show-fact-link:hover { text-decoration-thickness:2px; }
    .show-company-more { display:block; margin-top:.6rem; font-size:.8125rem; font-weight:600; color:var(--te-primary-fg); }
    .show-similar { list-style:none; margin:0; padding:.25rem 1.25rem .75rem; }
    .show-similar li { display:flex; gap:.7rem; align-items:center; padding:.6rem 0; border-bottom:1px solid var(--te-border-subtle); }
    .show-similar li:last-child { border-bottom:none; }
    .show-similar-title { display:block; font-size:.875rem; font-weight:700; color:var(--te-text); text-decoration:none; line-height:1.3; }
    .show-similar-title:hover { color:var(--te-primary-fg); text-decoration:underline; }
    .show-similar-meta { font-size:.75rem; color:var(--te-text-muted); margin-top:.1rem; }

    .show-closed-msg { margin:0; padding:.85rem 1rem; border-radius:var(--te-radius-sm); background:var(--te-danger-soft); border:1px solid var(--te-danger-border); color:var(--te-danger-fg); font-size:.875rem; font-weight:600; }

    /* ══ Report card ════════════════════════════════════════════ */
    .show-report-btn {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .4rem .875rem; border-radius:8px;
        font-size: .8rem; font-weight: 700; color:var(--te-danger-fg);
        border:1.5px solid var(--te-danger-border); background:var(--te-surface);
        text-decoration: none; white-space: nowrap;
        transition: all var(--tr);
    }

    /* ══ Animations ═════════════════════════════════════════════ */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
    .show-anim { }
    .show-anim-d4 { animation-delay: .2s; }
    </style>

    <div class="show-page">

        {{-- ── Bandeau de modération : admin (actions) et entreprise propriétaire (information) ── --}}
        @php
            $viewerIsAdmin = Auth::check() && Auth::user()->isAdmin();
            $viewerIsOwner = Auth::check() && Auth::id() === $jobOffer->user_id;
        @endphp
        @if($viewerIsAdmin && $jobOffer->status === 'pending_validation')
            <section class="show-mod-banner is-warning" aria-labelledby="mod-banner-title">
                <span class="show-mod-ico"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <div class="show-mod-text">
                    <h2 id="mod-banner-title">Cette offre attend votre validation</h2>
                    <p>Déposée par {{ $jobOffer->company->name ?? 'une entreprise' }} le {{ $jobOffer->created_at->translatedFormat('j F Y') }}. Une fois validée, elle devient visible des candidats.</p>
                </div>
                <div class="show-mod-actions">
                    {{-- Même route et même contrôleur que la page Modération (AdminController@updateOfferStatus) --}}
                    <form method="POST" action="{{ route('admin.offers.status', $jobOffer) }}" class="js-validate-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="open">
                        <button type="submit" class="show-mod-btn is-ok"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Valider</button>
                    </form>
                    <form method="POST" action="{{ route('admin.offers.status', $jobOffer) }}" class="js-refuse-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="refused">
                        <button type="submit" class="show-mod-btn is-no"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> Refuser</button>
                    </form>
                </div>
            </section>
        @elseif($viewerIsAdmin && $jobOffer->status === 'refused')
            <section class="show-mod-banner is-danger" role="status">
                <span class="show-mod-ico"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg></span>
                <div class="show-mod-text">
                    <h2>Cette offre a été refusée</h2>
                    <p>Elle n'est pas visible des candidats. L'entreprise peut la modifier pour la soumettre de nouveau.</p>
                </div>
            </section>
        @elseif($viewerIsAdmin && $jobOffer->status === 'archived')
            <section class="show-mod-banner is-neutral" role="status">
                <span class="show-mod-ico"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg></span>
                <div class="show-mod-text">
                    <h2>Cette offre a été archivée</h2>
                    <p>L'entreprise l'a retirée de la liste des offres ; elle n'est plus visible des candidats.</p>
                </div>
            </section>
        @elseif($viewerIsOwner && $jobOffer->status === 'pending_validation')
            <section class="show-mod-banner is-info" role="status">
                <span class="show-mod-ico"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <div class="show-mod-text">
                    <h2>En attente de validation par l'administrateur</h2>
                    <p>Votre offre sera visible des candidats dès qu'elle aura été validée.</p>
                </div>
            </section>
        @endif

        {{-- ══════════════  LEFT COLUMN  ══════════════ --}}
        <div>

            {{-- ── Hero Banner ── --}}
            <div class="show-hero show-anim">
                @php $bannerImg = $jobOffer->offerCategory->banner_src ?? null; @endphp
                <div class="show-hero-banner" @if($bannerImg) style="--banner-img:url('{{ $bannerImg }}')" @endif>
                    <div class="show-hero-top">
                        <div class="show-hero-logo-wrap">
                            <x-company-logo :company="$jobOffer->company" :size="72" />
                        </div>
                        <div class="show-hero-info">
                            <div class="show-hero-company">{{ $jobOffer->company->name }}</div>
                            <h1 class="show-hero-title">{{ $jobOffer->title }}</h1>
                        </div>
                    </div>
                    <div class="show-hero-tags">
                        <span class="show-tag show-tag-hero">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $jobOffer->location ?? 'Non spécifié' }}
                        </span>
                        <span class="show-tag show-tag-hero"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tb['icon'] }}"/></svg> {{ $tb['label'] }}</span>
                        @if($categoryName)
                            <span class="show-tag show-tag-hero">
                                <x-category-icon :category="$jobOffer->offerCategory" :size="12" />
                                {{ $categoryName }}
                            </span>
                        @endif
                        @if($jobOffer->closedBadge())
                            <span class="show-tag show-tag-expired">{{ $jobOffer->closedBadge() }}</span>
                        @elseif($urgentExpiry)
                            <span class="show-tag show-tag-urgency"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Expire dans {{ $expiryText }}</span>
                        @endif
                    </div>
                </div>

                {{-- Pull-up meta row --}}
                <div class="show-hero-pullup">
                    <div class="show-hero-meta">
                        @if($jobOffer->salary)
                            <div class="show-hero-meta-item">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <strong style="color:var(--te-text);">{{ $jobOffer->salaryLabel() }}</strong>
                            </div>
                        @endif
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $jobOffer->vacanciesLabel() }}
                        </div>
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Publiée le {{ $jobOffer->created_at->translatedFormat('j F Y') }}
                        </div>
                        @if($jobOffer->deadlineLabel())
                        <div class="show-hero-meta-item">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span @if($jobOffer->isExpired()) style="color:var(--te-danger-fg);font-weight:700;" @endif>{{ $jobOffer->deadlineLabel() }}</span>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Description & Requirements ── --}}
            <div class="show-card show-anim show-anim-d1" style="margin-bottom:1.25rem;">
                <div class="show-card-head">
                    <div class="show-card-head-icon indigo" style="background:var(--te-primary-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2>Description du poste</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-prose" style="margin-bottom:2rem;">{{ $jobOffer->description }}</div>

                    <p class="show-section-title">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Exigences &amp; Compétences
                    </p>
                    <div class="show-prose">{{ $jobOffer->requirements }}</div>

                    @if($jobOffer->keywords)
                        <p class="show-section-title" style="margin-top:1.75rem;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Mots-clés
                        </p>
                        <div class="show-keywords">
                            @foreach(explode(',', $jobOffer->keywords) as $kw)
                                @if(trim($kw))
                                    <span class="show-keyword"># {{ trim($kw) }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Details chips ── --}}
            <div class="show-card show-anim show-anim-d2" style="margin-bottom:1.25rem;">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-success-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-success-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h2>Détails de l'offre</h2>
                </div>
                <div class="show-card-body">
                    <div class="show-details-grid">

                        @if($categoryName)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon indigo">
                                <x-category-icon :category="$jobOffer->offerCategory" :size="16" />
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Catégorie</div>
                                <div class="show-detail-chip-value">{{ $categoryName }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon blue"><x-education-icon :level="$jobOffer->education_level" :size="16" />
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Niveau d'études</div>
                                <div class="show-detail-chip-value">{{ \App\Support\Education::label($jobOffer->education_level) }}</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon green">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Expérience</div>
                                <div class="show-detail-chip-value">{{ \App\Support\Experience::label($jobOffer->experience_years) }}</div>
                            </div>
                        </div>

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon purple">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Postes vacants</div>
                                <div class="show-detail-chip-value">{{ $jobOffer->vacancies }}</div>
                            </div>
                        </div>

                        @if($jobOffer->languages)
                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon amber">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label">Langues</div>
                                <div class="show-detail-chip-value">{{ $jobOffer->languages }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="show-detail-chip">
                            <div class="show-detail-chip-icon red">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="show-detail-chip-label" style="color:var(--te-danger-fg);">Expiration</div>
                                <div class="show-detail-chip-value red">
                                    {{ \Carbon\Carbon::parse($jobOffer->expiration_date)->translatedFormat('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Application section ── --}}
            @guest
                @if(!$jobOffer->acceptsApplications())
                <div class="show-card show-anim show-anim-d3">
                    <div class="show-card-head">
                        <h2>Candidatures closes</h2>
                    </div>
                    <div class="show-card-body">
                        <p class="show-closed-msg" role="status">{{ $jobOffer->closedMessage() }}</p>
                        <a href="{{ route('job-offers.index') }}" class="show-btn-ghost" style="display:inline-flex;margin-top:.9rem;">Voir les offres ouvertes</a>
                    </div>
                </div>
                @else
                <div class="show-card show-anim show-anim-d3">
                    <div class="show-card-head">
                        <div class="show-card-head-icon" style="background:var(--te-primary-soft);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <h2>Postuler à cette offre</h2>
                    </div>
                    <div class="show-card-body show-guest-cta">
                        <div class="show-guest-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="show-guest-title">Connectez-vous pour postuler</h3>
                        <p class="show-guest-sub">Créez un compte candidat gratuit ou connectez-vous pour envoyer votre candidature en quelques clics.</p>
                        <div class="show-guest-actions">
                            <a href="{{ route('login') }}" class="show-btn-ghost">Se connecter</a>
                            <a href="{{ route('register') }}" class="show-btn-solid">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer un compte
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            @endguest

            @if(Auth::check() && Auth::user()->isCandidate())
                <div class="show-card show-anim show-anim-d3">
                    <div class="show-card-head">
                        <div class="show-card-head-icon" style="background:var(--te-primary-soft);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <h2>Soumettre ma candidature</h2>
                    </div>
                    <div class="show-card-body">

                        @if(Auth::user()->applications->where('job_offer_id', $jobOffer->id)->count() > 0)
                            @php
                                $application = Auth::user()->applications->where('job_offer_id', $jobOffer->id)->first();
                                $appStatus = $application->status;
                                $sc = [
                                    'pending'  => ['label'=>'En attente',  'desc'=>"L'entreprise examine votre candidature.", 'dot'=>'var(--te-warning-solid)','bg'=>'var(--te-warning-soft)','color'=>'var(--te-warning-fg)','border'=>'var(--te-warning-border)','icon_bg'=>'var(--te-warning-soft)','icon_c'=>'var(--te-warning-fg)'],
                                    'accepted' => ['label'=>'Acceptée',    'desc'=>"Félicitations ! L'entreprise souhaite vous rencontrer.", 'dot'=>'var(--te-success-solid)','bg'=>'var(--te-success-soft)','color'=>'var(--te-success-fg)','border'=>'var(--te-success-border)','icon_bg'=>'var(--te-success-soft)','icon_c'=>'var(--te-success-fg)'],
                                    'rejected' => ['label'=>'Non retenue', 'desc'=>"L'entreprise n'a pas donné suite à votre profil.", 'dot'=>'var(--te-danger-solid)','bg'=>'var(--te-danger-soft)','color'=>'var(--te-danger-fg)','border'=>'var(--te-danger-border)','icon_bg'=>'var(--te-danger-soft)','icon_c'=>'var(--te-danger-fg)'],
                                ][$appStatus] ?? ['label'=>ucfirst($appStatus),'desc'=>'','dot'=>'var(--te-neutral-400)','bg'=>'var(--te-surface-2)','color'=>'var(--te-text-2)','border'=>'var(--te-border)','icon_bg'=>'var(--te-surface-3)','icon_c'=>'var(--te-text-muted)'];
                            @endphp

                            <div class="show-status-banner" style="border-color:{{ $sc['border'] }};">
                                <div class="show-status-head" style="background:{{ $sc['icon_bg'] }};">
                                    <div class="show-status-icon" style="background:{{ $sc['icon_bg'] }}; border:1.5px solid {{ $sc['border'] }};">
                                        <svg width="16" height="16" fill="none" stroke="{{ $sc['icon_c'] }}" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div style="font-size:.875rem; font-weight:800; color:var(--te-text);">Candidature déjà envoyée</div>
                                        <div style="font-size:.75rem; color:var(--te-text-muted); margin-top:2px;">Vous avez soumis votre profil pour cette offre</div>
                                    </div>
                                </div>
                                <div class="show-status-body" style="background:{{ $sc['bg'] }};">
                                    <div style="display:flex; align-items:center;">
                                        <span class="show-status-dot" style="background:{{ $sc['dot'] }};"></span>
                                        <div>
                                            <div style="font-size:.9rem; font-weight:800; color:{{ $sc['color'] }};">{{ $sc['label'] }}</div>
                                            <div style="font-size:.775rem; color:{{ $sc['color'] }}; opacity:.8; margin-top:2px;">{{ $sc['desc'] }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="show-status-foot" style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }};">
                                    <svg width="12" height="12" fill="none" stroke="{{ $sc['dot'] }}" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    Candidature envoyée le {{ $application->created_at->translatedFormat('d/m/Y') }}
                                </div>
                            </div>

                        @elseif(!$jobOffer->acceptsApplications())
                            <p class="show-closed-msg" role="status">{{ $jobOffer->closedMessage() }}</p>
                            <a href="{{ route('job-offers.index') }}" class="show-btn-ghost" style="display:inline-flex;margin-top:.9rem;">Voir les offres ouvertes</a>
                        @else
                            <form method="POST" action="{{ route('applications.store', $jobOffer) }}" enctype="multipart/form-data" id="apply-form">
                                @csrf

                                {{-- CV selection --}}
                                <div class="show-form-group">
                                    <label class="show-label">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Sélection du CV
                                        <span style="font-weight:400;font-size:.75rem;color:var(--te-text-muted);">(obligatoire)</span>
                                    </label>
                                    <div class="show-cv-grid">
                                        <label class="show-cv-card">
                                            <div class="show-cv-card-top">
                                                <span class="show-cv-card-title">Mon CV actuel</span>
                                                <input type="radio" name="cv_type" value="existing"
                                                       style="accent-color:var(--te-primary-fg); width:16px; height:16px;"
                                                       {{ old('cv_type','existing') == 'existing' ? 'checked' : '' }}
                                                       onchange="toggleNewCv(false)">
                                            </div>
                                            <span class="show-cv-card-sub">
                                                @if(Auth::user()->cv_path)
                                                    <span class="show-cv-avail">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                        CV disponible
                                                    </span>
                                                    <a href="{{ route('profile.cv.download') }}"
                                                       target="_blank"
                                                       onclick="event.stopPropagation();"
                                                       style="display:inline-flex;align-items:center;gap:.25rem;margin-top:.45rem;font-size:.75rem;font-weight:700;color:var(--te-primary-fg);background:var(--te-primary-soft);border:1px solid var(--te-primary-border);border-radius:5px;padding:.18rem .5rem;text-decoration:none;transition:all .15s;"
                                                       onmouseover="this.style.background='var(--te-primary)';this.style.color='var(--te-on-primary)';"
                                                       onmouseout="this.style.background='var(--te-primary-soft)';this.style.color='var(--te-primary-fg)';">
                                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        Consulter mon CV
                                                    </a>
                                                @else
                                                    <span class="show-cv-none">Aucun CV enregistré</span>
                                                @endif
                                            </span>
                                        </label>

                                        <label class="show-cv-card">
                                            <div class="show-cv-card-top">
                                                <span class="show-cv-card-title">Nouveau CV</span>
                                                <input type="radio" name="cv_type" value="new"
                                                       style="accent-color:var(--te-primary-fg); width:16px; height:16px;"
                                                       {{ old('cv_type') == 'new' ? 'checked' : '' }}
                                                       onchange="toggleNewCv(true)">
                                            </div>
                                            <span class="show-cv-card-sub">Spécifique pour ce poste</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('cv_type')" class="mt-2"/>
                                </div>

                                {{-- New CV upload --}}
                                <div id="new_cv_container"
                                     style="{{ old('cv_type') == 'new' ? '' : 'display:none;' }}margin-bottom:.75rem;">
                                    <div id="new-cv-zone" class="show-upload-zone" data-upload-for="new_cv"
                                         role="button" tabindex="0" style="cursor:pointer;" aria-label="Choisir un nouveau CV (PDF, DOC ou DOCX, 5 Mo maximum)">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             style="margin:0 auto .5rem;color:var(--te-text-muted);display:block;" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <p style="font-size:.82rem;font-weight:600;color:var(--te-text-muted);text-align:center;margin:0;">
                                            <span style="color:var(--te-primary-fg);">Choisir un fichier</span>
                                        </p>
                                        <p style="font-size:.75rem;color:var(--te-text-muted);text-align:center;margin:.35rem 0 0;">
                                            PDF, DOC, DOCX · Max 5 Mo
                                        </p>
                                    </div>
                                    <div id="new-cv-chip" class="show-file-chip" hidden>
                                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <div class="show-file-meta">
                                            <span class="show-file-name" data-file-name></span>
                                            <span class="show-file-size" data-file-size></span>
                                        </div>
                                        <button type="button" class="show-file-remove" data-upload-clear="new_cv" aria-label="Retirer le fichier">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Retirer
                                        </button>
                                    </div>
                                    <input type="file" name="new_cv" id="new_cv" accept=".pdf,.doc,.docx"
                                           style="display:none;" {{ old('cv_type') == 'new' ? '' : 'disabled' }}>
                                    <x-input-error :messages="$errors->get('new_cv')" class="mt-2"/>
                                </div>

                                {{-- Motivation letter (file upload) --}}
                                <div class="show-form-group">
                                    <label class="show-label">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Lettre de motivation
                                        <span style="font-weight:400;font-size:.75rem;color:var(--te-text-muted);">(facultative)</span>
                                    </label>
                                    <div id="cover-letter-zone" class="show-upload-zone" data-upload-for="cover_letter"
                                         role="button" tabindex="0" style="cursor:pointer;" aria-label="Choisir une lettre de motivation (PDF, DOC ou DOCX, 5 Mo maximum)">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             style="margin:0 auto .5rem;color:var(--te-text-muted);display:block;" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <p style="font-size:.82rem;font-weight:600;color:var(--te-text-muted);text-align:center;margin:0;">
                                            <span style="color:var(--te-primary-fg);">Choisir un fichier</span>
                                        </p>
                                        <p style="font-size:.75rem;color:var(--te-text-muted);text-align:center;margin:.35rem 0 0;">
                                            PDF, DOC, DOCX · Max 5 Mo
                                        </p>
                                    </div>
                                    <div id="cover-letter-chip" class="show-file-chip" hidden>
                                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <div class="show-file-meta">
                                            <span class="show-file-name" data-file-name></span>
                                            <span class="show-file-size" data-file-size></span>
                                        </div>
                                        <button type="button" class="show-file-remove" data-upload-clear="cover_letter" aria-label="Retirer le fichier">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Retirer
                                        </button>
                                    </div>
                                    <input type="file" id="cover_letter" name="cover_letter"
                                           accept=".pdf,.doc,.docx" style="display:none;">
                                    <x-input-error :messages="$errors->get('cover_letter')" class="mt-2"/>
                                </div>

                                <button type="submit" class="show-submit-btn" id="apply-btn">
                                    
                                    Envoyer ma candidature
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- ══════════════  SIDEBAR  ══════════════ --}}
        <aside class="show-sidebar show-anim show-anim-d2">

            {{-- Company card --}}
            <div class="show-card">
                <div class="show-card-head">
                    <div class="show-card-head-icon" style="background:var(--te-info-soft);">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-info-fg)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2>À propos de l'entreprise</h2>
                </div>
                <div class="show-card-body">
                    <div style="display:flex; align-items:center; gap:.875rem; margin-bottom:1rem;">
                        <x-company-logo :company="$jobOffer->company" :size="52" />
                        <div style="min-width:0;">
                            <div class="show-company-name">{{ $jobOffer->company->name }}</div>
                            @if($jobOffer->company->sector)
                                <div class="show-company-sub">{{ $jobOffer->company->sector }}</div>
                            @endif
                        </div>
                    </div>

                    <ul class="show-company-facts">
                        @if($jobOffer->company->city)
                            <li><svg class="show-fact-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span class="sr-only">Ville : </span>{{ $jobOffer->company->city }}</li>
                        @endif
                        @if($jobOffer->company->company_size)
                            <li><svg class="show-fact-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg><span class="sr-only">Taille : </span>{{ $jobOffer->company->company_size }}</li>
                        @endif
                        @if($jobOffer->company->website)
                            <li><svg class="show-fact-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg><span class="sr-only">Site : </span><a href="{{ $jobOffer->company->website }}" target="_blank" rel="noopener" class="show-fact-link">{{ parse_url($jobOffer->company->website, PHP_URL_HOST) ?: $jobOffer->company->website }}<span class="sr-only"> (nouvel onglet)</span></a></li>
                        @endif
                    </ul>

                    @if($jobOffer->company->bio)
                        <p style="font-size:.83rem; color:var(--te-text-muted); line-height:1.7; margin-bottom:.75rem;">{{ $jobOffer->company->bio }}</p>
                    @endif

                    @if($jobOffer->company->website)
                        <a href="{{ $jobOffer->company->website }}" target="_blank" rel="noopener" class="show-company-link">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Visiter le site web
                        </a>
                    @endif
                    <a href="{{ route('job-offers.index', ['search' => $jobOffer->company->name]) }}" class="show-company-more">
                        Toutes les offres de {{ $jobOffer->company->name }}
                    </a>
                </div>
            </div>

            {{-- Offres similaires --}}
            @if($similarOffers->isNotEmpty())
                <section class="show-card" aria-labelledby="similar-title">
                    <div class="show-card-head">
                        <h2 id="similar-title">Offres similaires</h2>
                    </div>
                    <ul class="show-similar">
                        @foreach($similarOffers as $sim)
                            <li>
                                <x-company-logo :company="$sim->company" :size="40" />
                                <div style="min-width:0;">
                                    <a href="{{ route('job-offers.show', $sim) }}" class="show-similar-title">{{ $sim->title }}</a>
                                    <div class="show-similar-meta">{{ $sim->company->name }}@if($sim->location) · {{ $sim->location }}@endif</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- Company owner actions card --}}
            @if(Auth::check() && Auth::user()->isCompany() && $jobOffer->user_id === Auth::id())
                @php $isArchived = $jobOffer->status === 'archived'; @endphp
                <div class="show-actions-card">
                    <div class="show-actions-head">
                        <div class="show-card-head-icon" style="background:var(--te-surface-3);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--te-text-2)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </div>
                        <h2 style="font-size:.95rem; font-weight:800; color:var(--te-text); margin:0;">Gestion de l'offre</h2>
                    </div>
                    <div class="show-actions-body">
                        <a href="{{ route('job-offers.edit', $jobOffer) }}" class="show-act-btn show-act-edit">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Modifier l'offre
                        </a>
                        @if($isArchived)
                            <form method="POST" action="{{ route('job-offers.unarchive', $jobOffer) }}"
                                  class="js-restore-form" data-title="{{ $jobOffer->title }}" style="display:contents;">
                                @csrf
                                <button type="submit" class="show-act-btn show-act-unarchive">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Réactiver
                                </button>
                            </form>
                        @elseif($jobOffer->status === 'open')
                            <form method="POST" action="{{ route('job-offers.archive', $jobOffer) }}"
                                  class="js-archive-form" data-title="{{ $jobOffer->title }}" style="display:contents;">
                                @csrf
                                <button type="submit" class="show-act-btn show-act-archive">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="21 8 21 21 3 21 3 8"/>
                                        <rect x="1" y="3" width="22" height="5" stroke-width="2"/>
                                        <line x1="10" y1="12" x2="14" y2="12" stroke-width="2"/>
                                    </svg>
                                    Archiver
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Report card (only if applied to company) --}}
            @if($hasAppliedToCompany)
                <div class="show-report-card">
                    <div>
                        <div style="font-size:.83rem; font-weight:800; color:var(--te-danger-fg);">Un problème avec cette entreprise ?</div>
                        <div style="font-size:.75rem; color:var(--te-danger-fg); margin-top:.15rem;">Signalez-le à notre équipe de modération.</div>
                    </div>
                    <a href="{{ route('reports.create', $jobOffer->company) }}" class="show-report-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        Signaler
                    </a>
                </div>
            @endif

        </aside>

    </div>{{-- /.show-page --}}

{{-- Confirmation modal --}}
@if(Auth::check() && ((Auth::user()->isCompany() && $jobOffer->user_id === Auth::id()) || (Auth::user()->isAdmin() && $jobOffer->status === 'pending_validation')))
<div id="confirm-modal" class="modal-backdrop" role="dialog" aria-modal="true">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-ico" id="modal-ico"></div>
            <div>
                <h3 class="modal-title" id="modal-title"></h3>
                <p class="modal-msg" id="modal-msg"></p>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="modal-cancel" id="modal-cancel">Annuler</button>
            <button type="button" class="modal-ok" id="modal-ok"></button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Choix du CV : le champ « nouveau CV » n'est envoyé que si cette option est cochée.
    // Le fichier déjà choisi est conservé quand on bascule d'une option à l'autre.
    function toggleNewCv(show) {
        const el = document.getElementById('new_cv_container');
        const input = document.getElementById('new_cv');
        if (el) el.style.display = show ? 'block' : 'none';
        if (input) input.disabled = !show;
    }

    // Zones de dépôt : affiche le fichier choisi (nom, taille) avec un bouton « Retirer »
    function syncUpload(input) {
        const zone = document.querySelector('[data-upload-for="' + input.id + '"]');
        const chip = zone && zone.nextElementSibling;
        if (!zone || !chip) return;
        const file = input.files && input.files[0];
        zone.hidden = !!file;
        chip.hidden = !file;
        if (!file) return;
        chip.querySelector('[data-file-name]').textContent = file.name;
        chip.querySelector('[data-file-name]').title = file.name;
        chip.querySelector('[data-file-size]').textContent = file.size < 1024 * 1024
            ? Math.max(1, Math.round(file.size / 1024)) + ' Ko'
            : (file.size / 1024 / 1024).toFixed(1).replace('.', ',') + ' Mo';
    }

    function initUploads() {
        document.querySelectorAll('[data-upload-for]').forEach(zone => {
            const input = document.getElementById(zone.dataset.uploadFor);
            if (!input) return;
            zone.addEventListener('click', () => input.click());
            zone.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); }
            });
            input.addEventListener('change', () => syncUpload(input));
            syncUpload(input);
        });
        document.querySelectorAll('[data-upload-clear]').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.uploadClear);
                input.value = '';
                syncUpload(input);
                document.querySelector('[data-upload-for="' + input.id + '"]').focus();
            });
        });
    }

    // État cohérent au chargement et au retour arrière (le navigateur peut restaurer radios et fichiers)
    function syncApplyForm() {
        const checked = document.querySelector('#apply-form input[name="cv_type"]:checked');
        if (checked) toggleNewCv(checked.value === 'new');
        document.querySelectorAll('[data-upload-for]').forEach(zone => {
            const input = document.getElementById(zone.dataset.uploadFor);
            if (input) syncUpload(input);
        });
    }
    window.addEventListener('pageshow', syncApplyForm);

    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('apply-form')) { initUploads(); syncApplyForm(); }

        // Animate cards on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.opacity = '1';
                    e.target.style.transform = 'none';
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.show-anim').forEach(el => {
            el.style.willChange = 'opacity, transform';
            observer.observe(el);
        });
    });

    // Archive/restore confirmation modal
    (function () {
        var modal   = document.getElementById('confirm-modal');
        if (!modal) return;
        var ico     = document.getElementById('modal-ico');
        var title   = document.getElementById('modal-title');
        var msg     = document.getElementById('modal-msg');
        var btnOk   = document.getElementById('modal-ok');
        var btnNo   = document.getElementById('modal-cancel');
        var pending = null;

        // Modération (admin) : valider ou refuser
        var MOD = {
            validate: { title: 'Valider cette offre ?', msg: "L'offre sera publiée et visible des candidats.", ok: 'Valider', color: 'var(--te-success-solid)', bg: 'var(--te-success-soft)', fg: 'var(--te-success-fg)', d: 'M5 13l4 4L19 7' },
            refuse:   { title: 'Refuser cette offre ?', msg: "L'offre ne sera pas publiée. L'entreprise pourra la modifier et la soumettre de nouveau.", ok: 'Refuser', color: 'var(--te-danger-solid)', bg: 'var(--te-danger-soft)', fg: 'var(--te-danger-fg)', d: 'M6 18L18 6M6 6l12 12' },
        };
        function openMod(form, variant) {
            var v = MOD[variant];
            pending = form;
            ico.style.background = v.bg;
            ico.innerHTML = '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + v.fg + '" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="' + v.d + '"/></svg>';
            title.textContent = v.title;
            msg.textContent = v.msg;
            btnOk.textContent = v.ok;
            btnOk.style.background = v.color;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
            btnOk.focus();
        }

        function open(form, variant) {
            pending = form;
            var isArchive = variant === 'archive';
            var color = isArchive ? 'var(--te-danger-fg)' : 'var(--te-primary-fg)';
            var bg    = isArchive ? 'var(--te-danger-soft)' : 'var(--te-primary-soft)';

            ico.style.background = bg;
            ico.innerHTML = isArchive
                ? '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + color + '" stroke-width="2" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>'
                : '<svg width="20" height="20" fill="none" stroke="currentColor" style="color:' + color + '" stroke-width="2" viewBox="0 0 24 24"><polyline stroke-linecap="round" stroke-linejoin="round" points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><polyline stroke-linecap="round" stroke-linejoin="round" points="10 12 12 14 14 12"/><line x1="12" y1="8" x2="12" y2="14"/></svg>';

            title.textContent = isArchive ? 'Archiver cette offre ?' : 'Réactiver cette offre ?';
            msg.textContent   = isArchive
                ? "L'offre sera masquée des résultats de recherche. Vous pourrez la réactiver à tout moment."
                : "L'offre sera de nouveau visible par les candidats.";

            btnOk.textContent      = isArchive ? 'Archiver' : 'Réactiver';
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

        document.querySelectorAll('.js-archive-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'archive'); };
        });
        document.querySelectorAll('.js-restore-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); open(f, 'restore'); };
        });
        document.querySelectorAll('.js-validate-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); openMod(f, 'validate'); };
        });
        document.querySelectorAll('.js-refuse-form').forEach(function (f) {
            f.onsubmit = function (e) { e.preventDefault(); openMod(f, 'refuse'); };
        });
    })();
</script>
@endpush

</x-app-layout>