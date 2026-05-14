/* ══════════════════════════════════════════════════════════════
   Edunoly · material-repaso-edit.js
   Formulario de edición — carga los datos actuales del material
   y los tutores disponibles, luego envía los cambios via fetch.
══════════════════════════════════════════════════════════════ */

const matId = document.getElementById('mat-data').dataset.id;

/* ════════════════════════════════════════════
   ARRANQUE — carga material y tutores en paralelo
════════════════════════════════════════════ */
(async () => {
    const [material, tutores] = await Promise.all([
        api('GET', `/api/material-repaso/${matId}`),
        api('GET', '/api/material-repaso/tutores'),
    ]);

    if (!material || material.ok === false) {
        document.querySelector('.mat-form-card').innerHTML =
            '<p style="color:var(--texto-suave);padding:1rem">No se pudo cargar el material.</p>';
        return;
    }

    rellenarFormulario(material);
    renderTutores(tutores, material.tutores.map(t => t.id));
})();

/* ════════════════════════════════════════════
   RELLENA EL FORMULARIO CON LOS DATOS ACTUALES
════════════════════════════════════════════ */
function rellenarFormulario(m) {
    document.getElementById('hero-sub').textContent  = m.titulo;
    document.getElementById('titulo').value          = m.titulo;
    document.getElementById('descripcion').value     = m.descripcion ?? '';
    document.getElementById('materia').value         = m.materia ?? '';
    document.getElementById('tema').value            = m.tema ?? '';
    document.getElementById('chk-publicado').checked = m.publicado;

    // Mostrar sección según el tipo de contenido (no se puede cambiar en edición)
    if (m.tipo_contenido === 'archivo') {
        const info = document.getElementById('seccionArchivoInfo');
        info.style.display = '';
        document.getElementById('info-archivo').innerHTML =
            `${esc(m.archivo_nombre_original ?? '')} ${m.tamano_legible ? `(${esc(m.tamano_legible)})` : ''}
            <br><small>Para reemplazar el archivo, elimina este material y crea uno nuevo.</small>`;
    } else {
        document.getElementById('seccionUrl').style.display = '';
        document.getElementById('url_externa').value = m.url_externa ?? '';
    }
}

/* ════════════════════════════════════════════
   RENDER CHECKBOXES DE TUTORES
════════════════════════════════════════════ */
function renderTutores(tutores, seleccionados) {
    const lista = document.getElementById('tutores-lista');

    if (!tutores || tutores.ok === false || !tutores.length) {
        lista.innerHTML = '<p style="color:var(--texto-suave);padding:.5rem;font-size:.9rem">No hay tutores registrados.</p>';
        return;
    }

    lista.innerHTML = tutores.map(t => `
        <div class="tutor-item">
            <input type="checkbox" name="tutores[]" value="${t.id}" id="tutor_${t.id}"
                   ${seleccionados.includes(t.id) ? 'checked' : ''}>
            <label for="tutor_${t.id}">${esc(t.nombre)}</label>
        </div>`).join('');
}

/* ════════════════════════════════════════════
   ENVÍO DEL FORMULARIO
════════════════════════════════════════════ */
document.getElementById('form-editar').addEventListener('submit', async e => {
    e.preventDefault();

    const btn  = document.getElementById('btn-submit');
    btn.disabled    = true;
    btn.textContent = 'Guardando...';

    // Recoger tutores seleccionados
    const tutoresIds = [...document.querySelectorAll('input[name="tutores[]"]:checked')]
        .map(cb => parseInt(cb.value, 10));

    const body = {
        titulo:      document.getElementById('titulo').value.trim(),
        descripcion: document.getElementById('descripcion').value.trim() || null,
        url_externa: document.getElementById('url_externa')?.value.trim() || null,
        materia:     document.getElementById('materia').value.trim() || null,
        tema:        document.getElementById('tema').value.trim() || null,
        publicado:   document.getElementById('chk-publicado').checked,
        tutores:     tutoresIds,
    };

    const data = await api('PUT', `/api/material-repaso/${matId}`, body);

    btn.disabled    = false;
    btn.textContent = 'Guardar cambios';

    if (data?.ok) {
        sessionStorage.setItem('mat_flash', 'Material actualizado correctamente.');
        window.location.href = '/material-repaso';
        return;
    }

    mostrarErrores(data?.errors || data?.mensaje);
});

/* ════════════════════════════════════════════
   ERRORES DE VALIDACIÓN
════════════════════════════════════════════ */
function mostrarErrores(errores) {
    const el = document.getElementById('alert-errores');

    if (typeof errores === 'string') {
        el.innerHTML = `<div class="flash-err">${esc(errores)}</div>`;
    } else if (errores && typeof errores === 'object') {
        const items = Object.values(errores).flat().map(e => `<li>${esc(e)}</li>`).join('');
        el.innerHTML = `<div class="flash-err"><strong>Corrige los siguientes errores:</strong><ul style="margin:.4rem 0 0 1.2rem">${items}</ul></div>`;
    } else {
        el.innerHTML = `<div class="flash-err">Error al guardar. Inténtalo de nuevo.</div>`;
    }

    el.style.display = '';
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function esc(str) {
    return String(str ?? '').replace(/[&<>"']/g, c => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));
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
            if (res.status === 422 && data.errors)
                return { ok: false, errors: data.errors };
            if (res.status === 401) { window.location.href = '/login'; return { ok: false }; }
            return { ok: false, mensaje: data.message || 'Error inesperado.' };
        }
        return data;
    } catch (e) {
        console.error('[API]', e);
        return { ok: false, mensaje: 'Error de conexión.' };
    }
}
