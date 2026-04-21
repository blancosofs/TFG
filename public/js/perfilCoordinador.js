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

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    // const data = await api('GET', '/api/me');
    // if (!data || !data.id) { window.location.href = 'login.html'; return; }
    // if (data.rol !== 'coordinador') { window.location.href = 'login.html'; return; }
    // sesion = data;

    // Datos de prueba — quitar cuando el servidor esté activo
    sesion = {
        id: 1,
        nombre: 'Ana',
        apellidos: 'Ruiz Sánchez',
        email: 'aruiz@colegio.es',
        telefono: '600 111 222',
        rol: 'coordinador',
        colegio: 'IES Ejemplo Madrid',
        colegio_id: 1,
        ultimo_acceso: new Date().toISOString()
    };

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

    // const data = await api('PUT', '/api/me/datos', payload);
    // if (data.error) { toast('❌ ' + data.error); return; }

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

    // const data = await api('PUT', '/api/me/password', { passwordActual: actual, passwordNueva: nueva });
    // if (data.error) { alertPass('❌ ' + data.error); return; }

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
    // En producción: await Promise.all([cargarCursos(), cargarAlumnos(), cargarDocentes(), cargarTutores()]);

    // Cursos y clases de prueba
    cursos = [
        { id: 1, nombre: '1º ESO' }, { id: 2, nombre: '2º ESO' },
        { id: 3, nombre: '3º ESO' }, { id: 4, nombre: '4º ESO' },
    ];
    clases = [
        { id: 1, nombre: 'A', curso_id: 1 }, { id: 2, nombre: 'B', curso_id: 1 },
        { id: 3, nombre: 'A', curso_id: 2 }, { id: 4, nombre: 'B', curso_id: 2 },
        { id: 5, nombre: 'A', curso_id: 3 }, { id: 6, nombre: 'A', curso_id: 4 },
    ];

    // Alumnos de prueba
    alumnos = [
        { id: 1, nombre: 'Carlos',    apellidos: 'García López',    fnac: '2010-03-15', curso: '1º ESO', clase: 'A', tutor: 'María López' },
        { id: 2, nombre: 'Lucía',     apellidos: 'Martínez Ruiz',   fnac: '2011-07-22', curso: '1º ESO', clase: 'B', tutor: 'Juan Martínez' },
        { id: 3, nombre: 'Alejandro', apellidos: 'Sánchez Pérez',   fnac: '2010-11-03', curso: '2º ESO', clase: 'A', tutor: '' },
    ];

    // Docentes de prueba
    docentes = [
        { id: 1, nombre: 'Pedro',   apellidos: 'Fernández Gil', email: 'pfernandez@colegio.es', telefono: '600 111 222', asignaturas: ['Matemáticas', 'Física'] },
        { id: 2, nombre: 'Carmen',  apellidos: 'Torres Vega',   email: 'ctorres@colegio.es',    telefono: '600 333 444', asignaturas: ['Lengua'] },
    ];

    // Tutores de prueba
    tutores = [
        { id: 1, nombre: 'María', apellidos: 'López Sánchez',   email: 'mlopez@gmail.com',    telefono: '600 987 654', alumnos: ['Carlos García López'] },
        { id: 2, nombre: 'Juan',  apellidos: 'Martínez García', email: 'jmartinez@gmail.com',  telefono: '600 123 456', alumnos: ['Lucía Martínez Ruiz'] },
    ];

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
                <td>${a.fnac ? formatFecha(a.fnac) : '—'}</td>
                <td>${a.curso || '—'}</td><td>${a.clase || '—'}</td>
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarAlumno(${a.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('alumno',${a.id},'${a.nombre} ${a.apellidos}')">🗑️</button>
                </div></td></tr>`).join('');

    } else if (tipo === 'docentes') {
        tbody.innerHTML = !docentes.length
            ? `<tr class="fila-vacia"><td colspan="6">No hay docentes. Pulsa "Nuevo docente".</td></tr>`
            : docentes.map(d => `<tr>
                <td>${d.nombre}</td><td>${d.apellidos}</td>
                <td>${d.email}</td><td>${d.telefono || '—'}</td>
                <td>${(d.asignaturas||[]).map(a=>`<span class="tag">${a}</span>`).join('') || '—'}</td>
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarDocente(${d.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('docente',${d.id},'${d.nombre} ${d.apellidos}')">🗑️</button>
                </div></td></tr>`).join('');

    } else if (tipo === 'tutores') {
        tbody.innerHTML = !tutores.length
            ? `<tr class="fila-vacia"><td colspan="6">No hay tutores. Pulsa "Nuevo tutor".</td></tr>`
            : tutores.map(t => `<tr>
                <td>${t.nombre}</td><td>${t.apellidos}</td>
                <td>${t.email}</td><td>${t.telefono || '—'}</td>
                <td>${(t.alumnos||[]).map(a=>`<span class="tag">${a}</span>`).join('') || '—'}</td>
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarTutor(${t.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('tutor',${t.id},'${t.nombre} ${t.apellidos}')">🗑️</button>
                </div></td></tr>`).join('');
    }
}

/* ════════════════════════════════════════════
   BUSCAR
════════════════════════════════════════════ */
function filtrarLista(tipo) {
    const q     = document.getElementById(`buscar-${tipo}`).value.toLowerCase();
    const datos = tipo === 'alumnos' ? alumnos : tipo === 'docentes' ? docentes : tutores;
    const fil   = q ? datos.filter(x => `${x.nombre} ${x.apellidos}`.toLowerCase().includes(q)) : datos;
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
        // Rellenar select de cursos
        document.getElementById('a-curso').innerHTML =
            '<option value="">Seleccionar…</option>' +
            cursos.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
        document.getElementById('a-clase').innerHTML = '<option value="">Seleccionar…</option>';
        document.getElementById('a-tutor').innerHTML =
            '<option value="">Sin tutor asignado</option>' +
            tutores.map(t => `<option value="${t.id}">${t.nombre} ${t.apellidos}</option>`).join('');
        ['a-nombre','a-apellidos','a-fnac'].forEach(id => set(id, ''));
        set('a-curso',''); set('a-tutor','');
        document.getElementById('modal-alumno-titulo').textContent = '➕ Nuevo alumno';
    }

    if (modalId === 'modal-docente') {
        ['d-nombre','d-apellidos','d-email','d-telefono','d-password','d-fnac'].forEach(id => set(id,''));
        document.getElementById('modal-docente-titulo').textContent = '➕ Nuevo docente';
    }

    if (modalId === 'modal-tutor') {
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

/* ── Guardar alumno ── */
async function guardarAlumno() {
    const nombre    = v('a-nombre');
    const apellidos = v('a-apellidos');
    const cursoId   = v('a-curso');
    const claseId   = v('a-clase');
    const tutorId   = v('a-tutor');
    const fnac      = v('a-fnac');

    if (!nombre || !apellidos || !cursoId || !claseId) {
        alertModalUsuario('alert-alumno', '⚠️ Nombre, apellidos, curso y clase son obligatorios.'); return;
    }

    const curso = cursos.find(c => c.id == cursoId);
    const clase = clases.find(c => c.id == claseId);
    const tutor = tutores.find(t => t.id == tutorId);

    if (modoModal === 'nuevo') {
        // await api('POST', '/api/coord/alumnos', { nombre, apellidos, fnac, curso_idCurso: cursoId, clase_idClase: claseId, tutor_id: tutorId||null });
        alumnos.push({ id: Date.now(), nombre, apellidos, fnac, curso: curso?.nombre||'', clase: clase?.nombre||'', tutor: tutor?`${tutor.nombre} ${tutor.apellidos}`:'' });
    } else {
        const idx = alumnos.findIndex(a => a.id === idEditando);
        if (idx !== -1) alumnos[idx] = { ...alumnos[idx], nombre, apellidos, fnac, curso: curso?.nombre||'', clase: clase?.nombre||'' };
    }

    renderTabla('alumnos'); actualizarStats();
    cerrarModalUsuario('modal-alumno');
    toast(modoModal === 'nuevo' ? '✓ Alumno registrado' : '✓ Alumno actualizado');
}

function editarAlumno(id) {
    const a = alumnos.find(x => x.id === id); if (!a) return;
    modoModal = 'editar'; idEditando = id;
    abrirModalUsuario('modal-alumno', 'editar');
    setTimeout(() => {
        set('a-nombre', a.nombre); set('a-apellidos', a.apellidos); set('a-fnac', a.fnac||'');
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
        // await api('POST', '/api/coord/docentes', { nombre, apellidos, email, telefono, password, fnac });
        docentes.push({ id: Date.now(), nombre, apellidos, email, telefono, fnac, asignaturas: [] });
    } else {
        const idx = docentes.findIndex(d => d.id === idEditando);
        if (idx !== -1) docentes[idx] = { ...docentes[idx], nombre, apellidos, email, telefono, fnac };
    }

    renderTabla('docentes'); actualizarStats();
    cerrarModalUsuario('modal-docente');
    toast(modoModal === 'nuevo' ? '✓ Docente registrado' : '✓ Docente actualizado');
}

function editarDocente(id) {
    const d = docentes.find(x => x.id === id); if (!d) return;
    modoModal = 'editar'; idEditando = id;
    abrirModalUsuario('modal-docente', 'editar');
    setTimeout(() => {
        set('d-nombre',d.nombre); set('d-apellidos',d.apellidos);
        set('d-email',d.email); set('d-telefono',d.telefono||''); set('d-fnac',d.fnac||'');
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

    const alumno = alumnos.find(a => a.id == alumnoId);

    if (modoModal === 'nuevo') {
        // await api('POST', '/api/coord/tutores', { nombre, apellidos, email, telefono, password, alumno_id: alumnoId||null, parentesco });
        tutores.push({ id: Date.now(), nombre, apellidos, email, telefono, alumnos: alumno?[`${alumno.nombre} ${alumno.apellidos}`]:[] });
    } else {
        const idx = tutores.findIndex(t => t.id === idEditando);
        if (idx !== -1) tutores[idx] = { ...tutores[idx], nombre, apellidos, email, telefono };
    }

    renderTabla('tutores'); actualizarStats();
    cerrarModalUsuario('modal-tutor');
    toast(modoModal === 'nuevo' ? '✓ Tutor registrado' : '✓ Tutor actualizado');
}

function editarTutor(id) {
    const t = tutores.find(x => x.id === id); if (!t) return;
    modoModal = 'editar'; idEditando = id;
    abrirModalUsuario('modal-tutor', 'editar');
    setTimeout(() => {
        set('t-nombre',t.nombre); set('t-apellidos',t.apellidos);
        set('t-email',t.email); set('t-telefono',t.telefono||'');
        document.getElementById('modal-tutor-titulo').textContent = '✏️ Editar tutor';
    }, 50);
}

/* ── Confirmar eliminar ── */
function confirmarEliminar(tipo, id, nombre) {
    document.getElementById('confirm-texto').textContent =
        `Vas a eliminar a "${nombre}". Esta acción no se puede deshacer.`;
    document.getElementById('btn-confirm-ok').onclick = async () => {
        // await api('DELETE', `/api/coord/${tipo}s/${id}`);
        if (tipo === 'alumno')  alumnos  = alumnos.filter(x => x.id !== id);
        if (tipo === 'docente') docentes = docentes.filter(x => x.id !== id);
        if (tipo === 'tutor')   tutores  = tutores.filter(x => x.id !== id);
        renderTabla(tipo+'s'); actualizarStats();
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
    window.location.href = 'login.html';
});
