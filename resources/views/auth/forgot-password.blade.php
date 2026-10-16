{{-- ============================================================
     forgot-password.blade.php
     ============================================================ --}}
<x-guest-layout>
    <x-slot name="title">Mot de passe oublié</x-slot>

<style>
:root {
            --t:.15s;
        }
        .fp-heading { font-size:1.35rem; font-weight:800; color:var(--te-text); letter-spacing:-.025em; margin:0 0 .25rem; }
.fp-sub { font-size:.8rem; color:var(--te-text-muted); margin:0 0 1.5rem; line-height:1.55; }
.fp-label { display:block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--te-text-muted); margin-bottom:.4rem; }
.fp-wrap { position:relative; }
.fp-icon { position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
.fp-input { display:block; width:100%; padding:.62rem .875rem .62rem 2.35rem; border:1.5px solid var(--te-border); border-radius:8px; font-size:.875rem; font-family:inherit; color:var(--te-text); background:var(--te-surface); outline:none; box-sizing:border-box; transition:border-color var(--t),box-shadow var(--t); }
.fp-input:focus { border-color:var(--te-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
.fp-submit { display:flex; align-items:center; justify-content:center; gap:.5rem; width:100%; padding:.7rem; border-radius:10px; background:linear-gradient(135deg,var(--te-primary),var(--te-primary-400)); color:var(--te-on-primary); border:none; font-size:.9rem; font-weight:700; font-family:inherit; cursor:pointer; box-shadow:0 4px 12px rgba(79,70,229,.28); transition:opacity var(--t); margin-top:1.25rem; }
.fp-submit:hover { opacity:.9; }
.fp-link { font-size:.8rem; font-weight:600; color:var(--te-primary-fg); text-decoration:none; }
.fp-link:hover { color:var(--te-primary-fg); text-decoration:underline; }
.fp-alert-ok { display:flex; align-items:flex-start; gap:.5rem; padding:.65rem .875rem; border-radius:8px; margin-bottom:1rem; background:var(--te-success-soft); border:1px solid var(--te-success-border); color:var(--te-success-fg); font-size:.8rem; font-weight:600; line-height:1.4; }
</style>

<div style="text-align:center; margin-bottom:1.5rem;">
    <div style="width:52px; height:52px; border-radius:14px; background:var(--te-primary-soft); display:flex; align-items:center; justify-content:center; margin:0 auto .875rem;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="color:var(--te-primary-fg)">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>
    <h2 class="fp-heading">Mot de passe oublié ?</h2>
    <p class="fp-sub">Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
</div>

@if(session('status'))
    <div class="fp-alert-ok">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('status') }}
    </div>
@endif

<x-auth-session-status :status="session('status')"/>

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div>
        <label class="fp-label" for="email">Adresse e-mail</label>
        <div class="fp-wrap">
            <span class="fp-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </span>
            <input id="email" name="email" type="email" class="fp-input"
                   value="{{ old('email') }}" required autofocus placeholder="vous@exemple.com">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
    </div>

    <button type="submit" class="fp-submit">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
        </svg>
        Envoyer le lien de réinitialisation
    </button>

    <p style="text-align:center; font-size:.8rem; color:var(--te-text-muted); margin-top:1rem;">
        <a href="{{ route('login') }}" class="fp-link">
            ← Retour à la connexion
        </a>
    </p>
</form>
</x-guest-layout>
