<x-app-layout>
    <x-slot name="title">Catégories</x-slot>

    <x-slot name="header">
        @php
            $nbCat    = $categories->count();
            $nbOffers = (int) $categories->sum('job_offers_count');
            $plural   = fn (int $n, string $word) => $n . ' ' . $word . ($n > 1 ? 's' : '');
        @endphp
        <x-page-header
            title="Gestion des catégories"
            subtitle="{{ $plural($nbCat, 'catégorie') }} · {{ $plural($nbOffers, 'offre') }} {{ $nbOffers > 1 ? 'associées' : 'associée' }}"
            icon="tag"
        />
    </x-slot>

    {{-- ════════════════  MAIN LAYOUT  ════════════════ --}}
    <div class="cat-layout">

        {{-- ────── Stat cards row ────── --}}
        <div class="cat-stats-row">
            <div class="cat-stat-card cat-stat-indigo">
                <div class="cat-stat-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="cat-stat-value">{{ $categories->count() }}</div>
                    <div class="cat-stat-label">Catégories</div>
                </div>
            </div>
            <div class="cat-stat-card cat-stat-green">
                <div class="cat-stat-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="cat-stat-value">{{ $categories->sum('job_offers_count') }}</div>
                    <div class="cat-stat-label">Total offres</div>
                </div>
            </div>
            <div class="cat-stat-card cat-stat-blue">
                <div class="cat-stat-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="cat-stat-value">{{ number_format((float) $categories->avg('job_offers_count'), 1, ',', "\u{202F}") }}</div>
                    <div class="cat-stat-label">Offres par catégorie (moyenne)</div>
                </div>
            </div>
            
        </div>

        {{-- ────── Two-column grid: Sidebar form + List ────── --}}
        <div class="cat-grid">

            {{-- ══ SIDEBAR: Add Category Form ══ --}}
            <aside class="cat-sidebar">
                <div class="cat-card">
                    <div class="cat-card-header">
                        <div class="cat-card-header-icon cat-icon-indigo">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <h2 class="cat-card-title">Nouvelle catégorie</h2>
                    </div>
                    <div class="cat-card-body">
                        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" id="form-create">
                            @csrf

                            {{-- Category name --}}
                            <div class="cat-field">
                                <label for="name" class="cat-label">
                                    Nom de la catégorie <span class="cat-required">*</span>
                                </label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="cat-input @error('name') cat-input-error @enderror"
                                    placeholder="Ex. : Développement web"
                                    required
                                    value="{{ old('name') }}"
                                />
                                @error('name')
                                    <p class="cat-error-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Image URL --}}
                            <div class="cat-field">
                                <label for="image_url" class="cat-label">URL de l'image</label>
                                <div class="cat-input-icon-wrap">
                                    <svg class="cat-input-icon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    <input
                                        id="image_url"
                                        name="image_url"
                                        type="url"
                                        class="cat-input cat-input-pl @error('image_url') cat-input-error @enderror"
                                        placeholder="https://…"
                                        value="{{ old('image_url') }}"
                                        oninput="previewFromUrl(this,'create-preview')"
                                    />
                                </div>
                                @error('image_url')
                                    <p class="cat-error-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Divider --}}
                            <div class="cat-or-divider"><span>ou importer</span></div>

                            {{-- File upload --}}
                            <div class="cat-field">
                                <label class="cat-label">Fichier image local</label>
                                <label class="cat-file-zone" id="create-file-zone">
                                    <input
                                        name="image_file"
                                        type="file"
                                        accept=".jpg,.jpeg,.png,.gif,.webp"
                                        class="cat-file-hidden"
                                        onchange="handleFileChange(this,'create-preview','create-file-label','create-file-zone')"
                                    />
                                    <div id="create-preview" class="cat-file-preview hidden"></div>
                                    <div class="cat-file-placeholder" id="create-file-placeholder">
                                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Cliquez pour choisir une image</span>
                                        <small>JPG, PNG, GIF, WebP · Max 2 Mo</small>
                                    </div>
                                    <div class="cat-file-chosen hidden" id="create-file-label">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="cat-file-name"></span>
                                    </div>
                                </label>
                                @error('image_file')
                                    <p class="cat-error-msg">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="cat-btn-primary cat-btn-full">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Ajouter la catégorie
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Info tip --}}
                <div class="cat-tip">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Les catégories permettent de classer et filtrer les offres d'emploi publiées sur la plateforme.</span>
                </div>
            </aside>

            {{-- ══ MAIN: Category List ══ --}}
            <main class="cat-main">
                <div class="cat-card">
                    <div class="cat-card-header">
                        <div class="cat-card-header-icon cat-icon-slate">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <h2 class="cat-card-title">
                            Catégories existantes
                            <span class="cat-count-badge">{{ $categories->count() }}</span>
                        </h2>
                        {{-- Search --}}
                        <div class="cat-search-wrap">
                            <svg class="cat-search-icon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                type="text"
                                id="cat-search"
                                aria-label="Rechercher une catégorie"
                                class="cat-search-input"
                                placeholder="Rechercher…"
                                oninput="filterCategories(this.value)"
                            />
                        </div>
                    </div>

                    <div class="cat-card-body cat-list-body">
                        @if($categories->count() > 0)
                            <div id="cat-list">
                                @foreach($categories as $category)
                                    @php
                                        $isLocal = $category->image_url && !str_starts_with($category->image_url, 'http');
                                        $fileName = $isLocal ? basename($category->image_url) : null;
                                    @endphp
                                    <div class="cat-item" data-name="{{ strtolower($category->name) }}">

                                        {{-- Thumbnail --}}
                                        <div class="cat-thumb-wrap">
                                            @if($category->image_url)
                                                <img
                                                    src="{{ $category->thumb_src }}"
                                                    alt="{{ $category->name }}"
                                                    class="cat-thumb"
                                                    width="150" height="150" loading="lazy"
                                                />
                                            @else
                                                <div class="cat-thumb-placeholder">
                                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Edit form body --}}
                                        <div class="cat-item-body">
                                            <form
                                                method="POST"
                                                action="{{ route('admin.categories.update', $category) }}"
                                                enctype="multipart/form-data"
                                                class="cat-edit-form"
                                                id="form-edit-{{ $category->id }}"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <div class="cat-edit-row">
                                                    {{-- Name --}}
                                                    <div class="cat-field cat-field-grow">
                                                        <label class="cat-label" for="cat-name-{{ $category->id }}">Nom</label>
                                                        <input
                                                            type="text"
                                                            id="cat-name-{{ $category->id }}"
                                                            name="name"
                                                            value="{{ $category->name }}"
                                                            class="cat-input"
                                                            required
                                                        />
                                                    </div>

                                                    {{-- Image URL --}}
                                                    <div class="cat-field cat-field-grow">
                                                        <label class="cat-label" for="cat-url-{{ $category->id }}">URL image</label>
                                                        <div class="cat-input-icon-wrap">
                                                            <svg class="cat-input-icon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                            </svg>
                                                            <input
                                                                type="url"
                                                                id="cat-url-{{ $category->id }}"
                                                                name="image_url"
                                                                value="{{ !$isLocal ? $category->image_url : '' }}"
                                                                class="cat-input cat-input-pl"
                                                                placeholder="{{ $isLocal ? 'Image locale' : 'https://…' }}"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- File upload row --}}
                                                <div class="cat-edit-upload-row">
                                                    <div class="cat-field cat-field-grow">
                                                        <label class="cat-label">Remplacer l'image</label>
                                                        <label class="cat-file-inline" id="zone-{{ $category->id }}">
                                                            <input
                                                                type="file"
                                                                name="image_file"
                                                                accept=".jpg,.jpeg,.png,.gif,.webp"
                                                                class="cat-file-hidden"
                                                                onchange="handleInlineFile(this,'label-{{ $category->id }}')"
                                                            />
                                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <span id="label-{{ $category->id }}" class="{{ $isLocal ? 'cat-file-has' : '' }}">
                                                                {{ $isLocal ? $fileName : 'Choisir un fichier…' }}
                                                            </span>
                                                        </label>
                                                    </div>

                                                    {{-- Actions --}}
                                                    <div class="cat-item-actions">
                                                        <span class="cat-offers-badge" title="Offres associées">
                                                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                            </svg>
                                                            {{ $category->job_offers_count }} offre{{ $category->job_offers_count > 1 ? 's' : '' }}
                                                        </span>
                                                        <button type="submit" class="cat-btn-save">
                                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            Enregistrer
                                                        </button>
                                                        <button type="button" class="cat-btn-delete"
                                                                aria-label="Supprimer la catégorie {{ $category->name }}" title="Supprimer"
                                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-offers="{{ $category->job_offers_count }}"
                                                                onclick="openDeleteModal(this)">
                                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" id="delete-form-{{ $category->id }}" hidden>
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            {{-- Empty search state --}}
                            <div id="cat-empty-search" class="cat-empty hidden">
                                <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p>Aucune catégorie ne correspond à votre recherche.</p>
                            </div>

                        @else
                            <div class="cat-empty">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                </svg>
                                <p class="cat-empty-title">Aucune catégorie pour le moment</p>
                                <p class="cat-empty-sub">Créez votre première catégorie via le formulaire à gauche.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- ════════════  DELETE CONFIRMATION MODAL  ════════════ --}}
    <div id="delete-modal" class="cat-modal-backdrop hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="cat-modal" id="delete-modal-box">
            <div class="cat-modal-icon">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="cat-modal-title" id="modal-title">Supprimer la catégorie ?</h3>
            <p class="cat-modal-body" id="modal-msg">Cette action est irréversible.</p>
            <div class="cat-modal-actions">
                <button type="button" class="cat-btn-cancel" id="modal-cancel-btn" onclick="closeDeleteModal()">Annuler</button>
                <button type="button" class="cat-btn-confirm-delete" id="modal-confirm-btn" onclick="confirmDelete()">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Supprimer définitivement
                </button>
            </div>
        </div>
    </div>

{{-- ════════════════  STYLES  ════════════════ --}}
<style>
/* ── Root palette (admin: slate/indigo) ─────────────────── */
:root {
            --cat-radius:      14px;
            --cat-radius-sm:   8px;
            --cat-shadow:      0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --cat-shadow-md:   0 4px 20px rgba(15,23,42,.08);
            --cat-shadow-lg:   0 8px 32px rgba(15,23,42,.12);
            --cat-trans:       .18s cubic-bezier(.4,0,.2,1);
        }
        /* ── Layout ─────────────────────────────────────────────── */
.cat-layout { max-width: 80rem; margin: 0 auto; padding: 2rem 1.5rem; } /* comme « Modération » */
@media (max-width: 640px) { .cat-layout { padding: 1rem; } }

/* ── Page header slot ─────────────────────────────────── */
.cat-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.cat-header-left { display: flex; align-items: center; gap: .75rem; }
.cat-header-icon {
    width: 40px; height: 40px;
    background:var(--te-surface-3);
    border-radius:10px;
    display: flex; align-items: center; justify-content: center;
    color:var(--te-text-2);
    flex-shrink: 0;
}
.cat-page-title { font-size: 1.15rem; font-weight: 700; color:var(--te-text); line-height: 1.2; }
.cat-page-subtitle { font-size: .75rem; color:var(--te-text-muted); margin-top: 2px; }
.cat-header-right { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }
.cat-badge-user {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .75rem; font-weight: 600;
    color:var(--te-text-muted);
    background:var(--te-surface-3);
    border:1px solid var(--te-border);
    padding: .3rem .75rem; border-radius:99px;
}

/* ── Stat cards ───────────────────────────────────────── */
.cat-stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 900px) { .cat-stats-row { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 640px) { .cat-stats-row { gap: .75rem; margin-bottom: 1rem; } .cat-stat-card { padding: .85rem; gap: .6rem; min-width: 0; } }
@media (max-width: 359px) { .cat-stats-row { grid-template-columns: minmax(0, 1fr); } }

.cat-stat-card {
    background:var(--te-surface);
    border:1px solid var(--te-border);
    border-radius:var(--cat-radius);
    padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: .85rem;
    box-shadow:var(--cat-shadow);
    transition: transform var(--cat-trans), box-shadow var(--cat-trans);
}
.cat-stat-card:hover { transform: translateY(-2px); box-shadow:var(--cat-shadow-md); }
.cat-stat-icon {
    width: 40px; height: 40px; border-radius:10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.cat-stat-indigo .cat-stat-icon { background:var(--te-primary-soft); color:var(--te-primary-fg); }
.cat-stat-green  .cat-stat-icon { background:var(--te-success-soft); color:var(--te-success-fg); }
.cat-stat-blue   .cat-stat-icon { background:var(--te-info-soft); color:var(--te-info-fg); }
.cat-stat-purple .cat-stat-icon { background:var(--te-violet-soft); color:var(--te-violet-fg); }
.cat-stat-value { font-size: 1.6rem; font-weight: 800; color:var(--te-text); line-height: 1; }
.cat-stat-label { font-size: .75rem; font-weight: 500; color:var(--te-text-muted); margin-top: 3px; }

/* ── Two-column layout ────────────────────────────────── */
.cat-grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 5rem;
    align-items: start;
}
@media (max-width: 960px) { .cat-grid { grid-template-columns: 1fr; } }

/* ── Sidebar sticky ───────────────────────────────────── */
.cat-sidebar { position: sticky; top: 5rem; }

/* ── Card base ────────────────────────────────────────── */
.cat-card {
    background:var(--te-surface);
    border:1px solid var(--te-border);
    border-radius:var(--cat-radius);
    overflow: hidden;
    box-shadow:var(--cat-shadow);
}
.cat-card-header {
    display: flex; align-items: center; gap: .65rem; flex-wrap: wrap;
    padding: 1rem 1.25rem;
    border-bottom:1px solid var(--te-border);
    background:linear-gradient(135deg,var(--te-surface-2) 0%,var(--te-info-soft) 100%);
}
.cat-card-header-icon {
    width: 30px; height: 30px; border-radius:8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.cat-icon-indigo { background:var(--te-primary-soft); color:var(--te-primary-fg); }
.cat-icon-slate  { background:var(--te-surface-3);   color:var(--te-text-2); }
.cat-card-title  { font-size: .95rem; font-weight: 700; color:var(--te-primary-fg); display: flex; align-items: center; gap: .5rem; }
.cat-count-badge {
    font-size: .75rem; font-weight: 700;
    color:var(--te-primary-fg);
    background:var(--te-primary-soft);
    padding: .15rem .5rem; border-radius:99px;
}
.cat-card-body { padding: 1rem 1.25rem; }
.cat-list-body { padding: .75rem; display: flex; flex-direction: column; gap: 5rem; }

/* ── Form fields ──────────────────────────────────────── */
.cat-field { display: flex; flex-direction: column; gap: .35rem; margin-bottom: 1rem; }
.cat-field:last-of-type { margin-bottom: 0; }
.cat-field-grow { flex: 1; min-width: 0; }
.cat-label {
    font-size: .78rem; font-weight: 600;
    color:var(--te-text-2);
    display: flex; align-items: center; gap: .3rem;
}
.cat-required { color:var(--te-danger-fg); }
.cat-input {
    width: 100%;
    padding: .55rem .85rem;
    font-size: .875rem;
    color:var(--te-text);
    background:var(--te-surface-2);
    border:1px solid var(--te-border-strong);
    border-radius:var(--cat-radius-sm);
    outline:none;
    transition: border-color var(--cat-trans), box-shadow var(--cat-trans), background var(--cat-trans);
}
.cat-input:focus {
    border-color:var(--te-primary);
    background:var(--te-surface);
    box-shadow:0 0 0 3px rgba(79,70,229,.12);
}
.cat-input-error { border-color:var(--te-danger-solid) !important; }
.cat-error-msg { font-size: .75rem; color:var(--te-danger-fg); margin-top: .15rem; }
.cat-input-icon-wrap { position: relative; }
.cat-input-icon {
    position: absolute; left: .7rem; top: 50%; transform: translateY(-50%);
    color:var(--te-text-muted); pointer-events: none;
}
.cat-input-pl { padding-left: 2.1rem; }

/* ── OR divider ───────────────────────────────────────── */
.cat-or-divider {
    display: flex; align-items: center; gap: .75rem;
    margin: .85rem 0;
    font-size: .75rem; font-weight: 600; color:var(--te-text-muted); text-transform: uppercase; letter-spacing: .05em;
}
.cat-or-divider::before, .cat-or-divider::after {
    content: ''; flex: 1; height: 1px; background:var(--te-border);
}

/* ── File upload drop zone (sidebar) ─────────────────── */
.cat-file-zone {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .5rem;
    min-height: 110px;
    border:2px dashed var(--te-border-strong);
    border-radius:var(--cat-radius-sm);
    background:var(--te-surface-2);
    cursor: pointer;
    transition: border-color var(--cat-trans), background var(--cat-trans);
    padding: 1rem;
    position: relative;
    overflow: hidden;
}
.cat-file-zone:hover { border-color:var(--te-primary); background:var(--te-primary-soft); }
.cat-file-hidden {
    position: absolute; inset: 0; width: 100%; height: 100%;
    opacity: 0; cursor: pointer; z-index: 2;
}
.cat-file-placeholder {
    display: flex; flex-direction: column; align-items: center; gap: .3rem;
    color:var(--te-text-muted); font-size: .8rem; text-align: center;
}
.cat-file-placeholder svg { color:var(--te-text-muted); }
.cat-file-placeholder small { font-size: .75rem; color:var(--te-text-muted); }
.cat-file-preview {
    width: 70px; height: 70px; border-radius:10px; object-fit: cover;
    border:2px solid var(--te-primary); z-index: 1; pointer-events: none;
}
.cat-file-preview.hidden { display: none; }
.cat-file-chosen {
    display: flex; align-items: center; gap: .4rem;
    font-size: .78rem; font-weight: 600; color:var(--te-success-fg);
}
.cat-file-chosen.hidden { display: none; }
.cat-file-name { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ── Buttons (matched 1-to-1 to Job Offers page) ─────── */

/* Primary → matches .btn-p from offers page */
.cat-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
    font-size: .78rem; font-weight: 700;
    color:var(--te-on-primary);
    background:var(--te-primary);
    border:none; border-radius:8px;
    padding: .45rem .95rem;
    cursor: pointer;
    white-space: nowrap;
    transition: background var(--cat-trans), transform var(--cat-trans), box-shadow var(--cat-trans);
    margin-top: 1.25rem;
}
.cat-btn-primary:hover { background:var(--te-primary-hover); transform: translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,.3); }
.cat-btn-primary:active { transform: translateY(0); }
.cat-btn-full { width: 100%; justify-content: center; }

/* Save/Edit → same blue as primary (.btn-p) */
.cat-btn-save {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .75rem; font-weight: 700;
    color:var(--te-on-primary);
    background:var(--te-primary);
    border:none; border-radius:8px;
    padding: .38rem .85rem;
    cursor: pointer;
    white-space: nowrap;
    transition: background var(--cat-trans), transform var(--cat-trans), box-shadow var(--cat-trans);
}
.cat-btn-save:hover { background:var(--te-primary-hover); transform: translateY(-1px); box-shadow:0 4px 12px rgba(79,70,229,.3); }

/* Ghost/Secondary → matches .btn-g from offers page */
.cat-btn-g {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .43rem .875rem;
    background:var(--te-surface); color:var(--te-text-muted);
    border:1.5px solid var(--te-border); border-radius:8px;
    font-family: inherit; font-size: .78rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    transition: all var(--cat-trans); white-space: nowrap;
}
.cat-btn-g:hover { border-color:var(--te-primary); color:var(--te-primary-fg); background:var(--te-primary-soft); }

/* Delete icon button → matches .btn-rd (red soft) from offers page */
.cat-btn-delete {
    width: 32px; height: 32px; border-radius:8px; flex-shrink: 0;
    display: inline-flex; align-items: center; justify-content: center;
    color:var(--te-danger-fg);
    background:var(--te-danger-soft); border:1.5px solid var(--te-danger-border);
    cursor: pointer;
    transition: border-color var(--cat-trans);
}
.cat-btn-delete:hover { border-color:var(--te-danger-solid); }
.cat-btn-delete:focus-visible { outline:2px solid var(--te-danger-solid); outline-offset:2px; }

/* ── Search bar ───────────────────────────────────────── */
.cat-search-wrap {
    position: relative; margin-left: auto;
}
.cat-search-icon {
    position: absolute; left: .6rem; top: 50%; transform: translateY(-50%);
    color:var(--te-text-muted); pointer-events: none;
}
.cat-search-input {
    font-size: .8rem; font-weight: 500;
    color:var(--te-text);
    background:var(--te-surface);
    border:1px solid var(--te-border-strong);
    border-radius:8px;
    padding: .4rem .75rem .4rem 2rem;
    width: 200px;
    outline:none;
    transition: border-color var(--cat-trans), box-shadow var(--cat-trans);
}
.cat-search-input:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px var(--te-primary-ring); }
.cat-search-input:focus-visible { outline:none; } /* l'anneau ci-dessus suffit : pas de second contour */

/* ── Category list item ───────────────────────────────── */
.cat-item {
    display: flex; align-items: flex-start; gap: 1rem;
    padding: .9rem 1.25rem;
    border:1px solid var(--te-border);
    border-radius:var(--cat-radius-sm);
    background:var(--te-surface);
    box-shadow:var(--cat-shadow);
    position: relative;
    overflow: hidden;
    transition: box-shadow var(--cat-trans), border-color var(--cat-trans), transform var(--cat-trans);
}
.cat-item::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background:linear-gradient(90deg, var(--te-primary), var(--te-primary-400));
    transform: scaleX(0);
    transition: transform var(--cat-trans);
}
.cat-item:hover { box-shadow:var(--cat-shadow-md); border-color:var(--te-primary-border); transform: translateY(-2px); }
.cat-item:hover::before { transform: scaleX(1); }

/* Thumbnail */
.cat-thumb-wrap { flex-shrink: 0; margin-top: .25rem; }
.cat-thumb {
    width: 150px; height: 150px; border-radius:10px;
    object-fit: cover; border:1.5px solid var(--te-border);
}
.cat-thumb-placeholder {
    width: 150px; height: 150px; border-radius:10px;
    background:linear-gradient(135deg, var(--te-primary-soft), var(--te-primary-soft));
    border:1.5px solid var(--te-primary-border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; font-weight: 800; color:var(--te-primary-fg);
}

/* Edit form inside item */
.cat-item-body { flex: 1; min-width: 0; }
.cat-edit-form { display: flex; flex-direction: column; gap: .6rem; }
.cat-edit-row { display: flex; gap: .75rem; flex-wrap: wrap; }
.cat-edit-upload-row {
    display: flex; align-items: flex-end; gap: .75rem; flex-wrap: wrap;
}
.cat-file-inline {
    display: inline-flex; align-items: center; gap: .45rem;
    font-size: .78rem; font-weight: 500; color:var(--te-text-muted);
    background:var(--te-surface-2); border:1px solid var(--te-border-strong);
    border-radius:7px; padding: .42rem .75rem;
    cursor: pointer; position: relative; overflow: hidden;
    transition: border-color var(--cat-trans), background var(--cat-trans);
    white-space: nowrap; max-width: 240px;
}
.cat-file-inline:hover { border-color:var(--te-primary); background:var(--te-primary-soft); }
.cat-file-inline span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 160px; }
.cat-file-has { color:var(--te-primary-fg) !important; font-weight: 600; }
.cat-item-actions { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
.cat-offers-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .75rem; font-weight: 600; color:var(--te-primary-fg);
    background:var(--te-primary-soft); border:1px solid var(--te-primary-border);
    padding: .3rem .65rem; border-radius:99px; white-space: nowrap;
}

/* ── Empty states ─────────────────────────────────────── */
.cat-empty {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .75rem; padding: 3.5rem 5rem; text-align: center;
    color:var(--te-text-muted);
}
.cat-empty.hidden { display: none; }
.cat-empty-title { font-size: 1rem; font-weight: 700; color:var(--te-text); }
.cat-empty-sub { font-size: .83rem; color:var(--te-text-muted); max-width: 340px; }

/* ── Tip box ──────────────────────────────────────────── */
.cat-tip {
    display: flex; align-items: flex-start; gap: .5rem;
    font-size: .75rem; color:var(--te-text-muted);
    background:var(--te-surface-2); border:1px solid var(--te-border);
    border-radius:var(--cat-radius-sm); padding: .75rem 1rem;
    margin-top: .85rem; line-height: 1.5;
}
.cat-tip svg { flex-shrink: 0; margin-top: 1px; }

/* ── Delete modal ─────────────────────────────────────── */
.cat-modal-backdrop {
    position: fixed; inset: 0; z-index: 9000;
    background:rgba(15,23,42,.45);
    backdrop-filter: blur(3px);
    display: flex; align-items: center; justify-content: center;
    padding: 1rem;
    animation: fadeBackdrop .15s ease;
}
.cat-modal-backdrop.hidden { display: none; }
@keyframes fadeBackdrop { from { opacity: 0; } to { opacity: 1; } }

.cat-modal {
    background:var(--te-surface);
    border-radius:var(--cat-radius);
    box-shadow:var(--cat-shadow-lg);
    padding: 2rem 1.75rem;
    max-width: 420px; width: 100%;
    text-align: center;
    animation: slideModal .2s cubic-bezier(.34,1.56,.64,1);
}
@keyframes slideModal { from { opacity: 0; transform: translateY(12px) scale(.97); } to { opacity: 1; transform: none; } }

.cat-modal-icon {
    width: 56px; height: 56px; border-radius:99px;
    background:var(--te-danger-soft); border:2px solid var(--te-danger-border);
    color:var(--te-danger-fg);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem;
}
.cat-modal-title { font-size: 1.1rem; font-weight: 800; color:var(--te-text); margin-bottom: .5rem; }
.cat-modal-body  { font-size: .85rem; color:var(--te-text-muted); margin-bottom: 5rem; line-height: 1.6; }
.cat-modal-actions { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }

/* Modal Cancel → matches .modal-cancel from offers page */
.cat-btn-cancel {
    padding: .53rem 1.2rem; border-radius:8px;
    border:1.5px solid var(--te-border); background:var(--te-surface);
    font-size: .85rem; font-weight: 600; color:var(--te-text-muted);
    cursor: pointer; transition: all var(--cat-trans); font-family: inherit;
}
.cat-btn-cancel:hover { border-color:var(--te-primary); color:var(--te-primary-fg); }

/* Modal Confirm Delete → matches .btn-rd (red soft) from offers page */
.cat-btn-confirm-delete[hidden] { display:none; }
.cat-btn-confirm-delete {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .38rem .85rem; border-radius:8px; font-size: .75rem; font-weight: 700;
    color:var(--te-danger-fg); background:var(--te-danger-soft); border:1.5px solid var(--te-danger-border);
    cursor: pointer; transition: all var(--cat-trans); white-space: nowrap;
    font-family: inherit;
}
.cat-btn-confirm-delete:hover { background:var(--te-danger-soft); border-color:var(--te-danger-solid); }

/* Close modal on backdrop click */
.cat-modal-backdrop { cursor: default; }
</style>

@push('scripts')
<script>
/* ── Image preview from URL ───────────────── */
function previewFromUrl(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    const url = input.value.trim();
    if (url && preview.tagName === 'IMG') {
        preview.src = url;
        preview.classList.remove('hidden');
    } else if (url && preview.tagName === 'DIV') {
        preview.style.backgroundImage = `url('${url}')`;
    }
}

/* ── Sidebar file drop zone handler ──────── */
function handleFileChange(input, previewId, labelId, zoneId) {
    const file = input.files[0];
    const labelEl = document.getElementById(labelId);
    const placeholder = input.closest('label').querySelector('.cat-file-placeholder');
    const previewEl = document.getElementById(previewId);

    if (!file) return;

    // show filename
    if (labelEl) {
        labelEl.querySelector('.cat-file-name').textContent = file.name;
        labelEl.classList.remove('hidden');
    }
    if (placeholder) placeholder.classList.add('hidden');

    // show image preview
    if (previewEl) {
        const reader = new FileReader();
        reader.onload = e => {
            previewEl.style.backgroundImage = `url('${e.target.result}')`;
            previewEl.style.backgroundSize = 'cover';
            previewEl.style.backgroundPosition = 'center';
            previewEl.style.display = 'block';
            previewEl.style.borderRadius = '10px';
            previewEl.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

/* ── Inline list file handler ─────────────── */
function handleInlineFile(input, labelSpanId) {
    const file = input.files[0];
    const span = document.getElementById(labelSpanId);
    if (span && file) {
        span.textContent = file.name;
        span.classList.add('cat-file-has');
    }
}

/* ── Live search filter ───────────────────── */
function filterCategories(query) {
    const items = document.querySelectorAll('#cat-list .cat-item');
    const empty = document.getElementById('cat-empty-search');
    const q = query.toLowerCase().trim();
    let visible = 0;

    items.forEach(item => {
        const name = item.dataset.name || '';
        const match = name.includes(q);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    if (empty) empty.classList.toggle('hidden', visible > 0 || q === '');
}

/* ── Delete modal ─────────────────────────── */
let pendingDeleteId = null;

let deleteTrigger = null;
function openDeleteModal(btn) {
    deleteTrigger = btn;
    const offers  = parseInt(btn.dataset.offers, 10) || 0;
    const modal   = document.getElementById('delete-modal');
    const title   = document.getElementById('modal-title');
    const msg     = document.getElementById('modal-msg');
    const confirm = document.getElementById('modal-confirm-btn');
    const cancel  = document.getElementById('modal-cancel-btn');
    const strong  = document.createElement('strong');
    strong.textContent = btn.dataset.name;
    msg.textContent = '';
    if (offers > 0) {
        // Catégorie utilisée : suppression refusée (même règle côté serveur)
        pendingDeleteId = null;
        title.textContent = 'Suppression impossible';
        msg.append(strong, ` est utilisée par ${offers} offre${offers > 1 ? 's' : ''}. Changez d'abord la catégorie de ces offres.`);
        confirm.hidden = true;
        cancel.textContent = 'Fermer';
    } else {
        pendingDeleteId = btn.dataset.id;
        title.textContent = 'Supprimer la catégorie ?';
        msg.append('Vous êtes sur le point de supprimer ', strong, '. Cette action est irréversible.');
        confirm.hidden = false;
        cancel.textContent = 'Annuler';
    }
    modal.classList.remove('hidden');
    (confirm.hidden ? cancel : confirm).focus();
    document.addEventListener('keydown', handleModalKeydown);
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    pendingDeleteId = null;
    document.removeEventListener('keydown', handleModalKeydown);
    if (deleteTrigger) { deleteTrigger.focus(); deleteTrigger = null; }
}

function confirmDelete() {
    if (!pendingDeleteId) return;
    document.getElementById(`delete-form-${pendingDeleteId}`).submit();
}

function handleModalKeydown(e) {
    if (e.key === 'Escape') closeDeleteModal();
}

// Close modal on backdrop click
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush

</x-app-layout>
