<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Tablón de Anuncios</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosTablon.css') }}">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu" id="menuPrincipal">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>

                @php $user = auth()->user(); @endphp

                @if($user->docente)
                    {{-- Nav docente --}}
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="{{ route('calendario') }}">Mi Horario</a></li>
                    <li><a href="{{ route('pasarLista') }}">Pasar Lista</a></li>
                    <li class="activo"><a href="{{ route('tablon') }}">Tablón</a></li>
                    <li><a href="{{ route('material-repaso.index') }}">Material</a></li>
                    <li><a href="{{ route('perfil') }}">Mi Perfil</a></li>
                @elseif($user->coordinador)
                    {{-- Nav coordinador --}}
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="{{ route('coordinador') }}">Mi Centro</a></li>
                    <li class="activo"><a href="{{ route('tablon') }}">Tablón</a></li>
                                    <li><a href="{{ route('perfilCoordinador') }}">Mi Perfil</a></li>
                @elseif($user->tutor)
                    {{-- Nav tutor/familia --}}
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="{{ route('tutor.faltas') }}">Faltas</a></li>
                    <li class="activo"><a href="{{ route('tablon') }}">Tablón</a></li>
                    <li><a href="{{ route('tutor.materiales.index') }}">Material</a></li>
                    <li><a href="{{ route('perfilFamilia') }}">Mi Perfil</a></li>
                @else
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li class="activo"><a href="{{ route('tablon') }}">Tablón</a></li>
                @endif

                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre">
                            <span id="nav-nombre">{{ trim(($user->name ?? '') . ' ' . ($user->apellidos ?? '')) }}</span>
                        </li>
                        <li class="dropdown-rol">
                            <span id="nav-rol-label">
                                @if($user->docente) Docente
                                @elseif($user->coordinador) Coordinador
                                @elseif($user->tutor) Tutor legal
                                @else Usuario
                                @endif
                            </span>
                        </li>
                        <li class="dropdown-sep"></li>
                        @if($user->docente)
                            <li><a href="{{ route('perfil') }}">👤 Mi perfil</a></li>
                        @elseif($user->coordinador)
                            <li><a href="{{ route('perfilCoordinador') }}">👤 Mi perfil</a></li>
                        @elseif($user->tutor)
                            <li><a href="{{ route('perfil') }}">👤 Mi perfil</a></li>
                        @endif
                        <li><a href="{{ route('configPerfiles') }}">⚙️ Configuración</a></li>
                        <li><a href="#" id="btn-logout">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- ── HERO ── -->
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

<!-- ── FILTROS ── -->
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

<!-- ── LAYOUT ── -->
<div class="tablon-layout">

    <!-- Anuncios -->
    <main class="anuncios-col">
        <div id="anuncios-lista"></div>
        <div id="anuncios-vacio" class="vacio" style="display:none">
            <span>📭</span>
            <p>No hay anuncios que mostrar.</p>
        </div>
    </main>

    <!-- Sidebar -->
    <aside class="tablon-aside">

        <div class="aside-card">
            <h3 class="aside-titulo">📅 Próximos eventos</h3>
            <div id="proximos-lista">
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
            </div>
        </div>

        <div class="aside-card">
            <h3 class="aside-titulo">📊 Resumen</h3>
            <div class="aside-stats">
                <div class="aside-stat">
                    <span class="aside-num" id="stat-total">0</span>
                    <span class="aside-lbl">Anuncios</span>
                </div>
                <div class="aside-stat">
                    <span class="aside-num rojo" id="stat-urgentes">0</span>
                    <span class="aside-lbl">Urgentes</span>
                </div>
                <div class="aside-stat">
                    <span class="aside-num" id="stat-hoy">0</span>
                    <span class="aside-lbl">Nuevos hoy</span>
                </div>
            </div>
        </div>

    </aside>
</div>

<!-- ══ MODAL: Publicar anuncio ══ -->
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
                    <option value="General">📢 General</option>
                    <option value="Examen">📝 Examen</option>
                    <option value="Evento">🎉 Evento</option>
                    <option value="Urgente">🚨 Urgente</option>
                    <option value="Tarea">📚 Tarea</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Dirigido a</label>
                <select class="finput" id="pub-dirigido">
                    <option value="Todos">Todos</option>
                    <option value="Solo familias">Solo familias</option>
                    <option value="Solo docentes">Solo docentes</option>
                </select>
            </div>
            <div class="fgroup full">
                <label class="flabel">Contenido *</label>
                <textarea class="finput" id="pub-contenido" rows="5"
                          placeholder="Escribe el contenido del anuncio…"></textarea>
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

<!-- ══ MODAL: Ver anuncio completo ══ -->
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

        <!-- ── COMENTARIOS ── -->
        <div class="comentarios-seccion">
            <h4 class="comentarios-titulo">
                💬 Comentarios
                <span id="comentarios-count" class="comentarios-count">0</span>
            </h4>
            <div id="comentarios-lista"></div>
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

<!-- ══ MODAL: Confirmar eliminar ══ -->
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

<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/menuResponsive.js') }}"></script>
<script>
window.csrfToken = '{{ csrf_token() }}';

document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await fetch('/api/logout', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });
    window.location.href = '{{ route("login") }}';
});
</script>
<script src="{{ asset('js/tablon.js') }}"></script>
</body>
</html>
