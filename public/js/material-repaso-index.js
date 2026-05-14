/* ══════════════════════════════════════════════════════════════
   Edunoly · material-repaso-index.js
   Lista de materiales del docente — carga, renderiza y gestiona
   el borrado con modal de confirmación.
══════════════════════════════════════════════════════════════ */

let pendingId = null;

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    // Mostrar mensaje de éxito si viene de crear/editar/borrar
    const flash = sessionStorage.getItem('mat_flash');
    if (flash) {
        const el = document.getElementById('flash-ok');
        el.textContent = flash;
        el.style.display = '';
        sessionStorage.removeItem('mat_flash');
    }

    await cargarMateriales(1);
})();

/* ════════════════════════════════════════════
   CARGA Y RENDER
════════════════════════════════════════════ */
async function cargarMateriales(pagina) {
    const data = await api('GET', `/api/material-repaso?page=${pagina}`);

    const contenido = document.getElementById('mat-contenido');

    if (!data || data.ok === false) {
        contenido.innerHTML = '<p style="color:var(--texto-suave);padding:1rem">Error al cargar los materiales.</p>';
        return;
    }

    if (!data.data.length) {
        contenido.innerHTML = `
            <div class="mat-vacio">
                <p>No has subido ningún material aún.</p>
                <a href="/material-repaso/create" class="btn-crear">Crear el primero</a>
            </div>`;
        return;
    }

    const filas = data.data.map(m => `
        <tr>
            <td>
                <strong>${esc(m.titulo)}</strong>
                ${m.tema ? `<br><small>${esc(m.tema)}</small>` : ''}
            </td>
            <td><span class="badge badge-tipo">${m.tipo_contenido === 'archivo' ? 'Archivo' : 'URL'}</span></td>
            <td>${esc(m.materia ?? '—')}</td>
            <td><span class="badge badge-num">${m.tutores.length}</span></td>
            <td>
                ${m.publicado
                    ? '<span class="badge badge-pub">Publicado</span>'
                    : '<span class="badge badge-bor">Borrador</span>'}
            </td>
            <td>
                <a href="/material-repaso/${m.id}" class="btn-accion btn-ver">Ver</a>
                <a href="/material-repaso/${m.id}/edit" class="btn-accion btn-editar-accion">Editar</a>
                <button class="btn-accion btn-eliminar"
                        data-id="${m.id}" data-nombre="${esc(m.titulo)}">Eliminar</button>
            </td>
        </tr>`).join('');

    contenido.innerHTML = `
        <table class="mat-tabla">
            <thead>
                <tr>
                    <th>Título</th><th>Tipo</th><th>Materia</th>
                    <th>Tutores</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody id="mat-tbody">${filas}</tbody>
        </table>
        <div class="mat-paginacion" id="mat-paginacion"></div>`;

    // Delegación de eventos para botones Eliminar
    document.getElementById('mat-tbody').addEventListener('click', e => {
        const btn = e.target.closest('.btn-eliminar');
        if (!btn) return;
        pendingId = btn.dataset.id;
        document.getElementById('confirm-texto').textContent =
            `Vas a eliminar "${btn.dataset.nombre}". Esta acción no se puede deshacer.`;
        document.getElementById('modal-confirmar').classList.add('open');
    });

    renderPaginacion(data.meta, pagina);
}

function renderPaginacion(meta, pagina) {
    const el = document.getElementById('mat-paginacion');
    if (!el || meta.last_page <= 1) return;

    let html = '';
    if (pagina > 1)
        html += `<button onclick="cargarMateriales(${pagina - 1})">← Anterior</button>`;
    html += ` <span>Página ${meta.current_page} de ${meta.last_page}</span> `;
    if (pagina < meta.last_page)
        html += `<button onclick="cargarMateriales(${pagina + 1})">Siguiente →</button>`;
    el.innerHTML = html;
}

/* ════════════════════════════════════════════
   MODAL CONFIRMAR ELIMINAR
════════════════════════════════════════════ */
document.getElementById('btn-confirm-ok').addEventListener('click', async () => {
    if (!pendingId) return;

    const data = await api('DELETE', `/api/material-repaso/${pendingId}`);
    cerrarModal();

    if (data?.ok) {
        sessionStorage.setItem('mat_flash', 'Material eliminado correctamente.');
        await cargarMateriales(1);
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
    pendingId = null;
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
