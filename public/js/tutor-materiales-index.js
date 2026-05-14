/* ══════════════════════════════════════════════════════════════
   Edunoly · tutor-materiales-index.js
   Lista de materiales disponibles para el tutor legal.
══════════════════════════════════════════════════════════════ */

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    await cargarMateriales(1);
})();

/* ════════════════════════════════════════════
   CARGA Y RENDER
════════════════════════════════════════════ */
async function cargarMateriales(pagina) {
    const data = await api('GET', `/api/tutor/materiales?page=${pagina}`);

    const contenido = document.getElementById('mat-contenido');

    if (!data || data.ok === false) {
        contenido.innerHTML = '<p style="color:var(--texto-suave);padding:1rem">Error al cargar los materiales.</p>';
        return;
    }

    if (!data.data.length) {
        contenido.innerHTML = `
            <div class="mat-vacio">
                <p>No hay materiales disponibles para ti en este momento.</p>
            </div>`;
        return;
    }

    const tarjetas = data.data.map(m => `
        <div class="mat-card-lista">
            <div class="mat-card-info">
                <h3>${esc(m.titulo)}</h3>
                <div>
                    ${m.materia ? `<span class="badge badge-tipo">${esc(m.materia)}</span>` : ''}
                    ${m.tema    ? `<span class="badge badge-tipo">${esc(m.tema)}</span>`    : ''}
                </div>
                <p>Prof. ${esc(m.docente)} · ${m.created_at}</p>
                ${m.descripcion ? `<p>${esc(m.descripcion.substring(0, 100))}${m.descripcion.length > 100 ? '…' : ''}</p>` : ''}
            </div>
            <div class="mat-card-acciones">
                <a href="/tutor/materiales/${m.id}" class="btn-accion btn-ver">Ver</a>
                ${m.tipo_contenido === 'archivo'
                    ? `<a href="/tutor/materiales/${m.id}/descargar" class="btn-descargar">Descargar</a>`
                    : `<a href="${esc(m.url_externa)}" target="_blank" class="btn-enlace">Abrir</a>`}
            </div>
        </div>`).join('');

    contenido.innerHTML = tarjetas + `<div class="mat-paginacion" id="mat-paginacion"></div>`;

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
   UTILIDADES
════════════════════════════════════════════ */
function esc(str) {
    return String(str ?? '').replace(/[&<>"']/g, c => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));
}

async function api(method, ruta) {
    try {
        const res  = await fetch(ruta, {
            method,
            credentials: 'include',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            if (res.status === 401) { window.location.href = '/login'; return { ok: false }; }
            return { ok: false };
        }
        return data;
    } catch (e) {
        console.error('[API]', e);
        return { ok: false };
    }
}
