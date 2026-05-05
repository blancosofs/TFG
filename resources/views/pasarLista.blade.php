{{-- resources/views/pasarLista.blade.php --}}
@extends('layouts.app')

@section('title', 'Edunoly · Pasar Lista')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosPasarLista.css') }}">
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
                <li><a href="{{ route('perfil') }}">Mi Perfil</a></li>
                <li><a href="{{ route('calendario') }}">Mi Horario</a></li>
                <li class="activo"><a href="{{ route('pasarLista') }}">Ausencias</a></li>
                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre">
                            <span id="nav-nombre">{{ auth()->user()->name ?? 'Docente' }}</span>
                        </li>
                        <li class="dropdown-rol">Docente</li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('perfil') }}">👤 Mi perfil</a></li>
                        <li><a href="{{ route('config') }}">⚙️ Configuración</a></li>
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
<div class="lista-hero">
    <div class="lista-hero-inner">
        <div>
            <p class="etiqueta">Control de asistencia</p>
            <h1>Ausencias</h1>
            <p class="hero-sub">Registra la asistencia de tus alumnos por clase y fecha.</p>
        </div>
        <div class="stats-row">
            <div class="stat-chip">
                <div class="stat-chip-num" id="stat-presentes">0</div>
                <div class="stat-chip-label">Presentes</div>
            </div>
            <div class="stat-chip stat-chip-falta">
                <div class="stat-chip-num" id="stat-ausentes">0</div>
                <div class="stat-chip-label">Ausentes</div>
            </div>
            <div class="stat-chip stat-chip-retraso">
                <div class="stat-chip-num" id="stat-retrasos">0</div>
                <div class="stat-chip-label">Retrasos</div>
            </div>
        </div>
    </div>
</div>

{{-- ── FILTROS ── --}}
<div class="filtros-wrap">
    <div class="filtros">

        <div class="filtro-grupo">
            <label class="filtro-label">Fecha</label>
            <input class="filtro-input" type="date" id="filtro-fecha">
        </div>

        <div class="filtro-grupo">
            <label class="filtro-label">Clase</label>
            <select class="filtro-input" id="filtro-clase" onchange="cargarAlumnos()">
                <option value="">Seleccionar clase…</option>
                @foreach ($clases ?? [] as $clase)
                    <option value="{{ $clase->id }}">{{ $clase->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="filtro-grupo">
            <label class="filtro-label">Asignatura</label>
            <select class="filtro-input" id="filtro-asignatura">
                <option value="">Seleccionar asignatura…</option>
                @foreach ($asignaturas ?? [] as $asignatura)
                    <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="filtro-acciones">
            <button class="btn-todos" onclick="marcarTodos('presente')">✅ Todos presentes</button>
        </div>

    </div>
</div>

{{-- ── LISTA DE ALUMNOS ── --}}
<div class="lista-layout">

    {{-- Aviso si no hay clase seleccionada --}}
    <div id="aviso-seleccionar" class="aviso-info">
        <span class="aviso-ico">📋</span>
        <p>Selecciona una clase para comenzar a pasar lista.</p>
    </div>

    {{-- Tabla de alumnos --}}
    <div id="lista-alumnos" style="display:none">

        <div class="lista-header">
            <div class="lista-info">
                <span id="lista-clase-nombre" class="lista-clase">—</span>
                <span id="lista-fecha-texto" class="lista-fecha">—</span>
            </div>
            <div class="lista-acciones-top">
                <input class="buscador-lista" type="text" placeholder="🔍 Buscar alumno…"
                       id="buscador-lista" oninput="filtrarAlumnos()">
            </div>
        </div>

        {{-- Tarjetas de alumnos --}}
        <div class="alumnos-grid" id="alumnos-grid"></div>

        {{-- Botón guardar --}}
        <div class="guardar-wrap">
            <div id="resumen-texto" class="resumen-texto"></div>
            <button class="btn-guardar-lista" id="btn-guardar" onclick="guardarLista()">
                💾 Guardar lista
            </button>
        </div>

    </div>

</div>

{{-- ══ MODAL: Añadir nota a un alumno ══ --}}
<div class="modal-overlay" id="modal-nota">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-titulo">📝 Añadir observación</div>
            <button class="modal-cerrar" onclick="cerrarModal()">✕</button>
        </div>
        <div class="modal-alumno-info" id="modal-alumno-nombre"></div>
        <div class="fgroup">
            <label class="flabel">Estado de asistencia</label>
            <div class="estado-selector" id="modal-estado-selector"></div>
        </div>
        <div class="fgroup" style="margin-top:14px">
            <label class="flabel">Observación (opcional)</label>
            <textarea class="finput-area" id="modal-nota-texto"
                      placeholder="Ej: El alumno llegó tarde por médico…" rows="3"></textarea>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-primary" onclick="guardarNota()">✓ Confirmar</button>
        </div>
    </div>
</div>

{{-- ══ MODAL: Confirmar guardar ══ --}}
<div class="modal-overlay" id="modal-confirmar">
    <div class="modal" style="max-width:420px;text-align:center">
        <div style="font-size:36px;margin-bottom:12px">📋</div>
        <h3 class="modal-titulo" style="justify-content:center;margin-bottom:8px">Confirmar lista</h3>
        <p id="confirm-resumen" style="font-size:13px;color:var(--texto-suave);line-height:1.7;margin-bottom:20px"></p>
        <div class="modal-actions" style="justify-content:center">
            <button class="btn-ghost" onclick="cerrarModalConfirm()">Revisar</button>
            <button class="btn-primary" onclick="confirmarGuardado()">💾 Guardar definitivamente</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

@endsection

@push('scripts')
    <script src="{{ asset('js/MenuSesion.js') }}"></script>
    <script src="{{ asset('js/menuResponsive.js') }}"></script>
    <script src="{{ asset('js/pasarLista.js') }}"></script>
    {{-- Token CSRF disponible para peticiones AJAX --}}
    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>
@endpush
