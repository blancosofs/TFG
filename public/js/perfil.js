/* ══════════════════════════════════════════════════════════════
   Edunoly · perfil.js
   Lógica del perfil del docente
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
//     if (!data || !data.id) { window.location.href = '/login'; return; }
//     if (data.rol !== 'docente') { window.location.href = '/login'; return; }
//     cargarPerfil(data);
// })();

// Datos de prueba — quitar cuando el servidor esté activo
cargarPerfil({
    id: 1,
    nombre: 'Carlos',
    apellidos: 'García Martínez',
    email: 'cgarcia@centro.es',
    telefono: '600 123 456',
    rol: 'docente',
    colegio: 'IES Ejemplo',
    color: '#47ad79',
    fechaNacimiento: '1985-03-15',
    ultimo_acceso: new Date().toISOString()
});

/* ── Cargar perfil ── */
async function cargarPerfil(usuario) {
    const nombreCompleto = `${usuario.nombre} ${usuario.apellidos}`;

    document.getElementById('nav-nombre').textContent             = nombreCompleto;
    document.getElementById('perfil-nombre-completo').textContent = nombreCompleto;
    document.getElementById('perfil-email-corto').textContent     = usuario.email    || '—';
    document.getElementById('perfil-telefono-corto').textContent  = usuario.telefono || '—';
    document.getElementById('perfil-colegio').textContent         = usuario.colegio  || '—';

    if (usuario.ultimo_acceso)
        document.getElementById('ultimo-acceso').textContent =
            new Date(usuario.ultimo_acceso).toLocaleString('es-ES');

    document.getElementById('v-nombre').textContent      = usuario.nombre      || '—';
    document.getElementById('v-apellidos').textContent   = usuario.apellidos   || '—';
    document.getElementById('v-email').textContent       = usuario.email       || '—';
    document.getElementById('v-telefono').textContent    = usuario.telefono    || '—';
    document.getElementById('v-usuario').textContent     = usuario.email       || '—';
    document.getElementById('v-fnacimiento').textContent = usuario.fechaNacimiento
        ? new Date(usuario.fechaNacimiento).toLocaleDateString('es-ES') : '—';

    set('e-nombre',      usuario.nombre      || '');
    set('e-apellidos',   usuario.apellidos   || '');
    set('e-telefono',    usuario.telefono    || '');
    set('e-fnacimiento', usuario.fechaNacimiento?.slice(0, 10) || '');

    document.getElementById('v-colegio').textContent = usuario.colegio || '—';

    // const clases = await api('GET', '/api/clases?desde=2025-01-01&hasta=2099-12-31');
    // Datos de prueba — quitar cuando el servidor esté activo
    const clases = [
        { materia: 'Matemáticas',  grupo: '1ºA' },
        { materia: 'Matemáticas',  grupo: '1ºB' },
        { materia: 'Física',       grupo: '2ºA' },
        { materia: 'Programación', grupo: '2ºB' },
    ];

    if (clases && !clases.error) {
        const asigs       = [...new Set(clases.map(c => c.materia))];
        const gruposUnicos = [...new Set(clases.map(c => c.grupo).filter(Boolean))];

        document.getElementById('stat-clases').textContent      = gruposUnicos.length || '—';
        document.getElementById('stat-asignaturas').textContent = asigs.length        || '—';
        document.getElementById('stat-alumnos').textContent     = gruposUnicos.length
            ? `~${gruposUnicos.length * 25}` : '—';

        document.getElementById('v-asignaturas').innerHTML = asigs.length
            ? asigs.map(a => `<span class="tag-item">${a}</span>`).join('')
            : '<span class="dato-val">—</span>';

        document.getElementById('v-clases').innerHTML = gruposUnicos.length
            ? gruposUnicos.map(g => `<span class="tag-item tag-clase">${g}</span>`).join('')
            : '<span class="dato-val">—</span>';
    }
}

/* ── Toggle editar ── */
function toggleEditar(seccion) {
    const vistas = {
        personal: ['vista-personal', 'form-personal'],
        pass:     ['vista-pass',     'form-pass']
    };
    const [vistaId, formId] = vistas[seccion];
    const forma   = document.getElementById(formId);
    const vista   = document.getElementById(vistaId);
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

/* ── Cambiar contraseña ── */
async function guardarPassword() {
    const actual  = v('p-actual');
    const nueva   = v('p-nueva');
    const repetir = v('p-repetir');

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
    window.location.href = '/login';
});

/* ── Utilidades ── */
function v(id)        { return document.getElementById(id)?.value.trim() || ''; }
function set(id, val) { const el = document.getElementById(id); if (el) el.value = val; }

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}
