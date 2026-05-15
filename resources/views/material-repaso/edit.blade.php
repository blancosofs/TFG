<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Editar Material</title>
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
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li><a href="{{ route('material-repaso.index') }}">← Material</a></li>
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
        <p class="mat-etiqueta">Material de Repaso</p>
        <h1>Editar Material</h1>
        <p class="mat-hero-sub" id="hero-sub">Cargando...</p>
    </div>
</div>

<main class="mat-main">
<div class="mat-form-card">

    <!-- El JS muestra aquí los errores de validación -->
    <div id="alert-errores" style="display:none"></div>

    <form id="form-editar">

        <div class="fgroup">
            <label class="flabel">Título *</label>
            <input type="text" id="titulo" name="titulo" class="finput" required>
        </div>

        <div class="fgroup">
            <label class="flabel">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="finput" rows="3"></textarea>
        </div>

        <!-- El JS muestra una u otra sección según el tipo del material -->
        <div class="fgroup" id="seccionArchivoInfo" style="display:none">
            <label class="flabel">Archivo actual</label>
            <div class="info-actual" id="info-archivo">
                <small>Para reemplazar el archivo, elimina este material y crea uno nuevo.</small>
            </div>
        </div>

        <div class="fgroup" id="seccionUrl" style="display:none">
            <label class="flabel">URL externa</label>
            <input type="url" id="url_externa" name="url_externa" class="finput">
        </div>

        <div class="frow">
            <div class="fgroup">
                <label class="flabel">Materia</label>
                <input type="text" id="materia" name="materia" class="finput">
            </div>
            <div class="fgroup">
                <label class="flabel">Tema</label>
                <input type="text" id="tema" name="tema" class="finput">
            </div>
        </div>

        <div class="fgroup">
            <label class="flabel">Tutores destinatarios</label>
            <!-- El JS rellena este bloque con los checkboxes de tutores -->
            <div class="tutores-scroll" id="tutores-lista">
                <div style="color:var(--texto-suave);font-size:.9rem;padding:.5rem">Cargando tutores...</div>
            </div>
        </div>

        <div class="fgroup">
            <div class="check-publicar">
                <input type="checkbox" id="chk-publicado" name="publicado">
                <label for="chk-publicado">Publicado</label>
            </div>
        </div>

        <div class="form-acciones">
            <button type="submit" class="btn-submit" id="btn-submit">Guardar cambios</button>
            <a href="{{ route('material-repaso.index') }}" class="btn-cancelar">Cancelar</a>
        </div>

    </form>

</div>
</main>

<div class="mat-toast" id="toast"></div>

<div id="mat-data" data-id="{{ $id }}" style="display:none"></div>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/material-repaso-edit.js') }}"></script>
</body>
</html>
