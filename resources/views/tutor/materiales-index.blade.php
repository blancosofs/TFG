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
                <li><a href="{{ route('index') }}">Inicio</a></li>
                <li><a href="{{ route('tutor.faltas') }}">Faltas</a></li>
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li class="activo"><a href="{{ route('tutor.materiales.index') }}">Material</a></li>
                <li><a href="{{ route('perfilFamilia') }}">Mi Perfil</a></li>
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
        <p class="mat-etiqueta">Zona familias</p>
        <h1>Material de Repaso</h1>
        <p class="mat-hero-sub">Materiales compartidos por los docentes de tu colegio.</p>
    </div>
</div>

<main class="mat-main">

    @if($materiales->count())
        @foreach($materiales as $m)
        <div class="mat-card-lista">
            <div class="mat-card-info">
                <h3>{{ $m->titulo }}</h3>
                <div>
                    @if($m->materia)<span class="badge badge-tipo">{{ $m->materia }}</span>@endif
                    @if($m->tema)<span class="badge badge-tipo">{{ $m->tema }}</span>@endif
                </div>
                <p>Prof. {{ $m->docente->user->name }} {{ $m->docente->user->apellidos }}
                   · {{ $m->created_at->format('d/m/Y') }}</p>
                @if($m->descripcion)
                    <p>{{ Str::limit($m->descripcion, 100) }}</p>
                @endif
            </div>
            <div class="mat-card-acciones">
                <a href="{{ route('tutor.materiales.show', $m) }}" class="btn-accion btn-ver">Ver</a>
                @if($m->tipo_contenido === 'archivo')
                    <a href="{{ route('tutor.materiales.descargar', $m) }}" class="btn-descargar">Descargar</a>
                @else
                    <a href="{{ $m->url_externa }}" target="_blank" class="btn-enlace">Abrir</a>
                @endif
            </div>
        </div>
        @endforeach
        <div class="mat-paginacion">{{ $materiales->links() }}</div>
    @else
        <div class="mat-vacio">
            <p>No hay materiales disponibles para ti en este momento.</p>
        </div>
    @endif

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
