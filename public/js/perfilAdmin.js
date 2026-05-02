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
document.addEventListener('DOMContentLoaded', () => {

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

}); // fin DOMContentLoaded

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
let hijos = []; // variable global para acceder desde contactarDocente

function renderHijos(hijosData) {
    hijos = hijosData; // guardar referencia global
    const lista = document.getElementById('hijos-lista');

    if (!hijosData || hijosData.error || !hijosData.length) {
        lista.innerHTML = `<div class="sin-hijos">
            <span>👦</span>
            No hay alumnos registrados a tu cargo.
        </div>`;
        document.getElementById('stat-hijos').textContent = '0';
        return;
    }

    document.getElementById('stat-hijos').textContent = hijosData.length;

    lista.innerHTML = hijosData.map(h => `
        <div class="hijo-card">
            <div class="hijo-header">
                <div class="hijo-foto-wrap">
                    <img src="${h.foto || 'alumno-default.png'}"
                         alt="${h.nombre}"
                         class="hijo-foto"
                         onerror="this.onerror=null;this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><circle cx=%2220%22 cy=%2220%22 r=%2220%22 fill=%22%2347ad79%22/><text x=%2220%22 y=%2226%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2216%22 font-family=%22Arial%22>${h.nombre.charAt(0)}${h.apellidos.charAt(0)}</text></svg>'">
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
function verFaltas(idAlumno)       { toast('📋 Próximamente: historial de faltas'); }
function justificarFalta(idAlumno) { toast('✏️ Próximamente: justificación de faltas'); }

/* ── Contactar docente — abre el modal ── */
function contactarDocente(idAlumno) {
    const hijo = hijos.find(h => h.id === idAlumno);
    if (!hijo) return;

    // Si el hijo tiene varios docentes, cogemos el primero por defecto
    // En producción podrías mostrar un selector si tiene más de uno
    const docente = hijo.docentes && hijo.docentes.length ? hijo.docentes[0] : null;

    // Rellenar datos del modal
    document.getElementById('modal-contactar-subtitulo').textContent =
        `Sobre: ${hijo.nombre} ${hijo.apellidos}`;

    document.getElementById('modal-docente-nombre').textContent =
        docente ? `${docente.nombre} ${docente.apellidos}` : 'Docente del centro';

    document.getElementById('modal-docente-asig').textContent =
        docente?.asignatura ? `${docente.asignatura}` : 'Docente';

    // Guardar referencia al alumno e id del docente para el envío
    document.getElementById('modal-contactar').dataset.alumnoId  = idAlumno;
    document.getElementById('modal-contactar').dataset.docenteId = docente?.id || '';

    // Limpiar campos
    document.getElementById('contactar-asunto').value  = '';
    document.getElementById('contactar-mensaje').value = '';
    document.getElementById('alert-contactar').innerHTML = '';

    // Abrir modal
    document.getElementById('modal-contactar').style.opacity      = '1';
    document.getElementById('modal-contactar').style.pointerEvents = 'all';
    document.body.style.overflow = 'hidden';
}

function cerrarContactar() {
    document.getElementById('modal-contactar').style.opacity      = '0';
    document.getElementById('modal-contactar').style.pointerEvents = 'none';
    document.body.style.overflow = '';
}

function setSugerencia(texto) {
    document.getElementById('contactar-asunto').value = texto;
    document.getElementById('contactar-asunto').focus();
}

async function enviarMensaje() {
    const asunto  = document.getElementById('contactar-asunto').value.trim();
    const mensaje = document.getElementById('contactar-mensaje').value.trim();
    const alertEl = document.getElementById('alert-contactar');

    alertEl.innerHTML = '';

    if (!asunto) {
        alertEl.innerHTML = `<div style="background:rgba(231,76,60,.1);border:1px solid rgba(231,76,60,.3);color:#e74c3c;border-radius:8px;padding:9px 12px;font-size:13px;margin-bottom:12px">⚠️ El asunto es obligatorio.</div>`;
        return;
    }
    if (!mensaje) {
        alertEl.innerHTML = `<div style="background:rgba(231,76,60,.1);border:1px solid rgba(231,76,60,.3);color:#e74c3c;border-radius:8px;padding:9px 12px;font-size:13px;margin-bottom:12px">⚠️ El mensaje no puede estar vacío.</div>`;
        return;
    }

    const docenteId = document.getElementById('modal-contactar').dataset.docenteId;
    const alumnoId  = document.getElementById('modal-contactar').dataset.alumnoId;

    // En producción:
    // const data = await api('POST', '/api/mensajes', {
    //     asunto, mensaje,
    //     receptor_id: docenteId,
    //     alumno_id:   alumnoId
    // });
    // if (data.error) { alertEl.innerHTML = `<div ...>${data.error}</div>`; return; }

    cerrarContactar();
    toast('✉️ Mensaje enviado correctamente al docente');
}

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