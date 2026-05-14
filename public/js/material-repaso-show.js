/* ══════════════════════════════════════════════════════════════
   Edunoly · material-repaso-show.js
   Detalle de un material del docente — carga datos y gestiona
   el borrado con modal de confirmación.
══════════════════════════════════════════════════════════════ */

const matId = document.getElementById('mat-data').dataset.id;

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    const data = await api('GET', `/api/material-repaso/${matId}`);

    if (!data || data.ok === false) {
        document.getElementById('mat-detalle').innerHTML =
            '<p style="color:var(--texto-suave);padding:1rem">No se pudo cargar el material.</p>';
        return;
    }

    // Actualizar el hero con el título real
    document.getElementById('hero-titulo').textContent = data.titulo;
    document.getElementById('hero-sub').textContent    = `Detalle del material · ${data.created_at}`;

    renderDetalle(data);
})();

/* ════════════════════════════════════════════
   RENDER
════════════════════════════════════════════ */
function renderDetalle(m) {
    const tutoresHtml = m.tutores.length
        ? m.tutores.map(t => `<span class="mat-tutor-tag">${esc(t.nombre)}</span>`).join('')
        : '<span style="color:var(--texto-suave);font-size:.9rem">Ninguno asignado.</span>';

    const contenidoHtml = m.tipo_contenido === 'archivo'
        ? `<div class="mat-dato">
               <div class="mat-dato-lbl">Archivo</div>
               <div class="mat-dato-val">
                   ${esc(m.archivo_nombre_original ?? '—')}
                   ${m.tamano_legible ? `<span style="color:var(--texto-suave);font-size:.85rem">(${esc(m.tamano_legible)})</span>` : ''}
               </div>
           </div>`
        : `<div class="mat-dato">
               <div class="mat-dato-lbl">URL externa</div>
               <div class="mat-dato-val">
                   <a href="${esc(m.url_externa)}" target="_blank">${esc(m.url_externa)}</a>
               </div>
           </div>`;

    document.getElementById('mat-detalle').innerHTML = `
        <div class="mat-detalle">
            <div class="mat-meta">
                ${m.publicado
                    ? '<span class="badge badge-pub">Publicado</span>'
                    : '<span class="badge badge-bor">Borrador</span>'}
                <span class="badge badge-tipo">${m.tipo_contenido === 'archivo' ? 'Archivo' : 'URL externa'}</span>
                ${m.materia ? `<span class="badge badge-tipo">${esc(m.materia)}</span>` : ''}
                ${m.tema    ? `<span class="badge badge-tipo">${esc(m.tema)}</span>`    : ''}
            </div>

            ${m.descripcion ? `<p class="mat-descripcion">${esc(m.descripcion)}</p>` : ''}

            <hr class="mat-sep">
            ${contenidoHtml}
            <hr class="mat-sep">

            <div class="mat-dato">
                <div class="mat-dato-lbl">Tutores con acceso (${m.tutores.length})</div>
                <div class="mat-tutores-wrap">${tutoresHtml}</div>
            </div>

            <div class="mat-acciones">
                <a href="/material-repaso/${m.id}/edit" class="btn-accion btn-editar-accion">Editar</a>
                <button class="btn-accion btn-eliminar" id="btn-eliminar">Eliminar</button>
            </div>
        </div>`;

    // Botón eliminar
    document.getElementById('btn-eliminar').addEventListener('click', () => {
        document.getElementById('confirm-texto').textContent =
            `Vas a eliminar "${m.titulo}". Esta acción no se puede deshacer.`;
        document.getElementById('modal-confirmar').classList.add('open');
    });
}

/* ════════════════════════════════════════════
   MODAL CONFIRMAR ELIMINAR
════════════════════════════════════════════ */
document.getElementById('btn-confirm-ok').addEventListener('click', async () => {
    const data = await api('DELETE', `/api/material-repaso/${matId}`);
    cerrarModal();

    if (data?.ok) {
        sessionStorage.setItem('mat_flash', 'Material eliminado correctamente.');
        window.location.href = '/material-repaso';
    } else {
        toast('❌ ' + (data?.mensaje || 'Error al eliminar.'));
    }
});

document.getElementById('btn-confirm-cancel').addEventListener('click', cerrarModal);

document.getElementById('modal-confirmar').addEventListener('click', e => {
    if (e.target === document.getElementById('modal-confirmar')) cerrarModal();
});

function cerrarModal() {
    document.getElementById('modal-confirmar').classList.remove('open');
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function esc(str) {
    return String(str ?? '').replace(/[&<>"']/g, c => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));
}

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

async function api(method, ruta, body = null) {
    const opts = {
        method,
        credentials: 'include',
        headers: {
            'Accept':       'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
    };
    if (body) {
        opts.headers['Content-Type'] = 'application/json';
        opts.body = JSON.stringify(body);
    }
    try {
        const res  = await fetch(ruta, opts);
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            if (res.status === 422 && data.errors) {
                const primer = Object.values(data.errors)[0];
                return { ok: false, mensaje: Array.isArray(primer) ? primer[0] : primer };
            }
            if (res.status === 401) { window.location.href = '/login'; return { ok: false }; }
            return { ok: false, mensaje: data.message || 'Error inesperado.' };
        }
        return data;
    } catch (e) {
        console.error('[API]', e);
        return { ok: false, mensaje: 'Error de conexión.' };
    }
}
