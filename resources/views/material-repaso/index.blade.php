<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Material de Repaso</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosMaterialRepaso.css') }}">
</head>
<body>

<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>
                <li><a href="{{ route('perfil') }}">Mi Perfil</a></li>
                <li><a href="{{ route('calendario') }}">Mi Horario</a></li>
                <li><a href="{{ route('pasarLista') }}">Pasar Lista</a></li>
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li class="activo"><a href="{{ route('material-repaso.index') }}">Material</a></li>
                <li class="derecha menuSesion">
                    <div class="fotoPerfil avatar-iniciales">{{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) . strtoupper(mb_substr(auth()->user()->apellidos ?? '', 0, 1)) }}</div>
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">{{ auth()->user()->name }} {{ auth()->user()->apellidos }}</span></li>
                        <li class="dropdown-rol">Docente</li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('perfil') }}">👤 Mi perfil</a></li>
                        <li><a href="{{ route('configPerfiles') }}">⚙️ Configuración</a></li>
                        <li><a href="#" id="btn-logout">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="mat-hero">
    <div class="mat-hero-inner">
        <p class="mat-etiqueta">Zona docente</p>
        <h1>Material de Repaso</h1>
        <p class="mat-hero-sub">Gestiona los materiales que compartes con las familias.</p>
    </div>
</div>

<main class="mat-main">

    <div id="flash-ok" class="flash-ok" style="display:none"></div>

    <div class="mat-cabecera">
        <h2>Mis materiales</h2>
        <a href="{{ route('material-repaso.create') }}" class="btn-crear">+ Nuevo material</a>
    </div>

    <!-- El JS rellena este bloque con la tabla o el aviso de vacío -->
    <div id="mat-contenido">
        <div id="mat-loading" style="padding:2rem;text-align:center;color:var(--texto-suave)">Cargando...</div>
    </div>

</main>

<!-- Modal confirmación eliminar -->
<div class="modal-overlay" id="modal-confirmar">
    <div class="modal-confirmar-box">
        <div style="font-size:40px;margin-bottom:12px">⚠️</div>
        <h3>¿Eliminar material?</h3>
        <p id="confirm-texto">Esta acción no se puede deshacer.</p>
        <div class="modal-btns">
            <button class="btn-modal-cancelar" id="btn-confirm-cancel">Cancelar</button>
            <button class="btn-modal-eliminar" id="btn-confirm-ok">🗑️ Eliminar</button>
        </div>
    </div>
</div>

<div class="mat-toast" id="toast"></div>

<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/material-repaso-index.js') }}"></script>
</body>
</html>
