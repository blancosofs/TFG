@extends('layouts.app')

@section('title', 'Edunoly · Panel Coordinador')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/EstilosCoordinador.css') }}">
@endpush

@section('content')

<!-- ── NAVEGACIÓN ── -->
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
                <li><a href="{{ route('perfilCoordinador') }}">Mi Perfil</a></li>
                <li class="activo"><a href="{{ route('coordinador') }}">Mi centro</a></li>
                <li><a href="{{ route('tablon') }}">Tablón anuncios</a></li>
                <li><a href="{{ route('configPerfiles') }}">Configuración</a></li>

                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">Coordinador</span></li>
                        <li class="dropdown-rol"><span id="nav-rol">Coordinador</span></li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('perfilCoordinador') }}">👤 Mi perfil</a></li>
                        <li><a href="{{ route('configPerfiles') }}">⚙️ Configuración</a></li>
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

<!-- ── HERO ── -->
<div class="coord-hero">
    <div class="coord-hero-inner">
        <div>
            <p class="etiqueta">Panel del coordinador</p>
            <h1 id="hero-colegio">Mi centro educativo</h1>
            <p class="hero-sub">Gestiona los alumnos, docentes y tutores de tu centro.</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:12px;align-items:flex-end">
            <div class="stats-row">
                <div class="stat-chip">
                    <div class="stat-chip-num" id="stat-alumnos">0</div>
                    <div class="stat-chip-label">Alumnos</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-num" id="stat-docentes">0</div>
                    <div class="stat-chip-label">Docentes</div>
                </div>
                <div class="stat-chip">
                    <div class="stat-chip-num" id="stat-tutores">0</div>
                    <div class="stat-chip-label">Tutores</div>
                </div>
            </div>
            <button class="btn-informe-centro" onclick="generarInformeCentro()" title="Descargar informe del centro en .txt">
                📄 Generar informe
            </button>
        </div>
    </div>
</div>

<!-- ── TABS ── -->
<div class="coord-tabs-wrap">
    <div class="coord-tabs">
        <button class="coord-tab activo" onclick="cambiarTab('alumnos', this)">
            🎒 Alumnos
        </button>
        <button class="coord-tab" onclick="cambiarTab('docentes', this)">
            👨‍🏫 Docentes
        </button>
        <button class="coord-tab" onclick="cambiarTab('tutores', this)">
            👨‍👩‍👧 Tutores legales
        </button>
        <button class="coord-tab" onclick="cambiarTab('cursos', this)">
            📚 Cursos
        </button>
        <button class="coord-tab" onclick="cambiarTab('clases', this)">
            🏫 Clases
        </button>
        <button class="coord-tab" onclick="cambiarTab('horarios', this)">
            📅 Horarios
        </button>
    </div>
</div>

<!-- ── CONTENIDO ── -->
<div class="coord-layout">

    <!-- ══ PANEL ALUMNOS ══ -->
    <div id="panel-alumnos" class="panel activo">

        <div class="panel-header">
            <div class="buscador-wrap">
                <span class="buscador-ico">🔍</span>
                <input class="buscador" id="buscar-alumnos" type="text"
                       placeholder="Buscar alumno…" oninput="filtrarLista('alumnos')">
            </div>
            <button class="btn-primary" onclick="abrirModal('modal-alumno', 'nuevo')">
                ➕ Nuevo alumno
            </button>
        </div>

        <div class="tabla-wrap">
            <table class="tabla" id="tabla-alumnos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Fecha nac.</th>
                        <th>Curso</th>
                        <th>Clase</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-alumnos">
                    <tr class="fila-vacia">
                        <td colspan="6">Cargando alumnos…</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══ PANEL DOCENTES ══ -->
    <div id="panel-docentes" class="panel">

        <div class="panel-header">
            <div class="buscador-wrap">
                <span class="buscador-ico">🔍</span>
                <input class="buscador" id="buscar-docentes" type="text"
                       placeholder="Buscar docente…" oninput="filtrarLista('docentes')">
            </div>
            <button class="btn-primary" onclick="abrirModal('modal-docente', 'nuevo')">
                ➕ Nuevo docente
            </button>
        </div>

        <div class="tabla-wrap">
            <table class="tabla" id="tabla-docentes">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Asignaturas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-docentes">
                    <tr class="fila-vacia">
                        <td colspan="6">Cargando docentes…</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══ PANEL TUTORES ══ -->
    <div id="panel-tutores" class="panel">

        <div class="panel-header">
            <div class="buscador-wrap">
                <span class="buscador-ico">🔍</span>
                <input class="buscador" id="buscar-tutores" type="text"
                       placeholder="Buscar tutor…" oninput="filtrarLista('tutores')">
            </div>
            <button class="btn-primary" onclick="abrirModal('modal-tutor', 'nuevo')">
                ➕ Nuevo tutor
            </button>
        </div>

        <div class="tabla-wrap">
            <table class="tabla" id="tabla-tutores">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Alumnos a cargo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-tutores">
                    <tr class="fila-vacia">
                        <td colspan="6">Cargando tutores…</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══ PANEL CURSOS ══ -->
    <div id="panel-cursos" class="panel">
        <div class="panel-header">
            <div class="buscador-wrap">
                <span class="buscador-ico">🔍</span>
                <input class="buscador" id="buscar-cursos" type="text"
                       placeholder="Buscar curso…" oninput="filtrarLista('cursos')">
            </div>
            <button class="btn-primary" onclick="abrirModal('modal-curso', 'nuevo')">
                ➕ Nuevo curso
            </button>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" id="tabla-cursos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nº Clases</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-cursos">
                    <tr class="fila-vacia"><td colspan="3">Cargando cursos…</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══ PANEL CLASES ══ -->
    <div id="panel-clases" class="panel">
        <div class="panel-header">
            <div class="buscador-wrap">
                <span class="buscador-ico">🔍</span>
                <input class="buscador" id="buscar-clases" type="text"
                       placeholder="Buscar clase…" oninput="filtrarLista('clases')">
            </div>
            <button class="btn-primary" onclick="abrirModal('modal-clase', 'nuevo')">
                ➕ Nueva clase
            </button>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" id="tabla-clases">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Curso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-clases">
                    <tr class="fila-vacia"><td colspan="3">Cargando clases…</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══ PANEL HORARIOS ══ -->
    <div id="panel-horarios" class="panel">
        <div class="panel-header">
            <div class="buscador-wrap">
                <span class="buscador-ico">🔍</span>
                <input class="buscador" id="buscar-horarios" type="text"
                       placeholder="Buscar docente o clase…" oninput="filtrarLista('horarios')">
            </div>
            <button class="btn-primary" onclick="abrirModal('modal-horario', 'nuevo')">
                ➕ Nuevo horario
            </button>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" id="tabla-horarios">
                <thead>
                    <tr>
                        <th>Docente</th>
                        <th>Clase</th>
                        <th>Asignatura</th>
                        <th>Día</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-horarios">
                    <tr class="fila-vacia">
                        <td colspan="7">Cargando horarios…</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════
    MODALES
══════════════════════════════════════════════ -->

<!-- Modal Alumno -->
<div class="modal-overlay" id="modal-alumno">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-alumno-titulo">➕ Nuevo alumno</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-alumno')">✕</button>
        </div>
        <div id="alert-alumno"></div>
        <div class="modal-grid">
            <div class="fgroup">
                <label class="flabel">Nombre *</label>
                <input class="finput" id="a-nombre" type="text" placeholder="Nombre del alumno">
            </div>
            <div class="fgroup">
                <label class="flabel">Apellidos *</label>
                <input class="finput" id="a-apellidos" type="text" placeholder="Apellidos">
            </div>
            <div class="fgroup">
                <label class="flabel">Fecha de nacimiento</label>
                <input class="finput" id="a-fnac" type="date">
            </div>
            <div class="fgroup">
                <label class="flabel">Curso *</label>
                <select class="finput" id="a-curso">
                    <option value="">Seleccionar curso…</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Clase *</label>
                <select class="finput" id="a-clase">
                    <option value="">Seleccionar clase…</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Tutor legal</label>
                <select class="finput" id="a-tutor">
                    <option value="">Sin tutor asignado</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Parentesco del tutor</label>
                <select class="finput" id="a-parentesco">
                    <option value="padre">Padre</option>
                    <option value="madre">Madre</option>
                    <option value="abuelo">Abuelo/a</option>
                    <option value="tio">Tío/a</option>
                    <option value="tutor_legal">Tutor legal</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-alumno')">Cancelar</button>
            <button class="btn-primary" onclick="guardarAlumno()">💾 Guardar alumno</button>
        </div>
    </div>
</div>

<!-- Modal Docente -->
<div class="modal-overlay" id="modal-docente">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-docente-titulo">➕ Nuevo docente</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-docente')">✕</button>
        </div>
        <div id="alert-docente"></div>
        <div class="modal-grid">
            <div class="fgroup">
                <label class="flabel">Nombre *</label>
                <input class="finput" id="d-nombre" type="text" placeholder="Nombre">
            </div>
            <div class="fgroup">
                <label class="flabel">Apellidos *</label>
                <input class="finput" id="d-apellidos" type="text" placeholder="Apellidos">
            </div>
            <div class="fgroup">
                <label class="flabel">Fecha de nacimiento</label>
                <input class="finput" id="d-fnac" type="date">
            </div>
            <div class="fgroup">
                <label class="flabel">Email (usuario de acceso) *</label>
                <input class="finput" id="d-email" type="email" placeholder="docente@centro.es">
            </div>
            <div class="fgroup">
                <label class="flabel">Teléfono</label>
                <input class="finput" id="d-telefono" type="tel" placeholder="600 000 000">
            </div>
            <div class="fgroup">
                <label class="flabel" for="d-asignaturas">Asignaturas <span style="font-weight: normal; font-size: 0.9em; color: #666;">(Opcional)</span></label>
                <input class="finput" id="d-asignaturas" name="asignaturas" type="text" placeholder="Ej: Matemáticas, Lengua, Física">
                <small style="color: #888; font-size: 0.85em; margin-top: 4px; display: block;">Separa las asignaturas usando comas.</small>
            </div>
            <div class="fgroup">
                <label class="flabel">Contraseña inicial *</label>
                <input class="finput" id="d-password" type="password" placeholder="Mín. 8 caracteres">
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-docente')">Cancelar</button>
            <button class="btn-primary" onclick="guardarDocente()">💾 Guardar docente</button>
        </div>
    </div>
</div>

<!-- Modal Tutor -->
<div class="modal-overlay" id="modal-tutor">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-tutor-titulo">➕ Nuevo tutor legal</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-tutor')">✕</button>
        </div>
        <div id="alert-tutor"></div>
        <div class="modal-grid">
            <div class="fgroup">
                <label class="flabel">Nombre *</label>
                <input class="finput" id="t-nombre" type="text" placeholder="Nombre">
            </div>
            <div class="fgroup">
                <label class="flabel">Apellidos *</label>
                <input class="finput" id="t-apellidos" type="text" placeholder="Apellidos">
            </div>
            <div class="fgroup">
                <label class="flabel">Email (usuario de acceso) *</label>
                <input class="finput" id="t-email" type="email" placeholder="familia@email.com">
            </div>
            <div class="fgroup">
                <label class="flabel">Teléfono *</label>
                <input class="finput" id="t-telefono" type="tel" placeholder="600 000 000">
            </div>
            <div class="fgroup">
                <label class="flabel">Contraseña inicial *</label>
                <input class="finput" id="t-password" type="password" placeholder="Mín. 8 caracteres">
            </div>
            <div class="fgroup">
                <label class="flabel">Alumno a cargo</label>
                <select class="finput" id="t-alumno">
                    <option value="">Sin alumno asignado aún</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Parentesco</label>
                <select class="finput" id="t-parentesco">
                    <option value="padre">Padre</option>
                    <option value="madre">Madre</option>
                    <option value="abuelo">Abuelo/a</option>
                    <option value="tio">Tío/a</option>
                    <option value="hermano">Hermano/a mayor</option>
                    <option value="tutor_legal">Tutor legal</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-tutor')">Cancelar</button>
            <button class="btn-primary" onclick="guardarTutor()">💾 Guardar tutor</button>
        </div>
    </div>
</div>

<!-- Modal Curso -->
<div class="modal-overlay" id="modal-curso">
    <div class="modal" style="max-width:420px">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-curso-titulo">➕ Nuevo curso</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-curso')">✕</button>
        </div>
        <div id="alert-curso"></div>
        <div class="modal-grid">
            <div class="fgroup" style="grid-column:1/-1">
                <label class="flabel">Nombre del curso *</label>
                <input class="finput" id="c-nombre" type="text" placeholder="Ej: 1º ESO, 2º Bachillerato…">
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-curso')">Cancelar</button>
            <button class="btn-primary" onclick="guardarCurso()">💾 Guardar curso</button>
        </div>
    </div>
</div>

<!-- Modal Clase -->
<div class="modal-overlay" id="modal-clase">
    <div class="modal" style="max-width:420px">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-clase-titulo">➕ Nueva clase</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-clase')">✕</button>
        </div>
        <div id="alert-clase"></div>
        <div class="modal-grid">
            <div class="fgroup">
                <label class="flabel">Curso *</label>
                <select class="finput" id="cl-curso">
                    <option value="">Seleccionar curso…</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Nombre de la clase *</label>
                <input class="finput" id="cl-nombre" type="text" placeholder="Ej: A, B, C…">
            </div>
            <div class="fgroup" style="grid-column:1/-1">
                <label class="flabel">Código de acceso al aula virtual <span style="font-weight:normal;color:var(--texto-suave)">(opcional)</span></label>
                <input class="finput" id="cl-codigo" type="text" maxlength="10" placeholder="Ej: ABC123">
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-clase')">Cancelar</button>
            <button class="btn-primary" onclick="guardarClase()">💾 Guardar clase</button>
        </div>
    </div>
</div>

<!-- Modal Horario -->
<div class="modal-overlay" id="modal-horario">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-horario-titulo">➕ Nuevo horario</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-horario')">✕</button>
        </div>
        <div id="alert-horario"></div>
        <div class="modal-grid">
            <div class="fgroup">
                <label class="flabel">Docente *</label>
                <select class="finput" id="h-docente">
                    <option value="">Seleccionar docente…</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Clase *</label>
                <select class="finput" id="h-clase">
                    <option value="">Seleccionar clase…</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Día de la semana *</label>
                <select class="finput" id="h-dia">
                    <option value="">Seleccionar día…</option>
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                </select>
            </div>
            <div class="fgroup">
                <label class="flabel">Asignatura</label>
                <input class="finput" id="h-asignatura" type="text" placeholder="Ej: Matemáticas, Lengua…" list="h-asignatura-list">
                <datalist id="h-asignatura-list"></datalist>
            </div>
            <div class="fgroup">
                <label class="flabel">Hora inicio *</label>
                <input class="finput" id="h-inicio" type="time">
            </div>
            <div class="fgroup">
                <label class="flabel">Hora fin *</label>
                <input class="finput" id="h-fin" type="time">
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-horario')">Cancelar</button>
            <button class="btn-primary" onclick="guardarHorario()">💾 Guardar horario</button>
        </div>
    </div>
</div>

<!-- Modal confirmación eliminar -->
<div class="modal-overlay" id="modal-confirmar">
    <div class="modal" style="max-width:400px">
        <div class="confirm-body">
            <div style="font-size:40px;margin-bottom:12px">⚠️</div>
            <h3>¿Eliminar registro?</h3>
            <p id="confirm-texto">Esta acción no se puede deshacer.</p>
        </div>
        <div class="modal-actions" style="justify-content:center;margin-top:20px">
            <button class="btn-ghost" onclick="cerrarModal('modal-confirmar')">Cancelar</button>
            <button class="btn-danger" id="btn-confirm-ok">🗑️ Eliminar</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

@endsection

@push('scripts')
    <script src="{{ asset('js/auditoria.js') }}"></script>
    <script src="{{ asset('js/MenuSesion.js') }}"></script>
    <script src="{{ asset('js/menuResponsive.js') }}"></script>
    <script src="{{ asset('js/coordinador.js') }}"></script>
@endpush
