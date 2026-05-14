<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Detalle de Material</title>
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
                <li><a href="{{ route('index') }}">Inicio</a></li>
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li><a href="{{ route('material-repaso.index') }}">← Material</a></li>
                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
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

<!-- El JS actualiza el hero con el título del material -->
<div class="mat-hero">
    <div class="mat-hero-inner">
        <p class="mat-etiqueta">Material de Repaso</p>
        <h1 id="hero-titulo">Cargando...</h1>
        <p class="mat-hero-sub" id="hero-sub"></p>
    </div>
</div>

<main class="mat-main">
    <!-- El JS rellena este bloque con los datos del material -->
    <div id="mat-detalle">
        <div style="padding:2rem;text-align:center;color:var(--texto-suave)">Cargando...</div>
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

<!-- El ID del material lo pasa el controlador para que el JS sepa qué pedir -->
<div id="mat-data" data-id="{{ $id }}" style="display:none"></div>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/material-repaso-show.js') }}"></script>
</body>
</html>
