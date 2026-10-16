{{-- Menu mobile : thème clair / sombre (même mémorisation que le bouton de l'en-tête : window.teToggleTheme) --}}
<div class="te-mobile-theme" role="group" aria-labelledby="te-mobile-theme-label">
    <span class="te-mobile-theme-label" id="te-mobile-theme-label">Thème</span>
    <div class="te-mobile-theme-btns">
        <button type="button" class="te-mobile-theme-btn" data-theme-choice="light" aria-pressed="false" aria-label="Thème clair">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Clair
        </button>
        <button type="button" class="te-mobile-theme-btn" data-theme-choice="dark" aria-pressed="false" aria-label="Thème sombre">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            Sombre
        </button>
    </div>
</div>
