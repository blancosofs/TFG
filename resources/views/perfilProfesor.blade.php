<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Mi Perfil</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosPerfil.css') }}">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo">
                    <img src="{{ asset('img/logo.svg') }}" alt="Edunoly">
                </li>
                <li><a href="{{ route('index') }}">Inicio</a></li>
                <li><a href="{{ route('contacto') }}">Contacto</a></li>
                <li><a href="{{ route('calendario') }}">Mi Horario</a></li>
                <li class="activo"><a href="{{ route('perfil') }}">Mi Perfil</a></li>

                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">Docente</span></li>
                        <li class="dropdown-rol"><span id="nav-rol">Docente</span></li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('perfil') }}">👤 Mi perfil</a></li>
                        <li><a href="{{ route('config') }}">⚙️ Configuración</a></li>
                        <li><a href="#" id="btn-logout">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- ── HERO ── -->
<div class="perfil-hero">
    <div class="perfil-hero-inner">
        <p class="etiqueta verde">Mi espacio docente</p>
        <h1>Mi Perfil</h1>
        <p class="hero-sub">Consulta y gestiona tu información personal y profesional.</p>
    </div>
</div>

<!-- ── CONTENIDO ── -->
<div class="perfil-layout">

    <!-- ══ COLUMNA IZQUIERDA ══ -->
    <aside class="perfil-sidebar">

        <!-- Foto + datos principales -->
        <div class="card-perfil">
            <div class="foto-wrap">
                <img src="{{ asset('img/perfil.png') }}" alt="Foto de perfil" class="foto-grande" id="foto-preview">
                <label class="foto-btn" title="Cambiar foto">
                    📷
                    <input type="file" id="input-foto" accept="image/*" style="display:none" onchange="previsualizarFoto(this)">
                </label>
            </div>

            <div class="nombre-bloque">
                <h2 id="perfil-nombre-completo">Cargando…</h2>
                <span class="rol-badge">Docente</span>
            </div>

            <div class="info-rapida">
                <div class="info-rapida-item">
                    <span class="info-ico">🏫</span>
                    <span id="perfil-colegio">—</span>
                </div>
                <div class="info-rapida-item">
                    <span class="info-ico">📧</span>
                    <span id="perfil-email-corto">—</span>
                </div>
                <div class="info-rapida-item">
                    <span class="info-ico">📞</span>
                    <span id="perfil-telefono-corto">—</span>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="stats-perfil">
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-clases">—</span>
                    <span class="stat-lbl">Clases</span>
                </div>
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-asignaturas">—</span>
                    <span class="stat-lbl">Asignaturas</span>
                </div>
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-alumnos">—</span>
                    <span class="stat-lbl">Alumnos</span>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos -->
        <div class="card-links">
            <p class="card-section-titulo">Accesos rápidos</p>
            <a href="{{ route('calendario') }}" class="link-rapido">
                <span class="link-ico">📅</span>
                <span>Mi horario</span>
                <span class="link-arrow">›</span>
            </a>
            <a href="{{ route('config') }}" class="link-rapido">
                <span class="link-ico">⚙️</span>
                <span>Configuración</span>
                <span class="link-arrow">›</span>
            </a>
            <a href="{{ route('contacto') }}" class="link-rapido">
                <span class="link-ico">✉️</span>
                <span>Contacto</span>
                <span class="link-arrow">›</span>
            </a>
        </div>

    </aside>

    <!-- ══ COLUMNA DERECHA ══ -->
    <main class="perfil-main">

        <!-- ── Datos personales ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">👤 Datos personales</h3>
                    <p class="seccion-sub">Tu información básica en la plataforma.</p>
                </div>
                <button class="btn-editar" id="btn-editar-personal" onclick="toggleEditar('personal')">
                    ✏️ Editar
                </button>
            </div>

            <!-- Vista -->
            <div id="vista-personal" class="datos-grid">
                <div class="dato-item">
                    <span class="dato-lbl">Nombre</span>
                    <span class="dato-val" id="v-nombre">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Apellidos</span>
                    <span class="dato-val" id="v-apellidos">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Correo electrónico</span>
                    <span class="dato-val" id="v-email">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Teléfono</span>
                    <span class="dato-val" id="v-telefono">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Fecha de nacimiento</span>
                    <span class="dato-val" id="v-fnacimiento">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Usuario de acceso</span>
                    <span class="dato-val" id="v-usuario">—</span>
                </div>
            </div>

            <!-- Edición -->
            <div id="form-personal" class="form-edicion" style="display:none">
                <div class="form-edicion-grid">
                    <div class="fgroup">
                        <label class="flabel">Nombre</label>
                        <input class="finput" id="e-nombre" type="text">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Apellidos</label>
                        <input class="finput" id="e-apellidos" type="text">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Teléfono</label>
                        <input class="finput" id="e-telefono" type="tel">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Fecha de nacimiento</label>
                        <input class="finput" id="e-fnacimiento" type="date">
                    </div>
                </div>
                <div class="form-edicion-acciones">
                    <button class="btn-cancelar-edit" onclick="toggleEditar('personal')">Cancelar</button>
                    <button class="btn-guardar-edit" onclick="guardarPersonal()">💾 Guardar cambios</button>
                </div>
            </div>
        </div>

        <!-- ── Información profesional ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🎓 Información profesional</h3>
                    <p class="seccion-sub">Tu rol y asignaturas asignadas en el centro.</p>
                </div>
            </div>

            <div class="datos-grid">
                <div class="dato-item">
                    <span class="dato-lbl">Centro educativo</span>
                    <span class="dato-val" id="v-colegio">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Rol</span>
                    <span class="dato-val">
                        <span class="badge-rol">Docente</span>
                    </span>
                </div>
                <div class="dato-item full">
                    <span class="dato-lbl">Asignaturas que imparte</span>
                    <div class="tags-wrap" id="v-asignaturas">—</div>
                </div>
                <div class="dato-item full">
                    <span class="dato-lbl">Clases asignadas</span>
                    <div class="tags-wrap" id="v-clases">—</div>
                </div>
            </div>
        </div>

        <!-- ── Seguridad ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🔒 Seguridad</h3>
                    <p class="seccion-sub">Gestiona tu contraseña de acceso.</p>
                </div>
                <button class="btn-editar" id="btn-editar-pass" onclick="toggleEditar('pass')">
                    🔑 Cambiar contraseña
                </button>
            </div>

            <div id="vista-pass">
                <p class="pass-info">••••••••••••  <span class="pass-hint">Tu contraseña está cifrada y protegida.</span></p>
            </div>

            <div id="form-pass" class="form-edicion" style="display:none">
                <div class="form-edicion-grid">
                    <div class="fgroup full">
                        <label class="flabel">Contraseña actual</label>
                        <input class="finput" id="p-actual" type="password" placeholder="••••••••">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Nueva contraseña</label>
                        <input class="finput" id="p-nueva" type="password" placeholder="Mín. 8 caracteres">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Repetir nueva contraseña</label>
                        <input class="finput" id="p-repetir" type="password" placeholder="Repite la contraseña">
                    </div>
                </div>
                <div id="alert-pass"></div>
                <div class="form-edicion-acciones">
                    <button class="btn-cancelar-edit" onclick="toggleEditar('pass')">Cancelar</button>
                    <button class="btn-guardar-edit" onclick="guardarPassword()">🔒 Actualizar contraseña</button>
                </div>
            </div>
        </div>

        <!-- ── Actividad reciente ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🕐 Actividad reciente</h3>
                    <p class="seccion-sub">Últimas acciones registradas en tu cuenta.</p>
                </div>
            </div>
            <div id="actividad-lista">
                <div class="actividad-item">
                    <span class="actividad-ico">🔑</span>
                    <div class="actividad-info">
                        <span class="actividad-texto">Inicio de sesión</span>
                        <span class="actividad-fecha" id="ultimo-acceso">—</span>
                    </div>
                </div>
                <div class="actividad-item">
                    <span class="actividad-ico">📅</span>
                    <div class="actividad-info">
                        <span class="actividad-texto">Consulta de horario</span>
                        <span class="actividad-fecha">Hoy</span>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/temas.js') }}"></script>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script>
/* ══════════════════════════════════════════════════════════════
   CONFIG
══════════════════════════════════════════════════════════════ */
const API = '';

/* ══════════════════════════════════════════════════════════════
   API
══════════════════════════════════════════════════════════════ */
async function api(method, ruta, body) {
    try {
        const opts = { method, credentials: 'include', headers: { 'Content-Type': 'application/json' } };
        if (body) opts.body = JSON.stringify(body);
        const r = await fetch(API + ruta, opts);
        return await r.json();
    } catch (e) {
        return { error: 'Error de conexión.' };
    }
}

/* ══════════════════════════════════════════════════════════════
   ARRANQUE — comprueba sesión
══════════════════════════════════════════════════════════════ */
 (async () => {
     const data = await api('GET', '/api/me');
     if (!data || !data.id) { window.location.href = '/login'; return; }
     if (data.rol !== 'docente') { window.location.href = '/login'; return; }
     cargarPerfil(data);
})();


/* ══════════════════════════════════════════════════════════════
   CARGAR PERFIL
══════════════════════════════════════════════════════════════ */
async function cargarPerfil(usuario) {
    const nombreCompleto = `${usuario.nombre} ${usuario.apellidos}`;

    // Nav
    document.getElementById('nav-nombre').textContent = nombreCompleto;

    // Sidebar
    document.getElementById('perfil-nombre-completo').textContent = nombreCompleto;
    document.getElementById('perfil-email-corto').textContent     = usuario.email || '—';
    document.getElementById('perfil-telefono-corto').textContent  = usuario.telefono || '—';
    document.getElementById('perfil-colegio').textContent         = usuario.colegio || '—';

    // Último acceso
    if (usuario.ultimo_acceso) {
        document.getElementById('ultimo-acceso').textContent =
            new Date(usuario.ultimo_acceso).toLocaleString('es-ES');
    }

    // Datos personales
    document.getElementById('v-nombre').textContent      = usuario.nombre      || '—';
    document.getElementById('v-apellidos').textContent   = usuario.apellidos   || '—';
    document.getElementById('v-email').textContent       = usuario.email       || '—';
    document.getElementById('v-telefono').textContent    = usuario.telefono    || '—';
    document.getElementById('v-usuario').textContent     = usuario.email       || '—';
    document.getElementById('v-fnacimiento').textContent = usuario.fechaNacimiento
        ? new Date(usuario.fechaNacimiento).toLocaleDateString('es-ES')
        : '—';

    // Prellenar campos de edición
    set('e-nombre',      usuario.nombre      || '');
    set('e-apellidos',   usuario.apellidos   || '');
    set('e-telefono',    usuario.telefono    || '');
    set('e-fnacimiento', usuario.fechaNacimiento?.slice(0,10) || '');

    // Datos profesionales
    document.getElementById('v-colegio').textContent = usuario.colegio || '—';

    // Cargar asignaturas y clases desde la API
    const clases = await api('GET', `/api/clases?desde=2025-01-01&hasta=2099-12-31`);
    if (clases && !clases.error) {
        // Asignaturas únicas
        const asigs = [...new Set(clases.map(c => c.materia))];
        const gruposUnicos = [...new Set(clases.map(c => c.grupo).filter(Boolean))];

        document.getElementById('stat-clases').textContent      = gruposUnicos.length  || '—';
        document.getElementById('stat-asignaturas').textContent = asigs.length         || '—';

        document.getElementById('v-asignaturas').innerHTML = asigs.length
            ? asigs.map(a => `<span class="tag-item">${a}</span>`).join('')
            : '<span class="dato-val">—</span>';

        document.getElementById('v-clases').innerHTML = gruposUnicos.length
            ? gruposUnicos.map(g => `<span class="tag-item tag-clase">${g}</span>`).join('')
            : '<span class="dato-val">—</span>';

        // Contar alumnos aproximados (grupos × media)
        document.getElementById('stat-alumnos').textContent = gruposUnicos.length
            ? `~${gruposUnicos.length * 25}` : '—';
    }
}

/* ══════════════════════════════════════════════════════════════
   TOGGLE EDITAR
══════════════════════════════════════════════════════════════ */
function toggleEditar(seccion) {
    const vistas = { personal: ['vista-personal','form-personal'], pass: ['vista-pass','form-pass'] };
    const [vistaId, formId] = vistas[seccion];
    const forma = document.getElementById(formId);
    const vista = document.getElementById(vistaId);

    const editando = forma.style.display !== 'none';
    forma.style.display = editando ? 'none' : 'block';
    vista.style.display = editando ? 'grid' : 'none';

    const btn = document.getElementById(`btn-editar-${seccion === 'personal' ? 'personal' : 'pass'}`);
    btn.textContent = editando
        ? (seccion === 'personal' ? '✏️ Editar' : '🔑 Cambiar contraseña')
        : '✕ Cancelar';
}

/* ══════════════════════════════════════════════════════════════
   GUARDAR DATOS PERSONALES
══════════════════════════════════════════════════════════════ */
async function guardarPersonal() {
    const payload = {
        nombre:          v('e-nombre'),
        apellidos:       v('e-apellidos'),
        telefono:        v('e-telefono'),
        fechaNacimiento: v('e-fnacimiento') || null,
    };

    if (!payload.nombre || !payload.apellidos) {
        toast('⚠️ Nombre y apellidos son obligatorios.');
        return;
    }

    const data = await api('PUT', '/api/me/datos', payload);

    if (data.error) { toast('❌ ' + data.error); return; }

    // Actualizar vista
    document.getElementById('v-nombre').textContent    = payload.nombre;
    document.getElementById('v-apellidos').textContent = payload.apellidos;
    document.getElementById('v-telefono').textContent  = payload.telefono || '—';
    document.getElementById('v-fnacimiento').textContent = payload.fechaNacimiento
        ? new Date(payload.fechaNacimiento).toLocaleDateString('es-ES') : '—';

    document.getElementById('perfil-nombre-completo').textContent = `${payload.nombre} ${payload.apellidos}`;
    document.getElementById('nav-nombre').textContent             = `${payload.nombre} ${payload.apellidos}`;
    document.getElementById('perfil-telefono-corto').textContent  = payload.telefono || '—';

    toggleEditar('personal');
    toast('✓ Datos actualizados correctamente');
}

/* ══════════════════════════════════════════════════════════════
   CAMBIAR CONTRASEÑA
══════════════════════════════════════════════════════════════ */
async function guardarPassword() {
    const actual   = v('p-actual');
    const nueva    = v('p-nueva');
    const repetir  = v('p-repetir');
    const alertEl  = document.getElementById('alert-pass');

    alertEl.innerHTML = '';

    if (!actual || !nueva || !repetir) {
        mostrarAlertPass('err', 'Todos los campos son obligatorios.');
        return;
    }

    if (nueva.length < 8) {
        mostrarAlertPass('err', 'La nueva contraseña debe tener al menos 8 caracteres.');
        return;
    }

    if (nueva !== repetir) {
        mostrarAlertPass('err', 'Las contraseñas no coinciden.');
        return;
    }

    const data = await api('PUT', '/api/me/password', { passwordActual: actual, passwordNueva: nueva });

    if (data.error) { mostrarAlertPass('err', data.error); return; }

    ['p-actual','p-nueva','p-repetir'].forEach(id => set(id, ''));
    toggleEditar('pass');
    toast('🔒 Contraseña actualizada correctamente');
}

function mostrarAlertPass(tipo, texto) {
    document.getElementById('alert-pass').innerHTML = `
        <div class="alert-pass alert-pass-${tipo}">
            ${tipo === 'err' ? '❌' : '✅'} ${texto}
        </div>`;
}

/* ══════════════════════════════════════════════════════════════
   FOTO DE PERFIL
══════════════════════════════════════════════════════════════ */
function previsualizarFoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('foto-preview').src = e.target.result;
        document.querySelector('.fotoPerfil').src    = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
    toast('📷 Foto actualizada (solo vista previa — sube al servidor cuando esté implementado)');
}

/* ══════════════════════════════════════════════════════════════
   LOGOUT
══════════════════════════════════════════════════════════════ */
document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = '{{ route("/login") }}';
});

/* ── Utilidades ── */
function v(id)        { return document.getElementById(id)?.value.trim() || ''; }
function set(id, val) { const el = document.getElementById(id); if (el) el.value = val; }

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
</body>
</html>
