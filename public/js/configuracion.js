/* ══════════════════════════════════════════════════════════════
   Edunoly · configuracion.js
══════════════════════════════════════════════════════════════ */

/* ── Estado — config guardada vs temporal ── */
let configGuardada = {};
let configTemporal = {};
let temaGuardado   = '';
let temaTemporal   = '';

/* Valores por defecto */
const DEFAULTS = {
    animaciones:  false,
    fuente:       'normal',
    contraste:    false,
    enlaces:      false,
    idioma:       'es',
    fecha:        'dd/mm/yyyy',
};

document.addEventListener('DOMContentLoaded', () => {
    configGuardada = cargarOpciones();
    configTemporal = { ...configGuardada };
    temaGuardado   = temaActual();
    temaTemporal   = temaGuardado;

    // Marcar tema activo
    document.querySelectorAll('.tema-card').forEach(card => {
        card.classList.toggle('activo', card.dataset.tema === temaGuardado);
    });

    // Rellenar controles
    rellenarControles(configGuardada);

    // Aplicar opciones guardadas
    aplicarOpciones(configGuardada);

    // Aplicar idioma con pequeño delay para asegurar que traducciones.js está cargado
    setTimeout(() => {
        if (typeof aplicarIdioma === 'function') {
            aplicarIdioma(configGuardada.idioma || 'es');
        }
    }, 50);
});

/* ── Rellena todos los controles con una config dada ── */
function rellenarControles(opts) {
    const get = (k) => opts[k] !== undefined ? opts[k] : DEFAULTS[k];

    document.getElementById('opt-animaciones').checked  = get('animaciones');
    document.getElementById('opt-contraste').checked    = get('contraste');
    document.getElementById('opt-enlaces').checked      = get('enlaces');
    document.getElementById('opt-fuente').value         = get('fuente');
    document.getElementById('opt-idioma').value         = get('idioma');
    document.getElementById('opt-fecha').value          = get('fecha');
}

/* ── Seleccionar tema (vista previa, no guarda) ── */
function seleccionarTema(nombre, el) {
    document.querySelectorAll('.tema-card').forEach(c => c.classList.remove('activo'));
    el.classList.add('activo');
    temaTemporal = nombre;
    aplicarTema(nombre);
}

/* ── Cambiar opción (vista previa inmediata, no guarda) ── */
function guardarOpcion(clave, valor) {
    configTemporal[clave] = valor;
    aplicarOpciones(configTemporal);
    if (clave === 'idioma') {
        // El idioma se persiste de inmediato para que otras páginas lo lean al navegar
        const stored = JSON.parse(localStorage.getItem('edunoly-config') || '{}');
        stored.idioma = valor;
        localStorage.setItem('edunoly-config', JSON.stringify(stored));
        if (typeof aplicarIdioma === 'function') aplicarIdioma(valor);
    }
}

function cargarOpciones() {
    try { return JSON.parse(localStorage.getItem('edunoly-config') || '{}'); }
    catch { return {}; }
}

/* ── Aplica todas las opciones visualmente ── */
function aplicarOpciones(opts) {
    const get = (k) => opts[k] !== undefined ? opts[k] : DEFAULTS[k];

    /* ── Tamaño de fuente — clases en body ── */
    document.body.classList.remove('fuente-grande', 'fuente-muy-grande');
    if (get('fuente') === 'grande')     document.body.classList.add('fuente-grande');
    if (get('fuente') === 'muy-grande') document.body.classList.add('fuente-muy-grande');

    /* ── Animaciones reducidas ── */
    let estiloAnim = document.getElementById('estilo-animaciones');
    if (!estiloAnim) {
        estiloAnim = document.createElement('style');
        estiloAnim.id = 'estilo-animaciones';
        document.head.appendChild(estiloAnim);
    }
    estiloAnim.textContent = get('animaciones')
        ? `*, *::before, *::after { animation-duration: 0ms !important; transition-duration: 0ms !important; }`
        : '';

    /* ── Alto contraste — clase en body en vez de filter ── */
    if (get('contraste')) {
        document.body.classList.add('alto-contraste');
    } else {
        document.body.classList.remove('alto-contraste');
    }
    // Limpiar el estilo inyectado si existía antes
    const estiloContrasteViejo = document.getElementById('estilo-contraste');
    if (estiloContrasteViejo) estiloContrasteViejo.textContent = '';

    /* ── Subrayar enlaces ── */
    let estiloEnlaces = document.getElementById('estilo-enlaces');
    if (!estiloEnlaces) {
        estiloEnlaces = document.createElement('style');
        estiloEnlaces.id = 'estilo-enlaces';
        document.head.appendChild(estiloEnlaces);
    }
    estiloEnlaces.textContent = get('enlaces') ? `a { text-decoration: underline !important; }` : '';

    /* ── Idioma ── */
    if (typeof aplicarIdioma === 'function') aplicarIdioma(get('idioma'));
}

/* ── Guardar todo — escribe en localStorage ── */
function guardarTodo() {
    guardarTema(temaTemporal);
    localStorage.setItem('edunoly-config', JSON.stringify(configTemporal));
    temaGuardado   = temaTemporal;
    configGuardada = { ...configTemporal };
    aplicarOpciones(configGuardada);
    mostrarToast('✓ Configuración guardada correctamente');
}

/* ── Cancelar — revierte a lo guardado ── */
function cancelar() {
    temaTemporal   = temaGuardado;
    configTemporal = { ...configGuardada };

    aplicarTema(temaGuardado);
    document.querySelectorAll('.tema-card').forEach(card => {
        card.classList.toggle('activo', card.dataset.tema === temaGuardado);
    });

    rellenarControles(configGuardada);
    aplicarOpciones(configGuardada);
    mostrarToast('↩ Cambios descartados');
}

/* ── Toast ── */
function mostrarToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

/* ── Comprobar sesión — mostrar menú de perfil solo si está logueado ── */
(async () => {
    try {
        const r = await fetch('/api/me', { credentials: 'include' });
        const data = await r.json();
        if (data && data.id) {
            document.getElementById('menuSesionNav').style.display = 'flex';
        }
    } catch (e) {
        // Sin servidor o sin sesión — el menú permanece oculto
    }
})();

/* ── Logout ── */
document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await fetch('/api/logout', { method: 'POST', credentials: 'include' });
    window.location.href = 'login.html';
});
