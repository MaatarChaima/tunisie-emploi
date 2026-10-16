{{-- ============================================================
     confirm-password.blade.php
     Save this file as: resources/views/auth/confirm-password.blade.php
     ============================================================ --}}
<x-guest-layout>
    <x-slot name="title">Confirmation du mot de passe</x-slot>

<style>
:root {
            --t:.15s;
        }
        .cp-heading { font-size:1.35rem; font-weight:800; color:var(--te-text); letter-spacing:-.025em; margin:0 0 .25rem; }
.cp-sub { font-size:.8rem; color:var(--te-text-muted); margin:0 0 1.5rem; line-height:1.55; }
.cp-label { display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); margin-bottom:.4rem; }
.cp-wrap { position:relative; }
.cp-icon { position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
.cp-input { display:block; width:100%; padding:.62rem .875rem .62rem 2.35rem; border:1.5px solid var(--te-border); border-radius:8px; font-size:.875rem; font-family:inherit; color:var(--te-text); background:var(--te-surface); outline:none; box-sizing:border-box; transition:border-color var(--t),box-shadow var(--t); }
.cp-input:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
.cp-toggle { position:absolute; right:.75rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--te-text-muted); padding:0; line-height:0; transition:color var(--t); }
.cp-toggle:hover { color:var(--te-primary-fg); }
.cp-submit { display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%; padding:.7rem; border-radius:10px; background:linear-gradient(135deg,var(--te-primary),var(--te-primary-400)); color:var(--te-on-primary); border:none; font-size:.9rem; font-weight:700; font-family:inherit; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,.28); transition:opacity var(--t); margin-top:1.25rem; }
.cp-submit:hover { opacity:.9; }
.cp-warn { display:flex; align-items:flex-start; gap:.5rem; padding:.65rem .875rem; border-radius:8px; margin-bottom:1.25rem; background:var(--te-warning-soft); border:1px solid var(--te-warning-border); color:var(--te-warning-fg); font-size:.8rem; font-weight:600; line-height:1.4; }
</style>

<div style="text-align:center; margin-bottom:1.5rem;">
    <div style="width:52px; height:52px; border-radius:14px; background:var(--te-warning-soft); display:flex; align-items:center; justify-content:center; margin:0 auto .875rem;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="color:var(--te-warning-fg)">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
    </div>
    <h2 class="cp-heading">Zone sécurisée</h2>
    <p class="cp-sub">Confirmez votre mot de passe pour continuer vers cette section sécurisée.</p>
</div>

<div class="cp-warn">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    Cette action nécessite une vérification de votre identité.
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    <div>
        <label class="cp-label" for="password">Mot de passe</label>
        <div class="cp-wrap">
            <span class="cp-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
            <input id="cp_password" name="password" type="password" class="cp-input"
                   required autocomplete="current-password">
            <button type="button" class="cp-toggle" onclick="cpToggle()" aria-label="Afficher">
                <svg id="cp-eye" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
    </div>

    <button type="submit" class="cp-submit">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        Confirmer et continuer
    </button>
</form>
<script>
function cpToggle() {
    const inp = document.getElementById('cp_password');
    const btn = document.getElementById('cp-eye');
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    btn.innerHTML = show
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}
</script>
</x-guest-layout>
