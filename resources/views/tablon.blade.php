{{-- resources/views/tablon.blade.php --}}
@extends('layouts.app')

@section('title', 'Edunoly · Tablón de Anuncios')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosTablon.css') }}">
@endpush

@push('scripts_head')
    <script src="{{ asset('js/temas.js') }}"></script>
@endpush

@section('content')

{{-- ── NAVEGACIÓN ── --}}
<header>
    <nav>
        <div class="barraNav">
            <ul class="menu" id="menuPrincipal">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>
                <li class="menu-toggle-li">
                    <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
                        <span></span><span></span><span></span>
                    </button>
                </li>
                <li><a href="#" id="nav-inicio">Inicio</a></li>
                <li class="activo"><a href="{{ route('tablon') }}">Tablón</a></li>
                <li><a href="#" id="nav-perfil-link">Mi Perfil</a></li>
                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre">
                            <span id="nav-nombre">{{ auth()->user()->name ?? 'Usuario' }}</span>
                        </li>
                        <li class="dropdown-rol">
                            <span id="nav-rol-label">{{ auth()->user()->rol ?? '—' }}</span>
                        </li>
                        <li class="dropdown-sep"></li>
                        <li><a href="#" id="nav-mi-perfil">👤 Mi perfil</a></li>
                        <li><a href="{{ route('configuracion') }}">⚙️ Configuración</a></li>
                        <li>
                            <a href="#" id="btn-logout"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

{{-- ── HERO ── --}}
<div class="tablon-hero">
    <div class="tablon-hero-inner">
        <div>
            <p class="etiqueta">Comunicación del centro</p>
            <h1>Tablón de Anuncios</h1>
            <p class="hero-sub">Mantente informado de todo lo que ocurre en el centro.</p>
        </div>
        <div id="hero-acciones" style="display:none">
            <button class="btn-primary" onclick="abrirModalPublicar()">
                ✏️ Publicar anuncio
            </button>
        </div>
    </div>
</div>

{{-- ── FILTROS ── --}}
<div class="filtros-wrap">
    <div class="filtros">
        <div class="chips-wrap">
            <button class="chip activo" onclick="filtrarCategoria('todos', this)">Todos</button>
            <button class="chip" onclick="filtrarCategoria('general', this)">📢 General</button>
            <button class="chip" onclick="filtrarCategoria('examen', this)">📝 Exámenes</button>
            <button class="chip" onclick="filtrarCategoria('evento', this)">🎉 Eventos</button>
            <button class="chip" onclick="filtrarCategoria('urgente', this)">🚨 Urgente</button>
            <button class="chip" onclick="filtrarCategoria('tarea', this)">📚 Tareas</button>
        </div>
        <div class="buscador-wrap">
            <span class="buscador-ico">🔍</span>
            <input class="buscador" type="text" id="buscador"
                   placeholder="Buscar anuncio…" oninput="filtrarBusqueda()">
        </div>
    </div>
</div>

{{-- ── LAYOUT ── --}}
<div class="tablon-layout">

    {{-- Anuncios --}}
    <main class="anuncios-col">
        <div id="anuncios-lista"></div>
        <div id="anuncios-vacio" class="vacio" style="display:none">
            <span>📭</span>
            <p>No hay anuncios que mostrar.</p>
        </div>
    </main>

    {{-- Sidebar --}}
    <aside class="tablon-aside">

        <div class="aside-card">
            <h3 class="aside-titulo">📅 Próximos eventos</h3>
            <div id="proximos-lista">
                @forelse ($proximosEventos ?? [] as $evento)
                    <div class="evento-item">
                        <div class="evento-fecha">
                            <span class="evento-dia">{{ \Carbon\Carbon::parse($evento->fecha)->format('d') }}</span>
                            <span class="evento-mes">{{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('M') }}</span>
                        </div>
                        <div class="evento-info">
                            <div class="evento-nombre">{{ $evento->nombre }}</div>
                            <div class="evento-hora">{{ $evento->hora_inicio }} — {{ $evento->hora_fin }}</div>
                        </div>
                    </div>
                @empty
                    {{-- Datos de ejemplo si no hay eventos desde el controlador --}}
                    <div class="evento-item">
                        <div class="evento-fecha"><span class="evento-dia">15</span><span class="evento-mes">May</span></div>
                        <div class="evento-info">
                            <div class="evento-nombre">Examen Matemáticas 1ºA</div>
                            <div class="evento-hora">9:00 — 10:00</div>
                        </div>
                    </div>
                    <div class="evento-item">
                        <div class="evento-fecha"><span class="evento-dia">20</span><span class="evento-mes">May</span></div>
                        <div class="evento-info">
                            <div class="evento-nombre">Reunión de padres</div>
                            <div class="evento-hora">17:00 — 19:00</div>
                        </div>
                    </div>
                    <div class="evento-item">
                        <div class="evento-fecha"><span class="evento-dia">28</span><span class="evento-mes">May</span></div>
                        <div class="evento-info">
                            <div class="evento-nombre">Fin de trimestre</div>
                            <div class="evento-hora">Todo el día</div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="aside-card">
            <h3 class="aside-titulo">📊 Resumen</h3>
            <div class="aside-stats">
                <div class="aside-stat">
                    <span class="aside-num" id="stat-total">{{ $stats['total'] ?? 0 }}</span>
                    <span class="aside-lbl">Anuncios</span>
                </div>
                <div class="aside-stat">
                    <span class="aside-num rojo" id="stat-urgentes">{{ $stats['urgentes'] ?? 0 }}</span>
                    <span class="aside-lbl">Urgentes</span>
                </div>
                <div class="aside-stat">
                    <span class="aside-num" id="stat-hoy">{{ $stats['hoy'] ?? 0 }}</span>
                    <span class="aside-lbl">Nuevos hoy</span>
                </div>
            </div>
        </div>

    </aside>
</div>

{{-- ══ MODAL: Publicar anuncio ══ --}}
<div class="modal-overlay" id="modal-publicar">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-pub-titulo">✏️ Publicar anuncio</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-publicar')">✕</button>
        </div>
        <div id="alert-publicar"></div>
        <div class="modal-grid">
            <div class="fgroup full">
                <label class="flabel">Título *</label>
                <input class="finput" id="pub-titulo" type="text" placeholder="Título del anuncio">
            </div>
            <div class="fgroup">
                <label class="flabel">Categoría *</label>
                <select class="finput" id="pub-categoria">
                    <option value="general">📢 General</option>
                    <option value="examen">📝 Examen</option>
                    <option value="evento">🎉 Evento</option>
                    <option value="urgente">🚨 Urgente</option>
                    <option value="tarea">📚 Tarea</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Dirigido a</label>
                <select class="finput" id="pub-dirigido">
                    <option value="todos">Todos</option>
                    <option value="familias">Solo familias</option>
                    <option value="docentes">Solo docentes</option>
                </select>
            </div>
            <div class="fgroup full">
                <label class="flabel">Contenido *</label>
                <textarea class="finput" id="pub-contenido" rows="5"
                          placeholder="Escribe el contenido del anuncio…"></textarea>
            </div>
            <div class="fgroup">
                <label class="flabel">Clase (opcional)</label>
                <select class="finput" id="pub-clase">
                    <option value="">Todas las clases</option>
                    @foreach ($clases ?? [] as $clase)
                        <option value="{{ $clase->id }}">{{ $clase->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Fecha límite (opcional)</label>
                <input class="finput" id="pub-fecha-limite" type="date">
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-publicar')">Cancelar</button>
            <button class="btn-primary" onclick="publicarAnuncio()">📢 Publicar</button>
        </div>
    </div>
</div>

{{-- ══ MODAL: Ver anuncio completo ══ --}}
<div class="modal-overlay" id="modal-ver">
    <div class="modal modal-grande">
        <div class="modal-head">
            <span id="ver-badge" class="cat-badge"></span>
            <button class="modal-cerrar" onclick="cerrarModal('modal-ver')">✕</button>
        </div>
        <h2 id="ver-titulo" class="ver-titulo"></h2>
        <div id="ver-meta" class="ver-meta"></div>
        <div id="ver-contenido" class="ver-contenido"></div>
        <div id="ver-footer" class="ver-footer"></div>

        {{-- ── COMENTARIOS ── --}}
        <div class="comentarios-seccion">
            <h4 class="comentarios-titulo">
                💬 Comentarios
                <span id="comentarios-count" class="comentarios-count">0</span>
            </h4>

            {{-- Lista de comentarios --}}
            <div id="comentarios-lista"></div>

            {{-- Formulario nuevo comentario --}}
            <div class="nuevo-comentario">
                <div class="nuevo-comentario-avatar" id="nuevo-avatar">—</div>
                <div class="nuevo-comentario-form">
                    <textarea class="comentario-input" id="comentario-texto"
                              placeholder="Escribe un comentario…" rows="2"></textarea>
                    <div class="comentario-acciones">
                        <span class="comentario-aviso">Los comentarios son visibles para docentes y familias del centro.</span>
                        <button class="btn-comentar" onclick="enviarComentario()">Enviar ›</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL: Confirmar eliminar ══ --}}
<div class="modal-overlay" id="modal-eliminar">
    <div class="modal" style="max-width:380px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">🗑️</div>
        <h3 style="font-size:16px;font-weight:700;color:var(--texto);margin-bottom:8px">¿Eliminar anuncio?</h3>
        <p style="font-size:13px;color:var(--texto-suave)">Esta acción no se puede deshacer.</p>
        <div class="modal-actions" style="justify-content:center;margin-top:20px">
            <button class="btn-ghost" onclick="cerrarModal('modal-eliminar')">Cancelar</button>
            <button class="btn-danger" id="btn-confirmar-eliminar">🗑️ Eliminar</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

@endsection

@push('scripts')
    <script src="{{ asset('js/MenuSesion.js') }}"></script>
    <script src="{{ asset('js/menuResponsive.js') }}"></script>
    <script src="{{ asset('js/tablon.js') }}"></script>
    {{-- Token CSRF disponible para peticiones AJAX --}}
    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>
@endpush
