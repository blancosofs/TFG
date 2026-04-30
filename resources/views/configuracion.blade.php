<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Configuración</title>

    <!-- Temas PRIMERO para evitar flash de color incorrecto -->
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosConfiguracion.css') }}">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav>
        <div class="barraNav">
            <ul class="menu" id="menuPrincipal">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>
                <li class="menu-toggle-li">
                    <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
                        <span></span><span></span><span></span>
                    </button>
                </li>
                <li><a href="{{ route('index') }}">Inicio</a></li>
                <li><a href="{{ route('contacto') }}">Contacto</a></li>
                <li class="activo"><a href="{{ route('config') }}">Configuración</a></li>
                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li><a href="{{ route('config') }}">⚙️ Configuración</a></li>
                        <li><a href="#">Mi perfil</a></li>
                        <li>
                            <li>                      
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar sesión</a>
                            </li>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- ── HERO ── -->
<div class="config-hero">
    <div class="config-hero-inner">
        <p class="etiqueta">Panel de usuario</p>
        <h1>Configuración</h1>
        <p>Personaliza la apariencia de Edunoly según tus preferencias.</p>
    </div>
</div>

<!-- ── CONTENIDO ── -->
<div class="config-layout">

    <!-- SECCIÓN: ESTILO VISUAL -->
    <div class="config-seccion">
        <div class="seccion-header">
            <div class="seccion-titulo" data-i18n="config.estiloVisual">🎨 Estilo visual</div>
            <div class="seccion-sub" data-i18n="config.estiloVisualSub">Elige el tema de color que se aplicará en todas las páginas de Edunoly.</div>
        </div>

        <div class="temas-grid" id="temas-grid">

            <div class="tema-card p-verde" data-tema="verde" onclick="seleccionarTema('verde', this)">
                <div class="tema-preview">
                    <div class="tema-preview-nav"></div>
                    <div class="tema-preview-body">
                        <div class="tema-preview-barra"></div>
                        <div class="tema-preview-barra corta"></div>
                        <div class="tema-preview-barra"></div>
                    </div>
                </div>
                <div class="tema-check">✓</div>
                <div class="tema-info">
                    <div class="tema-nombre">Verde Bosque</div>
                    <div class="tema-desc">Original · Por defecto</div>
                </div>
            </div>

            <div class="tema-card p-negro" data-tema="negro" onclick="seleccionarTema('negro', this)">
                <div class="tema-preview">
                    <div class="tema-preview-nav"></div>
                    <div class="tema-preview-body">
                        <div class="tema-preview-barra"></div>
                        <div class="tema-preview-barra corta"></div>
                        <div class="tema-preview-barra"></div>
                    </div>
                </div>
                <div class="tema-check">✓</div>
                <div class="tema-info">
                    <div class="tema-nombre">Noche Profunda</div>
                    <div class="tema-desc">Negro · Modo oscuro</div>
                </div>
            </div>

            <div class="tema-card p-blanco" data-tema="blanco" onclick="seleccionarTema('blanco', this)">
                <div class="tema-preview">
                    <div class="tema-preview-nav"></div>
                    <div class="tema-preview-body">
                        <div class="tema-preview-barra"></div>
                        <div class="tema-preview-barra corta"></div>
                        <div class="tema-preview-barra"></div>
                    </div>
                </div>
                <div class="tema-check" style="color:#333;background:#ccc">✓</div>
                <div class="tema-info">
                    <div class="tema-nombre">Blanco Polar</div>
                    <div class="tema-desc">Claro · Modo día</div>
                </div>
            </div>

            <div class="tema-card p-azul" data-tema="azul" onclick="seleccionarTema('azul', this)">
                <div class="tema-preview">
                    <div class="tema-preview-nav"></div>
                    <div class="tema-preview-body">
                        <div class="tema-preview-barra"></div>
                        <div class="tema-preview-barra corta"></div>
                        <div class="tema-preview-barra"></div>
                    </div>
                </div>
                <div class="tema-check">✓</div>
                <div class="tema-info">
                    <div class="tema-nombre">Azul Océano</div>
                    <div class="tema-desc">Azul · Profesional</div>
                </div>
            </div>

            <div class="tema-card p-purpura" data-tema="purpura" onclick="seleccionarTema('purpura', this)">
                <div class="tema-preview">
                    <div class="tema-preview-nav"></div>
                    <div class="tema-preview-body">
                        <div class="tema-preview-barra"></div>
                        <div class="tema-preview-barra corta"></div>
                        <div class="tema-preview-barra"></div>
                    </div>
                </div>
                <div class="tema-check">✓</div>
                <div class="tema-info">
                    <div class="tema-nombre">Púrpura Crepúsculo</div>
                    <div class="tema-desc">Violeta · Creativo</div>
                </div>
            </div>

            <div class="tema-card p-rojo" data-tema="rojo" onclick="seleccionarTema('rojo', this)">
                <div class="tema-preview">
                    <div class="tema-preview-nav"></div>
                    <div class="tema-preview-body">
                        <div class="tema-preview-barra"></div>
                        <div class="tema-preview-barra corta"></div>
                        <div class="tema-preview-barra"></div>
                    </div>
                </div>
                <div class="tema-check">✓</div>
                <div class="tema-info">
                    <div class="tema-nombre">Rojo Carmín</div>
                    <div class="tema-desc">Rojo · Intenso</div>
                </div>
            </div>

        </div>
    </div>

    <!-- SECCIÓN: ACCESIBILIDAD -->
    <div class="config-seccion">
        <div class="seccion-header">
            <div class="seccion-titulo" data-i18n="config.accesibilidad">♿ Accesibilidad</div>
            <div class="seccion-sub" data-i18n="config.accesibilidadSub">Ajustes para mejorar la experiencia de uso.</div>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.animaciones">Animaciones reducidas</div>
                <div class="opcion-desc">Desactiva las transiciones y animaciones para reducir el movimiento.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-animaciones" onchange="guardarOpcion('animaciones', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.fuente">Tamaño de fuente</div>
                <div class="opcion-desc">Ajusta el tamaño del texto en toda la plataforma. El cambio se aplica al guardar.</div>
            </div>
            <select class="opcion-select" id="opt-fuente" onchange="guardarOpcion('fuente', this.value)">
                <option value="normal">Normal (14px)</option>
                <option value="grande">Grande (18px)</option>
                <option value="muy-grande">Muy grande (24px)</option>
            </select>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.contraste">Alto contraste</div>
                <div class="opcion-desc">Aumenta el contraste de textos y bordes para mayor legibilidad.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-contraste" onchange="guardarOpcion('contraste', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.enlaces">Subrayar enlaces</div>
                <div class="opcion-desc">Muestra un subrayado en todos los enlaces para identificarlos más fácilmente.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-enlaces" onchange="guardarOpcion('enlaces', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

    </div>

    <!-- SECCIÓN: IDIOMA -->
    <div class="config-seccion">
        <div class="seccion-header">
            <div class="seccion-titulo" data-i18n="config.idioma">🌐 Idioma</div>
            <div class="seccion-sub" data-i18n="config.idiomaSub">Selecciona el idioma de la interfaz.</div>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.idiomaPlataforma">Idioma de la plataforma</div>
                <div class="opcion-desc">Cambia el idioma en el que se muestra la interfaz de Edunoly.</div>
            </div>
            <select class="opcion-select" id="opt-idioma" onchange="guardarOpcion('idioma', this.value)">
                <option value="es">🇪🇸 Español</option>
                <option value="en">🇬🇧 English</option>
                <option value="ca">🏴 Català</option>
                <option value="eu">🏴 Euskara</option>
                <option value="gl">🏴 Galego</option>
            </select>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.formatoFecha">Formato de fecha</div>
                <div class="opcion-desc">Elige cómo se muestran las fechas en la plataforma.</div>
            </div>
            <select class="opcion-select" id="opt-fecha" onchange="guardarOpcion('fecha', this.value)">
                <option value="dd/mm/yyyy">DD/MM/AAAA (31/12/2026)</option>
                <option value="mm/dd/yyyy">MM/DD/AAAA (12/31/2026)</option>
                <option value="yyyy-mm-dd">AAAA-MM-DD (2026-12-31)</option>
            </select>
        </div>

    </div>

    <!-- SECCIÓN: NOTIFICACIONES -->
    <div class="config-seccion">
        <div class="seccion-header">
            <div class="seccion-titulo" data-i18n="config.notificaciones">🔔 Notificaciones</div>
            <div class="seccion-sub" data-i18n="config.notificacionesSub">Decide qué notificaciones quieres recibir.</div>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.recordatorio">Recordatorio de clases</div>
                <div class="opcion-desc">Aviso 15 minutos antes de que empiece cada clase.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-recordatorio" onchange="guardarOpcion('recordatorio', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.cambios">Cambios en el horario</div>
                <div class="opcion-desc">Notificar cuando el coordinador modifique alguna clase.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-cambios" onchange="guardarOpcion('cambios', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.faltas">Nuevas faltas de asistencia</div>
                <div class="opcion-desc">Recibir aviso cuando se registre una falta de un alumno a tu cargo.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-faltas" onchange="guardarOpcion('faltas', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre" data-i18n="config.sonido">Sonido de notificaciones</div>
                <div class="opcion-desc">Reproducir un sonido al recibir una notificación nueva.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-sonido" onchange="guardarOpcion('sonido', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

    </div>

    <!-- Footer -->
    <div class="config-footer">
        <button class="btn-cancelar" onclick="cancelar()" data-i18n="config.cancelar">Cancelar</button>
        <button class="btn-guardar" onclick="guardarTodo()" data-i18n="config.guardar">Guardar cambios</button>
    </div>

</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/temas.js') }}"></script>
<script src="{{ asset('js/traducciones.js') }}"></script>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script>
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
    window.location.href = '{{ route('login') }}';
});
</script>
<script src="{{ asset('js/menuResponsive.js') }}"></script>
</body>
</html>
