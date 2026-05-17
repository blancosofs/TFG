/* ══════════════════════════════════════════════════════════════
   Edunoly · coordinador.js
══════════════════════════════════════════════════════════════ */

const API = '';

/* ── Estado ── */
let sesion     = null;
let alumnos    = [];
let docentes   = [];
let tutores    = [];
let cursos     = [];
let clases     = [];
let horarios   = [];
let modoModal  = 'nuevo';
let idEditando = null;
let tabActiva  = 'alumnos';

/* ════════════════════════════════════════════
   API
════════════════════════════════════════════ */
async function api(method, ruta, body = null) {
    const opts = {
        method,
        credentials: 'include',
        headers: {
            'Content-Type':  'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]')?.content,
        },
    };
    if (body) opts.body = JSON.stringify(body);

    try {
        const res  = await fetch(ruta, opts);
        const data = await res.json().catch(() => ({}));

        if (!res.ok) {
            if (res.status === 422 && data.errors) {
                const primer = Object.values(data.errors)[0];
                return { ok: false, mensaje: Array.isArray(primer) ? primer[0] : primer };
            }
            if (res.status === 401) { window.location.href = '/login'; return { ok: false }; }
            if (res.status === 403) return { ok: false, mensaje: 'No tienes permisos para realizar esta acción.' };
            if (res.status === 404) return { ok: false, mensaje: 'El registro solicitado no existe.' };
            if (res.status >= 500) {
                console.error(`[API ${method} ${ruta}]`, data);
                return { ok: false, mensaje: 'Error interno del servidor. Inténtalo de nuevo más tarde.' };
            }
            return { ok: false, mensaje: data.mensaje || data.message || 'Ha ocurrido un error inesperado.' };
        }

        return data;
    } catch (e) {
        console.error('[API red]', e);
        return { ok: false, mensaje: 'Error de conexión. Comprueba tu red e inténtalo de nuevo.' };
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

    const nombreCompleto = `${sesion.nombre} ${sesion.apellidos}`.trim();
    const navNombre = document.getElementById('nav-nombre');
    if (navNombre) navNombre.textContent = nombreCompleto;
    const heroColegio = document.getElementById('hero-colegio');
    if (heroColegio) heroColegio.textContent = sesion.colegio ?? 'Mi centro educativo';

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
        cargarTutores(),
        cargarHorarios(),
    ]);
    actualizarStats();
}

async function cargarCursos() {
    const resCursos = await api('GET', '/api/cursos');
    const resClases = await api('GET', '/api/clases');

    cursos = resCursos.error ? [] : (Array.isArray(resCursos) ? resCursos : []);
    clases = resClases.error ? [] : (Array.isArray(resClases) ? resClases : []);

    renderTabla('cursos');
    renderTabla('clases');
}

async function cargarAlumnos() {
    const data = await api('GET', '/api/alumnos');
    alumnos = (data.error || !Array.isArray(data)) ? [] : data.map(a => ({
        id:       a.id,
        nombre:   a.nombre,
        apellidos: a.apellidos,
        fnac:     a.fecha_nacimiento ?? '',
        curso:    a.curso  ? a.curso.nombre  : '—',
        clase:    a.clase  ? a.clase.nombre  : '—',
        curso_id: a.curso_id,
        clase_id: a.clase_id,
    }));
    renderTabla('alumnos');
}

async function cargarDocentes() {
    const data = await api('GET', '/api/docentes');
    docentes = (data.error || !Array.isArray(data)) ? [] : data.map(d => ({
        id:          d.id,
        nombre:      d.user ? d.user.name      : 'Sin nombre',
        apellidos:   d.user ? d.user.apellidos  : '',
        email:       d.user ? d.user.email      : '',
        telefono:    d.telefono || '—',
        asignaturas: d.asignaturas
            ? d.asignaturas.split(',').map(s => s.trim()).filter(Boolean)
            : [],
    }));
    renderTabla('docentes');
}

async function cargarTutores() {
    const data = await api('GET', '/api/tutores');
    tutores = (data.error || !Array.isArray(data)) ? [] : data.map(t => ({
        id:                  t.id,
        nombre:              t.user ? t.user.name      : 'Sin nombre',
        apellidos:           t.user ? t.user.apellidos  : '',
        email:               t.user ? t.user.email      : '',
        telefono:            t.telefono || '—',
        alumnos:             t.alumnos ? t.alumnos.map(h => `${h.nombre} ${h.apellidos}`) : [],
        alumno_ids:          t.alumnos ? t.alumnos.map(h => h.id) : [],
        alumno_parentescos:  t.alumnos ? t.alumnos.map(h => h.pivot?.parentesco ?? 'padre') : [],
    }));
    renderTabla('tutores');
}

async function cargarHorarios() {
    const data = await api('GET', '/api/horarios');
    horarios = Array.isArray(data) ? data : [];
    renderTabla('horarios');
}

/* ════════════════════════════════════════════
   RENDER TABLAS
════════════════════════════════════════════ */
function renderTabla(tipo) {
    const tbody = document.getElementById(`tbody-${tipo}`);
    if (!tbody) return;
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
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarAlumno(${a.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('alumno', ${a.id}, '${a.nombre} ${a.apellidos}')">🗑️</button>
                </div></td>
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
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarDocente(${d.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('docente', ${d.id}, '${d.nombre} ${d.apellidos}')">🗑️</button>
                </div></td>
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
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarTutor(${t.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('tutor', ${t.id}, '${t.nombre} ${t.apellidos}')">🗑️</button>
                </div></td>
            </tr>`).join('');

    } else if (tipo === 'cursos') {
        datos = cursos;
        if (!datos.length) {
            tbody.innerHTML = `<tr class="fila-vacia"><td colspan="3">No hay cursos registrados.<br>Pulsa "Nuevo curso" para añadir el primero.</td></tr>`;
            return;
        }
        html = datos.map(c => {
            const nClases = clases.filter(cl => cl.curso_id === c.id).length;
            return `<tr>
                <td>${c.nombre}</td>
                <td>${nClases}</td>
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarCurso(${c.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('curso', ${c.id}, '${c.nombre}')">🗑️</button>
                </div></td>
            </tr>`;
        }).join('');

    } else if (tipo === 'clases') {
        datos = clases;
        if (!datos.length) {
            tbody.innerHTML = `<tr class="fila-vacia"><td colspan="3">No hay clases registradas.<br>Pulsa "Nueva clase" para añadir la primera.</td></tr>`;
            return;
        }
        html = datos.map(cl => {
            const curso = cursos.find(c => c.id === cl.curso_id);
            return `<tr>
                <td>${cl.nombre}</td>
                <td>${curso?.nombre ?? '—'}</td>
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarClase(${cl.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('clase', ${cl.id}, '${cl.nombre}')">🗑️</button>
                </div></td>
            </tr>`;
        }).join('');

    } else if (tipo === 'horarios') {
        datos = horarios;
        if (!datos.length) {
            tbody.innerHTML = `<tr class="fila-vacia"><td colspan="7">No hay horarios registrados.<br>Pulsa "Nuevo horario" para añadir el primero.</td></tr>`;
            return;
        }
        const diasLabel = { lunes: 'Lunes', martes: 'Martes', miercoles: 'Miércoles', jueves: 'Jueves', viernes: 'Viernes' };
        html = datos.map(h => `
            <tr>
                <td>${h.docente}</td>
                <td>${h.clase}</td>
                <td>${h.asignatura ? `<span class="tag">${h.asignatura}</span>` : '—'}</td>
                <td>${diasLabel[h.dia_semana] ?? h.dia_semana}</td>
                <td>${h.hora_inicio?.slice(0, 5) ?? '—'}</td>
                <td>${h.hora_fin?.slice(0, 5) ?? '—'}</td>
                <td><div class="acciones">
                    <button class="btn-tabla" onclick="editarHorario(${h.id})">✏️ Editar</button>
                    <button class="btn-tabla danger" onclick="confirmarEliminar('horario', ${h.id}, '${h.docente} – ${h.clase}')">🗑️</button>
                </div></td>
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
    const mapa = { alumnos, docentes, tutores, horarios, cursos, clases };
    const datos = mapa[tipo] ?? [];

    const filtrados = q
        ? datos.filter(x => {
            const texto = tipo === 'horarios'
                ? `${x.docente} ${x.clase} ${x.dia_semana}`.toLowerCase()
                : (tipo === 'cursos' || tipo === 'clases')
                    ? `${x.nombre}`.toLowerCase()
                    : `${x.nombre} ${x.apellidos} ${x.email ?? ''}`.toLowerCase();
            return texto.includes(q);
        })
        : datos;

    const cols = { alumnos: 6, docentes: 6, tutores: 6, horarios: 7, cursos: 3, clases: 3 };
    const tbody = document.getElementById(`tbody-${tipo}`);
    if (!filtrados.length) {
        tbody.innerHTML = `<tr class="fila-vacia"><td colspan="${cols[tipo] ?? 6}">Sin resultados para "${q}"</td></tr>`;
        return;
    }

    const backup = [...datos];
    if (tipo === 'alumnos')  alumnos  = filtrados;
    else if (tipo === 'docentes') docentes = filtrados;
    else if (tipo === 'tutores')  tutores  = filtrados;
    else if (tipo === 'horarios') horarios = filtrados;
    else if (tipo === 'cursos')   cursos   = filtrados;
    else if (tipo === 'clases')   clases   = filtrados;

    renderTabla(tipo);

    if (tipo === 'alumnos')  alumnos  = backup;
    else if (tipo === 'docentes') docentes = backup;
    else if (tipo === 'tutores')  tutores  = backup;
    else if (tipo === 'horarios') horarios = backup;
    else if (tipo === 'cursos')   cursos   = backup;
    else if (tipo === 'clases')   clases   = backup;
}

/* ════════════════════════════════════════════
   ABRIR MODAL
════════════════════════════════════════════ */
function abrirModal(modalId, modo) {
    modoModal  = modo;
    idEditando = null;

    ['alert-alumno','alert-docente','alert-tutor','alert-curso','alert-clase','alert-horario']
        .forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = ''; });

    if (modalId === 'modal-alumno') {
        const selCurso = document.getElementById('a-curso');
        selCurso.innerHTML = '<option value="">Seleccionar curso…</option>' +
            cursos.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
        selCurso.onchange = () => {
            const cursoId = parseInt(selCurso.value);
            const selClase = document.getElementById('a-clase');
            selClase.innerHTML = '<option value="">Seleccionar clase…</option>' +
                clases.filter(c => c.curso_id === cursoId)
                      .map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
        };
        document.getElementById('a-clase').innerHTML = '<option value="">Seleccionar clase…</option>';
        document.getElementById('a-tutor').innerHTML =
            '<option value="">Sin tutor asignado</option>' +
            tutores.map(t => `<option value="${t.id}">${t.nombre} ${t.apellidos}</option>`).join('');
        ['a-nombre','a-apellidos','a-fnac'].forEach(id => set(id, ''));
        set('a-curso', ''); set('a-tutor', '');
        document.getElementById('modal-alumno-titulo').textContent = '➕ Nuevo alumno';
    }

    if (modalId === 'modal-docente') {
        ['d-nombre','d-apellidos','d-email','d-telefono','d-password','d-fnac','d-asignaturas']
            .forEach(id => set(id, ''));
        document.getElementById('modal-docente-titulo').textContent = '➕ Nuevo docente';
    }

    if (modalId === 'modal-tutor') {
        document.getElementById('t-alumno').innerHTML =
            '<option value="">Sin alumno asignado aún</option>' +
            alumnos.map(a => `<option value="${a.id}">${a.nombre} ${a.apellidos}</option>`).join('');
        ['t-nombre','t-apellidos','t-email','t-telefono','t-password'].forEach(id => set(id, ''));
        document.getElementById('modal-tutor-titulo').textContent = '➕ Nuevo tutor legal';
    }

    if (modalId === 'modal-curso') {
        set('c-nombre', '');
        document.getElementById('modal-curso-titulo').textContent = '➕ Nuevo curso';
    }

    if (modalId === 'modal-clase') {
        document.getElementById('cl-curso').innerHTML =
            '<option value="">Seleccionar curso…</option>' +
            cursos.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
        set('cl-nombre', ''); set('cl-curso', ''); set('cl-codigo', '');
        document.getElementById('modal-clase-titulo').textContent = '➕ Nueva clase';
    }

    if (modalId === 'modal-horario') {
        const selDocente = document.getElementById('h-docente');
        selDocente.innerHTML = '<option value="">Seleccionar docente…</option>' +
            docentes.map(d => `<option value="${d.id}">${d.nombre} ${d.apellidos}</option>`).join('');
        document.getElementById('h-clase').innerHTML = '<option value="">Seleccionar clase…</option>' +
            clases.map(c => {
                const curso = cursos.find(x => x.id === c.curso_id);
                return `<option value="${c.id}">${curso ? curso.nombre + ' – ' : ''}${c.nombre}</option>`;
            }).join('');
        selDocente.onchange = () => actualizarAsignaturasHorario();
        ['h-dia','h-inicio','h-fin','h-asignatura'].forEach(id => set(id, ''));
        document.getElementById('modal-horario-titulo').textContent = '➕ Nuevo horario';
    }

    document.getElementById(modalId).classList.add('open');
}

/* ════════════════════════════════════════════
   GUARDAR ALUMNO
════════════════════════════════════════════ */
async function guardarAlumno() {
    const nombre     = v('a-nombre');
    const apellidos  = v('a-apellidos');
    const fnac       = v('a-fnac');
    const cursoId    = v('a-curso');
    const claseId    = v('a-clase');
    const tutorId    = v('a-tutor');
    const parentesco = v('a-parentesco');

    if (!nombre || !apellidos || !fnac || !cursoId || !claseId) {
        alertModal('alert-alumno', 'err', '❌ Nombre, apellidos, fecha, curso y clase son obligatorios.');
        return;
    }

    const payload = { nombre, apellidos, fecha_nacimiento: fnac, curso_id: cursoId, clase_id: claseId, tutor_id: tutorId || null, parentesco, activo: true };
    const url    = modoModal === 'nuevo' ? '/api/alumnos' : `/api/alumnos/${idEditando}`;
    const metodo = modoModal === 'nuevo' ? 'POST' : 'PUT';

    const btn = document.querySelector('#modal-alumno .modal-actions button:last-child');
    setBtnLoading(btn, true);
    try {
        const r = await api(metodo, url, payload);
        if (r.error || r.message || r.errors) {
            alertModal('alert-alumno', 'err', '❌ ' + (r.error || r.message || 'Error desconocido'));
            return;
        }
        await cargarTodo();
        cerrarModal('modal-alumno');
        toast(modoModal === 'nuevo' ? '✅ Alumno registrado' : '✅ Alumno actualizado');
        if (typeof audit === 'function') audit(modoModal === 'nuevo' ? 'alumno_registrado' : 'alumno_actualizado', 'alumno', `${nombre} ${apellidos}`);
    } finally {
        setBtnLoading(btn, false);
    }
}

function editarAlumno(id) {
    const a = alumnos.find(x => x.id === id);
    if (!a) return;
    abrirModal('modal-alumno', 'editar');
    idEditando = id;
    set('a-nombre',    a.nombre);
    set('a-apellidos', a.apellidos);
    set('a-fnac',      a.fnac || '');
    const selCurso = document.getElementById('a-curso');
    selCurso.value = a.curso_id;
    selCurso.dispatchEvent(new Event('change'));
    document.getElementById('a-clase').value = a.clase_id;
    document.getElementById('modal-alumno-titulo').textContent = '✏️ Editar alumno';
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
    const asignaturas = v('d-asignaturas');

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

    const payload = { nombre, apellidos, email, telefono, fnac, asignaturas };
    if (password) payload.password = password;

    const url    = modoModal === 'nuevo' ? '/api/docentes' : `/api/docentes/${idEditando}`;
    const metodo = modoModal === 'nuevo' ? 'POST' : 'PUT';

    const btn = document.querySelector('#modal-docente .modal-actions button:last-child');
    setBtnLoading(btn, true);
    try {
        const r = await api(metodo, url, payload);
        if (!r.ok) {
            alertModal('alert-docente', 'err', '❌ ' + (r.mensaje || r.message || 'Error desconocido'));
            return;
        }
        await cargarTodo();
        cerrarModal('modal-docente');
        toast(modoModal === 'nuevo' ? '✓ Docente registrado' : '✓ Docente actualizado');
        if (typeof audit === 'function') audit(modoModal === 'nuevo' ? 'docente_registrado' : 'docente_actualizado', 'docente', `${nombre} ${apellidos}`);
    } finally {
        setBtnLoading(btn, false);
    }
}

function editarDocente(id) {
    const d = docentes.find(x => x.id === id);
    if (!d) return;
    abrirModal('modal-docente', 'editar');
    idEditando = id;
    set('d-nombre',      d.nombre);
    set('d-apellidos',   d.apellidos);
    set('d-email',       d.email);
    set('d-telefono',    d.telefono === '—' ? '' : d.telefono || '');
    set('d-fnac',        d.fnac || '');
    set('d-asignaturas', (d.asignaturas || []).join(', '));
    document.getElementById('modal-docente-titulo').textContent = '✏️ Editar docente';
}

/* ════════════════════════════════════════════
   GUARDAR TUTOR
════════════════════════════════════════════ */
async function guardarTutor() {
    const nombre     = v('t-nombre');
    const apellidos  = v('t-apellidos');
    const email      = v('t-email');
    const telefono   = v('t-telefono');
    const password   = v('t-password');
    const alumnoId   = v('t-alumno');
    const parentesco = v('t-parentesco');

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

    const btn = document.querySelector('#modal-tutor .modal-actions button:last-child');
    setBtnLoading(btn, true);
    try {
        if (modoModal === 'nuevo') {
            const r = await api('POST', '/api/tutores', { nombre, apellidos, email, telefono, password, alumno_id: alumnoId || null, parentesco });
            if (!r.ok) { alertModal('alert-tutor', 'err', '❌ ' + (r.mensaje || r.message || 'Error desconocido')); return; }
        } else {
            const payload = { nombre, apellidos, email, telefono, alumno_id: alumnoId || null, parentesco };
            if (password) payload.password = password;
            const r = await api('PUT', `/api/tutores/${idEditando}`, payload);
            if (!r.ok) { alertModal('alert-tutor', 'err', '❌ ' + (r.mensaje || r.message || 'Error desconocido')); return; }
        }
        await cargarTodo();
        cerrarModal('modal-tutor');
        toast(modoModal === 'nuevo' ? '✓ Tutor registrado' : '✓ Tutor actualizado');
        if (typeof audit === 'function') audit(modoModal === 'nuevo' ? 'tutor_registrado' : 'tutor_actualizado', 'tutor', `${nombre} ${apellidos}`);
    } finally {
        setBtnLoading(btn, false);
    }
}

function editarTutor(id) {
    const t = tutores.find(x => x.id === id);
    if (!t) return;
    abrirModal('modal-tutor', 'editar');
    idEditando = id;
    set('t-nombre',    t.nombre);
    set('t-apellidos', t.apellidos);
    set('t-email',     t.email);
    set('t-telefono',  t.telefono === '—' ? '' : t.telefono || '');
    if (t.alumno_ids?.length) {
        set('t-alumno',     t.alumno_ids[0]);
        set('t-parentesco', t.alumno_parentescos[0] || 'padre');
    }
    document.getElementById('modal-tutor-titulo').textContent = '✏️ Editar tutor';
}

/* ════════════════════════════════════════════
   GUARDAR CURSO
════════════════════════════════════════════ */
async function guardarCurso() {
    const nombre = v('c-nombre');
    if (!nombre) { alertModal('alert-curso', 'err', '⚠️ El nombre es obligatorio.'); return; }

    const url    = modoModal === 'nuevo' ? '/api/cursos' : `/api/cursos/${idEditando}`;
    const metodo = modoModal === 'nuevo' ? 'POST' : 'PUT';
    const r = await api(metodo, url, { nombre });
    if (!r?.ok) { alertModal('alert-curso', 'err', '❌ ' + (r?.mensaje || r?.message || 'Error desconocido')); return; }

    await cargarCursos();
    cerrarModal('modal-curso');
    toast(modoModal === 'nuevo' ? '✓ Curso creado' : '✓ Curso actualizado');
}

function editarCurso(id) {
    const c = cursos.find(x => x.id === id);
    if (!c) return;
    abrirModal('modal-curso', 'editar');
    idEditando = id;
    set('c-nombre', c.nombre);
    document.getElementById('modal-curso-titulo').textContent = '✏️ Editar curso';
}

/* ════════════════════════════════════════════
   GUARDAR CLASE
════════════════════════════════════════════ */
async function guardarClase() {
    const nombre       = v('cl-nombre');
    const cursoId      = v('cl-curso');
    const codigoAcceso = v('cl-codigo') || null;
    if (!nombre || !cursoId) { alertModal('alert-clase', 'err', '⚠️ Nombre y curso son obligatorios.'); return; }

    const url    = modoModal === 'nuevo' ? '/api/clases' : `/api/clases/${idEditando}`;
    const metodo = modoModal === 'nuevo' ? 'POST' : 'PUT';
    const r = await api(metodo, url, { nombre, curso_id: cursoId, codigo_acceso: codigoAcceso });
    if (!r?.ok) { alertModal('alert-clase', 'err', '❌ ' + (r?.mensaje || r?.message || 'Error desconocido')); return; }

    await cargarCursos();
    cerrarModal('modal-clase');
    toast(modoModal === 'nuevo' ? '✓ Clase creada' : '✓ Clase actualizada');
}

function editarClase(id) {
    const cl = clases.find(x => x.id === id);
    if (!cl) return;
    abrirModal('modal-clase', 'editar');
    idEditando = id;
    set('cl-curso',  cl.curso_id);
    set('cl-nombre', cl.nombre);
    set('cl-codigo', cl.codigo_acceso ?? '');
    document.getElementById('modal-clase-titulo').textContent = '✏️ Editar clase';
}

/* ════════════════════════════════════════════
   GUARDAR HORARIO
════════════════════════════════════════════ */
function actualizarAsignaturasHorario(valorActual = '') {
    const docenteId = parseInt(document.getElementById('h-docente').value);
    const docente   = docentes.find(d => d.id === docenteId);
    document.getElementById('h-asignatura-list').innerHTML =
        (docente?.asignaturas ?? []).map(a => `<option value="${a}">`).join('');
    if (valorActual) set('h-asignatura', valorActual);
}

async function guardarHorario() {
    const docenteId = v('h-docente');
    const claseId   = v('h-clase');
    const dia       = v('h-dia');
    const inicio    = v('h-inicio');
    const fin       = v('h-fin');

    if (!docenteId || !claseId || !dia || !inicio || !fin) {
        alertModal('alert-horario', 'err', '⚠️ Todos los campos son obligatorios.');
        return;
    }
    if (inicio >= fin) {
        alertModal('alert-horario', 'err', '⚠️ La hora de fin debe ser posterior a la de inicio.');
        return;
    }

    const asignatura = v('h-asignatura') || null;
    const payload = { docente_id: docenteId, clase_id: claseId, dia_semana: dia, hora_inicio: inicio, hora_fin: fin, asignatura };
    const url    = modoModal === 'nuevo' ? '/api/horarios' : `/api/horarios/${idEditando}`;
    const metodo = modoModal === 'nuevo' ? 'POST' : 'PUT';

    const r = await api(metodo, url, payload);
    if (!r?.ok) { alertModal('alert-horario', 'err', '❌ ' + (r?.mensaje || r?.message || 'Error desconocido')); return; }

    await cargarHorarios();
    cerrarModal('modal-horario');
    toast(modoModal === 'nuevo' ? '✓ Horario creado' : '✓ Horario actualizado');
}

function editarHorario(id) {
    const h = horarios.find(x => x.id === id);
    if (!h) return;
    abrirModal('modal-horario', 'editar');
    idEditando = id;
    set('h-docente', h.docente_id);
    actualizarAsignaturasHorario(h.asignatura ?? '');
    set('h-clase',  h.clase_id);
    set('h-dia',    h.dia_semana);
    set('h-inicio', h.hora_inicio?.slice(0, 5) ?? '');
    set('h-fin',    h.hora_fin?.slice(0, 5) ?? '');
    document.getElementById('modal-horario-titulo').textContent = '✏️ Editar horario';
}

/* ════════════════════════════════════════════
   ELIMINAR
════════════════════════════════════════════ */
function confirmarEliminar(tipo, id, nombre) {
    document.getElementById('confirm-texto').textContent =
        `Vas a eliminar a "${nombre}". Esta acción no se puede deshacer.`;

    document.getElementById('btn-confirm-ok').onclick = async () => {
        const r = await api('DELETE', `/api/${tipo}s/${id}`);
        if (r.ok || r.mensaje) {
            if (tipo === 'alumno')  alumnos  = alumnos.filter(x => x.id !== id);
            if (tipo === 'docente') docentes = docentes.filter(x => x.id !== id);
            if (tipo === 'tutor')   tutores  = tutores.filter(x => x.id !== id);
            if (tipo === 'horario') horarios = horarios.filter(x => x.id !== id);
            if (tipo === 'curso')   { cursos = cursos.filter(x => x.id !== id); clases = clases.filter(x => x.curso_id !== id); renderTabla('clases'); }
            if (tipo === 'clase')   clases   = clases.filter(x => x.id !== id);

            const tablaMap = { alumno: 'alumnos', docente: 'docentes', tutor: 'tutores', horario: 'horarios', curso: 'cursos', clase: 'clases' };
            renderTabla(tablaMap[tipo] ?? tipo + 's');
            actualizarStats();
            cerrarModal('modal-confirmar');
            toast(`🗑️ ${nombre} eliminado`);
            if (typeof audit === 'function') audit('eliminado', tipo, nombre);
        } else {
            alert('No se pudo eliminar: ' + (r.message || r.error || 'Error desconocido'));
            cerrarModal('modal-confirmar');
        }
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
   INFORME DEL CENTRO
════════════════════════════════════════════ */
function calcularEstadisticasCentro() {
    const porCurso = {};
    alumnos.forEach(a => {
        const c = a.curso || 'Sin curso';
        porCurso[c] = (porCurso[c] || 0) + 1;
    });

    const ratioAlumnoDocente = docentes.length
        ? (alumnos.length / docentes.length).toFixed(1)
        : '—';

    const alumnosConTutor = new Set(tutores.flatMap(t => t.alumno_ids ?? []));
    const conTutor = alumnos.filter(a => alumnosConTutor.has(a.id)).length;
    const pctConTutor = alumnos.length
        ? Math.round((conTutor / alumnos.length) * 100)
        : 0;

    return { totalAlumnos: alumnos.length, totalDocentes: docentes.length, totalTutores: tutores.length, ratioAlumnoDocente, pctConTutor, distribucionCursos: porCurso };
}

function generarInformeCentro() {
    const stats = calcularEstadisticasCentro();
    const ahora = new Date().toLocaleString('es-ES');
    const coord = `${sesion.nombre} ${sesion.apellidos}`;

    const lineas = [
        `INFORME DEL CENTRO — ${sesion.colegio}`,
        `Generado por: ${coord}`,
        `Fecha: ${ahora}`,
        '═'.repeat(50),
        '',
        'RESUMEN',
        `  Alumnos:             ${stats.totalAlumnos}`,
        `  Docentes:            ${stats.totalDocentes}`,
        `  Tutores legales:     ${stats.totalTutores}`,
        `  Ratio alumnos/doc.:  ${stats.ratioAlumnoDocente}`,
        `  Alumnos con tutor:   ${stats.pctConTutor}%`,
        '',
        'DISTRIBUCIÓN POR CURSO',
        ...Object.entries(stats.distribucionCursos).map(
            ([curso, n]) => `  ${curso.padEnd(20)} ${n} alumno${n !== 1 ? 's' : ''}`
        ),
    ];

    const blob = new Blob([lineas.join('\n')], { type: 'text/plain;charset=utf-8' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url;
    a.download = `informe_centro_${new Date().toISOString().slice(0, 10)}.txt`;
    a.click();
    URL.revokeObjectURL(url);

    if (typeof audit === 'function') audit('informe_generado', 'centro', sesion.colegio);
    toast('📄 Informe del centro descargado');
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
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

function setBtnLoading(btn, loading) {
    if (!btn) return;
    if (loading) {
        btn.disabled = true;
        btn.dataset.orig = btn.innerHTML;
        btn.innerHTML = '⏳ Guardando…';
    } else {
        btn.disabled = false;
        btn.innerHTML = btn.dataset.orig ?? btn.innerHTML;
        delete btn.dataset.orig;
    }
}

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
