/* ══════════════════════════════════════════════════════════════
   Edunoly · pasarLista.js

   ALGORITMO DE CONTROL DE ASISTENCIA:
   1. Carga las clases del docente autenticado
   2. Al seleccionar clase, carga los alumnos de esa clase
   3. El docente marca a cada alumno como:
      - Presente ✅ / Ausente ❌ / Retraso ⏰
      - Con nota opcional
   4. Al guardar, envía el registro a la API:
      POST /api/asistencia con { fecha, clase_id, alumnos: [{id, estado, nota}] }
   5. El servidor guarda en la tabla 'ausencias' solo los que
      NO están presentes (ausencias y retrasos)
══════════════════════════════════════════════════════════════ */

const API = '';

/* ── Estado ── */
let sesion        = null;
let clases        = [];       // clases del docente
let alumnos       = [];       // alumnos de la clase seleccionada
let asistencia    = {};       // { alumno_id: { estado: 'presente'|'ausente'|'retraso', nota: '' } }
let alumnoModal   = null;     // id del alumno que se está editando en el modal

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    // const data = await api('GET', '/api/me');
    // if (!data || !data.id) { window.location.href = '/login'; return; }
    // if (data.rol !== 'docente') { window.location.href = '/login'; return; }
    // sesion = data;

    // Datos de prueba — quitar cuando el servidor esté activo
    sesion = {
        id: 1, nombre: 'Pedro', apellidos: 'Fernández Gil',
        email: 'pfernandez@colegio.es', rol: 'docente', colegio_id: 1
    };

    document.getElementById('nav-nombre').textContent = `${sesion.nombre} ${sesion.apellidos}`;

    // Fecha de hoy por defecto
    const hoy = new Date().toISOString().slice(0, 10);
    document.getElementById('filtro-fecha').value = hoy;

    await cargarClasesDocente();
})();

/* ════════════════════════════════════════════
   CARGAR CLASES DEL DOCENTE
════════════════════════════════════════════ */
async function cargarClasesDocente() {
    // const data = await api('GET', '/api/clases');
    // clases = data || [];

    // Datos de prueba
    clases = [
        { id: 1, nombre: '1ºA', curso: '1º ESO', asignaturas: ['Matemáticas', 'Física'] },
        { id: 2, nombre: '1ºB', curso: '1º ESO', asignaturas: ['Matemáticas'] },
        { id: 3, nombre: '2ºA', curso: '2º ESO', asignaturas: ['Física', 'Programación'] },
    ];

    const selClase = document.getElementById('filtro-clase');
    selClase.innerHTML = '<option value="">Seleccionar clase…</option>' +
        clases.map(c => `<option value="${c.id}">${c.curso} — ${c.nombre}</option>`).join('');

    selClase.addEventListener('change', () => {
        actualizarAsignaturas();
        cargarAlumnos();
    });
}

/* ── Actualizar asignaturas según la clase seleccionada ── */
function actualizarAsignaturas() {
    const claseId = parseInt(document.getElementById('filtro-clase').value);
    const clase   = clases.find(c => c.id === claseId);
    const selAsig = document.getElementById('filtro-asignatura');

    if (!clase) {
        selAsig.innerHTML = '<option value="">Seleccionar asignatura…</option>';
        return;
    }

    selAsig.innerHTML = '<option value="">Todas las asignaturas</option>' +
        clase.asignaturas.map(a => `<option value="${a}">${a}</option>`).join('');
}

/* ════════════════════════════════════════════
   CARGAR ALUMNOS DE LA CLASE
════════════════════════════════════════════ */
async function cargarAlumnos() {
    const claseId = document.getElementById('filtro-clase').value;

    if (!claseId) {
        document.getElementById('aviso-seleccionar').style.display = 'flex';
        document.getElementById('lista-alumnos').style.display     = 'none';
        return;
    }

    // const data = await api('GET', `/api/clases/${claseId}/alumnos`);
    // alumnos = data || [];

    // Datos de prueba según la clase
    const datosPrueba = {
        1: [
            { id: 1,  nombre: 'Carlos',    apellidos: 'García López' },
            { id: 2,  nombre: 'Lucía',     apellidos: 'Martínez Ruiz' },
            { id: 3,  nombre: 'Alejandro', apellidos: 'Sánchez Pérez' },
            { id: 4,  nombre: 'María',     apellidos: 'López Torres' },
            { id: 5,  nombre: 'Pablo',     apellidos: 'Fernández Gil' },
            { id: 6,  nombre: 'Ana',       apellidos: 'Rodríguez Mora' },
            { id: 7,  nombre: 'David',     apellidos: 'González Vega' },
            { id: 8,  nombre: 'Laura',     apellidos: 'Díaz Serrano' },
        ],
        2: [
            { id: 9,  nombre: 'Sofía',     apellidos: 'Ruiz Castillo' },
            { id: 10, nombre: 'Marcos',    apellidos: 'Jiménez Ramos' },
            { id: 11, nombre: 'Elena',     apellidos: 'Moreno Cruz' },
            { id: 12, nombre: 'Hugo',      apellidos: 'Navarro Blanco' },
        ],
        3: [
            { id: 13, nombre: 'Valentina', apellidos: 'Pérez Iglesias' },
            { id: 14, nombre: 'Daniel',    apellidos: 'Herrera Nieto' },
            { id: 15, nombre: 'Carmen',    apellidos: 'Romero Fuentes' },
        ],
    };

    alumnos = datosPrueba[claseId] || [];

    // Inicializar asistencia — todos presentes por defecto
    asistencia = {};
    alumnos.forEach(a => {
        asistencia[a.id] = { estado: 'presente', nota: '' };
    });

    // Mostrar UI
    const clase = clases.find(c => c.id == claseId);
    const fecha = document.getElementById('filtro-fecha').value;

    document.getElementById('lista-clase-nombre').textContent =
        `${clase?.curso} — Clase ${clase?.nombre}`;
    document.getElementById('lista-fecha-texto').textContent  =
        formatFechaLarga(fecha);

    document.getElementById('aviso-seleccionar').style.display = 'none';
    document.getElementById('lista-alumnos').style.display     = 'block';

    renderAlumnos();
    actualizarStats();
    actualizarResumen();
}

/* ════════════════════════════════════════════
   RENDER TARJETAS DE ALUMNOS
════════════════════════════════════════════ */
function renderAlumnos(filtro = '') {
    const grid  = document.getElementById('alumnos-grid');
    const q     = filtro.toLowerCase();
    const lista = q
        ? alumnos.filter(a => `${a.nombre} ${a.apellidos}`.toLowerCase().includes(q))
        : alumnos;

    if (!lista.length) {
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--texto-suave)">Sin resultados para "${filtro}"</div>`;
        return;
    }

    grid.innerHTML = lista.map(a => {
        const est  = asistencia[a.id]?.estado || 'presente';
        const nota = asistencia[a.id]?.nota   || '';
        const ini  = a.nombre.charAt(0) + a.apellidos.charAt(0);

        return `
        <div class="alumno-card ${est}" id="card-${a.id}">
            <div class="alumno-info">
                <div class="alumno-avatar">${ini}</div>
                <div class="alumno-texto">
                    <div class="alumno-nombre">${a.nombre} ${a.apellidos}</div>
                    <div class="alumno-curso">Alumno</div>
                </div>
            </div>

            ${nota ? `<div class="alumno-nota visible">📝 ${nota}</div>` : `<div class="alumno-nota" id="nota-${a.id}"></div>`}

            <div class="estado-btns">
                <button class="estado-btn presente-btn" onclick="marcarEstado(${a.id}, 'presente')">
                    ✅ Presente
                </button>
                <button class="estado-btn ausente-btn" onclick="marcarEstado(${a.id}, 'ausente')">
                    ❌ Ausente
                </button>
                <button class="estado-btn retraso-btn" onclick="marcarEstado(${a.id}, 'retraso')">
                    ⏰ Retraso
                </button>
                <button class="btn-nota" onclick="abrirModalNota(${a.id})" title="Añadir observación">
                    📝
                </button>
            </div>
        </div>`;
    }).join('');
}

function filtrarAlumnos() {
    renderAlumnos(document.getElementById('buscador-lista').value);
}

/* ════════════════════════════════════════════
   MARCAR ESTADO
════════════════════════════════════════════ */
function marcarEstado(alumnoId, estado) {
    asistencia[alumnoId] = { ...asistencia[alumnoId], estado };

    // Actualizar clase de la tarjeta sin re-renderizar todo
    const card = document.getElementById(`card-${alumnoId}`);
    if (card) {
        card.classList.remove('presente', 'ausente', 'retraso');
        card.classList.add(estado);
    }

    actualizarStats();
    actualizarResumen();
}

/* ── Marcar todos como presentes ── */
function marcarTodos(estado) {
    alumnos.forEach(a => {
        asistencia[a.id] = { ...asistencia[a.id], estado };
    });
    renderAlumnos(document.getElementById('buscador-lista').value);
    actualizarStats();
    actualizarResumen();
    toast(`✅ Todos marcados como ${estado}`);
}

/* ════════════════════════════════════════════
   MODAL DE NOTA
════════════════════════════════════════════ */
function abrirModalNota(alumnoId) {
    alumnoModal = alumnoId;
    const a   = alumnos.find(x => x.id === alumnoId);
    const est = asistencia[alumnoId]?.estado || 'presente';
    const nota = asistencia[alumnoId]?.nota  || '';

    document.getElementById('modal-alumno-nombre').textContent =
        `${a.nombre} ${a.apellidos}`;

    document.getElementById('modal-nota-texto').value = nota;

    // Selector de estado dentro del modal
    document.getElementById('modal-estado-selector').innerHTML = `
        <button class="estado-btn presente-btn ${est==='presente'?'activo':''}"
                onclick="setEstadoModal('presente')">✅ Presente</button>
        <button class="estado-btn ausente-btn ${est==='ausente'?'activo':''}"
                onclick="setEstadoModal('ausente')">❌ Ausente</button>
        <button class="estado-btn retraso-btn ${est==='retraso'?'activo':''}"
                onclick="setEstadoModal('retraso')">⏰ Retraso</button>
    `;

    document.getElementById('modal-nota').classList.add('open');
}

function setEstadoModal(estado) {
    if (alumnoModal) asistencia[alumnoModal].estado = estado;
    // Actualizar visual del selector
    document.querySelectorAll('#modal-estado-selector .estado-btn').forEach(btn => {
        btn.style.opacity = '0.5';
    });
    event.target.style.opacity = '1';
}

function guardarNota() {
    if (!alumnoModal) return;
    const nota   = document.getElementById('modal-nota-texto').value.trim();
    const estado = asistencia[alumnoModal]?.estado || 'presente';

    asistencia[alumnoModal] = { estado, nota };

    // Actualizar la tarjeta
    const card = document.getElementById(`card-${alumnoModal}`);
    if (card) {
        card.classList.remove('presente','ausente','retraso');
        card.classList.add(estado);
        const notaEl = card.querySelector('.alumno-nota');
        if (notaEl) {
            notaEl.textContent = nota ? `📝 ${nota}` : '';
            notaEl.classList.toggle('visible', !!nota);
        }
    }

    actualizarStats();
    actualizarResumen();
    cerrarModal();
    toast('✓ Observación guardada');
}

function cerrarModal() {
    document.getElementById('modal-nota').classList.remove('open');
    alumnoModal = null;
}

/* ════════════════════════════════════════════
   GUARDAR LISTA
════════════════════════════════════════════ */
function guardarLista() {
    const claseId = document.getElementById('filtro-clase').value;
    const fecha   = document.getElementById('filtro-fecha').value;

    if (!claseId || !fecha) { toast('⚠️ Selecciona clase y fecha antes de guardar.'); return; }
    if (!alumnos.length)    { toast('⚠️ No hay alumnos cargados.'); return; }

    // Mostrar modal de confirmación
    const presentes = Object.values(asistencia).filter(a => a.estado === 'presente').length;
    const ausentes  = Object.values(asistencia).filter(a => a.estado === 'ausente').length;
    const retrasos  = Object.values(asistencia).filter(a => a.estado === 'retraso').length;
    const clase     = clases.find(c => c.id == claseId);

    document.getElementById('confirm-resumen').innerHTML = `
        Clase <strong>${clase?.curso} — ${clase?.nombre}</strong><br>
        Fecha: <strong>${formatFechaLarga(fecha)}</strong><br><br>
        ✅ Presentes: <strong>${presentes}</strong> &nbsp;
        ❌ Ausentes: <strong>${ausentes}</strong> &nbsp;
        ⏰ Retrasos: <strong>${retrasos}</strong>
    `;

    document.getElementById('modal-confirmar').classList.add('open');
}

async function confirmarGuardado() {
    const claseId    = document.getElementById('filtro-clase').value;
    const fecha      = document.getElementById('filtro-fecha').value;
    const asignaturaEl = document.getElementById('filtro-asignatura');
    const asignatura = asignaturaEl.value || null;

    // Construir payload — solo enviamos los que NO son presentes
    // (los presentes no generan registro en la tabla ausencias)
    const registros = alumnos
        .filter(a => asistencia[a.id]?.estado !== 'presente')
        .map(a => ({
            alumno_idAlumno:   a.id,
            estado:            asistencia[a.id].estado,   // 'ausente' | 'retraso'
            nota:              asistencia[a.id].nota || null,
            fecha,
            clase_idClase:     parseInt(claseId),
            asignatura:        asignatura || null,
        }));

    // En producción:
    // const data = await api('POST', '/api/asistencia', { fecha, clase_id: claseId, asignatura, registros });
    // if (data.error) { toast('❌ ' + data.error); return; }

    // Simulación de guardado exitoso
    console.log('📋 Lista guardada:', { fecha, claseId, asignatura, registros });

    cerrarModalConfirm();
    toast('✅ Lista guardada correctamente');

    // Deshabilitar el botón de guardar para evitar doble envío
    const btn = document.getElementById('btn-guardar');
    btn.textContent = '✓ Lista guardada';
    btn.disabled = true;
    setTimeout(() => {
        btn.textContent = '💾 Guardar lista';
        btn.disabled = false;
    }, 4000);
}

function cerrarModalConfirm() {
    document.getElementById('modal-confirmar').classList.remove('open');
}

/* ════════════════════════════════════════════
   STATS Y RESUMEN
════════════════════════════════════════════ */
function actualizarStats() {
    const presentes = Object.values(asistencia).filter(a => a.estado === 'presente').length;
    const ausentes  = Object.values(asistencia).filter(a => a.estado === 'ausente').length;
    const retrasos  = Object.values(asistencia).filter(a => a.estado === 'retraso').length;

    document.getElementById('stat-presentes').textContent = presentes;
    document.getElementById('stat-ausentes').textContent  = ausentes;
    document.getElementById('stat-retrasos').textContent  = retrasos;
}

function actualizarResumen() {
    const total     = alumnos.length;
    const presentes = Object.values(asistencia).filter(a => a.estado === 'presente').length;
    const ausentes  = Object.values(asistencia).filter(a => a.estado === 'ausente').length;
    const retrasos  = Object.values(asistencia).filter(a => a.estado === 'retraso').length;
    const pct       = total ? Math.round((presentes / total) * 100) : 0;

    document.getElementById('resumen-texto').innerHTML =
        `<strong>${total}</strong> alumnos · ${pct}% asistencia · ` +
        `<strong style="color:#47ad79">${presentes} presentes</strong> · ` +
        `<strong style="color:#e74c3c">${ausentes} ausentes</strong> · ` +
        `<strong style="color:#e6a830">${retrasos} retrasos</strong>`;
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function formatFechaLarga(fecha) {
    if (!fecha) return '—';
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha + 'T12:00:00').toLocaleDateString('es-ES', opciones);
}

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

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

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => {
        if (e.target === o) {
            o.classList.remove('open');
            if (o.id === 'modal-nota') alumnoModal = null;
        }
    });
});

document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = '/login';
});
