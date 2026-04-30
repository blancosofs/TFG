/* ══════════════════════════════════════════════════════════════
   Estado — config guardada vs temporal
══════════════════════════════════════════════════════════════ */
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
    recordatorio: true,
    cambios:      true,
    faltas:       true,
    sonido:       false
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
});

/* ── Rellena todos los controles con una config dada ── */
function rellenarControles(opts) {
    const get = (k) => opts[k] !== undefined ? opts[k] : DEFAULTS[k];

    document.getElementById('opt-animaciones').checked  = get('animaciones');
    document.getElementById('opt-contraste').checked    = get('contraste');
    document.getElementById('opt-enlaces').checked      = get('enlaces');
    document.getElementById('opt-recordatorio').checked = get('recordatorio');
    document.getElementById('opt-cambios').checked      = get('cambios');
    document.getElementById('opt-faltas').checked       = get('faltas');
    document.getElementById('opt-sonido').checked       = get('sonido');
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
    // Idioma se aplica inmediatamente para ver el cambio al instante
    if (clave === 'idioma' && typeof aplicarIdioma === 'function') {
        aplicarIdioma(valor);
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

    /* ── Alto contraste ── */
    let estiloContraste = document.getElementById('estilo-contraste');
    if (!estiloContraste) {
        estiloContraste = document.createElement('style');
        estiloContraste.id = 'estilo-contraste';
        document.head.appendChild(estiloContraste);
    }
    estiloContraste.textContent = get('contraste')
        ? `body { filter: contrast(1.3); } .config-seccion, .tema-card { border-width: 2px !important; }`
        : '';

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

/* ── Logout ── */
document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await fetch('/api/logout', { method: 'POST', credentials: 'include' });
    window.location.href = 'login.html';
});