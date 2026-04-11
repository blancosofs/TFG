/* ══════════════════════════════════════════════════════════════
   Edunoly · PaginaInicio.js
   Animación de entrada del título y subtítulo
══════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {
    const titulo    = document.querySelector('.titulo');
    const subtitulo = document.querySelector('.subtitulo');

    if (titulo)    setTimeout(() => titulo.classList.add('visible'),    200);
    if (subtitulo) setTimeout(() => subtitulo.classList.add('visible'), 500);
});
