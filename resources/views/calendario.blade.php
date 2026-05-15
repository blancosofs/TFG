<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Mi Horario</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosCalendario.css') }}">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo">
                    <img src="{{ asset('img/logo.svg') }}" alt="Edunoly">
                </li>
                <li><a href="{{ route('perfil') }}">Mi Perfil</a></li>
                <li class="activo"><a href="{{ route('calendario') }}">Mi Horario</a></li>
                <li><a href="{{ route('pasarLista') }}">Pasar Lista</a></li>
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li><a href="{{ route('material-repaso.index') }}">Material</a></li>

                <li class="derecha menuSesion">
                    <div class="fotoPerfil avatar-iniciales">{{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) . strtoupper(mb_substr(auth()->user()->apellidos ?? '', 0, 1)) }}</div>
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">Docente</span></li>
                        <li class="dropdown-rol"><span id="nav-rol">Docente</span></li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('perfil') }}">👤 Mi perfil</a></li>
                        <li><a href="{{ route('configPerfiles') }}">⚙️ Configuración</a></li>
                        <li>
                            <a href="#" id="btn-logout"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- ── HERO ── -->
<div class="cal-hero">
    <div class="cal-hero-inner">
        <div>
            <p class="seccionEtiqueta verde">Mi espacio docente</p>
            <h2 class="cal-hero-titulo">Mi Horario</h2>
            <p class="cal-hero-sub" id="hero-sub">Cargando tu horario…</p>
        </div>
        <div class="profesor-chip">
            <div class="profesor-dot" id="profesor-dot"></div>
            <span id="profesor-nombre">…</span>
        </div>
    </div>
</div>

<!-- ── CUERPO ── -->
<div class="cal-layout">

    <!-- Sidebar -->
    <aside class="cal-sidebar">

        <div class="sidebar-card">
            <div class="mini-nav">
                <button class="mini-nav-btn" onclick="miniNav(-1)">&#8249;</button>
                <span id="mini-titulo" class="mini-titulo"></span>
                <button class="mini-nav-btn" onclick="miniNav(1)">&#8250;</button>
            </div>
            <div class="mini-grid" id="mini-grid"></div>
        </div>

        <div class="sidebar-card">
            <p class="s-label">Próximas clases</p>
            <div id="prox-lista"></div>
        </div>

    </aside>

    <!-- Calendario -->
    <main class="cal-main">

        <div class="cal-toolbar">
            <div class="toolbar-nav">
                <button class="btn-nav" onclick="mainNav(-1)">&#8249;</button>
                <h3 class="cal-titulo" id="cal-titulo"></h3>
                <button class="btn-nav" onclick="mainNav(1)">&#8250;</button>
            </div>
            <div class="toolbar-right">
                <button class="btn-hoy" onclick="irHoy()">Hoy</button>
                <div class="view-toggle">
                    <button class="vbtn active" onclick="setView('month', this)">Mes</button>
                    <button class="vbtn" onclick="setView('week', this)">Semana</button>
                </div>
            </div>
        </div>

        <div id="cal-vista">
            <div class="cargando">
                <div class="spinner"></div>
                <span>Cargando horario…</span>
            </div>
        </div>

    </main>
</div>

<!-- Modal detalle de clase -->
<div class="modal-overlay" id="modal-detalle">
    <div class="modal">
        <div class="modal-head">
            <h4 class="modal-titulo" id="det-titulo"></h4>
            <button class="modal-cerrar" onclick="cerrarModal('modal-detalle')">✕</button>
        </div>
        <div id="det-cuerpo"></div>
        <div class="modal-actions">
            <button class="btn-cancelar" onclick="cerrarModal('modal-detalle')">Cerrar</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/calendario.js') }}"></script>
</body>
</html>
