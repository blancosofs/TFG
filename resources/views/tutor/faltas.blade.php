<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Faltas de Asistencia</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosFaltas.css') }}">
</head>
<body>

<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>
                <li><a href="{{ route('perfilFamilia') }}">Mi Perfil</a></li>
                <li class="activo"><a href="{{ route('tutor.faltas') }}">Faltas</a></li>
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li><a href="{{ route('tutor.materiales.index') }}">Material</a></li>
                <li class="derecha menuSesion">
                    <div class="fotoPerfil avatar-iniciales">{{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) . strtoupper(mb_substr(auth()->user()->apellidos ?? '', 0, 1)) }}</div>
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">{{ auth()->user()->name }} {{ auth()->user()->apellidos }}</span></li>
                        <li class="dropdown-rol"><span id="nav-rol">Tutor legal</span></li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('perfilFamilia') }}">👤 Mi perfil</a></li>
                        <li><a href="{{ route('configPerfiles') }}">⚙️ Configuración</a></li>
                        <li><a href="#" id="btn-logout">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="faltas-hero">
    <div class="faltas-hero-inner">
        <p class="faltas-etiqueta">Zona familias</p>
        <h1>Faltas de Asistencia</h1>
        <p class="faltas-sub">Consulta el registro de asistencia de tus hijos tutelados.</p>
    </div>
</div>

<main class="faltas-main">

    <div id="faltas-loading" class="faltas-loading">
        <div class="spinner"></div>
        <p>Cargando datos…</p>
    </div>

    <div id="faltas-vacio" class="faltas-vacio" style="display:none">
        <span>👨‍👩‍👧</span>
        <p>No tienes alumnos vinculados a tu perfil.</p>
    </div>

    <div id="hijos-lista"></div>

</main>

<!-- ══ MODAL: Justificar falta ══ -->
<div class="modal-overlay" id="modal-justificar">
    <div class="modal-justif">
        <div class="modal-justif-head">
            <h3>✏️ Justificar falta</h3>
            <button class="modal-cerrar" onclick="cerrarModalJustificar()">✕</button>
        </div>
        <div id="modal-justif-info" class="modal-justif-info"></div>
        <div class="fgroup-modal">
            <label class="flabel-modal">Motivo de la justificación *</label>
            <textarea id="justif-texto" class="justif-textarea"
                      rows="4" placeholder="Ej: Cita médica, enfermedad, motivo familiar…"></textarea>
        </div>
        <div id="alert-justif"></div>
        <div class="modal-justif-actions">
            <button class="btn-cancelar-modal" onclick="cerrarModalJustificar()">Cancelar</button>
            <button class="btn-guardar-modal" onclick="guardarJustificacion()">Guardar justificación</button>
        </div>
    </div>
</div>

<div id="toast" class="toast-faltas"></div>

<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/faltas-tutor.js') }}"></script>
</body>
</html>
