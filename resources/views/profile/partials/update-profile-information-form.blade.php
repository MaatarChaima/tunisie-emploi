<style>
    :root {
            --r:10px;
            --t:.15s cubic-bezier(.4,0,.2,1);
        }
        .pf-label {
        display:block; font-size:.75rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); margin-bottom:.4rem;
    }
    .pf-input, .pf-select, .pf-textarea {
        display:block; width:100%; padding:.6rem .9rem;
        border:1.5px solid var(--te-border); border-radius:9px;
        font-size:.875rem; font-family:inherit; color:var(--te-text);
        background:var(--te-surface); outline:none;
        transition:border-color var(--t), box-shadow var(--t);
    }
    .pf-input:focus, .pf-select:focus, .pf-textarea:focus {
        border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1);
    }
    .pf-textarea { resize:vertical; min-height:96px; }

    .pf-input-icon { position:relative; }
    .pf-input-icon svg { position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
    .pf-input-icon .pf-input { padding-left:2.25rem; }

    /* File upload zone */
    .pf-file-zone {
        border:2px dashed var(--te-border); border-radius:10px;
        padding:1.1rem 1rem; background:var(--te-surface-2); text-align:center;
        cursor:pointer; transition:border-color var(--t), background var(--t), box-shadow var(--t);
    }
    .pf-file-zone:hover {
        border-color:var(--te-primary); background:var(--te-primary-soft);
        box-shadow:0 0 0 3px rgba(79,70,229,.07);
    }
    .pf-file-label { font-size:.8rem; font-weight:600; color:var(--te-text-muted); cursor:pointer; }
    .pf-file-label span { color:var(--te-primary-fg); }

    /* Current file badge */
    .pf-file-badge {
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.38rem .85rem; border-radius:99px;
        background:var(--te-primary-soft); border:1px solid var(--te-primary-border);
        font-size:.78rem; font-weight:600; color:var(--te-primary-fg);
        text-decoration:none; margin-bottom:.75rem;
        transition:background var(--t), box-shadow var(--t);
    }
    .pf-file-badge:hover { background:var(--te-primary-soft); box-shadow:0 2px 8px rgba(79,70,229,.12); }

    /* Section header */
    .pf-section-header {
        display:flex; align-items:center; gap:.75rem;
        margin:1.75rem 0 1.1rem;
    }
    form > .pf-section-header:first-of-type { margin-top:0; } /* première section : collée au haut du panneau */
    .pf-section-icon {
        width:32px; height:32px; border-radius:9px; flex-shrink:0;
        display:inline-flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg, var(--te-primary), var(--te-primary-400));
        box-shadow:0 2px 8px rgba(79,70,229,.25);
    }
    .pf-section-icon.green { background:linear-gradient(135deg, var(--te-success-solid), var(--te-success-solid)); box-shadow:0 2px 8px rgba(5,150,105,.2); }
    .pf-section-title {
        font-size:.78rem; font-weight:800; text-transform:uppercase;
        letter-spacing:.08em; color:var(--te-text-2);
    }
    .pf-section-line { flex:1; height:1px; background:linear-gradient(90deg, var(--te-border), transparent); }

    /* Save button */
    .pf-save-btn {
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.65rem 1.75rem; border-radius:9px;
        background:linear-gradient(135deg, var(--te-primary), var(--te-primary-400));
        color:var(--te-on-primary); border:none; font-size:.875rem; font-weight:700;
        font-family:inherit; cursor:pointer;
        transition:opacity var(--t), box-shadow var(--t), transform var(--t);
        box-shadow:0 4px 14px rgba(79,70,229,.3);
    }
    .pf-save-btn:hover {
        opacity:.92; box-shadow:0 6px 20px rgba(79,70,229,.38);
        transform:translateY(-1px);
    }
    .pf-save-btn:active { transform:translateY(0); }

    /* Save footer */
    .pf-save-footer {
        display:flex; align-items:center; gap:1rem;
        margin-top:2rem; padding-top:1.5rem;
        border-top:1px solid var(--te-border-subtle);
    }

    /* Saved flash */
    .pf-saved-flash {
        display:inline-flex; align-items:center; gap:.4rem;
        font-size:.8rem; font-weight:600; color:var(--te-success-fg);
        background:var(--te-success-soft); border:1px solid var(--te-success-border);
        padding:.38rem .85rem; border-radius:99px;
    }

    /* Hint text */
    .pf-hint { font-size:.75rem; color:var(--te-text-muted); margin-top:.3rem; }

    /* Grid */
    .pf-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    @media(max-width:600px) { .pf-grid { grid-template-columns:1fr; } }
    .pf-full { grid-column:1/-1; }

    /* Logo preview */
    .pf-logo-preview { display:flex; align-items:center; gap:.75rem; margin-bottom:.875rem; }
    .pf-logo-preview img {
        width:60px; height:60px; border-radius:12px; object-fit:cover;
        border:1.5px solid var(--te-border); box-shadow:0 2px 8px rgba(0,0,0,.09);
    }
    .pf-logo-preview-info { }
    .pf-logo-preview-label { font-size:.78rem; font-weight:700; color:var(--te-text-2); }
    .pf-logo-preview-hint { font-size:.75rem; color:var(--te-text-muted); margin-top:.15rem; }

    /* Verification warning */
    .pf-verify-warn {
        display:flex; align-items:flex-start; gap:.65rem;
        padding:.7rem .9rem; border-radius:9px;
        background:var(--te-warning-soft); border:1px solid var(--te-warning-border);
        font-size:.8rem; color:var(--te-warning-fg); margin-top:.5rem; line-height:1.5;
    }
    .pf-verify-warn button {
        font-weight:700; color:var(--te-warning-fg); text-decoration:underline;
        background:none; border:none; cursor:pointer; font-family:inherit; font-size:.8rem;
    }
    .pf-verify-ok {
        display:inline-flex; align-items:center; gap:.35rem;
        font-size:.8rem; font-weight:600; color:var(--te-success-fg);
        background:var(--te-success-soft); border:1px solid var(--te-success-border);
        padding:.35rem .75rem; border-radius:99px; margin-top:.5rem;
    }
</style>

<section>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- ── Infos communes ──────────────────────── --}}
        <div class="pf-section-header">
            <span class="pf-section-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-on-primary)">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
            <span class="pf-section-title">Compte</span>
            <span class="pf-section-line"></span>
        </div>

        <div class="pf-grid">
            <div class="pf-full">
                <label class="pf-label" for="name">Nom et Prénom</label>
                <div class="pf-input-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input id="name" name="name" type="text" class="pf-input"
                           value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
            </div>

            <div class="pf-full">
                <label class="pf-label" for="email">Adresse e-mail</label>
                <div class="pf-input-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input id="email" name="email" type="email" class="pf-input"
                           value="{{ old('email', $user->email) }}" required autocomplete="username">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')"/>

                @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="pf-verify-warn">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>
                            Votre e-mail n'est pas vérifié.
                            <button form="send-verification">Renvoyer l'e-mail de vérification.</button>
                        </span>
                    </div>
                    @if(session('status') === 'verification-link-sent')
                        <p class="pf-verify-ok"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Lien envoyé à votre adresse e-mail.</p>
                    @endif
                @endif
            </div>

            @if(!$user->isAdmin())
            <div>
                <label class="pf-label" for="phone">Téléphone</label>
                <div class="pf-input-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <input id="phone" name="phone" type="text" class="pf-input"
                           value="{{ old('phone', $user->phone) }}">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('phone')"/>
            </div>
            @endif
        </div>

        {{-- ── Biographie / Description : affichée sur la fiche candidat et dans « À propos de l'entreprise ».
             Pas pour l'administrateur (son profil n'est visible nulle part) ; la colonne reste en base. --}}
        @unless($user->isAdmin())
        <div style="margin-top:1rem;">
            <label class="pf-label" for="bio">
                {{ $user->isCompany() ? "Description de l'entreprise" : "Biographie" }}
            </label>
            <textarea id="bio" name="bio" class="pf-textarea" rows="3">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')"/>
        </div>
        @endunless

        {{-- ── Candidat ──────────────────────────────── --}}
        @if($user->isCandidate())
            <div class="pf-section-header">
                <span class="pf-section-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-on-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </span>
                <span class="pf-section-title">Profil candidat</span>
                <span class="pf-section-line"></span>
            </div>

            <div class="pf-grid">
                <div>
                    <label class="pf-label" for="birth_date">Date de naissance</label>
                    <input id="birth_date" name="birth_date" type="date" class="pf-input"
                           value="{{ old('birth_date', $user->birth_date) }}" required>
                    <x-input-error class="mt-2" :messages="$errors->get('birth_date')"/>
                </div>

                <div>
                    <label class="pf-label" for="city">Ville</label>
                    <input id="city" name="city" type="text" class="pf-input"
                           value="{{ old('city', $user->city) }}" required placeholder="Tunis, Sfax...">
                    <x-input-error class="mt-2" :messages="$errors->get('city')"/>
                </div>

                <div>
                    <label class="pf-label" for="domain">Domaine</label>
                    <input id="domain" name="domain" type="text" class="pf-input"
                           value="{{ old('domain', $user->domain) }}" required placeholder="Informatique, Marketing...">
                    <x-input-error class="mt-2" :messages="$errors->get('domain')"/>
                </div>

                <div>
                    <label class="pf-label" for="education_level">Niveau d'études</label>
                    <select id="education_level" name="education_level" class="pf-input" required>
                        <option value="">Sélectionner un niveau</option>
                        @foreach(\App\Support\Education::LEVELS as $lvl => $lvlLabel)
                            <option value="{{ $lvl }}" {{ old('education_level', $user->education_level) == $lvl ? 'selected' : '' }}>{{ $lvlLabel }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('education_level')"/>
                </div>

                <div>
                    <label class="pf-label" for="experience_years">Années d'expérience</label>
                    <input id="experience_years" name="experience_years" type="number" class="pf-input"
                           value="{{ old('experience_years', $user->experience_years) }}" required min="0" placeholder="0">
                    <x-input-error class="mt-2" :messages="$errors->get('experience_years')"/>
                </div>

                <div>
                    <label class="pf-label" for="linkedin_url">Lien LinkedIn</label>
                    <div class="pf-input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <input id="linkedin_url" name="linkedin_url" type="url" class="pf-input"
                               value="{{ old('linkedin_url', $user->linkedin_url) }}" placeholder="https://linkedin.com/in/...">
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')"/>
                </div>
            </div>

            {{-- CV upload --}}
            <div style="margin-top:1rem;">
                <label class="pf-label">CV (PDF, DOC, DOCX)</label>
                @if($user->cv_path)
                    <a href="{{ route('cv.show', $user->id) }}" target="_blank" class="pf-file-badge">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Voir mon CV actuel
                    </a>
                @endif
                
                <div id="pf-cv-zone" class="pf-file-zone" onclick="document.getElementById('cv').click()" style="cursor:pointer;">
                    <svg id="pf-cv-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-text-muted);margin:0 auto .4rem; display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <p class="pf-file-label" id="pf-cv-label"><span>Choisir un fichier</span> ou glisser ici</p>
                    <p style="font-size:.75rem; color:var(--te-text-muted); margin-top:.25rem;" id="pf-cv-hint">PDF, DOC, DOCX · Max 5 Mo</p>
                </div>
            
                <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" style="display:none;"
                       onchange="pfShowFile('cv','pf-cv-zone','pf-cv-label','pf-cv-hint','pf-cv-icon')">
            
                <x-input-error class="mt-2" :messages="$errors->get('cv')"/>
            </div>
        @endif

        {{-- ── Entreprise ──────────────────────────── --}}
        @if($user->isCompany())
            <div class="pf-section-header">
                <span class="pf-section-icon green">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-on-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <span class="pf-section-title">Informations entreprise</span>
                <span class="pf-section-line"></span>
            </div>

            <div class="pf-grid">
                <div>
                    <label class="pf-label" for="sector">Secteur d'activité</label>
                    <input id="sector" name="sector" type="text" class="pf-input"
                           value="{{ old('sector', $user->sector) }}" required>
                    <x-input-error class="mt-2" :messages="$errors->get('sector')"/>
                </div>

                <div>
                    <label class="pf-label" for="company_size">Taille de l'entreprise</label>
                    <input id="company_size" name="company_size" type="text" class="pf-input"
                           value="{{ old('company_size', $user->company_size) }}" required placeholder="10-50 employés...">
                    <x-input-error class="mt-2" :messages="$errors->get('company_size')"/>
                </div>

                <div>
                    <label class="pf-label" for="city">Ville</label>
                    <input id="city" name="city" type="text" class="pf-input"
                           value="{{ old('city', $user->city) }}" required>
                    <x-input-error class="mt-2" :messages="$errors->get('city')"/>
                </div>

                <div>
                    <label class="pf-label" for="address">Adresse</label>
                    <input id="address" name="address" type="text" class="pf-input"
                           value="{{ old('address', $user->address) }}" required>
                    <x-input-error class="mt-2" :messages="$errors->get('address')"/>
                </div>

                <div>
                    <label class="pf-label" for="tax_id">Matricule fiscal</label>
                    <input id="tax_id" name="tax_id" type="text" class="pf-input"
                           value="{{ old('tax_id', $user->tax_id) }}" required>
                    <x-input-error class="mt-2" :messages="$errors->get('tax_id')"/>
                </div>

                <div>
                    <label class="pf-label" for="website">Site web</label>
                    <div class="pf-input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <input id="website" name="website" type="text" class="pf-input"
                               value="{{ old('website', $user->website) }}" placeholder="https://...">
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('website')"/>
                </div>
            </div>

            {{-- Logo upload --}}
            <div style="margin-top:1rem;">
                <label class="pf-label">Logo de l'entreprise</label>
                <div id="pf-logo-zone" class="pf-file-zone" onclick="document.getElementById('logo').click()" style="cursor:pointer;">
                    <svg id="pf-logo-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--te-text-muted);margin:0 auto .4rem; display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <p class="pf-file-label" id="pf-logo-label"><span>Choisir un logo</span> ou glisser ici</p>
                    <p style="font-size:.75rem; color:var(--te-text-muted); margin-top:.25rem;" id="pf-logo-hint">PNG, JPG, GIF · Max 2 Mo</p>
                </div>
                <input id="logo" name="logo" type="file" accept=".png,.jpg,.jpeg,.gif" style="display:none;"
                       onchange="pfShowFile('logo','pf-logo-zone','pf-logo-label','pf-logo-hint','pf-logo-icon')">
                <x-input-error class="mt-2" :messages="$errors->get('logo')"/>
            </div>
        @endif

        {{-- ── Save ─────────────────────────────────── --}}
        <div class="pf-save-footer" id="pf-save-footer">
            <button type="submit" class="pf-save-btn">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Sauvegarder les modifications
            </button>

            @if(session('status') === 'profile-updated')
                <span class="pf-saved-flash"
                      x-data="{show:true}" x-show="show" x-transition
                      x-init="setTimeout(()=>show=false,3000)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Profil mis à jour !
                </span>
            @endif
        </div>
    </form>

<script>
function pfShowFile(inputId, zoneId, labelId, hintId, iconId) {
    const input = document.getElementById(inputId);
    const zone  = document.getElementById(zoneId);
    const label = document.getElementById(labelId);
    const hint  = document.getElementById(hintId);
    const icon  = document.getElementById(iconId);
    const file  = input.files[0];
    if (!file) return;

    // Checkmark icon
    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
    icon.setAttribute('stroke', 'currentColor'); icon.style.color = 'var(--te-success-fg)';

    // Show name + size
    const name = file.name.length > 32 ? file.name.substring(0, 29) + '...' : file.name;
    const size = (file.size / 1024).toFixed(0) + ' Ko';
    label.innerHTML = '<strong style="color:var(--te-text)">' + name + '</strong>';
    hint.textContent = size + ' · Cliquez pour changer';
    hint.style.color = 'var(--te-success-fg)';

    // Green border
    zone.style.borderColor = 'var(--te-success-solid)';
    zone.style.background  = 'var(--te-success-soft)';
}

document.addEventListener('DOMContentLoaded', function () {
    [
        { zoneId: 'pf-cv-zone',   inputId: 'cv',   labelId: 'pf-cv-label',   hintId: 'pf-cv-hint',   iconId: 'pf-cv-icon' },
        { zoneId: 'pf-logo-zone', inputId: 'logo', labelId: 'pf-logo-label', hintId: 'pf-logo-hint', iconId: 'pf-logo-icon' },
    ].forEach(function(cfg) {
        var zone  = document.getElementById(cfg.zoneId);
        var input = document.getElementById(cfg.inputId);
        if (!zone || !input) return;

        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            zone.style.borderColor = 'var(--te-primary)';
            zone.style.background  = 'var(--te-primary-soft)';
        });
        zone.addEventListener('dragleave', function() {
            zone.style.borderColor = '';
            zone.style.background  = '';
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            if (e.dataTransfer.files.length) {
                var dt = new DataTransfer();
                dt.items.add(e.dataTransfer.files[0]);
                input.files = dt.files;
                pfShowFile(cfg.inputId, cfg.zoneId, cfg.labelId, cfg.hintId, cfg.iconId);
            }
            zone.style.borderColor = '';
            zone.style.background  = '';
        });
    });
});
</script>
<script>
(function () {
    var logoInput = document.getElementById('logo');
    if (!logoInput) return;
    logoInput.addEventListener('change', function () {
        if (!this.files || !this.files.length) return;
        var footer = document.getElementById('pf-save-footer');
        if (footer) footer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
})();
</script>
</section>
