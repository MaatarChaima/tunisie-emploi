{{--
    Liste déroulante accessible : bouton + role="listbox" (flèches, Début/Fin, Entrée/Espace, Échap,
    saisie des premières lettres, fermeture au clic extérieur), icône ronde devant chaque option.
    Le <select> natif reste la source de vérité : envoyé avec le formulaire, seul affiché sans JavaScript
    (classe « js » sur <html>), et l'événement « change » y est déclenché à chaque choix.
    Après une modification du <select> par script : document.dispatchEvent(new CustomEvent('ui-listbox-sync')).

    options : [['value' => …, 'label' => …, 'icon' => tracé SVG (pastille ronde),
                'neutral' => bool, 'plain' => bool (option choisie affichée sans icône dans le bouton), 'count' => int|null, 'sr' => texte lu en plus,
                'data' => [attributs data-* du <option> natif]], …]
    buttonIcon : tracé SVG affiché seulement dans le bouton fermé (ex. repère de lieu pour Ville).
--}}
@props([
    'id',                      // id du <select> natif (ex. f-category)
    'name',
    'options' => [],
    'selected' => '',
    'labelledby',              // id du libellé visible (ou masqué) du champ
    'buttonClass' => '',
    'align' => 'left',         // left | right : côté d'ouverture de la liste
    'buttonIcon' => null,
])

@php
    $selected = (string) $selected;
    $check = '<svg class="ui-lb-check" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
@endphp

@once
    {{-- Classe « js » : la liste personnalisée remplace le <select> natif (posée avant l'affichage) --}}
    <script>document.documentElement.classList.add('js');</script>
@endonce

<select name="{{ $name }}" id="{{ $id }}" aria-labelledby="{{ $labelledby }}" {{ $attributes->merge(['class' => 'ui-lb-native']) }}>
    @foreach($options as $o)
        <option value="{{ $o['value'] }}"
                @foreach($o['data'] ?? [] as $k => $v) data-{{ $k }}="{{ $v }}" @endforeach
                @selected($selected === (string) $o['value'])>{{ $o['label'] }}</option>
    @endforeach
</select>

<div class="ui-lb" x-data="uiListbox('{{ $id }}')" @click.outside="open && close(false)" @ui-listbox-sync.document="sync()">
    <button type="button" class="ui-lb-btn {{ $buttonClass }}" id="{{ $id }}-btn" x-ref="btn"
            aria-haspopup="listbox" aria-controls="{{ $id }}-list" aria-expanded="false" :aria-expanded="open.toString()"
            aria-labelledby="{{ $labelledby }} {{ $id }}-btn"
            @click="open ? close(true) : show()" @keydown="onButtonKey($event)">
        @if($buttonIcon)
            <svg class="ui-lb-btn-ico" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $buttonIcon }}"/></svg>
        @endif
        <span class="ui-lb-main" x-ref="val"></span>
        <svg class="ui-lb-chev" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <ul class="ui-lb-list {{ $align === 'right' ? 'is-right' : '' }}" id="{{ $id }}-list" role="listbox" tabindex="-1"
        aria-labelledby="{{ $labelledby }}" x-ref="list" x-show="open" style="display:none"
        @keydown="onListKey($event)" @click="pick($event)" @mousemove="hover($event)">
        @foreach($options as $i => $o)
            <li role="option" id="{{ $id }}-opt-{{ $i }}" data-value="{{ $o['value'] }}" data-label="{{ $o['label'] }}" @if(!empty($o['plain'])) data-plain="1" @endif aria-selected="false">
                <span class="ui-lb-main">
                    @if(!empty($o['icon']))
                        <span class="ui-lb-ico {{ !empty($o['neutral']) ? 'is-neutral' : '' }}" aria-hidden="true">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $o['icon'] }}"/></svg>
                        </span>
                    @endif
                    <span class="ui-lb-name">{{ $o['label'] }}</span>
                </span>
                @if(isset($o['count']))
                    <span class="ui-lb-count" aria-hidden="true">{{ $o['count'] }}</span>
                @endif
                @if(!empty($o['sr']))
                    <span class="sr-only">{{ $o['sr'] }}</span>
                @endif
                {!! $check !!}
            </li>
        @endforeach
    </ul>
</div>

@once
    <style>
        /* Sans JavaScript : <select> natif seul ; avec : liste personnalisée seule */
        .ui-lb { display:none; position:relative; min-width:0; }
        .js .ui-lb { display:block; }
        .js .ui-lb-native { display:none; }
        .ui-lb .ui-lb-btn { position:relative; display:flex; align-items:center; gap:.5rem; width:100%; max-width:100%; min-width:0; overflow:hidden; min-height:2.375rem; padding:.3rem 2rem .3rem .45rem; text-align:left; cursor:pointer; }
        .ui-lb .ui-lb-btn:focus-visible { border-color:var(--te-primary); box-shadow:0 0 0 3px var(--te-primary-ring); outline:none; }
        .ui-lb-chev { position:absolute; right:.65rem; top:50%; transform:translateY(-50%); color:var(--te-text-muted); pointer-events:none; }
        .ui-lb-main { display:flex; align-items:center; gap:.55rem; min-width:0; flex:1; overflow:hidden; }
        .ui-lb-name { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        /* Icône dans une pastille ronde : 28 px dans la liste, 24 px dans le bouton */
        .ui-lb-ico { width:28px; height:28px; border-radius:50%; flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; background:var(--te-primary-soft); color:var(--te-primary-fg); }
        .ui-lb-ico.is-neutral { background:var(--te-surface-3); color:var(--te-text-muted); }
        .ui-lb-btn .ui-lb-ico { width:24px; height:24px; }
        .ui-lb-btn-ico { flex-shrink:0; margin-left:.2rem; color:var(--te-text-muted); }
        .ui-lb-list { position:absolute; z-index:40; top:calc(100% + 4px); left:0; width:max(100%, 16rem); max-height:21rem; overflow:auto; margin:0; padding:.3rem; list-style:none; background:var(--te-surface); border:1px solid var(--te-border); border-radius:10px; box-shadow:0 12px 32px rgba(15,23,42,.18); }
        .ui-lb-list.is-right { left:auto; right:0; }
        .ui-lb-list:focus { outline:none; }
        .ui-lb-list li { display:flex; align-items:center; gap:.5rem; padding:.42rem .5rem; border-radius:7px; cursor:pointer; font-size:.875rem; font-weight:500; color:var(--te-text); }
        .ui-lb-list li.is-active { background:var(--te-surface-2); box-shadow:inset 0 0 0 2px var(--te-focus); }
        .ui-lb-list li[aria-selected="true"] { font-weight:700; color:var(--te-primary-fg); }
        .ui-lb-count { margin-left:auto; padding-left:.5rem; color:var(--te-text-muted); font-size:.8125rem; font-weight:600; font-variant-numeric:tabular-nums; }
        .ui-lb-check { flex-shrink:0; margin-left:auto; color:var(--te-primary-fg); visibility:hidden; }
        .ui-lb-count + .ui-lb-check, .sr-only + .ui-lb-check { margin-left:0; }
        .ui-lb-list li[aria-selected="true"] .ui-lb-check { visibility:visible; }
    </style>
    <script>
    // Composant Alpine des listes du composant ui.listbox (défini avant le démarrage d'Alpine)
    window.uiListbox = function (selectId) {
        const norm = (t) => t.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
        return {
            open: false, active: 0, typed: '', typedAt: 0,
            init() {
                this.select = document.getElementById(selectId);
                this.opts = [...this.$refs.list.querySelectorAll('[role="option"]')];
                this.sync();
            },
            sync() {
                const v = this.select.value;
                this.opts.forEach((o) => o.setAttribute('aria-selected', o.dataset.value === v ? 'true' : 'false'));
                const cur = this.opts.find((o) => o.dataset.value === v) || this.opts[0];
                // Bouton fermé : copie de l'option choisie ; sans icône pour une option « plain » (ex. « Tous les domaines »)
                const main = cur.querySelector('.ui-lb-main').cloneNode(true);
                if (cur.dataset.plain) main.querySelectorAll('.ui-lb-ico').forEach((n) => n.remove());
                this.$refs.val.innerHTML = main.innerHTML;
            },
            show() {
                this.open = true;
                this.active = Math.max(0, this.opts.findIndex((o) => o.getAttribute('aria-selected') === 'true'));
                this.$nextTick(() => { this.$refs.list.focus(); this.highlight(); });
            },
            close(focusButton) {
                this.open = false;
                this.$refs.list.removeAttribute('aria-activedescendant');
                if (focusButton) this.$refs.btn.focus();
            },
            highlight() {
                this.opts.forEach((o, i) => o.classList.toggle('is-active', i === this.active));
                const o = this.opts[this.active];
                if (o) { this.$refs.list.setAttribute('aria-activedescendant', o.id); o.scrollIntoView({ block: 'nearest' }); }
            },
            choose(i) {
                const o = this.opts[i];
                if (!o) return;
                const changed = this.select.value !== o.dataset.value;
                this.select.value = o.dataset.value;
                this.sync();
                this.close(true);
                if (changed) this.select.dispatchEvent(new Event('change', { bubbles: true }));
            },
            pick(e) { const li = e.target.closest('[role="option"]'); if (li) this.choose(this.opts.indexOf(li)); },
            hover(e) { const li = e.target.closest('[role="option"]'); if (li && this.opts.indexOf(li) !== this.active) { this.active = this.opts.indexOf(li); this.highlight(); } },
            onButtonKey(e) {
                if (['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(e.key)) { e.preventDefault(); this.show(); }
            },
            onListKey(e) {
                const last = this.opts.length - 1;
                switch (e.key) {
                    case 'ArrowDown': e.preventDefault(); this.active = Math.min(last, this.active + 1); this.highlight(); break;
                    case 'ArrowUp':   e.preventDefault(); this.active = Math.max(0, this.active - 1); this.highlight(); break;
                    case 'Home':      e.preventDefault(); this.active = 0; this.highlight(); break;
                    case 'End':       e.preventDefault(); this.active = last; this.highlight(); break;
                    case 'Enter': case ' ': e.preventDefault(); this.choose(this.active); break;
                    case 'Escape':    e.preventDefault(); e.stopPropagation(); this.close(true); break;
                    case 'Tab':       this.close(false); break;
                    default:
                        if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) this.typeahead(e.key);
                }
            },
            // Une ou plusieurs lettres : option suivante dont le nom commence ainsi (accents ignorés)
            typeahead(ch) {
                const now = Date.now();
                this.typed = (now - this.typedAt < 700 ? this.typed : '') + norm(ch);
                this.typedAt = now;
                const n = this.opts.length;
                const startAt = this.typed.length === 1 ? this.active + 1 : this.active;
                for (let k = 0; k < n; k++) {
                    const i = (startAt + k) % n;
                    if (norm(this.opts[i].dataset.label).startsWith(this.typed)) { this.active = i; this.highlight(); return; }
                }
            },
        };
    };
    </script>
@endonce
