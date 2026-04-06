<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Configuración</title>

    <!-- 1. Temas PRIMERO para evitar flash de color incorrecto -->
    <script src="temas.js"></script>
    <link rel="stylesheet" href="temas.css">

    <!-- 2. CSS propio de esta página -->
    <style>
        /* ── Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: var(--fondo);
            color: var(--texto);
            min-height: 100vh;
        }

        /* ── Navegación ── */
        .barraNav {
            background: linear-gradient(to right, var(--nav-inicio), var(--nav-fin));
            padding: 0 40px;
        }

        .menu { display: flex; align-items: center; list-style: none; margin: 0 auto; padding: 0; max-width: 1800px; }
        .menu .derecha { margin-left: auto; }
        .menu li a { text-decoration: none; color: var(--nav-texto); padding: 20px; display: block; }
        .menu li a:hover, .menu li.activo > a { background-color: var(--nav-hover); }
        .logo { display: inline-block; padding: 20px; }
        .logo img { vertical-align: middle; }

        .menuSesion { position: relative; display: flex; align-items: center; margin-left: 20px; }
        .fotoPerfil { width: 30px; height: 30px; border-radius: 50%; cursor: pointer; object-fit: cover; display: block; }

        .dropdown {
            position: absolute !important; top: 100%; right: 0; margin-top: 8px;
            background: white; min-width: 170px; list-style: none; padding: 8px 0;
            border-radius: 8px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            opacity: 0; visibility: hidden; transform: translateY(-10px);
            transition: all 0.2s ease; z-index: 1000;
        }
        .dropdown.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .menu > li { position: relative; }
        .dropdown li a { display: block; padding: 10px 15px; text-decoration: none; color: #333; }
        .dropdown li a:hover { background-color: #f2f2f2; }

        /* ── Hero ── */
        .config-hero {
            background: linear-gradient(135deg, var(--fondo-oscuro) 0%, var(--fondo) 100%);
            border-bottom: 1px solid color-mix(in srgb, var(--texto) 10%, transparent);
            padding: 2.5rem 40px 2rem;
        }
        .config-hero-inner { max-width: 1100px; margin: 0 auto; }
        .etiqueta { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--acento); margin-bottom: .4rem; }
        .config-hero h1 { font-size: clamp(24px, 3vw, 32px); font-weight: 700; color: var(--texto); margin-bottom: .4rem; }
        .config-hero p  { font-size: 14px; color: var(--texto-suave); font-weight: normal; }

        /* ── Layout ── */
        .config-layout { max-width: 1100px; margin: 0 auto; padding: 32px 40px; display: flex; flex-direction: column; gap: 32px; }

        /* ── Secciones ── */
        .config-seccion {
            background: color-mix(in srgb, var(--texto) 4%, transparent);
            border: 1px solid color-mix(in srgb, var(--texto) 10%, transparent);
            border-radius: 16px; padding: 28px;
        }
        .seccion-header { margin-bottom: 24px; }
        .seccion-titulo { font-size: 16px; font-weight: 700; color: var(--texto); margin-bottom: .3rem; }
        .seccion-sub    { font-size: 13px; color: var(--texto-suave); font-weight: normal; }

        /* ── Grid de temas ── */
        .temas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; }

        .tema-card {
            border-radius: 12px;
            border: 2px solid color-mix(in srgb, var(--texto) 15%, transparent);
            overflow: hidden; cursor: pointer;
            transition: transform .2s, border-color .2s, box-shadow .2s;
            position: relative;
        }
        .tema-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.3); }
        .tema-card.activo {
            border-color: var(--acento);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--acento) 30%, transparent);
        }

        .tema-preview { height: 90px; position: relative; overflow: hidden; display: flex; flex-direction: column; }
        .tema-preview-nav  { height: 20px; width: 100%; flex-shrink: 0; }
        .tema-preview-body { flex: 1; padding: 8px; display: flex; flex-direction: column; gap: 4px; }
        .tema-preview-barra { height: 6px; border-radius: 3px; opacity: .7; }
        .tema-preview-barra.corta { width: 55%; }

        .tema-check {
            position: absolute; top: 8px; right: 8px;
            width: 22px; height: 22px; border-radius: 50%;
            background: var(--acento);
            display: none; align-items: center; justify-content: center;
            font-size: 12px; color: white; font-weight: 700;
            box-shadow: 0 2px 6px rgba(0,0,0,.3);
        }
        .tema-card.activo .tema-check { display: flex; }

        .tema-info   { padding: 10px 12px; background: rgba(0,0,0,.25); }
        .tema-nombre { font-size: 13px; font-weight: 600; color: #ffffff; }
        .tema-desc   { font-size: 11px; color: rgba(255,255,255,.55); margin-top: 2px; }

        /* Miniaturas fijas de cada tema — estos colores son intencionales
           (representan el aspecto del tema, no el tema activo)            */
        .p-verde   .tema-preview-nav   { background: linear-gradient(to right, #47ad79, #092422); }
        .p-verde   .tema-preview-body  { background: #104541; }
        .p-verde   .tema-preview-barra { background: #47ad79; }

        .p-negro   .tema-preview-nav   { background: linear-gradient(to right, #2a2a2a, #0a0a0a); }
        .p-negro   .tema-preview-body  { background: #1a1a1a; }
        .p-negro   .tema-preview-barra { background: #e0e0e0; }

        .p-blanco  .tema-preview-nav   { background: linear-gradient(to right, #f0f0f0, #d0d0d0); }
        .p-blanco  .tema-preview-body  { background: #ffffff; }
        .p-blanco  .tema-preview-barra { background: #333333; }

        .p-azul    .tema-preview-nav   { background: linear-gradient(to right, #1a6fa8, #0a2540); }
        .p-azul    .tema-preview-body  { background: #0d2f4a; }
        .p-azul    .tema-preview-barra { background: #4fb3e8; }

        .p-purpura .tema-preview-nav   { background: linear-gradient(to right, #7c3aed, #1e0a3c); }
        .p-purpura .tema-preview-body  { background: #1e0a3c; }
        .p-purpura .tema-preview-barra { background: #a78bfa; }

        .p-rojo    .tema-preview-nav   { background: linear-gradient(to right, #c0392b, #2c0a0a); }
        .p-rojo    .tema-preview-body  { background: #2c0a0a; }
        .p-rojo    .tema-preview-barra { background: #e74c3c; }

        /* ── Filas de opciones ── */
        .opcion-fila {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid color-mix(in srgb, var(--texto) 8%, transparent);
            gap: 16px; flex-wrap: wrap;
        }
        .opcion-fila:last-child { border-bottom: none; }
        .opcion-info  { flex: 1; min-width: 180px; }
        .opcion-nombre { font-size: 14px; font-weight: 500; color: var(--texto); }
        .opcion-desc   { font-size: 12px; color: var(--texto-suave); margin-top: 3px; }

        /* Toggle */
        .toggle { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; inset: 0; cursor: pointer;
            background: color-mix(in srgb, var(--texto) 20%, transparent);
            border-radius: 24px; transition: background .2s;
        }
        .toggle-slider::before {
            content: ''; position: absolute;
            width: 18px; height: 18px; left: 3px; top: 3px;
            background: white; border-radius: 50%; transition: transform .2s;
        }
        .toggle input:checked + .toggle-slider { background: var(--acento); }
        .toggle input:checked + .toggle-slider::before { transform: translateX(20px); }

        /* Select */
        .opcion-select {
            background: color-mix(in srgb, var(--texto) 8%, transparent);
            border: 1px solid color-mix(in srgb, var(--texto) 15%, transparent);
            border-radius: 8px; color: var(--texto); font-family: inherit;
            font-size: 13px; padding: 7px 12px; outline: none; cursor: pointer;
            transition: border-color .15s;
        }
        .opcion-select:focus { border-color: var(--acento); }
        .opcion-select option { background: var(--fondo-oscuro); color: var(--texto); }

        /* Botones */
        .config-footer { display: flex; justify-content: flex-end; gap: 10px; padding-top: 8px; }

        .btn-cancelar {
            padding: 10px 22px; border-radius: 8px;
            border: 1px solid color-mix(in srgb, var(--texto) 20%, transparent);
            background: color-mix(in srgb, var(--texto) 7%, transparent);
            color: var(--texto-suave); font-family: inherit; font-size: 14px;
            font-weight: 500; cursor: pointer; transition: all .15s;
        }
        .btn-cancelar:hover { color: var(--texto); border-color: color-mix(in srgb, var(--texto) 35%, transparent); }

        .btn-guardar {
            padding: 10px 28px; border-radius: 8px; border: none;
            background: var(--acento); color: var(--texto-sobre-claro);
            font-family: inherit; font-size: 14px; font-weight: 700;
            cursor: pointer; transition: background .15s, transform .12s;
        }
        .btn-guardar:hover { opacity: .9; transform: translateY(-1px); }

        /* Toast */
        .toast {
            position: fixed; bottom: 20px; right: 20px;
            background: var(--fondo-oscuro); color: var(--texto);
            border: 1px solid color-mix(in srgb, var(--texto) 15%, transparent);
            border-radius: 10px; padding: 12px 20px; font-size: 13px;
            font-weight: 500; z-index: 200;
            transform: translateY(60px); opacity: 0; transition: all .3s;
            box-shadow: 0 4px 20px rgba(0,0,0,.3);
        }
        .toast.show { transform: translateY(0); opacity: 1; }

        .divisor { height: 1px; background: color-mix(in srgb, var(--texto) 8%, transparent); margin: 4px 0; }

        @media (max-width: 700px) {
            .config-layout { padding: 20px 16px; }
            .config-hero   { padding: 1.5rem 20px 1.2rem; }
            .temas-grid    { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav>
        <div class="barraNav">
            <ul class="menu">
                <li class="logo"><img src="logo.svg" alt="Edunoly"></li>
                <li><a href="PaginaInicio.html">Inicio</a></li>
                <li><a href="PaginaContacto.html">Contacto</a></li>
                <li><a href="calendario.html">Mi Horario</a></li>
                <li class="activo"><a href="configuracion.html">Configuración</a></li>

                <li class="derecha menuSesion">
                    <img src="perfil.png" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li><a href="configuracion.html">⚙️ Configuración</a></li>
                        <li><a href="#">Mi perfil</a></li>
                        <li><a href="#" id="btn-logout">Cerrar sesión</a></li>
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
            <div class="seccion-titulo">🎨 Estilo visual</div>
            <div class="seccion-sub">Elige el tema de color que se aplicará en todas las páginas de Edunoly.</div>
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
            <div class="seccion-titulo">♿ Accesibilidad</div>
            <div class="seccion-sub">Ajustes para mejorar la experiencia de uso.</div>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre">Animaciones reducidas</div>
                <div class="opcion-desc">Desactiva las transiciones y animaciones de entrada para reducir el movimiento.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-animaciones" onchange="guardarOpcion('animaciones', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre">Tamaño de fuente</div>
                <div class="opcion-desc">Ajusta el tamaño base del texto en toda la plataforma.</div>
            </div>
            <select class="opcion-select" id="opt-fuente" onchange="guardarOpcion('fuente', this.value)">
                <option value="normal">Normal (14px)</option>
                <option value="grande">Grande (16px)</option>
                <option value="muy-grande">Muy grande (18px)</option>
            </select>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre">Alto contraste</div>
                <div class="opcion-desc">Aumenta el contraste de texto y bordes para mayor legibilidad.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-contraste" onchange="guardarOpcion('contraste', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>

    <!-- SECCIÓN: NOTIFICACIONES -->
    <div class="config-seccion">
        <div class="seccion-header">
            <div class="seccion-titulo">🔔 Notificaciones</div>
            <div class="seccion-sub">Decide qué notificaciones quieres recibir.</div>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre">Recordatorio de clases</div>
                <div class="opcion-desc">Aviso 15 minutos antes de que empiece cada clase.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-recordatorio" checked onchange="guardarOpcion('recordatorio', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div class="opcion-fila">
            <div class="opcion-info">
                <div class="opcion-nombre">Cambios en el horario</div>
                <div class="opcion-desc">Notificar cuando el coordinador modifique alguna clase.</div>
            </div>
            <label class="toggle">
                <input type="checkbox" id="opt-cambios" checked onchange="guardarOpcion('cambios', this.checked)">
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>

    <!-- Footer con botón guardar -->
    <div class="config-footer">
        <button class="btn-cancelar" onclick="window.history.back()">Cancelar</button>
        <button class="btn-guardar" onclick="guardarTodo()">Guardar cambios</button>
    </div>

</div>

<div class="toast" id="toast"></div>

<script src="temas.js"></script>
<script src="MenuSesion.js"></script>
<script>
/* ══════════════════════════════════════════════════════════════
   LÓGICA DE CONFIGURACIÓN
══════════════════════════════════════════════════════════════ */

// Marca el tema activo al cargar
document.addEventListener('DOMContentLoaded', () => {
    const actual = temaActual();
    document.querySelectorAll('.tema-card').forEach(card => {
        card.classList.toggle('activo', card.dataset.tema === actual);
    });

    // Restaurar opciones guardadas
    const opts = cargarOpciones();
    document.getElementById('opt-animaciones').checked = opts.animaciones || false;
    document.getElementById('opt-contraste').checked   = opts.contraste   || false;
    document.getElementById('opt-recordatorio').checked = opts.recordatorio !== false;
    document.getElementById('opt-cambios').checked      = opts.cambios     !== false;
    document.getElementById('opt-fuente').value         = opts.fuente      || 'normal';

    aplicarOpciones(opts);
});

/* ── Tema ── */
function seleccionarTema(nombre, el) {
    // Actualizar UI
    document.querySelectorAll('.tema-card').forEach(c => c.classList.remove('activo'));
    el.classList.add('activo');
    // Aplicar inmediatamente (vista previa en tiempo real)
    aplicarTema(nombre);
}

/* ── Opciones de accesibilidad ── */
function guardarOpcion(clave, valor) {
    const opts = cargarOpciones();
    opts[clave] = valor;
    localStorage.setItem('edunoly-config', JSON.stringify(opts));
    aplicarOpciones(opts);
}

function cargarOpciones() {
    try {
        return JSON.parse(localStorage.getItem('edunoly-config') || '{}');
    } catch { return {}; }
}

function aplicarOpciones(opts) {
    // Animaciones reducidas
    document.documentElement.style.setProperty(
        '--duracion-animacion', opts.animaciones ? '0ms' : '800ms'
    );

    // Tamaño de fuente
    const tamanos = { normal: '14px', grande: '16px', 'muy-grande': '18px' };
    document.documentElement.style.fontSize = tamanos[opts.fuente || 'normal'];

    // Alto contraste
    if (opts.contraste) {
        document.documentElement.style.setProperty('--opacidad-texto', '1');
        document.documentElement.style.filter = '';
    }
}

/* ── Guardar todo ── */
function guardarTodo() {
    // Las opciones ya se guardan en tiempo real con guardarOpcion()
    // El tema ya se guarda con seleccionarTema()
    // Este botón simplemente confirma
    mostrarToast('✓ Configuración guardada');
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
</script>
</body>
</html>