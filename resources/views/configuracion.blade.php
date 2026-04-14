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
            <ul class="menu">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>
                <li><a href="{{ route('inicio') }}">Inicio</a></li>
                <li><a href="{{ route('contacto') }}">Contacto</a></li>
                <li class="activo"><a href="{{ route('configuracion') }}">Configuración</a></li>
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

    <!-- Footer -->
    <div class="config-footer">
        <button class="btn-cancelar" onclick="window.history.back()">Cancelar</button>
        <button class="btn-guardar" onclick="guardarTodo()">Guardar cambios</button>
    </div>

</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/temas.js') }}"></script>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script>
/* ── Marca el tema activo al cargar ── */
document.addEventListener('DOMContentLoaded', () => {
    const actual = temaActual();
    document.querySelectorAll('.tema-card').forEach(card => {
        card.classList.toggle('activo', card.dataset.tema === actual);
    });

    const opts = cargarOpciones();
    document.getElementById('opt-animaciones').checked  = opts.animaciones || false;
    document.getElementById('opt-contraste').checked    = opts.contraste   || false;
    document.getElementById('opt-recordatorio').checked = opts.recordatorio !== false;
    document.getElementById('opt-cambios').checked      = opts.cambios     !== false;
    document.getElementById('opt-fuente').value         = opts.fuente      || 'normal';

    aplicarOpciones(opts);
});

/* ── Tema ── */
function seleccionarTema(nombre, el) {
    document.querySelectorAll('.tema-card').forEach(c => c.classList.remove('activo'));
    el.classList.add('activo');
    aplicarTema(nombre);
}

/* ── Opciones ── */
function guardarOpcion(clave, valor) {
    const opts = cargarOpciones();
    opts[clave] = valor;
    localStorage.setItem('edunoly-config', JSON.stringify(opts));
    aplicarOpciones(opts);
}

function cargarOpciones() {
    try { return JSON.parse(localStorage.getItem('edunoly-config') || '{}'); }
    catch { return {}; }
}

function aplicarOpciones(opts) {
    document.documentElement.style.setProperty(
        '--duracion-animacion', opts.animaciones ? '0ms' : '800ms'
    );
    const tamanos = { normal: '14px', grande: '16px', 'muy-grande': '18px' };
    document.documentElement.style.fontSize = tamanos[opts.fuente || 'normal'];
}

/* ── Guardar todo ── */
function guardarTodo() { mostrarToast('✓ Configuración guardada'); }

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
</body>
</html>
