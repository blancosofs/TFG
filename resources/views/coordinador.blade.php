<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Panel Coordinador</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosCoordinador.css') }}">
</head>
<body>

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
                <li class="activo"><a href="{{ route('coordinador') }}">Mi centro</a></li>
                <li><a href="{{ route('config') }}">Configuración</a></li>

                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">Coordinador</span></li>
                        <li class="dropdown-rol">Coordinador</li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('config') }}">⚙️ Configuración</a></li>
                        <li><a href="#" id="btn-logout">Cerrar sesión</a></li>
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

<script src="{{ asset('js/temas.js') }}"></script>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/menuResponsive.js') }}"></script>
<script src="{{ asset('js/coordinador.js') }}"></script>
</body>
</html>