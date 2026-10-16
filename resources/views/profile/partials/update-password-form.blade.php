<style>
    .pw-label {
        display:block; font-size:.75rem; font-weight:700;
        text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); margin-bottom:.4rem;
    }
    .pw-input-wrap { position:relative; }
    .pw-input {
        display:block; width:100%; padding:.6rem 2.75rem .6rem 2.25rem;
        border:1.5px solid var(--te-border); border-radius:9px;
        font-size:.875rem; font-family:inherit; color:var(--te-text);
        background:var(--te-surface); outline:none;
        transition:border-color .15s, box-shadow .15s;
        box-sizing:border-box;
    }
    .pw-input:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
    .pw-input-icon {
        position:absolute; left:.75rem; top:50%; transform:translateY(-50%);
        color:var(--te-text-muted); pointer-events:none;
    }
    .pw-eye-btn {
        position:absolute; right:.75rem; top:50%; transform:translateY(-50%);
        background:none; border:none; cursor:pointer; padding:0;
        color:var(--te-text-muted); display:flex; align-items:center;
        transition:color .15s;
    }
    .pw-eye-btn:hover { color:var(--te-text-muted); }

    /* Strength indicator */
    .pw-strength-wrap { margin-top:.6rem; }
    .pw-strength { display:flex; gap:.3rem; }
    .pw-strength-bar {
        flex:1; height:4px; border-radius:99px; background:var(--te-border);
        transition:background .3s cubic-bezier(.4,0,.2,1);
    }
    .pw-strength-label {
        font-size:.75rem; font-weight:700; margin-top:.35rem;
        transition:color .3s;
    }

    .pw-save-btn {
        display:inline-flex; align-items:center; gap:.5rem;
        padding:.65rem 1.75rem; border-radius:9px;
        background:linear-gradient(135deg, var(--te-primary), var(--te-primary-400));
        color:var(--te-on-primary); border:none; font-size:.875rem; font-weight:700;
        font-family:inherit; cursor:pointer;
        transition:opacity .15s, box-shadow .15s, transform .15s;
        box-shadow:0 4px 14px rgba(79,70,229,.3);
    }
    .pw-save-btn:hover { opacity:.92; box-shadow:0 6px 20px rgba(79,70,229,.38); transform:translateY(-1px); }
    .pw-save-btn:active { transform:translateY(0); }

    .pw-saved {
        display:inline-flex; align-items:center; gap:.4rem;
        font-size:.8rem; font-weight:600; color:var(--te-success-fg);
        background:var(--te-success-soft); border:1px solid var(--te-success-border);
        padding:.38rem .85rem; border-radius:99px;
    }

    .pw-tip {
        display:flex; align-items:flex-start; gap:.55rem;
        padding:.7rem .9rem; border-radius:10px;
        background:linear-gradient(135deg, var(--te-info-soft), var(--te-info-soft));
        border:1px solid var(--te-info-border);
        font-size:.78rem; color:var(--te-info-fg); line-height:1.55; margin-bottom:1.25rem;
    }

    .pw-section-header {
        display:flex; align-items:center; gap:.75rem;
        margin-bottom:1.75rem; padding-bottom:1.25rem;
        border-bottom:1px solid var(--te-border-subtle);
    }
    .pw-section-icon {
        width:32px; height:32px; border-radius:9px; flex-shrink:0;
        display:inline-flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg, var(--te-primary), var(--te-primary-400));
        box-shadow:0 2px 8px rgba(79,70,229,.25);
    }
    .pw-save-footer {
        display:flex; align-items:center; gap:1rem;
        margin-top:2rem; padding-top:1.5rem;
        border-top:1px solid var(--te-border-subtle);
    }
</style>

<section>
    <div class="pw-section-header">
        <span class="pw-section-icon">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-on-primary)">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </span>
        <div>
            <div style="font-size:1rem; font-weight:800; color:var(--te-text); line-height:1.2;">Mettre à jour le mot de passe</div>
            <div style="font-size:.78rem; color:var(--te-text-muted); margin-top:.15rem;">Utilisez un mot de passe long et aléatoire pour sécuriser votre compte.</div>
        </div>
    </div>

    <div class="pw-tip">
        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Recommandé : au moins <strong>12 caractères</strong> avec des lettres majuscules, des chiffres et des symboles.
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div style="display:flex; flex-direction:column; gap:1.25rem;">

            <div>
                <label class="pw-label" for="update_password_current_password">Mot de passe actuel</label>
                <div class="pw-input-wrap">
                    <span class="pw-input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input id="update_password_current_password" name="current_password"
                           type="password" class="pw-input" autocomplete="current-password">
                    <button type="button" class="pw-eye-btn"
                            onclick="pwToggle('update_password_current_password', 'eye-cur')"
                            aria-label="Afficher/masquer">
                        <svg id="eye-cur" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2"/>
            </div>

            <div>
                <label class="pw-label" for="update_password_password">Nouveau mot de passe</label>
                <div class="pw-input-wrap">
                    <span class="pw-input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input id="update_password_password" name="password"
                           type="password" class="pw-input" autocomplete="new-password"
                           placeholder="8 caractères minimum" oninput="updateStrength(this.value)">
                    <button type="button" class="pw-eye-btn"
                            onclick="pwToggle('update_password_password', 'eye-new')"
                            aria-label="Afficher/masquer">
                        <svg id="eye-new" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <div class="pw-strength-wrap">
                    <div class="pw-strength">
                        <div class="pw-strength-bar" id="bar1"></div>
                        <div class="pw-strength-bar" id="bar2"></div>
                        <div class="pw-strength-bar" id="bar3"></div>
                        <div class="pw-strength-bar" id="bar4"></div>
                    </div>
                    <div class="pw-strength-label" id="pw-strength-text" style="color:var(--te-text-muted);"></div>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2"/>
            </div>

            <div>
                <label class="pw-label" for="update_password_password_confirmation">Confirmer le nouveau mot de passe</label>
                <div class="pw-input-wrap">
                    <span class="pw-input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                    <input id="update_password_password_confirmation" name="password_confirmation"
                           type="password" class="pw-input" autocomplete="new-password"
                           placeholder="Retapez le mot de passe">
                    <button type="button" class="pw-eye-btn"
                            onclick="pwToggle('update_password_password_confirmation', 'eye-conf')"
                            aria-label="Afficher/masquer">
                        <svg id="eye-conf" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2"/>
            </div>
        </div>

        <div class="pw-save-footer">
            <button type="submit" class="pw-save-btn">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Mettre à jour le mot de passe
            </button>

            @if(session('status') === 'password-updated')
                <span class="pw-saved"
                      x-data="{show:true}" x-show="show" x-transition
                      x-init="setTimeout(()=>show=false,3000)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mot de passe mis à jour !
                </span>
            @endif
        </div>
    </form>
</section>

<script>
    const pwStrengthConfig = [
        { label:'',            color:'var(--te-text-muted)' },
        { label:'Trop court',  color:'var(--te-danger-fg)' },
        { label:'Faible',      color:'var(--te-warning-fg)' },
        { label:'Moyen',       color:'var(--te-info-fg)' },
        { label:'Fort',        color:'var(--te-success-fg)' },
    ];

    function updateStrength(val) {
        let score = 0;
        if (val.length >= 8)  score++;
        if (val.length >= 12) score++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const barColors = ['var(--te-danger-solid)','var(--te-warning-solid)','var(--te-info-solid)','var(--te-success-solid)'];
        ['bar1','bar2','bar3','bar4'].forEach(function(id, i) {
            document.getElementById(id).style.background = i < score ? barColors[score - 1] : 'var(--te-border)';
        });

        const txt = document.getElementById('pw-strength-text');
        if (val.length === 0) {
            txt.textContent = '';
        } else {
            const cfg = pwStrengthConfig[score] || pwStrengthConfig[4];
            txt.textContent = cfg.label;
            txt.style.color = cfg.color;
        }
    }

    function pwToggle(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        // swap eye / eye-off icon
        if (isPass) {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }
</script>
