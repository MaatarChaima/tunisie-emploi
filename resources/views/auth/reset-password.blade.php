<x-guest-layout>
    <x-slot name="title">Nouveau mot de passe</x-slot>

<style>
:root {
            --t:.15s;
        }
        .rp-heading { font-size:1.35rem; font-weight:800; color:var(--te-text); letter-spacing:-.025em; margin:0 0 .25rem; }
.rp-sub { font-size:.8rem; color:var(--te-text-muted); margin:0 0 1.5rem; line-height:1.55; }
.rp-label { display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); margin-bottom:.4rem; }
.rp-wrap { position:relative; }
.rp-icon { position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
.rp-input { display:block; width:100%; padding:.62rem .875rem .62rem 2.35rem; border:1.5px solid var(--te-border); border-radius:8px; font-size:.875rem; font-family:inherit; color:var(--te-text); background:var(--te-surface); outline:none; box-sizing:border-box; transition:border-color var(--t),box-shadow var(--t); }
.rp-input:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
.rp-toggle { position:absolute; right:.75rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--te-text-muted); padding:0; line-height:0; transition:color var(--t); }
.rp-toggle:hover { color:var(--te-primary-fg); }
.rp-field { margin-bottom:1rem; }
.rp-strength { display:flex; gap:.25rem; margin-top:.4rem; }
.rp-strength-bar { flex:1; height:3px; border-radius:99px; background:var(--te-border); transition:background .3s; }
.rp-submit { display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%; padding:.7rem; border-radius:10px; background:linear-gradient(135deg,var(--te-primary),var(--te-primary-400)); color:var(--te-on-primary); border:none; font-size:.9rem; font-weight:700; font-family:inherit; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,.28); transition:opacity var(--t); margin-top:1.25rem; }
.rp-submit:hover { opacity:.9; }
</style>

<div style="text-align:center; margin-bottom:1.5rem;">
    <div style="width:52px; height:52px; border-radius:14px; background:var(--te-primary-soft); display:flex; align-items:center; justify-content:center; margin:0 auto .875rem;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
    </div>
    <h2 class="rp-heading">Nouveau mot de passe</h2>
    <p class="rp-sub">Choisissez un mot de passe sécurisé pour votre compte.</p>
</div>

<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    {{-- Email --}}
    <div class="rp-field">
        <label class="rp-label" for="email">Adresse e-mail</label>
        <div class="rp-wrap">
            <span class="rp-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </span>
            <input id="email" name="email" type="email" class="rp-input"
                   value="{{ old('email', $request->email) }}" required autofocus placeholder="vous@exemple.com">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
    </div>

    {{-- New password --}}
    <div class="rp-field">
        <label class="rp-label" for="password">Nouveau mot de passe</label>
        <div class="rp-wrap">
            <span class="rp-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
            <input id="password" name="password" type="password" class="rp-input"
                   required autocomplete="new-password" placeholder="8 caractères minimum"
                   oninput="rpStrength(this.value)">
            <button type="button" class="rp-toggle" onclick="rpToggle('password',this)" aria-label="Afficher">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
        <div class="rp-strength">
            <div class="rp-strength-bar" id="rpb1"></div>
            <div class="rp-strength-bar" id="rpb2"></div>
            <div class="rp-strength-bar" id="rpb3"></div>
            <div class="rp-strength-bar" id="rpb4"></div>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
    </div>

    {{-- Confirm --}}
    <div class="rp-field">
        <label class="rp-label" for="password_confirmation">Confirmer le mot de passe</label>
        <div class="rp-wrap">
            <span class="rp-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </span>
            <input id="password_confirmation" name="password_confirmation" type="password" class="rp-input"
                   required autocomplete="new-password" placeholder="Retapez le mot de passe">
        </div>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
    </div>

    <button type="submit" class="rp-submit">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Réinitialiser le mot de passe
    </button>
</form>

<script>
function rpToggle(id, btn) {
    const inp = document.getElementById(id);
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    btn.innerHTML = show
        ? `<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`
        : `<svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
}
function rpStrength(val) {
    let s = 0;
    if (val.length >= 8) s++;
    if (val.length >= 12) s++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const cols = ['var(--te-danger-solid)','var(--te-warning-solid)','var(--te-info-solid)','var(--te-success-solid)'];
    ['rpb1','rpb2','rpb3','rpb4'].forEach((id,i) => {
        document.getElementById(id).style.background = i < s ? cols[s-1] : 'var(--te-border)';
    });
}
</script>
</x-guest-layout>
