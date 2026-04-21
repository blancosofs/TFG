/* ══════════════════════════════════════════════════════════════
   Edunoly · coordinador.js
   Lógica del panel del coordinador:
   - CRUD de alumnos, docentes y tutores del colegio
   - Todos los datos se asocian automáticamente al colegio
     del coordinador autenticado (viene de la sesión)
══════════════════════════════════════════════════════════════ */

const API = '';

/* ── Estado ── */
let sesion       = null;   // datos del coordinador logueado
let alumnos      = [];
let docentes     = [];
let tutores      = [];
let cursos       = [];
let clases       = [];
let modoModal    = 'nuevo'; // 'nuevo' | 'editar'
let idEditando   = null;
let tabActiva    = 'alumnos';

/* ════════════════════════════════════════════
   API
════════════════════════════════════════════ */
async function api(method, ruta, body) {
    try {
        const opts = {
            method,
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' }
        };
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
    // Verificar sesión
    // const data = await api('GET', '/api/me');
    // if (!data || !data.id) { window.location.href = 'login.html'; return; }
    // if (data.rol !== 'coordinador') { window.location.href = 'login.html'; return; }
    // sesion = data;

    // ── Datos de prueba — quitar cuando el servidor esté activo ──
    sesion = {
        id: 1,
        nombre: 'Ana',
        apellidos: 'Ruiz Sánchez',
        email: 'aruiz@colegio.es',
        rol: 'coordinador',
        colegio: 'IES Ejemplo Madrid',
        colegio_id: 1
    };

    document.getElementById('nav-nombre').textContent = `${sesion.nombre} ${sesion.apellidos}`;
    document.getElementById('hero-colegio').textContent = sesion.colegio;

    await cargarTodo();
})();

/* ════════════════════════════════════════════
   CARGA INICIAL DE DATOS
════════════════════════════════════════════ */
async function cargarTodo() {
    await Promise.all([
        cargarCursos(),
        cargarAlumnos(),
        cargarDocentes(),
        cargarTutores()
    ]);
    actualizarStats();
}

async function cargarCursos() {
    // const data = await api('GET', '/api/coord/cursos');
    // cursos = data.cursos || [];
    // clases = data.clases || [];

    // Datos de prueba
    cursos = [
        { id: 1, nombre: '1º ESO' },
        { id: 2, nombre: '2º ESO' },
        { id: 3, nombre: '3º ESO' },
        { id: 4, nombre: '4º ESO' },
    ];
    clases = [
        { id: 1, nombre: 'A', curso_id: 1 },
        { id: 2, nombre: 'B', curso_id: 1 },
        { id: 3, nombre: 'A', curso_id: 2 },
        { id: 4, nombre: 'B', curso_id: 2 },
        { id: 5, nombre: 'A', curso_id: 3 },
        { id: 6, nombre: 'A', curso_id: 4 },
    ];
}

async function cargarAlumnos() {
    // const data = await api('GET', '/api/coord/alumnos');
    // alumnos = data || [];

    // Datos de prueba
    alumnos = [
        { id: 1, nombre: 'Carlos',    apellidos: 'García López',    fnac: '2010-03-15', curso: '1º ESO', clase: 'A', tutor: 'María López' },
        { id: 2, nombre: 'Lucía',     apellidos: 'Martínez Ruiz',   fnac: '2011-07-22', curso: '1º ESO', clase: 'B', tutor: 'Juan Martínez' },
        { id: 3, nombre: 'Alejandro', apellidos: 'Sánchez Pérez',   fnac: '2010-11-03', curso: '2º ESO', clase: 'A', tutor: '' },
    ];
    renderTabla('alumnos');
}

async function cargarDocentes() {
    // const data = await api('GET', '/api/coord/docentes');
    // docentes = data || [];

    // Datos de prueba
    docentes = [
        { id: 1, nombre: 'Pedro',    apellidos: 'Fernández Gil',  email: 'pfernandez@colegio.es', telefono: '600 111 222', asignaturas: ['Matemáticas', 'Física'] },
        { id: 2, nombre: 'Carmen',   apellidos: 'Torres Vega',    email: 'ctorres@colegio.es',    telefono: '600 333 444', asignaturas: ['Lengua', 'Literatura'] },
        { id: 3, nombre: 'Roberto',  apellidos: 'Iglesias Mora',  email: 'riglesias@colegio.es',  telefono: '600 555 666', asignaturas: ['Historia'] },
    ];
    renderTabla('docentes');
}

async function cargarTutores() {
    // const data = await api('GET', '/api/coord/tutores');
    // tutores = data || [];

    // Datos de prueba
    tutores = [
        { id: 1, nombre: 'María',  apellidos: 'López Sánchez',  email: 'mlopez@gmail.com',  telefono: '600 987 654', alumnos: ['Carlos García López'] },
        { id: 2, nombre: 'Juan',   apellidos: 'Martínez García', email: 'jmartinez@gmail.com', telefono: '600 123 456', alumnos: ['Lucía Martínez Ruiz'] },
    ];
    renderTabla('tutores');
}

/* ════════════════════════════════════════════
   RENDER TABLAS
════════════════════════════════════════════ */
function renderTabla(tipo) {
    const tbody = document.getElementById(`tbody-${tipo}`);
    let datos, html;

    if (tipo === 'alumnos') {
        datos = alumnos;
        if (!datos.length) {
            tbody.innerHTML = `<tr class="fila-vacia"><td colspan="6">No hay alumnos registrados.<br>Pulsa "Nuevo alumno" para añadir el primero.</td></tr>`;
            return;
        }
        html = datos.map(a => `
            <tr>
                <td>${a.nombre}</td>
                <td>${a.apellidos}</td>
                <td>${a.fnac ? formatFecha(a.fnac) : '—'}</td>
                <td>${a.curso || '—'}</td>
                <td>${a.clase || '—'}</td>
                <td>
                    <div class="acciones">
                        <button class="btn-tabla" onclick="editarAlumno(${a.id})">✏️ Editar</button>
                        <button class="btn-tabla danger" onclick="confirmarEliminar('alumno', ${a.id}, '${a.nombre} ${a.apellidos}')">🗑️</button>
                    </div>
                </td>
            </tr>`).join('');

    } else if (tipo === 'docentes') {
        datos = docentes;
        if (!datos.length) {
            tbody.innerHTML = `<tr class="fila-vacia"><td colspan="6">No hay docentes registrados.<br>Pulsa "Nuevo docente" para añadir el primero.</td></tr>`;
            return;
        }
        html = datos.map(d => `
            <tr>
                <td>${d.nombre}</td>
                <td>${d.apellidos}</td>
                <td>${d.email}</td>
                <td>${d.telefono || '—'}</td>
                <td>${(d.asignaturas || []).map(a => `<span class="tag">${a}</span>`).join('') || '—'}</td>
                <td>
                    <div class="acciones">
                        <button class="btn-tabla" onclick="editarDocente(${d.id})">✏️ Editar</button>
                        <button class="btn-tabla danger" onclick="confirmarEliminar('docente', ${d.id}, '${d.nombre} ${d.apellidos}')">🗑️</button>
                    </div>
                </td>
            </tr>`).join('');

    } else if (tipo === 'tutores') {
        datos = tutores;
        if (!datos.length) {
            tbody.innerHTML = `<tr class="fila-vacia"><td colspan="6">No hay tutores registrados.<br>Pulsa "Nuevo tutor" para añadir el primero.</td></tr>`;
            return;
        }
        html = datos.map(t => `
            <tr>
                <td>${t.nombre}</td>
                <td>${t.apellidos}</td>
                <td>${t.email}</td>
                <td>${t.telefono || '—'}</td>
                <td>${(t.alumnos || []).map(a => `<span class="tag">${a}</span>`).join('') || '—'}</td>
                <td>
                    <div class="acciones">
                        <button class="btn-tabla" onclick="editarTutor(${t.id})">✏️ Editar</button>
                        <button class="btn-tabla danger" onclick="confirmarEliminar('tutor', ${t.id}, '${t.nombre} ${t.apellidos}')">🗑️</button>
                    </div>
                </td>
            </tr>`).join('');
    }

    tbody.innerHTML = html;
}

/* ════════════════════════════════════════════
   TABS
════════════════════════════════════════════ */
function cambiarTab(tab, el) {
    tabActiva = tab;
    document.querySelectorAll('.coord-tab').forEach(t => t.classList.remove('activo'));
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('activo'));
    el.classList.add('activo');
    document.getElementById(`panel-${tab}`).classList.add('activo');
}

/* ════════════════════════════════════════════
   FILTRAR / BUSCAR
════════════════════════════════════════════ */
function filtrarLista(tipo) {
    const q = document.getElementById(`buscar-${tipo}`).value.toLowerCase();
    const datos = tipo === 'alumnos' ? alumnos : tipo === 'docentes' ? docentes : tutores;
    const filtrados = q
        ? datos.filter(x => `${x.nombre} ${x.apellidos}`.toLowerCase().includes(q) || (x.email && x.email.toLowerCase().includes(q)))
        : datos;

    const tbody = document.getElementById(`tbody-${tipo}`);
    if (!filtrados.length) {
        tbody.innerHTML = `<tr class="fila-vacia"><td colspan="6">Sin resultados para "${q}"</td></tr>`;
        return;
    }

    // Re-renderizar con los filtrados
    const backup = tipo === 'alumnos' ? alumnos : tipo === 'docentes' ? docentes : tutores;
    if (tipo === 'alumnos') alumnos = filtrados;
    else if (tipo === 'docentes') docentes = filtrados;
    else tutores = filtrados;

    renderTabla(tipo);

    if (tipo === 'alumnos') alumnos = backup;
    else if (tipo === 'docentes') docentes = backup;
    else tutores = backup;
}

/* ════════════════════════════════════════════
   MODAL — ALUMNO
════════════════════════════════════════════ */
function abrirModal(modalId, modo) {
    modoModal  = modo;
    idEditando = null;
    document.getElementById('alert-alumno') && (document.getElementById('alert-alumno').innerHTML = '');
    document.getElementById('alert-docente') && (document.getElementById('alert-docente').innerHTML = '');
    document.getElementById('alert-tutor') && (document.getElementById('alert-tutor').innerHTML = '');

    // Rellenar selects de cursos y tutores en modal alumno
    if (modalId === 'modal-alumno') {
        const selCurso = document.getElementById('a-curso');
        selCurso.innerHTML = '<option value="">Seleccionar curso…</option>' +
            cursos.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');

        // Al cambiar curso filtrar clases
        selCurso.onchange = () => {
            const cursoId = parseInt(selCurso.value);
            const selClase = document.getElementById('a-clase');
            const clasesDelCurso = clases.filter(c => c.curso_id === cursoId);
            selClase.innerHTML = '<option value="">Seleccionar clase…</option>' +
                clasesDelCurso.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
        };

        document.getElementById('a-clase').innerHTML = '<option value="">Seleccionar clase…</option>';

        // Tutores disponibles
        document.getElementById('a-tutor').innerHTML =
            '<option value="">Sin tutor asignado</option>' +
            tutores.map(t => `<option value="${t.id}">${t.nombre} ${t.apellidos}</option>`).join('');

        // Limpiar campos
        ['a-nombre','a-apellidos','a-fnac'].forEach(id => set(id, ''));
        set('a-curso', '');
        set('a-tutor', '');
        document.getElementById('modal-alumno-titulo').textContent = '➕ Nuevo alumno';
    }

    if (modalId === 'modal-tutor') {
        document.getElementById('t-alumno').innerHTML =
            '<option value="">Sin alumno asignado aún</option>' +
            alumnos.map(a => `<option value="${a.id}">${a.nombre} ${a.apellidos}</option>`).join('');
        ['t-nombre','t-apellidos','t-email','t-telefono','t-password'].forEach(id => set(id, ''));
        document.getElementById('modal-tutor-titulo').textContent = '➕ Nuevo tutor legal';
    }

    if (modalId === 'modal-docente') {
        ['d-nombre','d-apellidos','d-email','d-telefono','d-password','d-fnac'].forEach(id => set(id, ''));
        document.getElementById('modal-docente-titulo').textContent = '➕ Nuevo docente';
    }

    document.getElementById(modalId).classList.add('open');
}

/* ════════════════════════════════════════════
   GUARDAR ALUMNO
════════════════════════════════════════════ */
async function guardarAlumno() {
    const nombre    = v('a-nombre');
    const apellidos = v('a-apellidos');
    const fnac      = v('a-fnac');
    const cursoId   = v('a-curso');
    const claseId   = v('a-clase');
    const tutorId   = v('a-tutor');

    if (!nombre || !apellidos || !cursoId || !claseId) {
        alertModal('alert-alumno', 'err', '⚠️ Nombre, apellidos, curso y clase son obligatorios.');
        return;
    }

    const curso = cursos.find(c => c.id == cursoId);
    const clase = clases.find(c => c.id == claseId);
    const tutor = tutores.find(t => t.id == tutorId);

    if (modoModal === 'nuevo') {
        // const data = await api('POST', '/api/coord/alumnos', { nombre, apellidos, fnac, curso_id: cursoId, clase_id: claseId, tutor_id: tutorId || null });
        // if (data.error) { alertModal('alert-alumno', 'err', data.error); return; }

        // Añadir localmente para pruebas
        alumnos.push({
            id: Date.now(),
            nombre, apellidos, fnac,
            curso: curso?.nombre || '',
            clase: clase?.nombre || '',
            tutor: tutor ? `${tutor.nombre} ${tutor.apellidos}` : ''
        });
    } else {
        // const data = await api('PUT', `/api/coord/alumnos/${idEditando}`, { nombre, apellidos, fnac, curso_id: cursoId, clase_id: claseId, tutor_id: tutorId || null });
        // if (data.error) { alertModal('alert-alumno', 'err', data.error); return; }

        const idx = alumnos.findIndex(a => a.id === idEditando);
        if (idx !== -1) alumnos[idx] = { ...alumnos[idx], nombre, apellidos, fnac, curso: curso?.nombre || '', clase: clase?.nombre || '', tutor: tutor ? `${tutor.nombre} ${tutor.apellidos}` : '' };
    }

    renderTabla('alumnos');
    actualizarStats();
    cerrarModal('modal-alumno');
    toast(modoModal === 'nuevo' ? '✓ Alumno registrado' : '✓ Alumno actualizado');
}

function editarAlumno(id) {
    const a = alumnos.find(x => x.id === id);
    if (!a) return;
    modoModal  = 'editar';
    idEditando = id;
    abrirModal('modal-alumno', 'editar');
    setTimeout(() => {
        set('a-nombre',    a.nombre);
        set('a-apellidos', a.apellidos);
        set('a-fnac',      a.fnac || '');
        document.getElementById('modal-alumno-titulo').textContent = '✏️ Editar alumno';
    }, 50);
}

/* ════════════════════════════════════════════
   GUARDAR DOCENTE
════════════════════════════════════════════ */
async function guardarDocente() {
    const nombre    = v('d-nombre');
    const apellidos = v('d-apellidos');
    const email     = v('d-email');
    const telefono  = v('d-telefono');
    const password  = v('d-password');
    const fnac      = v('d-fnac');

    if (!nombre || !apellidos || !email) {
        alertModal('alert-docente', 'err', '⚠️ Nombre, apellidos y email son obligatorios.');
        return;
    }
    if (modoModal === 'nuevo' && !password) {
        alertModal('alert-docente', 'err', '⚠️ La contraseña inicial es obligatoria.');
        return;
    }
    if (password && password.length < 8) {
        alertModal('alert-docente', 'err', '⚠️ La contraseña debe tener al menos 8 caracteres.');
        return;
    }

    if (modoModal === 'nuevo') {
        // const data = await api('POST', '/api/coord/docentes', { nombre, apellidos, email, telefono, password, fnac });
        // if (data.error) { alertModal('alert-docente', 'err', data.error); return; }
        docentes.push({ id: Date.now(), nombre, apellidos, email, telefono, fnac, asignaturas: [] });
    } else {
        // const data = await api('PUT', `/api/coord/docentes/${idEditando}`, { nombre, apellidos, email, telefono, fnac });
        // if (data.error) { alertModal('alert-docente', 'err', data.error); return; }
        const idx = docentes.findIndex(d => d.id === idEditando);
        if (idx !== -1) docentes[idx] = { ...docentes[idx], nombre, apellidos, email, telefono, fnac };
    }

    renderTabla('docentes');
    actualizarStats();
    cerrarModal('modal-docente');
    toast(modoModal === 'nuevo' ? '✓ Docente registrado' : '✓ Docente actualizado');
}

function editarDocente(id) {
    const d = docentes.find(x => x.id === id);
    if (!d) return;
    modoModal = 'editar'; idEditando = id;
    abrirModal('modal-docente', 'editar');
    setTimeout(() => {
        set('d-nombre',    d.nombre);
        set('d-apellidos', d.apellidos);
        set('d-email',     d.email);
        set('d-telefono',  d.telefono || '');
        set('d-fnac',      d.fnac || '');
        document.getElementById('modal-docente-titulo').textContent = '✏️ Editar docente';
    }, 50);
}

/* ════════════════════════════════════════════
   GUARDAR TUTOR
════════════════════════════════════════════ */
async function guardarTutor() {
    const nombre      = v('t-nombre');
    const apellidos   = v('t-apellidos');
    const email       = v('t-email');
    const telefono    = v('t-telefono');
    const password    = v('t-password');
    const alumnoId    = v('t-alumno');
    const parentesco  = v('t-parentesco');

    if (!nombre || !apellidos || !email || !telefono) {
        alertModal('alert-tutor', 'err', '⚠️ Nombre, apellidos, email y teléfono son obligatorios.');
        return;
    }
    if (modoModal === 'nuevo' && !password) {
        alertModal('alert-tutor', 'err', '⚠️ La contraseña inicial es obligatoria.');
        return;
    }
    if (password && password.length < 8) {
        alertModal('alert-tutor', 'err', '⚠️ La contraseña debe tener al menos 8 caracteres.');
        return;
    }

    const alumno = alumnos.find(a => a.id == alumnoId);

    if (modoModal === 'nuevo') {
        // const data = await api('POST', '/api/coord/tutores', { nombre, apellidos, email, telefono, password, alumno_id: alumnoId || null, parentesco });
        // if (data.error) { alertModal('alert-tutor', 'err', data.error); return; }
        tutores.push({
            id: Date.now(), nombre, apellidos, email, telefono,
            alumnos: alumno ? [`${alumno.nombre} ${alumno.apellidos}`] : []
        });
    } else {
        // const data = await api('PUT', `/api/coord/tutores/${idEditando}`, { nombre, apellidos, email, telefono });
        // if (data.error) { alertModal('alert-tutor', 'err', data.error); return; }
        const idx = tutores.findIndex(t => t.id === idEditando);
        if (idx !== -1) tutores[idx] = { ...tutores[idx], nombre, apellidos, email, telefono };
    }

    renderTabla('tutores');
    actualizarStats();
    cerrarModal('modal-tutor');
    toast(modoModal === 'nuevo' ? '✓ Tutor registrado' : '✓ Tutor actualizado');
}

function editarTutor(id) {
    const t = tutores.find(x => x.id === id);
    if (!t) return;
    modoModal = 'editar'; idEditando = id;
    abrirModal('modal-tutor', 'editar');
    setTimeout(() => {
        set('t-nombre',    t.nombre);
        set('t-apellidos', t.apellidos);
        set('t-email',     t.email);
        set('t-telefono',  t.telefono || '');
        document.getElementById('modal-tutor-titulo').textContent = '✏️ Editar tutor';
    }, 50);
}

/* ════════════════════════════════════════════
   ELIMINAR
════════════════════════════════════════════ */
function confirmarEliminar(tipo, id, nombre) {
    document.getElementById('confirm-texto').textContent =
        `Vas a eliminar a "${nombre}". Esta acción no se puede deshacer.`;

    document.getElementById('btn-confirm-ok').onclick = async () => {
        // await api('DELETE', `/api/coord/${tipo}s/${id}`);

        if (tipo === 'alumno')  alumnos  = alumnos.filter(x => x.id !== id);
        if (tipo === 'docente') docentes = docentes.filter(x => x.id !== id);
        if (tipo === 'tutor')   tutores  = tutores.filter(x => x.id !== id);

        renderTabla(tipo === 'alumno' ? 'alumnos' : tipo === 'docente' ? 'docentes' : 'tutores');
        actualizarStats();
        cerrarModal('modal-confirmar');
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
function abrirModal(id)  { document.getElementById(id).classList.add('open'); }
function cerrarModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});

function v(id)        { return document.getElementById(id)?.value.trim() || ''; }
function set(id, val) { const el = document.getElementById(id); if (el) el.value = val; }

function formatFecha(fecha) {
    if (!fecha) return '—';
    const [y, m, d] = fecha.split('-');
    return `${d}/${m}/${y}`;
}

function alertModal(contenedorId, tipo, texto) {
    document.getElementById(contenedorId).innerHTML =
        `<div class="alert-modal alert-${tipo}">${texto}</div>`;
}

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

/* ── Logout ── */
document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = 'login.html';
});
