/* ══════════════════════════════════════════════════════════════
   Edunoly · perfilAdmin.js
   Lógica del perfil del administrador del sistema
══════════════════════════════════════════════════════════════ */

const API = '/api';

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
document.addEventListener('DOMContentLoaded', async () => {
    const me = await api('GET', '/me');
    if (!me || me.error) { window.location.href = '/login'; return; }
    if (me.rol !== 'admin') { window.location.href = '/login'; return; }

    document.getElementById('nav-nombre').textContent    = me.nombre || 'Administrador';
    document.getElementById('admin-email').textContent   = me.email  || '—';

    if (me.ultimo_acceso) {
        document.getElementById('admin-ultimo-acceso').textContent =
            new Date(me.ultimo_acceso).toLocaleString('es-ES');
    }

    cargarStats();
    cargarResumenColegios();
    cargarAuditoria();
});

/* ── Stats y resumen ── */
async function cargarStats() {
    const data = await api('GET', '/admin/colegios');
    if (data.error || !Array.isArray(data)) return;

    document.getElementById('stat-colegios').textContent      = data.length;
    document.getElementById('stat-coordinadores').textContent = data.filter(c => c.coordinador).length;

    const totalUsuarios = await api('GET', '/admin/stats/usuarios');
    if (!totalUsuarios.error && totalUsuarios.total !== undefined) {
        document.getElementById('stat-usuarios').textContent = totalUsuarios.total;
    }
}

async function cargarResumenColegios() {
    const data = await api('GET', '/admin/colegios');
    const contenedor = document.getElementById('colegios-resumen');

    if (data.error || !Array.isArray(data) || !data.length) {
        contenedor.innerHTML = '<p style="color:var(--texto-suave);font-size:13px;padding:8px 0">No hay colegios registrados aún.</p>';
        return;
    }

    const ultimos = data.slice(-5).reverse();
    contenedor.innerHTML = ultimos.map(c => `
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid color-mix(in srgb,var(--texto) 7%,transparent)">
            <div style="width:32px;height:32px;border-radius:8px;background:color-mix(in srgb,var(--acento) 15%,transparent);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">🏫</div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;font-weight:600;color:var(--texto);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${c.nombre}</div>
                <div style="font-size:11px;color:var(--texto-suave);margin-top:1px">${c.ciudad || '—'} · ${c.tipo || '—'}</div>
            </div>
            ${c.coordinador
                ? `<span style="font-size:11px;color:var(--acento);white-space:nowrap">✓ ${c.coordinador.nombre} ${c.coordinador.apellidos}</span>`
                : '<span style="font-size:11px;color:var(--texto-suave)">Sin coordinador</span>'}
        </div>`).join('');
}

async function cargarAuditoria() {
    const data = await api('GET', '/admin/auditoria');
    const contenedor = document.getElementById('auditoria-lista');

    if (data.error || !Array.isArray(data) || !data.length) {
        contenedor.innerHTML = '<p style="color:var(--texto-suave);font-size:13px;padding:8px 0">No hay registros de auditoría disponibles.</p>';
        return;
    }

    contenedor.innerHTML = data.slice(0, 10).map(a => `
        <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid color-mix(in srgb,var(--texto) 7%,transparent)">
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;font-weight:600;color:var(--texto)">${a.accion || '—'}</div>
                <div style="font-size:11px;color:var(--texto-suave);margin-top:2px">${a.tabla_afectada || ''} ${a.id_registro ? '#' + a.id_registro : ''}</div>
            </div>
            <span style="font-size:11px;color:var(--texto-suave);white-space:nowrap;flex-shrink:0">
                ${a.creado_en ? new Date(a.creado_en).toLocaleString('es-ES') : ''}
            </span>
        </div>`).join('');
}

/* ── Toggle contraseña ── */
function togglePass() {
    const form  = document.getElementById('form-pass');
    const vista = document.getElementById('vista-pass');
    const btn   = document.getElementById('btn-editar-pass');
    const abierto = form.style.display !== 'none';

    form.style.display  = abierto ? 'none'  : 'block';
    vista.style.display = abierto ? 'block' : 'none';
    btn.textContent     = abierto ? '🔑 Cambiar contraseña' : '✕ Cancelar';

    if (abierto) {
        ['p-actual','p-nueva','p-repetir'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('alert-pass').innerHTML = '';
    }
}

/* ── Guardar contraseña ── */
async function guardarPassword() {
    const actual  = document.getElementById('p-actual')?.value  || '';
    const nueva   = document.getElementById('p-nueva')?.value   || '';
    const repetir = document.getElementById('p-repetir')?.value || '';

    document.getElementById('alert-pass').innerHTML = '';

    if (!actual || !nueva || !repetir) {
        mostrarAlertPass('err', 'Todos los campos son obligatorios.'); return;
    }
    if (nueva.length < 8) {
        mostrarAlertPass('err', 'La nueva contraseña debe tener al menos 8 caracteres.'); return;
    }
    if (nueva !== repetir) {
        mostrarAlertPass('err', 'Las contraseñas no coinciden.'); return;
    }

    const data = await api('PUT', '/me/password', { passwordActual: actual, passwordNueva: nueva });
    if (data.error) { mostrarAlertPass('err', data.error); return; }

    ['p-actual','p-nueva','p-repetir'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    togglePass();
    toast('🔒 Contraseña actualizada correctamente');
}

function mostrarAlertPass(tipo, texto) {
    document.getElementById('alert-pass').innerHTML = `
        <div class="alert-pass alert-pass-${tipo}">
            ${tipo === 'err' ? '❌' : '✅'} ${texto}
        </div>`;
}

/* ── Foto (solo visual) ── */
function previsualizarFoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('foto-preview');
        const nav  = document.querySelector('.fotoPerfil');
        if (prev) prev.src = e.target.result;
        if (nav)  nav.src  = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
    toast('📷 Foto actualizada (vista previa)');
}

/* ── Logout ── */
document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/logout');
    window.location.href = '/login';
});

/* ── Toast ── */
function toast(msg) {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}
