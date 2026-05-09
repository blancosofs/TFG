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
                <li><a href="{{ route('index') }}">Inicio</a></li>
                <li><a href="{{ route('material-repaso.index') }}">← Material</a></li>
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
        <p class="mat-etiqueta">Material de Repaso</p>
        <h1>Editar Material</h1>
        <p class="mat-hero-sub">{{ $material->titulo }}</p>
    </div>
</div>

<main class="mat-main">
<div class="mat-form-card">

    <form action="{{ route('material-repaso.update', $material) }}" method="POST">
        @csrf @method('PUT')

        <div class="fgroup">
            <label class="flabel">Título *</label>
            <input type="text" name="titulo"
                   class="finput @error('titulo') is-invalid @enderror"
                   value="{{ old('titulo', $material->titulo) }}" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="fgroup">
            <label class="flabel">Descripción</label>
            <textarea name="descripcion" class="finput" rows="3">{{ old('descripcion', $material->descripcion) }}</textarea>
        </div>

        @if($material->tipo_contenido === 'archivo')
            <div class="fgroup">
                <label class="flabel">Archivo actual</label>
                <div class="info-actual">
                    {{ $material->archivo_nombre_original }}
                    @if($material->tamañoLegible) ({{ $material->tamañoLegible }})@endif
                    <small>Para reemplazar el archivo, elimina este material y crea uno nuevo.</small>
                </div>
            </div>
        @else
            <div class="fgroup">
                <label class="flabel">URL externa</label>
                <input type="url" name="url_externa" class="finput"
                       value="{{ old('url_externa', $material->url_externa) }}">
            </div>
        @endif

        <div class="frow">
            <div class="fgroup">
                <label class="flabel">Materia</label>
                <input type="text" name="materia" class="finput" value="{{ old('materia', $material->materia) }}">
            </div>
            <div class="fgroup">
                <label class="flabel">Tema</label>
                <input type="text" name="tema" class="finput" value="{{ old('tema', $material->tema) }}">
            </div>
        </div>

        <div class="fgroup">
            <label class="flabel">Tutores destinatarios</label>
            <div class="tutores-scroll">
                @php $seleccionados = old('tutores', $material->tutores->pluck('id')->toArray()); @endphp
                @forelse($tutores as $tutor)
                <div class="tutor-item">
                    <input type="checkbox" name="tutores[]" value="{{ $tutor->id }}"
                           id="tutor_{{ $tutor->id }}"
                           {{ in_array($tutor->id, $seleccionados) ? 'checked' : '' }}>
                    <label for="tutor_{{ $tutor->id }}">
                        {{ $tutor->user->name }} {{ $tutor->user->apellidos }}
                    </label>
                </div>
                @empty
                <p style="color:var(--texto-suave);padding:.5rem;font-size:.9rem">No hay tutores registrados.</p>
                @endforelse
            </div>
        </div>

        <div class="fgroup">
            <div class="check-publicar">
                <input type="checkbox" name="publicado" value="1" id="chk-publicado"
                       {{ old('publicado', $material->publicado) ? 'checked' : '' }}>
                <label for="chk-publicado">Publicado</label>
            </div>
        </div>

        <div class="form-acciones">
            <button type="submit" class="btn-submit">Guardar cambios</button>
            <a href="{{ route('material-repaso.show', $material) }}" class="btn-cancelar">Cancelar</a>
        </div>
    </form>

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
