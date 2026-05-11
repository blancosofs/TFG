<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Nuevo Material</title>
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
        <h1>Nuevo Material</h1>
        <p class="mat-hero-sub">Sube un archivo o enlace y selecciona los tutores que podrán verlo.</p>
    </div>
</div>

<main class="mat-main">
<div class="mat-form-card">

    @if($errors->any())
    <div class="flash-err">
        <strong>Corrige los siguientes errores:</strong>
        <ul style="margin:.4rem 0 0 1.2rem">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('material-repaso.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="fgroup">
            <label class="flabel">Título *</label>
            <input type="text" name="titulo"
                   class="finput @error('titulo') is-invalid @enderror"
                   value="{{ old('titulo') }}" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="fgroup">
            <label class="flabel">Descripción</label>
            <textarea name="descripcion" class="finput" rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <div class="fgroup">
            <label class="flabel">Tipo de contenido *</label>
            <div class="radio-fila">
                <label class="radio-opcion">
                    <input type="radio" name="tipo_contenido" value="archivo" id="tipo_archivo"
                        {{ old('tipo_contenido', 'archivo') === 'archivo' ? 'checked' : '' }}>
                    Archivo
                </label>
                <label class="radio-opcion">
                    <input type="radio" name="tipo_contenido" value="url_externa" id="tipo_url"
                        {{ old('tipo_contenido') === 'url_externa' ? 'checked' : '' }}>
                    URL externa
                </label>
            </div>
        </div>

        <div class="fgroup" id="seccionArchivo">
            <label class="flabel">Archivo (máx. 50 MB) *</label>
            <input type="file" name="archivo"
                   class="finput @error('archivo') is-invalid @enderror"
                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.mp4,.zip">
            @error('archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="fgroup" id="seccionUrl" style="display:none">
            <label class="flabel">URL externa *</label>
            <input type="url" name="url_externa"
                   class="finput @error('url_externa') is-invalid @enderror"
                   value="{{ old('url_externa') }}" placeholder="https://...">
            @error('url_externa')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="frow">
            <div class="fgroup">
                <label class="flabel">Materia</label>
                <input type="text" name="materia" class="finput" value="{{ old('materia') }}">
            </div>
            <div class="fgroup">
                <label class="flabel">Tema</label>
                <input type="text" name="tema" class="finput" value="{{ old('tema') }}">
            </div>
        </div>

        <div class="fgroup">
            <label class="flabel">Tutores destinatarios</label>
            <div class="tutores-scroll">
                @forelse($tutores as $tutor)
                <div class="tutor-item">
                    <input type="checkbox" name="tutores[]" value="{{ $tutor->id }}"
                           id="tutor_{{ $tutor->id }}"
                           {{ in_array($tutor->id, old('tutores', [])) ? 'checked' : '' }}>
                    <label for="tutor_{{ $tutor->id }}">
                        {{ $tutor->user->name }} {{ $tutor->user->apellidos }}
                    </label>
                </div>
                @empty
                <p style="color:var(--texto-suave);padding:.5rem;font-size:.9rem">No hay tutores registrados en este colegio.</p>
                @endforelse
            </div>
        </div>

        <div class="fgroup">
            <div class="check-publicar">
                <input type="checkbox" name="publicado" value="1" id="chk-publicado"
                       {{ old('publicado', '1') ? 'checked' : '' }}>
                <label for="chk-publicado">Publicar ahora</label>
            </div>
        </div>

        <div class="form-acciones">
            <button type="submit" class="btn-submit">Crear Material</button>
            <a href="{{ route('material-repaso.index') }}" class="btn-cancelar">Cancelar</a>
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

const radios = document.querySelectorAll('input[name="tipo_contenido"]');
const secArchivo = document.getElementById('seccionArchivo');
const secUrl     = document.getElementById('seccionUrl');

function actualizarSecciones() {
    const val = document.querySelector('input[name="tipo_contenido"]:checked')?.value;
    secArchivo.style.display = val === 'archivo'     ? '' : 'none';
    secUrl.style.display     = val === 'url_externa' ? '' : 'none';
}

radios.forEach(r => r.addEventListener('change', actualizarSecciones));
actualizarSecciones();
</script>
</body>
</html>
