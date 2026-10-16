<style>
    .del-section {
        display:flex; align-items:flex-start; justify-content:space-between;
        gap:1.5rem; flex-wrap:wrap;
    }
    .del-info { flex:1; min-width:200px; }
    .del-info h2 {
        font-size:1rem; font-weight:800; color:var(--te-danger-fg); margin:0 0 .4rem;
        display:flex; align-items:center; gap:.65rem;
    }
    .del-icon-wrap {
        display:inline-flex; align-items:center; justify-content:center;
        width:32px; height:32px; border-radius:9px; flex-shrink:0;
        background:linear-gradient(135deg, var(--te-danger-solid), var(--te-danger-solid));
        box-shadow:0 2px 8px rgba(239,68,68,.25);
    }
    .del-info p { font-size:.82rem; color:var(--te-text-muted); margin:0; line-height:1.6; max-width:38ch; padding-left:3.1rem; }

    .del-open-btn {
        display:inline-flex; align-items:center; gap:.45rem;
        padding:.55rem 1.25rem; border-radius:8px;
        border:1.5px solid var(--te-danger-border); background:var(--te-danger-soft); color:var(--te-danger-fg);
        font-size:.875rem; font-weight:700; font-family:inherit;
        cursor:pointer; transition:all .15s; flex-shrink:0;
        text-transform:none; letter-spacing:normal;
    }
    .del-open-btn:hover { background:var(--te-danger-soft); border-color:var(--te-danger-solid); }

    /* Modal overrides */
    .del-modal-body { padding:1.5rem 1.5rem 0; }
    .del-modal-head { display:flex; align-items:flex-start; gap:.875rem; margin-bottom:1.25rem; }
    .del-modal-icon {
        width:44px; height:44px; border-radius:10px; flex-shrink:0;
        background:var(--te-danger-soft); border:1px solid var(--te-danger-border);
        display:flex; align-items:center; justify-content:center;
    }
    .del-modal-title { font-size:1rem; font-weight:800; color:var(--te-text); margin:0 0 .3rem; }
    .del-modal-desc { font-size:.8rem; color:var(--te-text-muted); line-height:1.55; margin:0; }

    .del-pw-wrap { position:relative; margin-top:1rem; }
    .del-pw-icon { position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
    .del-pw-input {
        display:block; width:100%; padding:.58rem .875rem .58rem 2.25rem;
        border:1.5px solid var(--te-danger-border); border-radius:8px;
        font-size:.875rem; font-family:inherit; color:var(--te-text);
        background:var(--te-danger-soft); outline:none; box-sizing:border-box;
        transition:border-color .15s, box-shadow .15s;
    }
    .del-pw-input:focus { border-color:var(--te-danger-solid); box-shadow:0 0 0 3px rgba(239,68,68,.1); }

    .del-modal-foot {
        display:flex; align-items:center; justify-content:flex-end; gap:.6rem;
        padding:1rem 1.5rem; border-top:1px solid var(--te-danger-border); margin-top:1.25rem;
    }
    .del-cancel-btn {
        padding:.55rem 1.1rem; border-radius:8px; font-size:.875rem; font-weight:600;
        background:var(--te-surface); color:var(--te-text-2); border:1.5px solid var(--te-border);
        cursor:pointer; font-family:inherit; transition:all .15s;
    }
    .del-cancel-btn:hover { border-color:var(--te-border-strong); }

    .del-confirm-btn {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.55rem 1.25rem; border-radius:8px; font-size:.875rem; font-weight:700;
        background:var(--te-danger-solid); color:var(--te-on-primary); border:none;
        cursor:pointer; font-family:inherit; transition:background .15s;
    }
    .del-confirm-btn:hover { background:var(--te-danger-solid); }
</style>

<section class="del-section">
    <div class="del-info">
        <h2>
            <span class="del-icon-wrap">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-on-primary)">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </span>
            Supprimer mon compte
        </h2>
        <p>Une fois votre compte supprimé, toutes vos données seront définitivement effacées. Téléchargez vos informations avant de continuer.</p>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal','confirm-user-deletion')"
        class="del-open-btn">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Supprimer mon compte
    </x-danger-button>
</section>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <div class="del-modal-body">
            <div class="del-modal-head">
                <div class="del-modal-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--te-danger-fg)">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="del-modal-title">Supprimer définitivement votre compte ?</p>
                    <p class="del-modal-desc">Cette action est irréversible. Toutes vos données seront définitivement supprimées. Confirmez en saisissant votre mot de passe.</p>
                </div>
            </div>

            <x-input-label for="password" value="Mot de passe" class="sr-only"/>
            <div class="del-pw-wrap">
                <span class="del-pw-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input id="password" name="password" type="password" class="del-pw-input" placeholder="Mot de passe">
            </div>
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2"/>
        </div>

        <div class="del-modal-foot">
            <button type="button" class="del-cancel-btn" x-on:click="$dispatch('close')">Annuler</button>
            <button type="submit" class="del-confirm-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Supprimer définitivement
            </button>
        </div>
    </form>
</x-modal>
