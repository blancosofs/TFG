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
                <li><a href="{{ route('tutor.faltas') }}">Faltas</a></li>
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li><a href="{{ route('tutor.materiales.index') }}">← Material</a></li>
                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">{{ auth()->user()->name }} {{ auth()->user()->apellidos }}</span></li>
                        <li class="dropdown-rol">Tutor legal</li>
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

<div id="mat-data" data-id="{{ $id }}" style="display:none"></div>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/tutor-materiales-show.js') }}"></script>
</body>
</html>
