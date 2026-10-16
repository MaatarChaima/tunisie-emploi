{{-- Thème clair / sombre : appliqué avant le premier rendu pour éviter tout flash.
     Choix enregistré dans localStorage, sinon préférence du système (suivie en direct). --}}
<meta name="color-scheme" content="light dark">
<script>
    (function () {
        var root = document.documentElement;
        var media = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
        function stored() {
            try { var t = localStorage.getItem('theme'); return t === 'dark' || t === 'light' ? t : null; } catch (e) { return null; }
        }
        function apply(theme) { root.setAttribute('data-theme', theme); }
        apply(stored() || (media && media.matches ? 'dark' : 'light'));
        if (media && media.addEventListener) {
            media.addEventListener('change', function (e) { if (!stored()) apply(e.matches ? 'dark' : 'light'); });
        }
        window.teToggleTheme = function () {
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            apply(next);
            try { localStorage.setItem('theme', next); } catch (e) {}
            return next;
        };
    })();
</script>
