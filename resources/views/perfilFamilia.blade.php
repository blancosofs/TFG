<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Mi Perfil Familiar</title>
    <script src="temas.js"></script>
    <link rel="stylesheet" href="temas.css">
    <link rel="stylesheet" href="EstilosPerfil.css">
    <link rel="stylesheet" href="EstilosPerfilFamilia.css">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo">
                    <img src="logo.svg" alt="Edunoly">
                </li>
                <li><a href="PaginaInicio.html">Inicio</a></li>
                <li><a href="PaginaContacto.html">Contacto</a></li>
                <li class="activo"><a href="perfilFamilia.html">Mi Perfil</a></li>

                <li class="derecha menuSesion">
                    <img src="perfil.png" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre"></span></li>
                        <li class="dropdown-rol"><span id="nav-rol">Tutor legal</span></li>
                        <li class="dropdown-sep"></li>
                        <li><a href="perfilFamilia.html">👤 Mi perfil</a></li>
                        <li><a href="configuracion.html">⚙️ Configuración</a></li>
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
        <p class="etiqueta verde">Mi espacio familiar</p>
        <h1>Mi Perfil</h1>
        <p class="hero-sub">Consulta la información de tus hijos y gestiona tus datos personales.</p>
    </div>
</div>

<!-- ── CONTENIDO ── -->
<div class="perfil-layout">

    <!-- ══ COLUMNA IZQUIERDA ══ -->
    <aside class="perfil-sidebar">

        <!-- Foto + datos del tutor -->
        <div class="card-perfil">
            <div class="foto-wrap">
                <img src="perfil.png" alt="Foto de perfil" class="foto-grande" id="foto-preview">
                <label class="foto-btn" title="Cambiar foto">
                    📷
                    <input type="file" id="input-foto" accept="image/*" style="display:none" onchange="previsualizarFoto(this)">
                </label>
            </div>

            <div class="nombre-bloque">
                <h2 id="perfil-nombre-completo">Cargando…</h2>
                <span class="rol-badge">Tutor legal</span>
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

            <!-- Estadística: nº de hijos -->
            <div class="stats-perfil">
                <div class="stat-perfil" style="grid-column:1/-1">
                    <span class="stat-num" id="stat-hijos">—</span>
                    <span class="stat-lbl">Hijos registrados</span>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos -->
        <div class="card-links">
            <p class="card-section-titulo">Accesos rápidos</p>
            <a href="configuracion.html" class="link-rapido">
                <span class="link-ico">⚙️</span>
                <span>Configuración</span>
                <span class="link-arrow">›</span>
            </a>
            <a href="PaginaContacto.html" class="link-rapido">
                <span class="link-ico">✉️</span>
                <span>Contactar con el centro</span>
                <span class="link-arrow">›</span>
            </a>
        </div>

    </aside>

    <!-- ══ COLUMNA DERECHA ══ -->
    <main class="perfil-main">

        <!-- ── MIS HIJOS ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">👨‍👩‍👧‍👦 Mis hijos</h3>
                    <p class="seccion-sub">Información académica de los menores a tu cargo.</p>
                </div>
            </div>
            <div id="hijos-lista">
                <div class="cargando-txt">Cargando…</div>
            </div>
        </div>

        <!-- ── DATOS PERSONALES ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">👤 Mis datos personales</h3>
                    <p class="seccion-sub">Tu información de contacto en la plataforma.</p>
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
                    <span class="dato-lbl">Usuario de acceso</span>
                    <span class="dato-val" id="v-usuario">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Centro educativo</span>
                    <span class="dato-val" id="v-colegio">—</span>
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
                    <div class="fgroup full">
                        <label class="flabel">Teléfono</label>
                        <input class="finput" id="e-telefono" type="tel">
                    </div>
                </div>
                <div class="form-edicion-acciones">
                    <button class="btn-cancelar-edit" onclick="toggleEditar('personal')">Cancelar</button>
                    <button class="btn-guardar-edit" onclick="guardarPersonal()">💾 Guardar cambios</button>
                </div>
            </div>
        </div>

        <!-- ── SEGURIDAD ── -->
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
                <p class="pass-info">••••••••••••
                    <span class="pass-hint">Tu contraseña está cifrada y protegida.</span>
                </p>
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

        <!-- ── ACTIVIDAD RECIENTE ── -->
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
                    <span class="actividad-ico">👁️</span>
                    <div class="actividad-info">
                        <span class="actividad-texto">Consulta de perfil de hijo</span>
                        <span class="actividad-fecha">Hoy</span>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<div class="toast" id="toast"></div>

<script src="temas.js"></script>
<script src="MenuSesion.js"></script>
<script>
const API = '';

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

/* ── Arranque ── */
// (async () => {
//     const data = await api('GET', '/api/me');
//     if (!data || !data.id) { window.location.href = 'login.html'; return; }
//     if (data.rol !== 'tutor') { window.location.href = 'login.html'; return; }
//     cargarPerfil(data);
// })();

// Datos de prueba — quitar cuando el servidor esté activo
cargarPerfil({
    id: 1,
    nombre: 'María',
    apellidos: 'López Sánchez',
    email: 'mlopez@gmail.com',
    telefono: '600 987 654',
    rol: 'tutor',
    colegio: 'IES Ejemplo',
    ultimo_acceso: new Date().toISOString()
});

// Hijos de prueba — quitar cuando el servidor esté activo
renderHijos([
    {
        id: 1,
        nombre: 'Alejandro',
        apellidos: 'López García',
        foto: '',
        curso: '1º ESO',
        clase: 'A',
        colegio: 'IES Ejemplo',
        parentesco: 'Madre',
        faltas: 2,
        docentes: [
            { nombre: 'Carlos', apellidos: 'García', asignatura: 'Matemáticas' },
            { nombre: 'Ana',    apellidos: 'Ruiz',   asignatura: 'Lengua' }
        ]
    },
    {
        id: 2,
        nombre: 'Sofía',
        apellidos: 'López García',
        foto: '',
        curso: '3º Primaria',
        clase: 'B',
        colegio: 'IES Ejemplo',
        parentesco: 'Madre',
        faltas: 0,
        docentes: [
            { nombre: 'Pedro', apellidos: 'Martínez', asignatura: 'Ciencias' }
        ]
    }
]);

/* ── Cargar perfil ── */
async function cargarPerfil(usuario) {
    const nombreCompleto = `${usuario.nombre} ${usuario.apellidos}`;

    document.getElementById('nav-nombre').textContent            = nombreCompleto;
    document.getElementById('perfil-nombre-completo').textContent = nombreCompleto;
    document.getElementById('perfil-email-corto').textContent    = usuario.email    || '—';
    document.getElementById('perfil-telefono-corto').textContent = usuario.telefono || '—';
    document.getElementById('perfil-colegio').textContent        = usuario.colegio  || '—';

    document.getElementById('v-nombre').textContent    = usuario.nombre    || '—';
    document.getElementById('v-apellidos').textContent = usuario.apellidos || '—';
    document.getElementById('v-email').textContent     = usuario.email     || '—';
    document.getElementById('v-telefono').textContent  = usuario.telefono  || '—';
    document.getElementById('v-usuario').textContent   = usuario.email     || '—';
    document.getElementById('v-colegio').textContent   = usuario.colegio   || '—';

    set('e-nombre',    usuario.nombre    || '');
    set('e-apellidos', usuario.apellidos || '');
    set('e-telefono',  usuario.telefono  || '');

    if (usuario.ultimo_acceso) {
        document.getElementById('ultimo-acceso').textContent =
            new Date(usuario.ultimo_acceso).toLocaleString('es-ES');
    }

    // Cargar hijos
    const hijos = await api('GET', '/api/tutor/alumnos');
    renderHijos(hijos);
}

/* ── Render tarjetas de hijos ── */
function renderHijos(hijos) {
    const lista = document.getElementById('hijos-lista');

    if (!hijos || hijos.error || !hijos.length) {
        lista.innerHTML = `<div class="sin-hijos">
            <span>👦</span>
            No hay alumnos registrados a tu cargo.
        </div>`;
        document.getElementById('stat-hijos').textContent = '0';
        return;
    }

    document.getElementById('stat-hijos').textContent = hijos.length;

    lista.innerHTML = hijos.map(h => `
        <div class="hijo-card">

            <!-- Foto e identidad -->
            <div class="hijo-header">
                <div class="hijo-foto-wrap">
                    <img src="${h.foto || 'alumno-default.png'}"
                         alt="${h.nombre}"
                         class="hijo-foto"
                         onerror="this.src='alumno-default.png'">
                </div>
                <div class="hijo-identidad">
                    <h4 class="hijo-nombre">${h.nombre} ${h.apellidos}</h4>
                    <span class="hijo-curso">${h.curso || '—'} · ${h.clase || '—'}</span>
                    <span class="hijo-parentesco">${h.parentesco || 'Tutor legal'}</span>
                </div>
            </div>

            <!-- Datos académicos -->
            <div class="hijo-datos">
                <div class="hijo-dato">
                    <span class="hijo-dato-lbl">Centro</span>
                    <span class="hijo-dato-val">${h.colegio || '—'}</span>
                </div>
                <div class="hijo-dato">
                    <span class="hijo-dato-lbl">Curso</span>
                    <span class="hijo-dato-val">${h.curso || '—'}</span>
                </div>
                <div class="hijo-dato">
                    <span class="hijo-dato-lbl">Clase</span>
                    <span class="hijo-dato-val">${h.clase || '—'}</span>
                </div>
                <div class="hijo-dato">
                    <span class="hijo-dato-lbl">Faltas este mes</span>
                    <span class="hijo-dato-val ${h.faltas > 3 ? 'val-alerta' : ''}">${h.faltas ?? '0'}</span>
                </div>
            </div>

            <!-- Docentes asignados -->
            ${h.docentes && h.docentes.length ? `
            <div class="hijo-docentes">
                <span class="hijo-dato-lbl">Docentes</span>
                <div class="docentes-lista">
                    ${h.docentes.map(d => `
                        <div class="docente-chip">
                            <span class="docente-ico">👨‍🏫</span>
                            <span>${d.nombre} ${d.apellidos}</span>
                            <span class="docente-asig">${d.asignatura || ''}</span>
                        </div>`).join('')}
                </div>
            </div>` : ''}

            <!-- Acciones rápidas del hijo -->
            <div class="hijo-acciones">
                <button class="hijo-btn" onclick="verFaltas(${h.id})">
                    📋 Ver faltas
                </button>
                <button class="hijo-btn" onclick="justificarFalta(${h.id})">
                    ✏️ Justificar falta
                </button>
                <button class="hijo-btn" onclick="contactarDocente(${h.id})">
                    ✉️ Contactar docente
                </button>
            </div>

        </div>`).join('');
}

/* ── Acciones de hijos (a implementar) ── */
function verFaltas(idAlumno)       { toast('📋 Próximamente: historial de faltas'); }
function justificarFalta(idAlumno) { toast('✏️ Próximamente: justificación de faltas'); }
function contactarDocente(idAlumno){ toast('✉️ Próximamente: mensajería con el docente'); }

/* ── Toggle editar ── */
function toggleEditar(seccion) {
    const vistas = {
        personal: ['vista-personal', 'form-personal'],
        pass:     ['vista-pass',     'form-pass']
    };
    const [vistaId, formId] = vistas[seccion];
    const forma = document.getElementById(formId);
    const vista = document.getElementById(vistaId);
    const editando = forma.style.display !== 'none';

    forma.style.display = editando ? 'none'  : 'block';
    vista.style.display = editando ? 'grid'  : 'none';

    const btnId = seccion === 'personal' ? 'btn-editar-personal' : 'btn-editar-pass';
    document.getElementById(btnId).textContent = editando
        ? (seccion === 'personal' ? '✏️ Editar' : '🔑 Cambiar contraseña')
        : '✕ Cancelar';
}

/* ── Guardar datos personales ── */
async function guardarPersonal() {
    const payload = {
        nombre:    v('e-nombre'),
        apellidos: v('e-apellidos'),
        telefono:  v('e-telefono'),
    };

    if (!payload.nombre || !payload.apellidos) {
        toast('⚠️ Nombre y apellidos son obligatorios.');
        return;
    }

    const data = await api('PUT', '/api/me/datos', payload);
    if (data.error) { toast('❌ ' + data.error); return; }

    document.getElementById('v-nombre').textContent    = payload.nombre;
    document.getElementById('v-apellidos').textContent = payload.apellidos;
    document.getElementById('v-telefono').textContent  = payload.telefono || '—';
    document.getElementById('perfil-nombre-completo').textContent = `${payload.nombre} ${payload.apellidos}`;
    document.getElementById('nav-nombre').textContent             = `${payload.nombre} ${payload.apellidos}`;
    document.getElementById('perfil-telefono-corto').textContent  = payload.telefono || '—';

    toggleEditar('personal');
    toast('✓ Datos actualizados correctamente');
}

/* ── Cambiar contraseña ── */
async function guardarPassword() {
    const actual  = v('p-actual');
    const nueva   = v('p-nueva');
    const repetir = v('p-repetir');

    document.getElementById('alert-pass').innerHTML = '';

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

/* ── Foto de perfil ── */
function previsualizarFoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('foto-preview').src = e.target.result;
        document.querySelector('.fotoPerfil').src    = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
    toast('📷 Foto actualizada (vista previa)');
}

/* ── Logout ── */
document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = 'login.html';
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
