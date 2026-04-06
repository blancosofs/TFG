/* ══════════════════════════════════════════════════════════════
   Edunoly · temas.js
   Incluir en TODAS las páginas ANTES del CSS propio.
══════════════════════════════════════════════════════════════ */

/* Aplica el tema guardado inmediatamente para evitar flash */
(function () {
    const tema = localStorage.getItem('edunoly-tema') || 'verde';
    document.documentElement.setAttribute('data-tema', tema);
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
 * Cambia el tema activo y lo persiste en localStorage.
 * @param {string} nombre — 'verde'|'negro'|'blanco'|'azul'|'purpura'|'rojo'
 */
function aplicarTema(nombre) {
    document.documentElement.setAttribute('data-tema', nombre);
    localStorage.setItem('edunoly-tema', nombre);
    /* El logo ya es inline, las variables CSS lo actualizan solas */
}

/** Devuelve el tema actualmente activo. */
function temaActual() {
    return localStorage.getItem('edunoly-tema') || 'verde';
}

/* Inyectar el logo en cuanto el DOM esté listo */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inyectarLogo);
} else {
    inyectarLogo();
}