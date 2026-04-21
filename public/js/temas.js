/* ══════════════════════════════════════════════════════════════
   Edunoly · temas.js
   Incluir en TODAS las páginas ANTES del CSS propio.
══════════════════════════════════════════════════════════════ */

/* Aplica el tema guardado inmediatamente para evitar flash */
(function () {
    const tema = localStorage.getItem('edunoly-tema') || 'verde';
    document.documentElement.setAttribute('data-tema', tema);

    // Aplicar opciones de accesibilidad guardadas en todas las páginas
    try {
        const opts = JSON.parse(localStorage.getItem('edunoly-config') || '{}');

        // Tamaño de fuente — aplica clase al body
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.remove('fuente-grande', 'fuente-muy-grande');
            if (opts.fuente === 'grande')      document.body.classList.add('fuente-grande');
            if (opts.fuente === 'muy-grande')  document.body.classList.add('fuente-muy-grande');
        });

        // Animaciones reducidas
        if (opts.animaciones) {
            const s = document.createElement('style');
            s.id = 'estilo-animaciones';
            s.textContent = `*, *::before, *::after {
                animation-duration: 0ms !important;
                transition-duration: 0ms !important;
            }`;
            document.head.appendChild(s);
        }

        // Alto contraste
        if (opts.contraste) {
            const s = document.createElement('style');
            s.id = 'estilo-contraste';
            s.textContent = `body { filter: contrast(1.25); }`;
            document.head.appendChild(s);
        }

        // Subrayar enlaces
        if (opts.enlaces) {
            const s = document.createElement('style');
            s.id = 'estilo-enlaces';
            s.textContent = `a { text-decoration: underline !important; }`;
            document.head.appendChild(s);
        }

    } catch(e) {}
})();

/* ──────────────────────────────────────────────────────────────
   INYECCIÓN DEL LOGO INLINE
   Busca todos los <img src="logo.svg"> y los sustituye por el
   SVG inline. Al estar inline, las clases CSS del SVG reciben
   las variables del tema activo y cambian de color solos.
────────────────────────────────────────────────────────────── */
function inyectarLogo() {
    document.querySelectorAll('img[src="logo.svg"], img[src*="logo.svg"]').forEach(img => {
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', '140');
        svg.setAttribute('height', '32');
        svg.setAttribute('viewBox', '0 0 140 32');
        svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');

        // Copia clases y estilos del img original si los tiene
        if (img.className) svg.setAttribute('class', img.className);

        svg.innerHTML = `
            <rect x="0" y="2" width="28" height="28" rx="5" class="logo-fondo"/>
            <text x="14" y="21" text-anchor="middle"
                  font-family="Arial, sans-serif" font-size="18" font-weight="700"
                  class="logo-letra-e">E</text>
            <text font-family="Arial, sans-serif" font-size="18" class="logo-edu">
                <tspan x="34" y="22" font-weight="700">Edu</tspan>
            </text>
            <text font-family="Arial, sans-serif" font-size="18" font-weight="400"
                  class="logo-noly">
                <tspan x="72" y="22">noly</tspan>
            </text>`;

        img.replaceWith(svg);
    });
}

/* ──────────────────────────────────────────────────────────────
   API PÚBLICA
────────────────────────────────────────────────────────────── */

/**
 * Aplica el tema visualmente SIN guardarlo en localStorage.
 * Usar para vista previa en la página de configuración.
 */
function aplicarTema(nombre) {
    document.documentElement.setAttribute('data-tema', nombre);
    /* NO guarda en localStorage — solo vista previa */
}

/**
 * Aplica el tema Y lo guarda en localStorage.
 * Llamar solo cuando el usuario pulse "Guardar cambios".
 */
function guardarTema(nombre) {
    document.documentElement.setAttribute('data-tema', nombre);
    localStorage.setItem('edunoly-tema', nombre);
}

/** Devuelve el tema actualmente guardado. */
function temaActual() {
    return localStorage.getItem('edunoly-tema') || 'verde';
}

/* Inyectar el logo en cuanto el DOM esté listo */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inyectarLogo);
} else {
    inyectarLogo();
}
