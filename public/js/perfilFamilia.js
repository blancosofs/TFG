/* ══════════════════════════════════════════════════════════════
   Edunoly · perfilFamilia.js
   Lógica del perfil del tutor legal
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

    document.getElementById('nav-nombre').textContent             = nombreCompleto;
    document.getElementById('perfil-nombre-completo').textContent = nombreCompleto;
    document.getElementById('perfil-email-corto').textContent     = usuario.email    || '—';
    document.getElementById('perfil-telefono-corto').textContent  = usuario.telefono || '—';
    document.getElementById('perfil-colegio').textContent         = usuario.colegio  || '—';

    document.getElementById('v-nombre').textContent    = usuario.nombre    || '—';
    document.getElementById('v-apellidos').textContent = usuario.apellidos || '—';
    document.getElementById('v-email').textContent     = usuario.email     || '—';
    document.getElementById('v-telefono').textContent  = usuario.telefono  || '—';
    document.getElementById('v-usuario').textContent   = usuario.email     || '—';
    document.getElementById('v-colegio').textContent   = usuario.colegio   || '—';

    set('e-nombre',    usuario.nombre    || '');
    set('e-apellidos', usuario.apellidos || '');
    set('e-telefono',  usuario.telefono  || '');

    if (usuario.ultimo_acceso)
        document.getElementById('ultimo-acceso').textContent =
            new Date(usuario.ultimo_acceso).toLocaleString('es-ES');

    // const hijos = await api('GET', '/api/tutor/alumnos');
    // renderHijos(hijos);  // descomentar cuando el servidor esté activo
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
            <div class="hijo-acciones">
                <button class="hijo-btn" onclick="verFaltas(${h.id})">📋 Ver faltas</button>
                <button class="hijo-btn" onclick="justificarFalta(${h.id})">✏️ Justificar falta</button>
                <button class="hijo-btn" onclick="contactarDocente(${h.id})">✉️ Contactar docente</button>
            </div>
        </div>`).join('');
}

/* ── Acciones de hijos ── */
function verFaltas(idAlumno)        { toast('📋 Próximamente: historial de faltas'); }
function justificarFalta(idAlumno)  { toast('✏️ Próximamente: justificación de faltas'); }
function contactarDocente(idAlumno) { toast('✉️ Próximamente: mensajería con el docente'); }

/* ── Toggle editar ── */
function toggleEditar(seccion) {
    const vistas = {
        personal: ['vista-personal', 'form-personal'],
        pass:     ['vista-pass',     'form-pass']
    };
    const [vistaId, formId] = vistas[seccion];
    const forma    = document.getElementById(formId);
    const vista    = document.getElementById(vistaId);
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
        toast('⚠️ Nombre y apellidos son obligatorios.'); return;
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
