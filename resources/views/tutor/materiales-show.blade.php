<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · {{ $material->titulo }}</title>
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
                <li><a href="{{ route('tutor.materiales.index') }}">← Material</a></li>
                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">{{ Auth::user()->name }}</span></li>
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

<div class="mat-hero">
    <div class="mat-hero-inner">
        <p class="mat-etiqueta">Material de Repaso</p>
        <h1>{{ $material->titulo }}</h1>
        <p class="mat-hero-sub">
            Prof. {{ $material->docente->user->name }} {{ $material->docente->user->apellidos }}
            · {{ $material->created_at->format('d/m/Y') }}
        </p>
    </div>
</div>

<main class="mat-main">

    <div class="mat-detalle">

        <div class="mat-meta">
            @if($material->materia)<span class="badge badge-tipo">{{ $material->materia }}</span>@endif
            @if($material->tema)<span class="badge badge-tipo">{{ $material->tema }}</span>@endif
            <span class="badge badge-tipo">{{ $material->tipo_contenido === 'archivo' ? 'Archivo' : 'URL externa' }}</span>
        </div>

        @if($material->descripcion)
            <p class="mat-descripcion">{{ $material->descripcion }}</p>
        @endif

        <hr class="mat-sep">

        @if($material->tipo_contenido === 'archivo')
            <div class="mat-dato">
                <div class="mat-dato-lbl">Archivo</div>
                <div class="mat-dato-val">
                    {{ $material->archivo_nombre_original }}
                    @if($material->tamañoLegible)
                        <span style="color:var(--texto-suave);font-size:.85rem">({{ $material->tamañoLegible }})</span>
                    @endif
                </div>
            </div>
            <div class="mat-acciones">
                <a href="{{ route('tutor.materiales.descargar', $material) }}" class="btn-descargar">Descargar archivo</a>
            </div>
        @else
            <div class="mat-dato">
                <div class="mat-dato-lbl">Enlace</div>
                <div class="mat-dato-val">
                    <a href="{{ $material->url_externa }}" target="_blank">{{ $material->url_externa }}</a>
                </div>
            </div>
            <div class="mat-acciones">
                <a href="{{ $material->url_externa }}" target="_blank" class="btn-enlace">Abrir enlace</a>
            </div>
        @endif

    </div>

</main>

<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script>
document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await fetch('/api/logout', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    window.location.href = '{{ route("login") }}';
});
</script>
</body>
</html>
