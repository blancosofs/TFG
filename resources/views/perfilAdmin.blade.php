<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Perfil Admin</title>
    <script src="temas.js"></script>
    <link rel="stylesheet" href="temas.css">
    <link rel="stylesheet" href="EstilosPerfil.css">
    <link rel="stylesheet" href="EstilosPerfilAdmin.css">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo"><img src="logo.svg" alt="Edunoly"></li>
                <li><a href="admin.html">Panel Admin</a></li>
                <li class="activo"><a href="perfilAdmin.html">Mi Perfil</a></li>

                <li class="derecha menuSesion">
                    <img src="perfil.png" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">Administrador</span></li>
                        <li class="dropdown-rol">Sistema</li>
                        <li class="dropdown-sep"></li>
                        <li><a href="perfilAdmin.html">👤 Mi perfil</a></li>
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
        <p class="etiqueta verde">Panel de administración</p>
        <h1>Perfil del Administrador</h1>
        <p class="hero-sub">Gestiona tus credenciales y consulta el estado general del sistema.</p>
    </div>
</div>

<!-- ── CONTENIDO ── -->
<div class="perfil-layout">

    <!-- ══ SIDEBAR ══ -->
    <aside class="perfil-sidebar">

        <!-- Foto + datos -->
        <div class="card-perfil">
            <div class="foto-wrap">
                <img src="perfil.png" alt="Admin" class="foto-grande" id="foto-preview">
                <label class="foto-btn foto-btn-admin" title="Cambiar foto">
                    📷
                    <input type="file" id="input-foto" accept="image/*" style="display:none" onchange="previsualizarFoto(this)">
                </label>
            </div>

            <div class="nombre-bloque">
                <h2>Administrador</h2>
                <span class="rol-badge rol-badge-admin">⚙ Sistema</span>
            </div>

            <div class="info-rapida">
                <div class="info-rapida-item">
                    <span class="info-ico">📧</span>
                    <span id="admin-email">—</span>
                </div>
                <div class="info-rapida-item">
                    <span class="info-ico">🕐</span>
                    <span id="admin-ultimo-acceso">—</span>
                </div>
            </div>

            <!-- Stats del sistema -->
            <div class="stats-perfil">
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-colegios">—</span>
                    <span class="stat-lbl">Colegios</span>
                </div>
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-coordinadores">—</span>
                    <span class="stat-lbl">Coordinadores</span>
                </div>
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-usuarios">—</span>
                    <span class="stat-lbl">Usuarios</span>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos -->
        <div class="card-links">
            <p class="card-section-titulo">Accesos rápidos</p>
            <a href="admin.html" class="link-rapido">
                <span class="link-ico">🏫</span>
                <span>Gestión de colegios</span>
                <span class="link-arrow">›</span>
            </a>
            <a href="configuracion.html" class="link-rapido">
                <span class="link-ico">⚙️</span>
                <span>Configuración</span>
                <span class="link-arrow">›</span>
            </a>
        </div>

    </aside>

    <!-- ══ CONTENIDO PRINCIPAL ══ -->
    <main class="perfil-main">

        <!-- ── Estado del sistema ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">📊 Estado del sistema</h3>
                    <p class="seccion-sub">Resumen general de la plataforma en tiempo real.</p>
                </div>
            </div>

            <div class="sistema-grid">
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Base de datos</div>
                        <div class="sistema-estado">Conectada</div>
                    </div>
                </div>
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Servidor</div>
                        <div class="sistema-estado">Operativo</div>
                    </div>
                </div>
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Sesiones</div>
                        <div class="sistema-estado">Activas</div>
                    </div>
                </div>
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Cifrado</div>
                        <div class="sistema-estado">bcrypt activo</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Resumen de colegios ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🏫 Resumen de colegios</h3>
                    <p class="seccion-sub">Últimos centros registrados en el sistema.</p>
                </div>
                <a href="admin.html" class="btn-editar">Ver todos ›</a>
            </div>

            <div id="colegios-resumen">
                <div class="dato-val" style="text-align:center;padding:16px">Cargando…</div>
            </div>
        </div>

        <!-- ── Seguridad ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🔒 Seguridad de la cuenta</h3>
                    <p class="seccion-sub">Cambia la contraseña de acceso al panel de administración.</p>
                </div>
                <button class="btn-editar" id="btn-editar-pass" onclick="togglePass()">
                    🔑 Cambiar contraseña
                </button>
            </div>

            <div id="vista-pass">
                <p class="pass-info">
                    ••••••••••••
                    <span class="pass-hint">Contraseña cifrada con bcrypt.</span>
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
                        <label class="flabel">Repetir contraseña</label>
                        <input class="finput" id="p-repetir" type="password" placeholder="Repite la contraseña">
                    </div>
                </div>
                <div id="alert-pass"></div>
                <div class="form-edicion-acciones">
                    <button class="btn-cancelar-edit" onclick="togglePass()">Cancelar</button>
                    <button class="btn-guardar-edit" onclick="guardarPassword()">🔒 Actualizar contraseña</button>
                </div>
            </div>
        </div>

        <!-- ── Auditoría ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">📋 Registro de auditoría</h3>
                    <p class="seccion-sub">Últimas acciones registradas en el sistema.</p>
                </div>
            </div>

            <div id="auditoria-lista">
                <div class="dato-val" style="text-align:center;padding:16px">Cargando…</div>
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
//     if (data.rol !== 'admin') { window.location.href = 'login.html'; return; }
//     cargarPerfil(data);
// })();

// Datos de prueba — quitar cuando el servidor esté activo
cargarPerfil({
    id: 1,
    email: 'admin@edunoly.es',
    rol: 'admin',
    ultimo_acceso: new Date().toISOString()
});

cargarEstadisticas({
    colegios: 4,
    coordinadores: 4,
    usuarios: 28
});

cargarColegios([
    { id: 1, nombre: 'IES Ejemplo Madrid',    ciudad: 'Madrid',    tipo: 'Instituto público',   coordinador: { nombre: 'Ana', apellidos: 'Ruiz' } },
    { id: 2, nombre: 'Colegio San José',       ciudad: 'Sevilla',   tipo: 'Colegio concertado',  coordinador: { nombre: 'Luis', apellidos: 'Pérez' } },
    { id: 3, nombre: 'CP La Paz',              ciudad: 'Valencia',  tipo: 'Colegio público',     coordinador: null },
    { id: 4, nombre: 'IES Tecnológico',        ciudad: 'Barcelona', tipo: 'Instituto privado',   coordinador: { nombre: 'Marta', apellidos: 'Gómez' } },
]);

cargarAuditoria([
    { accion: 'LOGIN',          usuario: 'admin@edunoly.es', fecha: new Date().toISOString(),                         tabla: '—' },
    { accion: 'CREAR_COLEGIO',  usuario: 'admin@edunoly.es', fecha: new Date(Date.now() - 3600000).toISOString(),     tabla: 'colegios' },
    { accion: 'CREAR_COORDINADOR', usuario: 'admin@edunoly.es', fecha: new Date(Date.now() - 7200000).toISOString(), tabla: 'usuarios' },
    { accion: 'LOGIN',          usuario: 'admin@edunoly.es', fecha: new Date(Date.now() - 86400000).toISOString(),    tabla: '—' },
]);

/* ── Cargar perfil ── */
function cargarPerfil(usuario) {
    document.getElementById('admin-email').textContent = usuario.email || '—';
    document.getElementById('admin-ultimo-acceso').textContent = usuario.ultimo_acceso
        ? new Date(usuario.ultimo_acceso).toLocaleString('es-ES') : '—';
}

/* ── Estadísticas ── */
function cargarEstadisticas(stats) {
    document.getElementById('stat-colegios').textContent      = stats.colegios      ?? '—';
    document.getElementById('stat-coordinadores').textContent = stats.coordinadores  ?? '—';
    document.getElementById('stat-usuarios').textContent      = stats.usuarios       ?? '—';
}

/* ── Resumen de colegios ── */
function cargarColegios(lista) {
    const el = document.getElementById('colegios-resumen');
    if (!lista.length) {
        el.innerHTML = '<p class="sin-datos">No hay colegios registrados.</p>';
        return;
    }

    el.innerHTML = lista.map(c => `
        <div class="colegio-fila">
            <div class="colegio-fila-info">
                <span class="colegio-fila-nombre">${c.nombre}</span>
                <span class="colegio-fila-meta">${c.ciudad} · ${c.tipo}</span>
            </div>
            <div class="colegio-fila-coord ${c.coordinador ? 'coord-ok' : 'coord-sin'}">
                ${c.coordinador
                    ? `👤 ${c.coordinador.nombre} ${c.coordinador.apellidos}`
                    : '⚠️ Sin coordinador'}
            </div>
        </div>`).join('');
}

/* ── Auditoría ── */
function cargarAuditoria(registros) {
    const iconos = {
        LOGIN:              '🔑',
        CREAR_COLEGIO:      '🏫',
        CREAR_COORDINADOR:  '👤',
        CREAR_ALUMNO:       '🎒',
        DELETE:             '🗑️',
    };

    const el = document.getElementById('auditoria-lista');
    if (!registros.length) {
        el.innerHTML = '<p class="sin-datos">Sin registros de auditoría.</p>';
        return;
    }

    el.innerHTML = registros.map(r => `
        <div class="actividad-item">
            <span class="actividad-ico">${iconos[r.accion] || '📋'}</span>
            <div class="actividad-info">
                <span class="actividad-texto">
                    <strong>${r.accion}</strong>
                    ${r.tabla !== '—' ? `<span class="audit-tabla">${r.tabla}</span>` : ''}
                </span>
                <span class="actividad-fecha">${new Date(r.fecha).toLocaleString('es-ES')} · ${r.usuario}</span>
            </div>
        </div>`).join('');
}

/* ── Toggle contraseña ── */
function togglePass() {
    const forma = document.getElementById('form-pass');
    const vista = document.getElementById('vista-pass');
    const btn   = document.getElementById('btn-editar-pass');
    const editando = forma.style.display !== 'none';

    forma.style.display = editando ? 'none'  : 'block';
    vista.style.display = editando ? 'block' : 'none';
    btn.textContent     = editando ? '🔑 Cambiar contraseña' : '✕ Cancelar';
}

/* ── Guardar contraseña ── */
async function guardarPassword() {
    const actual  = v('p-actual');
    const nueva   = v('p-nueva');
    const repetir = v('p-repetir');

    document.getElementById('alert-pass').innerHTML = '';

    if (!actual || !nueva || !repetir) {
        alertPass('err', 'Todos los campos son obligatorios.');
        return;
    }
    if (nueva.length < 8) {
        alertPass('err', 'La nueva contraseña debe tener al menos 8 caracteres.');
        return;
    }
    if (nueva !== repetir) {
        alertPass('err', 'Las contraseñas no coinciden.');
        return;
    }

    const data = await api('PUT', '/api/me/password', { passwordActual: actual, passwordNueva: nueva });
    if (data.error) { alertPass('err', data.error); return; }

    ['p-actual','p-nueva','p-repetir'].forEach(id => set(id, ''));
    togglePass();
    toast('🔒 Contraseña actualizada correctamente');
}

function alertPass(tipo, texto) {
    document.getElementById('alert-pass').innerHTML = `
        <div class="alert-pass alert-pass-${tipo}">
            ${tipo === 'err' ? '❌' : '✅'} ${texto}
        </div>`;
}

/* ── Foto ── */
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
