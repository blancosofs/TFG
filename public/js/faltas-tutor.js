/* ══════════════════════════════════════════════════════════════
   Edunoly · faltas-tutor.js
   Panel de faltas de asistencia para el tutor legal
══════════════════════════════════════════════════════════════ */

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

/* Historial cargado por hijo: { alumno_id: [ausencias] } */
const historialCache = {};

/* Estado del modal de justificación */
let justifAusenciaId  = null;
let justifAlumnoId    = null;

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    const hijos = await api('GET', '/api/tutor/alumnos');

    document.getElementById('faltas-loading').style.display = 'none';

    if (!hijos || hijos.error || !hijos.length) {
        document.getElementById('faltas-vacio').style.display = 'flex';
        return;
    }

    renderHijos(hijos);
})();

/* ════════════════════════════════════════════
   RENDER TARJETAS DE HIJOS
════════════════════════════════════════════ */
function renderHijos(hijos) {
    const lista = document.getElementById('hijos-lista');

    lista.innerHTML = hijos.map(h => `
        <div class="hijo-card" id="hijo-card-${h.id}">
            <div class="hijo-header">
                <div class="hijo-avatar">${iniciales(h.nombre, h.apellidos)}</div>
                <div class="hijo-info">
                    <div class="hijo-nombre">${h.nombre} ${h.apellidos}</div>
                    <div class="hijo-meta">
                        ${h.curso !== '—' ? `<span>${h.curso}</span>` : ''}
                        ${h.clase !== '—' ? `<span class="sep">·</span><span>Clase ${h.clase}</span>` : ''}
                        <span class="sep">·</span>
                        <span class="parentesco">${h.parentesco}</span>
                    </div>
                </div>
                <button class="btn-historial" onclick="toggleHistorial(${h.id})">
                    Ver historial
                </button>
            </div>

            <div class="hijo-stats">
                <div class="stat-item">
                    <span class="stat-num">${h.faltas}</span>
                    <span class="stat-lbl">Faltas este mes</span>
                </div>
            </div>

            <div class="historial-wrap" id="historial-${h.id}" style="display:none">
                <div class="historial-cargando" id="historial-loading-${h.id}">
                    <div class="spinner-sm"></div> Cargando historial…
                </div>
                <div id="historial-contenido-${h.id}"></div>
            </div>
        </div>
    `).join('');
}

/* ════════════════════════════════════════════
   TOGGLE HISTORIAL
════════════════════════════════════════════ */
async function toggleHistorial(alumnoId) {
    const wrap = document.getElementById(`historial-${alumnoId}`);
    const btn  = document.querySelector(`#hijo-card-${alumnoId} .btn-historial`);

    if (wrap.style.display !== 'none') {
        wrap.style.display = 'none';
        btn.textContent = 'Ver historial';
        return;
    }

    wrap.style.display = 'block';
    btn.textContent = 'Ocultar historial';

    if (historialCache[alumnoId]) {
        renderHistorial(alumnoId, historialCache[alumnoId]);
        return;
    }

    const data = await api('GET', `/api/ausencias/alumno/${alumnoId}`);
    document.getElementById(`historial-loading-${alumnoId}`).style.display = 'none';

    if (!data || data.error) {
        document.getElementById(`historial-contenido-${alumnoId}`).innerHTML =
            `<p class="historial-error">No se pudo cargar el historial.</p>`;
        return;
    }

    historialCache[alumnoId] = data;
    renderHistorial(alumnoId, data);
}

/* ════════════════════════════════════════════
   RENDER HISTORIAL DE AUSENCIAS
════════════════════════════════════════════ */
function renderHistorial(alumnoId, ausencias) {
    const el = document.getElementById(`historial-contenido-${alumnoId}`);

    if (!ausencias.length) {
        el.innerHTML = `<div class="historial-vacio">✅ Sin faltas registradas.</div>`;
        return;
    }

    const totalFaltas    = ausencias.filter(a => a.tipo === 'falta').length;
    const totalRetrasos  = ausencias.filter(a => a.tipo === 'retraso').length;
    const totalJustif    = ausencias.filter(a => a.justificada).length;
    const totalNoJustif  = ausencias.filter(a => !a.justificada).length;

    el.innerHTML = `
        <div class="hist-resumen">
            <div class="hist-stat"><span class="hist-num rojo">${totalFaltas}</span><span class="hist-lbl">Faltas</span></div>
            <div class="hist-stat"><span class="hist-num naranja">${totalRetrasos}</span><span class="hist-lbl">Retrasos</span></div>
            <div class="hist-stat"><span class="hist-num verde">${totalJustif}</span><span class="hist-lbl">Justificadas</span></div>
            <div class="hist-stat"><span class="hist-num gris">${totalNoJustif}</span><span class="hist-lbl">Sin justificar</span></div>
        </div>

        <table class="ausencias-tabla">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Justificación</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                ${ausencias.map(a => `
                <tr>
                    <td>${formatFecha(a.fecha)}</td>
                    <td><span class="badge-tipo ${a.tipo}">${a.tipo === 'falta' ? 'Falta' : 'Retraso'}</span></td>
                    <td>
                        ${a.justificada
                            ? `<span class="badge-estado justificada">✓ Justificada</span>`
                            : `<span class="badge-estado no-justificada">✗ Sin justificar</span>`}
                    </td>
                    <td class="justif-texto">${a.justificacion ?? '—'}</td>
                    <td>
                        ${!a.justificada
                            ? `<button class="btn-justificar" onclick="abrirModalJustificar(${a.id}, ${alumnoId}, '${a.fecha}', '${a.tipo}')">
                                Justificar
                               </button>`
                            : ''}
                    </td>
                </tr>`).join('')}
            </tbody>
        </table>
    `;
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function iniciales(nombre, apellidos) {
    return ((nombre?.[0] ?? '') + (apellidos?.[0] ?? '')).toUpperCase();
}

function formatFecha(f) {
    if (!f) return '—';
    const d = new Date(f + 'T12:00:00');
    return d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
}

/* ════════════════════════════════════════════
   MODAL JUSTIFICACIÓN
════════════════════════════════════════════ */
function abrirModalJustificar(ausenciaId, alumnoId, fecha, tipo) {
    justifAusenciaId = ausenciaId;
    justifAlumnoId   = alumnoId;

    document.getElementById('modal-justif-info').innerHTML = `
        <span class="justif-info-item">📅 ${formatFecha(fecha)}</span>
        <span class="badge-tipo ${tipo}" style="font-size:11px">${tipo === 'falta' ? 'Falta' : 'Retraso'}</span>
    `;
    document.getElementById('justif-texto').value = '';
    document.getElementById('alert-justif').innerHTML = '';
    document.getElementById('modal-justificar').classList.add('open');
    document.body.style.overflow = 'hidden';
    document.getElementById('justif-texto').focus();
}

function cerrarModalJustificar() {
    document.getElementById('modal-justificar').classList.remove('open');
    document.body.style.overflow = '';
}

async function guardarJustificacion() {
    const texto = document.getElementById('justif-texto').value.trim();
    if (!texto) {
        document.getElementById('alert-justif').innerHTML =
            `<p class="justif-alerta">Escribe el motivo de la justificación.</p>`;
        return;
    }

    const btn = document.querySelector('.btn-guardar-modal');
    btn.disabled = true;
    btn.textContent = 'Guardando…';

    const r = await api('PUT', `/familia/ausencias/${justifAusenciaId}`, {
        justificada:   true,
        justificacion: texto,
    });

    btn.disabled = false;
    btn.textContent = 'Guardar justificación';

    if (!r?.ok) {
        document.getElementById('alert-justif').innerHTML =
            `<p class="justif-alerta">${r?.mensaje || 'Error al guardar.'}</p>`;
        return;
    }

    // Actualizar cache y re-renderizar
    if (historialCache[justifAlumnoId]) {
        const aus = historialCache[justifAlumnoId].find(a => a.id === justifAusenciaId);
        if (aus) { aus.justificada = true; aus.justificacion = texto; }
        renderHistorial(justifAlumnoId, historialCache[justifAlumnoId]);
    }

    cerrarModalJustificar();
    toast('✓ Justificación guardada correctamente');
}

/* Cerrar al hacer clic fuera */
document.getElementById('modal-justificar')?.addEventListener('click', e => {
    if (e.target === document.getElementById('modal-justificar')) cerrarModalJustificar();
});

/* ════════════════════════════════════════════
   TOAST
════════════════════════════════════════════ */
function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

async function api(method, ruta, body) {
    try {
        const opts = {
            method,
            credentials: 'include',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        };
        if (body) opts.body = JSON.stringify(body);
        const r = await fetch(ruta, opts);
        return await r.json();
    } catch (e) {
        return { error: 'Error de conexión.' };
    }
}
