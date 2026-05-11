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
                <li><a href="{{ route('calendario') }}">Mi Horario</a></li>
                <li><a href="{{ route('pasarLista') }}">Pasar Lista</a></li>
                <li><a href="{{ route('tablon') }}">Tablón</a></li>
                <li class="activo"><a href="{{ route('material-repaso.index') }}">Material</a></li>
                <li><a href="{{ route('perfil') }}">Mi Perfil</a></li>
                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">{{ Auth::user()->name }}</span></li>
                        <li class="dropdown-rol"><span id="nav-rol">Docente</span></li>
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

    @if(session('success'))
        <div class="flash-ok">{{ session('success') }}</div>
    @endif

    <div class="mat-cabecera">
        <h2>Mis materiales</h2>
        <a href="{{ route('material-repaso.create') }}" class="btn-crear">+ Nuevo material</a>
    </div>

    @if($materiales->count())
        <table class="mat-tabla">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Materia</th>
                    <th>Tutores</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materiales as $m)
                <tr>
                    <td>
                        <strong>{{ $m->titulo }}</strong>
                        @if($m->tema)<br><small>{{ $m->tema }}</small>@endif
                    </td>
                    <td><span class="badge badge-tipo">{{ $m->tipo_contenido === 'archivo' ? 'Archivo' : 'URL' }}</span></td>
                    <td>{{ $m->materia ?? '—' }}</td>
                    <td><span class="badge badge-num">{{ $m->tutores->count() }}</span></td>
                    <td>
                        @if($m->publicado)
                            <span class="badge badge-pub">Publicado</span>
                        @else
                            <span class="badge badge-bor">Borrador</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('material-repaso.show', $m) }}" class="btn-accion btn-ver">Ver</a>
                        <a href="{{ route('material-repaso.edit', $m) }}" class="btn-accion btn-editar-accion">Editar</a>
                        <form action="{{ route('material-repaso.destroy', $m) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este material?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mat-paginacion">{{ $materiales->links() }}</div>
    @else
        <div class="mat-vacio">
            <p>No has subido ningún material aún.</p>
            <a href="{{ route('material-repaso.create') }}" class="btn-crear">Crear el primero</a>
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
