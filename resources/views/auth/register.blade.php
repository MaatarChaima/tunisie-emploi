<x-guest-layout>
    <x-slot name="title">Inscription</x-slot>

<style>
/* ── Register card ────────────────────────────────────────── */
.rg-heading  { font-size:1.4rem; font-weight:800; color:var(--text-main); letter-spacing:-.025em; margin:0 0 .3rem; }
.rg-sub      { font-size:.825rem; color:var(--text-muted); margin:0 0 1.5rem; }
.rg-label    { display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted); margin-bottom:.4rem; }
.rg-field    { margin-bottom:1rem; }

/* Input wrapper */
.rg-wrap     { position:relative; }
.rg-icon     { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
.rg-input    {
    display:block; width:100%;
    padding:.7rem 1rem .7rem 2.6rem;
    border:1.5px solid var(--border); border-radius:10px;
    font-size:.875rem; font-family:inherit;
    color:var(--text-main); background:var(--te-surface);
    outline:none; box-sizing:border-box;
    transition:border-color var(--transition), box-shadow var(--transition);
}
.rg-input.no-icon { padding-left:1rem; }
.rg-input:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.12); }
.rg-input::placeholder { color:var(--te-text-muted); }
.rg-select-wrap { position:relative; }
.rg-select-wrap::after { content:''; position:absolute; right:.75rem; top:50%; transform:translateY(-50%); width:11px; height:11px; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-size:contain; background-repeat:no-repeat; pointer-events:none; }
.rg-select   {
    display:block; width:100%;
    padding:.7rem 2.2rem .7rem 1rem; border:1.5px solid var(--border); border-radius:10px;
    font-size:.875rem; font-family:inherit;
    color:var(--text-main); background:var(--te-surface);
    outline:none; box-sizing:border-box; appearance:none;
    transition:border-color var(--transition), box-shadow var(--transition);
    cursor:pointer;
}
.rg-select:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.12); }

/* Password toggle */
.rg-toggle   {
    position:absolute; right:.8rem; top:50%; transform:translateY(-50%);
    background:none; border:none; cursor:pointer; color:var(--te-text-muted);
    padding:0; line-height:0; transition:color var(--transition);
}
.rg-toggle:hover { color:var(--te-primary-fg); }

/* Password strength bars */
.rg-strength     { display:flex; gap:.3rem; margin-top:.5rem; }
.rg-strength-bar { flex:1; height:3px; border-radius:99px; background:var(--te-border); transition:background .3s; }

/* ── Role tabs ────────────────────────────────────────────── */
.rg-roles    { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:1.5rem; }
.rg-role     {
    display:flex; flex-direction:column; align-items:center; gap:.45rem;
    padding:.9rem .75rem; border-radius:12px; border:1.5px solid var(--border);
    cursor:pointer; transition:all var(--transition); background:var(--te-surface); text-align:center;
}
.rg-role:has(input:checked) { border-color:var(--te-primary); background:var(--te-primary-soft); }
.rg-role input { display:none; }
.rg-role-ico {
    width:36px; height:36px; border-radius:9px;
    background:var(--te-surface-3); display:flex; align-items:center; justify-content:center;
    color:var(--te-text-muted); transition:all var(--transition);
}
.rg-role:has(input:checked) .rg-role-ico { background:var(--te-primary); color:var(--te-on-primary); }
.rg-role-lbl  { font-size:.8rem; font-weight:700; color:var(--text-muted); transition:color var(--transition); }
.rg-role:has(input:checked) .rg-role-lbl { color:var(--te-primary-fg); }

/* ── Section separator ────────────────────────────────────── */
.rg-section  {
    display:flex; align-items:center; gap:.55rem;
    font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
    color:var(--text-muted); margin:1.5rem 0 1.125rem;
}
.rg-section::after { content:''; flex:1; height:1px; background:var(--border); }
.rg-section-num {
    display:inline-flex; align-items:center; justify-content:center;
    width:20px; height:20px; border-radius:6px;
    background:var(--te-primary); color:var(--te-on-primary);
    font-size:.75rem; font-weight:900; flex-shrink:0;
}

/* ── Field grid ───────────────────────────────────────────── */
.rg-grid    { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
@media (max-width:560px) { .rg-grid { grid-template-columns:1fr; } }
.rg-span-2  { grid-column:1/-1; }

/* ── File upload zone ─────────────────────────────────────── */
.rg-file-zone {
    border:2px dashed var(--border); border-radius:10px;
    padding:.875rem 1rem; background:var(--te-surface-2);
    text-align:center; cursor:pointer;
    transition:all var(--transition);
}
.rg-file-zone:hover { border-color:var(--te-primary); background:var(--te-primary-soft); }
.rg-file-lbl  { font-size:.8rem; font-weight:600; color:var(--text-muted); }
.rg-file-lbl span { color:var(--te-primary-fg); }
.rg-file-hint { font-size:.75rem; color:var(--te-text-muted); margin-top:.25rem; }

/* ── Submit ───────────────────────────────────────────────── */
.rg-submit   {
    display:flex; align-items:center; justify-content:center; gap:.5rem;
    width:100%; padding:.8rem;
    border-radius:10px;
    background:linear-gradient(135deg, var(--te-primary) 0%, var(--te-primary-400) 100%);
    color:var(--te-on-primary); border:none;
    font-size:.925rem; font-weight:700; font-family:inherit;
    cursor:pointer;
    box-shadow:0 4px 16px rgba(79,70,229,.32);
    transition:opacity var(--transition), transform var(--transition), box-shadow var(--transition);
    margin-top:1.5rem;
}
.rg-submit:hover {
    opacity:.92;
    transform:translateY(-1px);
    box-shadow:0 6px 24px rgba(79,70,229,.38);
}
.rg-submit:active { transform:translateY(0); }

/* Link */
.rg-link { font-size:.8rem; font-weight:600; color:var(--te-primary-fg); text-decoration:none; transition:color var(--transition); }
.rg-link:hover { color:var(--te-primary-fg); text-decoration:underline; }
</style>

<div class="auth-card" style="max-width:680px;">

    <h2 class="rg-heading">Créer un compte</h2>
    <p class="rg-sub">Rejoignez des milliers de professionnels sur TunisieEmploi</p>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        {{-- ── Role selection ──────────────────────────────────── --}}
        <label class="rg-label">Type de compte</label>
        <div class="rg-roles">
            <label class="rg-role">
                <input type="radio" name="role" value="candidate"
                       {{ old('role','candidate') == 'candidate' ? 'checked' : '' }}
                       onchange="rgToggleFields('candidate')">
                <span class="rg-role-ico">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>
                <span class="rg-role-lbl">Candidat</span>
            </label>
            <label class="rg-role">
                <input type="radio" name="role" value="company"
                       {{ old('role') == 'company' ? 'checked' : '' }}
                       onchange="rgToggleFields('company')">
                <span class="rg-role-ico">
                    <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </span>
                <span class="rg-role-lbl">Entreprise</span>
            </label>
        </div>
        <x-input-error :messages="$errors->get('role')" class="mt-2"/>

        {{-- ── Section 1 — Account info ──────────────────────────── --}}
        <div class="rg-section">
            <span class="rg-section-num">1</span> Informations du compte
        </div>

        {{-- Hidden name field, populated by JS --}}
        <input type="hidden" name="name" id="name-combined" value="{{ old('name') }}">

        <div class="rg-grid">
            {{-- Candidate: two name fields --}}
            <div id="name-candidate-wrap" class="rg-field" style="{{ old('role','candidate') != 'candidate' ? 'display:none;' : '' }}">
                <label class="rg-label" for="first_name">Prénom <span style="color:var(--te-danger-fg)">*</span></label>
                <div class="rg-wrap">
                    <span class="rg-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input id="first_name" name="first_name" type="text" class="rg-input" autofocus
                           value="{{ old('first_name') }}" placeholder="Votre prénom"
                           oninput="rgCombineName()">
                </div>
            </div>
            <div id="name-candidate-wrap2" class="rg-field" style="{{ old('role','candidate') != 'candidate' ? 'display:none;' : '' }}">
                <label class="rg-label" for="last_name">Nom <span style="color:var(--te-danger-fg)">*</span></label>
                <div class="rg-wrap">
                    <span class="rg-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input id="last_name" name="last_name" type="text" class="rg-input"
                           value="{{ old('last_name') }}" placeholder="Votre nom"
                           oninput="rgCombineName()">
                </div>
            </div>
            {{-- Company: single name field --}}
            <div id="name-company-wrap" class="rg-field rg-span-2" style="{{ old('role','candidate') == 'candidate' ? 'display:none;' : '' }}">
                <label class="rg-label" for="company_name_input">Nom de l'entreprise <span style="color:var(--te-danger-fg)">*</span></label>
                <div class="rg-wrap">
                    <span class="rg-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </span>
                    <input id="company_name_input" type="text" class="rg-input"
                           value="{{ old('name') }}" placeholder="Nom de votre entreprise"
                           oninput="document.getElementById('name-combined').value=this.value">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            {{-- Email --}}
            <div class="rg-field rg-span-2">
                <label class="rg-label" for="email">Adresse e-mail <span style="color:var(--te-danger-fg)">*</span></label>
                <div class="rg-wrap">
                    <span class="rg-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input id="email" name="email" type="email" class="rg-input"
                           value="{{ old('email') }}" required placeholder="votre.adresse@exemple.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            {{-- Password --}}
            <div class="rg-field">
                <label class="rg-label" for="password">Mot de passe <span style="color:var(--te-danger-fg)">*</span></label>
                <div class="rg-wrap">
                    <span class="rg-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input id="password" name="password" type="password" class="rg-input"
                           required autocomplete="new-password" placeholder="8 caractères minimum"
                           oninput="rgStrengthCheck(this.value)">
                    <button type="button" class="rg-toggle" onclick="rgTogglePw('password',this)" aria-label="Afficher">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <div class="rg-strength">
                    <div class="rg-strength-bar" id="sb1"></div>
                    <div class="rg-strength-bar" id="sb2"></div>
                    <div class="rg-strength-bar" id="sb3"></div>
                    <div class="rg-strength-bar" id="sb4"></div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            {{-- Confirm password --}}
            <div class="rg-field">
                <label class="rg-label" for="password_confirmation">Confirmer le mot de passe <span style="color:var(--te-danger-fg)">*</span></label>
                <div class="rg-wrap">
                    <span class="rg-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="rg-input"
                           required autocomplete="new-password" placeholder="Retapez le mot de passe">
                    <button type="button" class="rg-toggle" onclick="rgTogglePw('password_confirmation',this)" aria-label="Afficher">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
            </div>
        </div>

        {{-- ── CANDIDATE FIELDS ─────────────────────────────────── --}}
        <div id="candidate-fields" style="{{ old('role','candidate') == 'candidate' ? '' : 'display:none' }}">
            <div class="rg-section">
                <span class="rg-section-num">2</span> Profil candidat
            </div>
            <div class="rg-grid">
                <div class="rg-field">
                    <label class="rg-label" for="birth_date">Date de naissance <span style="color:var(--te-danger-fg)">*</span></label>
                    <input id="birth_date" name="birth_date" type="date" class="rg-input no-icon"
                           value="{{ old('birth_date') }}">
                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="city_candidate">Ville <span style="color:var(--te-danger-fg)">*</span></label>
                    <div class="rg-select-wrap">
                        <select id="city_candidate" name="city" class="rg-select">
                            <option value="">Sélectionner</option>
                            @foreach(['Tunis','Ariana','Ben Arous','Manouba','Nabeul','Sousse','Monastir','Mahdia','Sfax','Bizerte','Kairouan','Gafsa','Gabès','Djerba'] as $c)
                                <option value="{{ $c }}" {{ old('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('city')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="domain">Domaine d'activité <span style="color:var(--te-danger-fg)">*</span></label>
                    <input id="domain" name="domain" type="text" class="rg-input no-icon"
                           value="{{ old('domain') }}" placeholder="Informatique, Marketing…">
                    <x-input-error :messages="$errors->get('domain')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="education_level">Niveau d'études <span style="color:var(--te-danger-fg)">*</span></label>
                    <div class="rg-select-wrap">
                        <select id="education_level" name="education_level" class="rg-select">
                            <option value="">Sélectionner</option>
                            @foreach(\App\Support\Education::LEVELS as $lvl => $lvlLabel)
                                <option value="{{ $lvl }}" {{ old('education_level') == $lvl ? 'selected' : '' }}>{{ $lvlLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('education_level')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="experience_years">Années d'expérience <span style="color:var(--te-danger-fg)">*</span></label>
                    <input id="experience_years" name="experience_years" type="number" class="rg-input no-icon"
                           value="{{ old('experience_years') }}" placeholder="0" min="0">
                    <x-input-error :messages="$errors->get('experience_years')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="phone_candidate">Téléphone</label>
                    <div class="rg-wrap">
                        <span class="rg-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <input id="phone_candidate" name="phone" type="text" class="rg-input"
                               value="{{ old('role') == 'candidate' ? old('phone') : '' }}" placeholder="+216…">
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
                </div>

                <div class="rg-field rg-span-2">
                    <label class="rg-label" for="linkedin_url">LinkedIn</label>
                    <div class="rg-wrap">
                        <span class="rg-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </span>
                        <input id="linkedin_url" name="linkedin_url" type="url" class="rg-input"
                               value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/…">
                    </div>
                    <x-input-error :messages="$errors->get('linkedin_url')" class="mt-2"/>
                </div>

                <div class="rg-field rg-span-2">
                    <label class="rg-label">CV <span style="color:var(--te-danger-fg)">*</span> <span style="font-weight:400;text-transform:none;letter-spacing:0;">(PDF, DOC, DOCX)</span></label>
                    <div id="cv-zone" class="rg-file-zone" onclick="document.getElementById('cv').click()">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             style="color:var(--te-text-muted);margin:0 auto .4rem;display:block;" id="cv-icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <p class="rg-file-lbl" id="cv-label"><span>Choisir un fichier</span> ou glisser ici</p>
                        <p class="rg-file-hint" id="cv-hint">PDF, DOC, DOCX · Max 5 Mo</p>
                    </div>
                    <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" style="display:none;"
                           onchange="rgShowFile('cv','cv-label','cv-hint','cv-icon','cv-zone')">
                    <x-input-error :messages="$errors->get('cv')" class="mt-2"/>
                </div>
            </div>
        </div>

        {{-- ── COMPANY FIELDS ───────────────────────────────────── --}}
        <div id="company-fields" style="{{ old('role') == 'company' ? '' : 'display:none' }}">
            <div class="rg-section">
                <span class="rg-section-num">2</span> Informations entreprise
            </div>
            <div class="rg-grid">
                <div class="rg-field">
                    <label class="rg-label" for="sector">Secteur d'activité <span style="color:var(--te-danger-fg)">*</span></label>
                    <input id="sector" name="sector" type="text" class="rg-input no-icon"
                           value="{{ old('sector') }}" placeholder="IT, Finance…">
                    <x-input-error :messages="$errors->get('sector')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="company_size">Taille de l'entreprise <span style="color:var(--te-danger-fg)">*</span></label>
                    <input id="company_size" name="company_size" type="text" class="rg-input no-icon"
                           value="{{ old('company_size') }}" placeholder="10-50 employés">
                    <x-input-error :messages="$errors->get('company_size')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="city_company">Ville <span style="color:var(--te-danger-fg)">*</span></label>
                    <div class="rg-select-wrap">
                        <select id="city_company" name="city" class="rg-select">
                            <option value="">Sélectionner</option>
                            @foreach(['Tunis','Ariana','Ben Arous','Manouba','Nabeul','Sousse','Monastir','Mahdia','Sfax','Bizerte','Kairouan','Gafsa','Gabès','Djerba'] as $c)
                                <option value="{{ $c }}" {{ old('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('city')" class="mt-2"/>
                </div>

                <div class="rg-field">
                    <label class="rg-label" for="phone_company">Téléphone <span style="color:var(--te-danger-fg)">*</span></label>
                    <div class="rg-wrap">
                        <span class="rg-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <input id="phone_company" name="phone" type="text" class="rg-input"
                               value="{{ old('role') == 'company' ? old('phone') : '' }}" placeholder="+216…">
                    </div>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
                </div>

                <div class="rg-field rg-span-2">
                    <label class="rg-label" for="address">Adresse <span style="color:var(--te-danger-fg)">*</span></label>
                    <input id="address" name="address" type="text" class="rg-input no-icon"
                           value="{{ old('address') }}" placeholder="Rue, numéro…">
                    <x-input-error :messages="$errors->get('address')" class="mt-2"/>
                </div>

                <div class="rg-field rg-span-2">
                    <label class="rg-label" for="tax_id">Matricule fiscal <span style="color:var(--te-danger-fg)">*</span></label>
                    <input id="tax_id" name="tax_id" type="text" class="rg-input no-icon"
                           value="{{ old('tax_id') }}" placeholder="Ex. : 1234567ABC">
                    <x-input-error :messages="$errors->get('tax_id')" class="mt-2"/>
                </div>

                <div class="rg-field rg-span-2">
                    <label class="rg-label" for="website">Site web</label>
                    <div class="rg-wrap">
                        <span class="rg-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </span>
                        <input id="website" name="website" type="url" class="rg-input"
                               value="{{ old('website') }}" placeholder="https://www.monentreprise.com">
                    </div>
                    <x-input-error :messages="$errors->get('website')" class="mt-2"/>
                </div>

                <div class="rg-field rg-span-2">
                    <label class="rg-label" for="bio">Description de l'entreprise</label>
                    <textarea id="bio" name="bio" class="rg-input no-icon" rows="4"
                              placeholder="Décrivez votre entreprise, vos activités, vos valeurs…"
                              style="height:auto;resize:vertical;">{{ old('bio') }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" class="mt-2"/>
                </div>

                <div class="rg-field rg-span-2">
                    <label class="rg-label">Logo de l'entreprise <span style="color:var(--te-danger-fg)">*</span></label>
                    <div id="logo-zone" class="rg-file-zone" onclick="document.getElementById('logo').click()">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             style="color:var(--te-text-muted);margin:0 auto .4rem;display:block;" id="logo-icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <p class="rg-file-lbl" id="logo-label"><span>Choisir un logo</span> ou glisser ici</p>
                        <p class="rg-file-hint" id="logo-hint">PNG, JPG, GIF · Max 2 Mo</p>
                    </div>
                    <input id="logo" name="logo" type="file" accept=".png,.jpg,.jpeg,.gif" style="display:none;"
                           onchange="rgShowFile('logo','logo-label','logo-hint','logo-icon','logo-zone')">
                    <x-input-error :messages="$errors->get('logo')" class="mt-2"/>
                </div>
            </div>
        </div>

        <button type="submit" class="rg-submit">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Créer mon compte
        </button>

        <p style="text-align:center;font-size:.825rem;color:var(--text-muted);margin-top:1.125rem;">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="rg-link" style="margin-left:.3rem;">Se connecter</a>
        </p>

    </form>
</div>

<script>
/* ── Name combination ────────────────────────────────────── */
function rgCombineName() {
    const first    = document.getElementById('first_name')?.value || '';
    const last     = document.getElementById('last_name')?.value  || '';
    const combined = document.getElementById('name-combined');
    if (combined) combined.value = (first + ' ' + last).trim();
}

/* ── Role toggle ─────────────────────────────────────────── */
function rgToggleFields(role) {
    const cand  = document.getElementById('candidate-fields');
    const comp  = document.getElementById('company-fields');
    const candN1 = document.getElementById('name-candidate-wrap');
    const candN2 = document.getElementById('name-candidate-wrap2');
    const compN  = document.getElementById('name-company-wrap');
    const isCand = role === 'candidate';

    cand.style.display = isCand ? '' : 'none';
    comp.style.display = isCand ? 'none' : '';

    if (candN1) { candN1.style.display = isCand ? '' : 'none'; candN1.querySelectorAll('input').forEach(el => el.disabled = !isCand); }
    if (candN2) { candN2.style.display = isCand ? '' : 'none'; candN2.querySelectorAll('input').forEach(el => el.disabled = !isCand); }
    if (compN)  { compN.style.display  = isCand ? 'none' : ''; compN.querySelectorAll('input').forEach(el => el.disabled = isCand); }

    cand.querySelectorAll('input, select, textarea').forEach(el => { el.disabled = !isCand; });
    comp.querySelectorAll('input, select, textarea').forEach(el => { el.disabled =  isCand; });
}

document.addEventListener('DOMContentLoaded', function () {
    const checked = document.querySelector('input[name="role"]:checked');
    rgToggleFields(checked ? checked.value : 'candidate');
});

/* ── Password visibility toggle ──────────────────────────── */
function rgTogglePw(id, btn) {
    const inp  = document.getElementById(id);
    const show = inp.type === 'password';
    inp.type   = show ? 'text' : 'password';
    btn.innerHTML = show
        ? `<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`
        : `<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
}

/* ── Password strength ────────────────────────────────────── */
function rgStrengthCheck(val) {
    let s = 0;
    if (val.length >= 8)  s++;
    if (val.length >= 12) s++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const cols = ['var(--te-danger-solid)','var(--te-warning-solid)','var(--te-info-solid)','var(--te-success-solid)'];
    ['sb1','sb2','sb3','sb4'].forEach((id, i) => {
        document.getElementById(id).style.background = i < s ? cols[s - 1] : 'var(--te-border)';
    });
}

/* ── File upload feedback ─────────────────────────────────── */
function rgShowFile(inputId, labelId, hintId, iconId, zoneId) {
    const input = document.getElementById(inputId);
    const file  = input.files[0];
    if (!file) return;

    const icon = document.getElementById(iconId);
    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>`;
    icon.setAttribute('stroke', 'currentColor'); icon.style.color = 'var(--te-success-fg)';

    const name = file.name.length > 32 ? file.name.substring(0, 29) + '…' : file.name;
    const size = file.size < 1024 * 1024
        ? (file.size / 1024).toFixed(0) + ' Ko'
        : (file.size / 1024 / 1024).toFixed(1) + ' Mo';

    document.getElementById(labelId).innerHTML = `<strong style="color:var(--te-text)">${name}</strong>`;
    const hint = document.getElementById(hintId);
    hint.textContent = size + ' · Cliquez pour changer';
    hint.style.color = 'var(--te-success-fg)';

    const zone = document.getElementById(zoneId);
    if (zone) { zone.style.borderColor = 'var(--te-success-solid)'; zone.style.background = 'var(--te-success-soft)'; }
}

/* ── Drag & drop ─────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    [
        { zone:'cv-zone',   input:'cv',   label:'cv-label',   hint:'cv-hint',   icon:'cv-icon'   },
        { zone:'logo-zone', input:'logo', label:'logo-label', hint:'logo-hint', icon:'logo-icon' },
    ].forEach(({ zone, input: inputId, label, hint, icon }) => {
        const zoneEl  = document.getElementById(zone);
        const inputEl = document.getElementById(inputId);
        if (!zoneEl || !inputEl) return;

        ['dragenter','dragover'].forEach(ev => {
            zoneEl.addEventListener(ev, e => {
                e.preventDefault();
                zoneEl.style.borderColor = 'var(--te-primary)';
                zoneEl.style.background  = 'var(--te-primary-soft)';
            });
        });
        ['dragleave','dragend'].forEach(ev => {
            zoneEl.addEventListener(ev, () => {
                zoneEl.style.borderColor = '';
                zoneEl.style.background  = '';
            });
        });
        zoneEl.addEventListener('drop', e => {
            e.preventDefault();
            if (e.dataTransfer.files.length) {
                const transfer = new DataTransfer();
                transfer.items.add(e.dataTransfer.files[0]);
                inputEl.files = transfer.files;
                rgShowFile(inputId, label, hint, icon, zone);
            }
            zoneEl.style.borderColor = '';
            zoneEl.style.background  = '';
        });
    });
});
</script>
</x-guest-layout>
