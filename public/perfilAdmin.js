/* ══════════════════════════════════════════════════════════════
   Edunoly · perfilAdmin.js
   Lógica del perfil del administrador del sistema
══════════════════════════════════════════════════════════════ */

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

/* ── Arranque — comprueba sesión ── */
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
    { id: 1, nombre: 'IES Ejemplo Madrid',  ciudad: 'Madrid',    tipo: 'Instituto público',  coordinador: { nombre: 'Ana',   apellidos: 'Ruiz'  } },
    { id: 2, nombre: 'Colegio San José',    ciudad: 'Sevilla',   tipo: 'Colegio concertado', coordinador: { nombre: 'Luis',  apellidos: 'Pérez' } },
    { id: 3, nombre: 'CP La Paz',           ciudad: 'Valencia',  tipo: 'Colegio público',    coordinador: null },
    { id: 4, nombre: 'IES Tecnológico',     ciudad: 'Barcelona', tipo: 'Instituto privado',  coordinador: { nombre: 'Marta', apellidos: 'Gómez' } },
]);

cargarAuditoria([
    { accion: 'LOGIN',             usuario: 'admin@edunoly.es', fecha: new Date().toISOString(),                          tabla: '—' },
    { accion: 'CREAR_COLEGIO',     usuario: 'admin@edunoly.es', fecha: new Date(Date.now() - 3600000).toISOString(),      tabla: 'colegios' },
    { accion: 'CREAR_COORDINADOR', usuario: 'admin@edunoly.es', fecha: new Date(Date.now() - 7200000).toISOString(),      tabla: 'usuarios' },
    { accion: 'LOGIN',             usuario: 'admin@edunoly.es', fecha: new Date(Date.now() - 86400000).toISOString(),     tabla: '—' },
]);

/* ── Cargar perfil ── */
function cargarPerfil(usuario) {
    document.getElementById('admin-email').textContent         = usuario.email || '—';
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
        LOGIN:             '🔑',
        CREAR_COLEGIO:     '🏫',
        CREAR_COORDINADOR: '👤',
        CREAR_ALUMNO:      '🎒',
        DELETE:            '🗑️',
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
    const forma    = document.getElementById('form-pass');
    const vista    = document.getElementById('vista-pass');
    const btn      = document.getElementById('btn-editar-pass');
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
        alertPass('err', 'Todos los campos son obligatorios.'); return;
    }
    if (nueva.length < 8) {
        alertPass('err', 'La nueva contraseña debe tener al menos 8 caracteres.'); return;
    }
    if (nueva !== repetir) {
        alertPass('err', 'Las contraseñas no coinciden.'); return;
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
