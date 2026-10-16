
<div x-data="{ show: false }" x-show="show" x-transition.opacity
     style="position:fixed; bottom:1rem; right:1rem; z-index:9999; display:flex; align-items:center; gap:0.5rem; background:rgba(0,0,0,0.8); color:var(--te-on-primary); padding:0.5rem 1rem; border-radius:99px; font-size:0.75rem;">
    <svg class="animate-spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <circle cx="12" cy="12" r="10" stroke-width="2" stroke-opacity="0.25"/>
        <path d="M12 2a10 10 0 0 1 10 10" stroke-width="2" stroke-linecap="round"/>
    </svg>
    <span x-text="message">Chargement...</span>
</div>