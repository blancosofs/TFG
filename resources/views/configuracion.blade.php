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
                <li class="derecha menuSesion"></li>
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

        <!-- Footer -->
    <div class="config-footer">
        <button class="btn-cancelar" onclick="cancelar()" data-i18n="config.cancelar">Cancelar</button>
        <button class="btn-guardar" onclick="guardarTodo()" data-i18n="config.guardar">Guardar cambios</button>
    </div>

</div>

<div class="toast" id="toast"></div>

<script src="temas.js"></script>
<script src="traducciones.js"></script>
<script src="MenuSesion.js"></script>
<script src="configuracion.js"></script>
<script src="menuResponsive.js"></script>
</body>
</html>
