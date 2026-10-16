<x-guest-layout>
    <x-slot name="title">Connexion</x-slot>

<style>
/* ── Login card ───────────────────────────────────────────── */
.al-heading  { font-size:1.45rem; font-weight:800; color:var(--text-main); letter-spacing:-.025em; margin:0 0 .3rem; }
.al-sub      { font-size:.825rem; color:var(--text-muted); margin:0 0 1.875rem; }
.al-label    { display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--text-muted); margin-bottom:.4rem; }
.al-field    { margin-bottom:1.1rem; }

/* Input wrapper */
.al-wrap     { position:relative; }
.al-icon     { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
.al-input    {
    display:block; width:100%;
    padding:.7rem 1rem .7rem 2.6rem;
    border:1.5px solid var(--border);
    border-radius:10px;
    font-size:.875rem; font-family:inherit;
    color:var(--text-main); background:var(--te-surface);
    outline:none; box-sizing:border-box;
    transition:border-color var(--transition), box-shadow var(--transition);
}
.al-input:focus  { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.12); }
.al-input::placeholder { color:var(--te-text-muted); }

.al-toggle   {
    position:absolute; right:.8rem; top:50%; transform:translateY(-50%);
    background:none; border:none; cursor:pointer; color:var(--te-text-muted);
    padding:0; line-height:0; transition:color var(--transition);
}
.al-toggle:hover { color:var(--te-primary-fg); }

/* Remember + forgot row */
.al-row      { display:flex; align-items:center; justify-content:space-between; }
.al-check    { display:flex; align-items:center; gap:.45rem; font-size:.8rem; font-weight:600; color:var(--text-muted); cursor:pointer; }
.al-check input { accent-color:var(--te-primary-fg); width:14px; height:14px; }

/* Links */
.al-link     { font-size:.8rem; font-weight:600; color:var(--te-primary-fg); text-decoration:none; transition:color var(--transition); }
.al-link:hover { color:var(--te-primary-fg); text-decoration:underline; }

/* Submit */
.al-submit   {
    display:flex; align-items:center; justify-content:center; gap:.5rem;
    width:100%; padding:.78rem;
    border-radius:10px;
    background:linear-gradient(135deg, var(--te-primary) 0%, var(--te-primary-400) 100%);
    color:var(--te-on-primary); border:none;
    font-size:.925rem; font-weight:700; font-family:inherit;
    cursor:pointer;
    box-shadow:0 4px 16px rgba(79,70,229,.32);
    transition:opacity var(--transition), transform var(--transition), box-shadow var(--transition);
    margin-top:1.5rem;
}
.al-submit:hover {
    opacity:.92;
    transform:translateY(-1px);
    box-shadow:0 6px 24px rgba(79,70,229,.38);
}
.al-submit:active { transform:translateY(0); }

/* Session alert */
.al-alert    {
    display:flex; align-items:flex-start; gap:.5rem;
    padding:.7rem .9rem; border-radius:10px; margin-bottom:1.1rem;
    font-size:.8rem; font-weight:600; line-height:1.4;
    background:var(--te-primary-soft); border:1px solid var(--te-primary-border); color:var(--te-primary-fg);
}

/* Divider */
.al-divider  {
    display:flex; align-items:center; gap:.75rem;
    font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em;
    color:var(--text-muted); margin:1.375rem 0;
}
.al-divider::before, .al-divider::after { content:''; flex:1; height:1px; background:var(--border); }
</style>

<div class="auth-card" style="max-width:460px;">

    {{-- Session status --}}
    @if(session('status'))
        <div class="al-alert">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <h2 class="al-heading">Bon retour</h2>
    <p class="al-sub">Connectez-vous à votre compte TunisieEmploi</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="al-field">
            <label class="al-label" for="email">Adresse e-mail</label>
            <div class="al-wrap">
                <span class="al-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input id="email" name="email" type="email" class="al-input"
                       value="{{ old('email') }}" required autofocus autocomplete="username"
                       placeholder="vous@exemple.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        {{-- Password --}}
        <div class="al-field">
            <div style="margin-bottom:.4rem;">
                <label class="al-label" for="password" style="margin:0">Mot de passe</label>
            </div>
            <div class="al-wrap">
                <span class="al-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input id="password" name="password" type="password" class="al-input"
                       required autocomplete="current-password">
                <button type="button" class="al-toggle" onclick="alTogglePw('password', this)"
                        aria-label="Afficher le mot de passe">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <button type="submit" class="al-submit">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Se connecter
        </button>

        <div class="al-divider">ou</div>

        <p style="text-align:center;font-size:.825rem;color:var(--text-muted);">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="al-link" style="margin-left:.3rem;">Créer un compte</a>
        </p>
    </form>

</div>

<script>
function alTogglePw(id, btn) {
    const inp  = document.getElementById(id);
    const show = inp.type === 'password';
    inp.type   = show ? 'text' : 'password';
    btn.innerHTML = show
        ? `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`
        : `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
}
</script>
</x-guest-layout>
