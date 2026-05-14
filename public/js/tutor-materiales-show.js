/* ══════════════════════════════════════════════════════════════
   Edunoly · tutor-materiales-show.js
   Detalle de un material para el tutor legal — solo lectura.
══════════════════════════════════════════════════════════════ */

const matId = document.getElementById('mat-data').dataset.id;

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    const data = await api('GET', `/api/tutor/materiales/${matId}`);

    if (!data || data.ok === false) {
        document.getElementById('mat-detalle').innerHTML =
            '<p style="color:var(--texto-suave);padding:1rem">No se pudo cargar el material.</p>';
        return;
    }

    document.getElementById('hero-titulo').textContent = data.titulo;
    document.getElementById('hero-sub').textContent    =
        `Prof. ${data.docente} · ${data.created_at}`;

    renderDetalle(data);
})();

/* ════════════════════════════════════════════
   RENDER
════════════════════════════════════════════ */
function renderDetalle(m) {
    const accionHtml = m.tipo_contenido === 'archivo'
        ? `<div class="mat-dato">
               <div class="mat-dato-lbl">Archivo</div>
               <div class="mat-dato-val">
                   ${esc(m.archivo_nombre_original ?? '—')}
                   ${m.tamano_legible ? `<span style="color:var(--texto-suave);font-size:.85rem">(${esc(m.tamano_legible)})</span>` : ''}
               </div>
           </div>
           <div class="mat-acciones">
               <a href="/tutor/materiales/${m.id}/descargar" class="btn-descargar">Descargar archivo</a>
           </div>`
        : `<div class="mat-dato">
               <div class="mat-dato-lbl">Enlace</div>
               <div class="mat-dato-val">
                   <a href="${esc(m.url_externa)}" target="_blank">${esc(m.url_externa)}</a>
               </div>
           </div>
           <div class="mat-acciones">
               <a href="${esc(m.url_externa)}" target="_blank" class="btn-enlace">Abrir enlace</a>
           </div>`;

    document.getElementById('mat-detalle').innerHTML = `
        <div class="mat-detalle">
            <div class="mat-meta">
                ${m.materia ? `<span class="badge badge-tipo">${esc(m.materia)}</span>` : ''}
                ${m.tema    ? `<span class="badge badge-tipo">${esc(m.tema)}</span>`    : ''}
                <span class="badge badge-tipo">${m.tipo_contenido === 'archivo' ? 'Archivo' : 'URL externa'}</span>
            </div>

            ${m.descripcion ? `<p class="mat-descripcion">${esc(m.descripcion)}</p>` : ''}

            <hr class="mat-sep">
            ${accionHtml}
        </div>`;
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
