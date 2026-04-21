/* ══════════════════════════════════════════════════════════════
   Edunoly · menuResponsive.js
   Lógica del menú hamburguesa para móvil.
   Incluir en todas las páginas que usen la barra de navegación.
══════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('menuToggle');
    const menu   = document.getElementById('menuPrincipal');

    if (!toggle || !menu) return;

    // Abrir / cerrar el menú al pulsar el botón
    toggle.addEventListener('click', () => {
        const abierto = menu.classList.toggle('abierto');
        toggle.classList.toggle('abierto', abierto);
        toggle.setAttribute('aria-expanded', abierto);
    });

    // Cerrar el menú al pulsar fuera de él
    document.addEventListener('click', e => {
        if (!menu.contains(e.target) && !toggle.contains(e.target)) {
            menu.classList.remove('abierto');
            toggle.classList.remove('abierto');
            toggle.setAttribute('aria-expanded', false);
        }
    });

    // Cerrar el menú al pulsar un enlace (navegación)
    menu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            menu.classList.remove('abierto');
            toggle.classList.remove('abierto');
        });
    });

    // Cerrar el menú si se amplía la ventana por encima del breakpoint
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            menu.classList.remove('abierto');
            toggle.classList.remove('abierto');
        }
    });
});
