/* ══════════════════════════════════════════════════════════════
   Edunoly · perfilCoordinador.js
   Perfil del coordinador + gestión de usuarios del centro
   (alumnos, docentes y tutores legales)
══════════════════════════════════════════════════════════════ */

const API = '';

/* ── Estado ── */
let sesion     = null;
let alumnos    = [];
let docentes   = [];
let tutores    = [];
let cursos     = [];
let clases     = [];
let modoModal  = 'nuevo';
let idEditando = null;
let tabActiva  = 'alumnos';

/* ════════════════════════════════════════════
   API
════════════════════════════════════════════ */
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

async function api(method, ruta, body) {
    try {
        const opts = { method, credentials: 'include', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF } };
        if (body) opts.body = JSON.stringify(body);
        const r = await fetch(API + ruta, opts);
        return await r.json();
    } catch (e) {
        return { error: 'Error de conexión.' };
    }
}

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    const data = await api('GET', '/api/me');
    if (!data || !data.id) { window.location.href = '/login'; return; }
    if (data.rol !== 'coordinador') { window.location.href = '/login'; return; }
    sesion = data;

    cargarPerfil(sesion);
    await cargarDatos();
})();

/* ════════════════════════════════════════════
   PERFIL PERSONAL
════════════════════════════════════════════ */
function cargarPerfil(u) {
    const nombreCompleto = `${u.nombre} ${u.apellidos}`;

    document.getElementById('nav-nombre').textContent             = nombreCompleto;
    document.getElementById('perfil-nombre-completo').textContent = nombreCompleto;
    document.getElementById('hero-colegio').textContent           = `Coordinador · ${u.colegio}`;
    document.getElementById('perfil-colegio').textContent         = u.colegio   || '—';
    document.getElementById('perfil-email-corto').textContent     = u.email     || '—';
    document.getElementById('perfil-telefono-corto').textContent  = u.telefono  || '—';

    document.getElementById('v-nombre').textContent    = u.nombre    || '—';
    document.getElementById('v-apellidos').textContent = u.apellidos || '—';
    document.getElementById('v-email').textContent     = u.email     || '—';
    document.getElementById('v-telefono').textContent  = u.telefono  || '—';
    document.getElementById('v-colegio').textContent   = u.colegio   || '—';

    set('e-nombre',    u.nombre    || '');
    set('e-apellidos', u.apellidos || '');
    set('e-telefono',  u.telefono  || '');

    if (u.ultimo_acceso)
        document.getElementById('ultimo-acceso').textContent =
            new Date(u.ultimo_acceso).toLocaleString('es-ES');
}

/* ── Toggle editar datos personales ── */
function toggleEditar(seccion) {
    const vistas = { personal: ['vista-personal','form-personal'], pass: ['vista-pass','form-pass'] };
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
    const payload = { nombre: v('e-nombre'), apellidos: v('e-apellidos'), telefono: v('e-telefono') };
    if (!payload.nombre || !payload.apellidos) { toast('⚠️ Nombre y apellidos son obligatorios.'); return; }

    const data = await api('PUT', '/api/me/datos', payload);
    if (data.error) { toast('❌ ' + data.error); return; }

    document.getElementById('v-nombre').textContent    = payload.nombre;
    document.getElementById('v-apellidos').textContent = payload.apellidos;
    document.getElementById('v-telefono').textContent  = payload.telefono || '—';
    document.getElementById('perfil-nombre-completo').textContent = `${payload.nombre} ${payload.apellidos}`;
    document.getElementById('nav-nombre').textContent             = `${payload.nombre} ${payload.apellidos}`;
    document.getElementById('perfil-telefono-corto').textContent  = payload.telefono || '—';

    toggleEditar('personal');
    toast('✓ Datos actualizados');
}

/* ── Cambiar contraseña ── */
async function guardarPassword() {
    const actual  = v('p-actual');
    const nueva   = v('p-nueva');
    const repetir = v('p-repetir');
    document.getElementById('alert-pass').innerHTML = '';

    if (!actual || !nueva || !repetir) { alertPass('⚠️ Todos los campos son obligatorios.'); return; }
    if (nueva.length < 8)              { alertPass('⚠️ Mínimo 8 caracteres.'); return; }
    if (nueva !== repetir)             { alertPass('⚠️ Las contraseñas no coinciden.'); return; }

    const data = await api('PUT', '/api/me/password', { passwordActual: actual, passwordNueva: nueva });
    if (data.error) { alertPass('❌ ' + data.error); return; }

    ['p-actual','p-nueva','p-repetir'].forEach(id => set(id, ''));
    toggleEditar('pass');
    toast('🔒 Contraseña actualizada');
}

function alertPass(texto) {
    document.getElementById('alert-pass').innerHTML =
        `<div class="alert-modal alert-err">${texto}</div>`;
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

/* ════════════════════════════════════════════
   NAVEGACIÓN ENTRE SECCIONES
════════════════════════════════════════════ */
function cambiarSeccion(seccion, el) {
    document.querySelectorAll('.coord-tab').forEach(t => t.classList.remove('activo'));
    document.querySelectorAll('.seccion').forEach(s => s.classList.remove('activo'));
    if (el) el.classList.add('activo');
    else {
        // Activar tab correspondiente por nombre
        document.querySelectorAll('.coord-tab').forEach(t => {
            if (t.getAttribute('onclick')?.includes(seccion)) t.classList.add('activo');
        });
    }
    document.getElementById(`seccion-${seccion}`).classList.add('activo');
}

// Llamada desde los accesos rápidos del sidebar
function cambiarSeccionDesde(seccion) {
    cambiarSeccion(seccion, null);
    return false;
}

/* ════════════════════════════════════════════
   CARGA DE DATOS DEL CENTRO
════════════════════════════════════════════ */
async function cargarDatos() {

    // Pedimos los datos a Laravel
    const resCursos   = await api('GET', '/api/cursos');
    const resClases   = await api('GET', '/api/clases');
    const resAlumnos  = await api('GET', '/api/alumnos');
    const resDocentes = await api('GET', '/api/docentes');
    const resTutores  = await api('GET', '/api/tutores');

    // Los asignamos (si Laravel no devuelve un array, devolvemos array vacío)
    cursos   = Array.isArray(resCursos) ? resCursos : [];
    clases   = Array.isArray(resClases) ? resClases : [];
    alumnos  = Array.isArray(resAlumnos) ? resAlumnos : [];
    docentes = Array.isArray(resDocentes) ? resDocentes : [];
    tutores  = Array.isArray(resTutores) ? resTutores : [];

    // Pintamos las tablas
    renderTabla('alumnos');
    renderTabla('docentes');
    renderTabla('tutores');
    actualizarStats();

}

/* ════════════════════════════════════════════
   TABS DE USUARIOS
════════════════════════════════════════════ */
function cambiarTab(tab, el) {
    tabActiva = tab;
    document.querySelectorAll('.subtab').forEach(t => t.classList.remove('activo'));
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('activo'));
    el.classList.add('activo');
    document.getElementById(`panel-${tab}`).classList.add('activo');
}

/* ════════════════════════════════════════════
   RENDER TABLAS
════════════════════════════════════════════ */
function renderTabla(tipo) {
    const tbody = document.getElementById(`tbody-${tipo}`);

    if (tipo === 'alumnos') {
        tbody.innerHTML = !alumnos.length
            ? `<tr class="fila-vacia"><td colspan="6">No hay alumnos. Pulsa "Nuevo alumno".</td></tr>`
            : alumnos.map(a => `<tr>
                <td>${a.nombre}</td><td>${a.apellidos}</td>
                <td>${a.fecha_nacimiento ? formatFecha(a.fecha_nacimiento) : '—'}</td>
                <td>${a.curso || '—'}</td><td>${a.clase || '—'}</td>
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarAlumno(${a.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('alumno',${a.id},'${a.nombre} ${a.apellidos}')">🗑️</button>
                </div></td></tr>`).join('');

    } else if (tipo === 'docentes') {
        tbody.innerHTML = !docentes.length
            ? `<tr class="fila-vacia"><td colspan="6">No hay docentes. Pulsa "Nuevo docente".</td></tr>`
            : docentes.map(d => {
                const nombre    = d.user?.name      ?? '—';
                const apellidos = d.user?.apellidos  ?? '—';
                const email     = d.user?.email      ?? '—';
                const asigs     = d.asignaturas
                    ? d.asignaturas.split(',').map(a => `<span class="tag">${a.trim()}</span>`).join('')
                    : '—';
                return `<tr>
                    <td>${nombre}</td><td>${apellidos}</td>
                    <td>${email}</td><td>${d.telefono || '—'}</td>
                    <td>${asigs}</td>
                    <td><div class="acciones">
                        <button class="btn-tabla" onclick="editarDocente(${d.id})">✏️ Editar</button>
                        <button class="btn-tabla danger" onclick="confirmarEliminar('docente',${d.id},'${nombre} ${apellidos}')">🗑️</button>
                    </div></td></tr>`;
            }).join('');

    } else if (tipo === 'tutores') {
        tbody.innerHTML = !tutores.length
            ? `<tr class="fila-vacia"><td colspan="6">No hay tutores. Pulsa "Nuevo tutor".</td></tr>`
            : tutores.map(t => {
                const nombre    = t.user?.name      ?? '—';
                const apellidos = t.user?.apellidos  ?? '—';
                const email     = t.user?.email      ?? '—';
                const hijos     = (t.alumnos || []).map(a => `<span class="tag">${a.nombre} ${a.apellidos}</span>`).join('') || '—';
                return `<tr>
                    <td>${nombre}</td><td>${apellidos}</td>
                    <td>${email}</td><td>${t.telefono || '—'}</td>
                    <td>${hijos}</td>
                    <td><div class="acciones">
                        <button class="btn-tabla" onclick="editarTutor(${t.id})">✏️ Editar</button>
                        <button class="btn-tabla danger" onclick="confirmarEliminar('tutor',${t.id},'${nombre} ${apellidos}')">🗑️</button>
                    </div></td></tr>`;
            }).join('');
    }
}

/* ════════════════════════════════════════════
   BUSCAR
════════════════════════════════════════════ */
function filtrarLista(tipo) {
    const q     = document.getElementById(`buscar-${tipo}`).value.toLowerCase();
    const datos = tipo === 'alumnos' ? alumnos : tipo === 'docentes' ? docentes : tutores;
    const fil   = q ? datos.filter(x => {
        const nom = tipo === 'alumnos' ? `${x.nombre} ${x.apellidos}` : `${x.user?.name ?? ''} ${x.user?.apellidos ?? ''}`;
        return nom.toLowerCase().includes(q);
    }) : datos;
    const tbody = document.getElementById(`tbody-${tipo}`);

    if (!fil.length) {
        tbody.innerHTML = `<tr class="fila-vacia"><td colspan="6">Sin resultados para "${q}"</td></tr>`;
        return;
    }

    const original = tipo === 'alumnos' ? alumnos : tipo === 'docentes' ? docentes : tutores;
    if (tipo === 'alumnos') { alumnos = fil; renderTabla('alumnos'); alumnos = original; }
    else if (tipo === 'docentes') { docentes = fil; renderTabla('docentes'); docentes = original; }
    else { tutores = fil; renderTabla('tutores'); tutores = original; }
}

/* ════════════════════════════════════════════
   MODALES DE USUARIOS
════════════════════════════════════════════ */
function abrirModalUsuario(modalId, modo) {
    modoModal = modo; idEditando = null;
    ['alert-alumno','alert-docente','alert-tutor'].forEach(id => {
        const el = document.getElementById(id); if (el) el.innerHTML = '';
    });

    if (modalId === 'modal-alumno') {
        resetFotoPreview('foto-preview-alumno', '👤', 'a-foto');
        // Rellenar select de cursos
        document.getElementById('a-curso').innerHTML =
            '<option value="">Seleccionar…</option>' +
            cursos.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
        document.getElementById('a-clase').innerHTML = '<option value="">Seleccionar…</option>';
        document.getElementById('a-tutor').innerHTML =
            '<option value="">Sin tutor asignado</option>' +
            tutores.map(t => `<option value="${t.id}">${t.user?.name ?? ''} ${t.user?.apellidos ?? ''}</option>`).join('');
        ['a-nombre','a-apellidos','a-fnac'].forEach(id => set(id, ''));
        set('a-curso',''); set('a-tutor','');
        document.getElementById('modal-alumno-titulo').textContent = '➕ Nuevo alumno';
    }

    if (modalId === 'modal-docente') {
        resetFotoPreview('foto-preview-docente', '👨‍🏫', 'd-foto');
        ['d-nombre','d-apellidos','d-email','d-telefono','d-password','d-fnac'].forEach(id => set(id,''));
        document.getElementById('modal-docente-titulo').textContent = '➕ Nuevo docente';
    }

    if (modalId === 'modal-tutor') {
        resetFotoPreview('foto-preview-tutor', '👨‍👩‍👧', 't-foto');
        document.getElementById('t-alumno').innerHTML =
            '<option value="">Sin alumno asignado aún</option>' +
            alumnos.map(a => `<option value="${a.id}">${a.nombre} ${a.apellidos}</option>`).join('');
        ['t-nombre','t-apellidos','t-email','t-telefono','t-password'].forEach(id => set(id,''));
        document.getElementById('modal-tutor-titulo').textContent = '➕ Nuevo tutor legal';
    }

    document.getElementById(modalId).classList.add('open');
}

function cerrarModalUsuario(id) { document.getElementById(id).classList.remove('open'); }

/* ── Filtrar clases por curso ── */
function filtrarClasesPorCurso() {
    const cursoId = parseInt(document.getElementById('a-curso').value);
    const sel = document.getElementById('a-clase');
    sel.innerHTML = '<option value="">Seleccionar…</option>' +
        clases.filter(c => c.curso_id === cursoId)
              .map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
}

/* ── Previsualizar foto en modal ── */
function previsualizarFotoModal(input, previewId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById(previewId);
        preview.innerHTML = `<img src="${e.target.result}" alt="Foto">`;
    };
    reader.readAsDataURL(input.files[0]);
}

/* ── Limpiar foto preview ── */
function resetFotoPreview(previewId, emoji, inputId) {
    const preview = document.getElementById(previewId);
    if (preview) preview.innerHTML = emoji;
    const input = document.getElementById(inputId);
    if (input) input.value = '';
}

async function guardarAlumno() {
    const nombre    = v('a-nombre');
    const apellidos = v('a-apellidos');
    const cursoId   = v('a-curso');
    const claseId   = v('a-clase');
    // (Mantenemos las validaciones iniciales de tu compañera)

    if (!nombre || !apellidos || !cursoId || !claseId) {
        alertModalUsuario('alert-alumno', '❌ Nombre, apellidos, curso y clase son obligatorios.'); return;
    }

    if (modoModal === 'nuevo') {
        
        // ¡CONEXIÓN REAL AL BACKEND! Enviamos el JSON a tu ruta
        const respuesta = await api('POST', '/api/alumnos', { 
            nombre: nombre, 
            apellidos: apellidos, 
            curso_id: cursoId, 
            clase_id: claseId 
        });

        // Si Laravel nos devuelve un error de validación, lo mostramos y paramos
        if (respuesta.error) {
            alertModalUsuario('alert-alumno', '❌ ' + respuesta.error);
            return;
        }

    } else {
        // ... (Aquí iría el PUT para editar, lo dejamos para luego)
    }

    // Volvemos a pedir todos los datos a la BD para que la tabla se actualice sola
    await cargarDatos(); 
    
    cerrarModalUsuario('modal-alumno');
    toast('✅ Alumno registrado en la Base de Datos');
}

function editarAlumno(id) {
    const a = alumnos.find(x => x.id === id); if (!a) return;
    modoModal = 'editar'; idEditando = id;
    abrirModalUsuario('modal-alumno', 'editar');
    setTimeout(() => {
        set('a-nombre', a.nombre); set('a-apellidos', a.apellidos); set('a-fnac', a.fecha_nacimiento||'');
        document.getElementById('modal-alumno-titulo').textContent = '✏️ Editar alumno';
    }, 50);
}

/* ── Guardar docente ── */
async function guardarDocente() {
    const nombre    = v('d-nombre');
    const apellidos = v('d-apellidos');
    const email     = v('d-email');
    const telefono  = v('d-telefono');
    const password  = v('d-password');
    const fnac      = v('d-fnac');

    if (!nombre || !apellidos || !email) { alertModalUsuario('alert-docente', '⚠️ Nombre, apellidos y email son obligatorios.'); return; }
    if (modoModal === 'nuevo' && !password) { alertModalUsuario('alert-docente', '⚠️ La contraseña inicial es obligatoria.'); return; }
    if (password && password.length < 8)   { alertModalUsuario('alert-docente', '⚠️ Mínimo 8 caracteres.'); return; }

    if (modoModal === 'nuevo') {
        const r = await api('POST', '/api/docentes', { nombre, apellidos, email, telefono, password });
        if (!r.ok) { alertModalUsuario('alert-docente', '❌ ' + (r.mensaje || r.error || 'Error al guardar')); return; }
    } else {
        const body = { nombre, apellidos, email, telefono };
        if (password) body.password = password;
        const r = await api('PUT', `/api/docentes/${idEditando}`, body);
        if (!r.ok) { alertModalUsuario('alert-docente', '❌ ' + (r.mensaje || r.error || 'Error al guardar')); return; }
    }

    await cargarDatos();
    cerrarModalUsuario('modal-docente');
    toast(modoModal === 'nuevo' ? '✓ Docente registrado' : '✓ Docente actualizado');
}

function editarDocente(id) {
    const d = docentes.find(x => x.id === id); if (!d) return;
    modoModal = 'editar'; idEditando = id;
    abrirModalUsuario('modal-docente', 'editar');
    setTimeout(() => {
        set('d-nombre',    d.user?.name      || '');
        set('d-apellidos', d.user?.apellidos || '');
        set('d-email',     d.user?.email     || '');
        set('d-telefono',  d.telefono        || '');
        document.getElementById('modal-docente-titulo').textContent = '✏️ Editar docente';
    }, 50);
}

/* ── Guardar tutor ── */
async function guardarTutor() {
    const nombre     = v('t-nombre');
    const apellidos  = v('t-apellidos');
    const email      = v('t-email');
    const telefono   = v('t-telefono');
    const password   = v('t-password');
    const alumnoId   = v('t-alumno');
    const parentesco = v('t-parentesco');

    if (!nombre || !apellidos || !email || !telefono) { alertModalUsuario('alert-tutor', '⚠️ Nombre, apellidos, email y teléfono son obligatorios.'); return; }
    if (modoModal === 'nuevo' && !password) { alertModalUsuario('alert-tutor', '⚠️ La contraseña inicial es obligatoria.'); return; }
    if (password && password.length < 8)   { alertModalUsuario('alert-tutor', '⚠️ Mínimo 8 caracteres.'); return; }

    if (modoModal === 'nuevo') {
        const r = await api('POST', '/api/tutores', { nombre, apellidos, email, telefono, password, alumno_id: alumnoId || null, parentesco });
        if (!r.ok) { alertModalUsuario('alert-tutor', '❌ ' + (r.mensaje || r.error || 'Error al guardar')); return; }
    } else {
        const body = { nombre, apellidos, email, telefono };
        if (password) body.password = password;
        const r = await api('PUT', `/api/tutores/${idEditando}`, body);
        if (!r.ok) { alertModalUsuario('alert-tutor', '❌ ' + (r.mensaje || r.error || 'Error al guardar')); return; }
    }

    await cargarDatos();
    cerrarModalUsuario('modal-tutor');
    toast(modoModal === 'nuevo' ? '✓ Tutor registrado' : '✓ Tutor actualizado');
}

function editarTutor(id) {
    const t = tutores.find(x => x.id === id); if (!t) return;
    modoModal = 'editar'; idEditando = id;
    abrirModalUsuario('modal-tutor', 'editar');
    setTimeout(() => {
        set('t-nombre',    t.user?.name      || '');
        set('t-apellidos', t.user?.apellidos || '');
        set('t-email',     t.user?.email     || '');
        set('t-telefono',  t.telefono        || '');
        document.getElementById('modal-tutor-titulo').textContent = '✏️ Editar tutor';
    }, 50);
}

/* ── Confirmar eliminar ── */
function confirmarEliminar(tipo, id, nombre) {
    document.getElementById('confirm-texto').textContent =
        `Vas a eliminar a "${nombre}". Esta acción no se puede deshacer.`;
    document.getElementById('btn-confirm-ok').onclick = async () => {
        const ruta = tipo === 'alumno' ? `/api/alumnos/${id}`
                   : tipo === 'docente' ? `/api/docentes/${id}`
                   : `/api/tutores/${id}`;

        const r = await api('DELETE', ruta);
        if (r && r.ok === false) {
            toast('❌ ' + (r.mensaje || 'Error al eliminar'));
            cerrarModalUsuario('modal-confirmar');
            return;
        }

        await cargarDatos();
        cerrarModalUsuario('modal-confirmar');
        toast(`🗑️ ${nombre} eliminado`);
    };
    document.getElementById('modal-confirmar').classList.add('open');
}

/* ════════════════════════════════════════════
   STATS
════════════════════════════════════════════ */
function actualizarStats() {
    document.getElementById('stat-alumnos').textContent  = alumnos.length;
    document.getElementById('stat-docentes').textContent = docentes.length;
    document.getElementById('stat-tutores').textContent  = tutores.length;
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function v(id)        { return document.getElementById(id)?.value.trim() || ''; }
function set(id, val) { const el = document.getElementById(id); if (el) el.value = val; }

function formatFecha(f) {
    if (!f) return '—';
    const [y,m,d] = f.split('-');
    return `${d}/${m}/${y}`;
}

function alertModalUsuario(id, texto) {
    document.getElementById(id).innerHTML = `<div class="alert-modal alert-err">${texto}</div>`;
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = '/login';
});
