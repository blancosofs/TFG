/* ══════════════════════════════════════════════════════════════
   Edunoly · material-repaso-create.js
   Formulario de creación de material — carga tutores, controla
   el toggle de tipo de contenido y envía el form via fetch.
══════════════════════════════════════════════════════════════ */

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    // Cargar tutores disponibles para los checkboxes
    const tutores = await api('GET', '/api/material-repaso/tutores');
    renderTutores(tutores);

    // Activar el toggle de tipo de contenido
    document.querySelectorAll('input[name="tipo_contenido"]')
        .forEach(r => r.addEventListener('change', actualizarSecciones));
    actualizarSecciones();
})();

/* ════════════════════════════════════════════
   TOGGLE TIPO DE CONTENIDO
════════════════════════════════════════════ */
function actualizarSecciones() {
    const val = document.querySelector('input[name="tipo_contenido"]:checked')?.value;
    document.getElementById('seccionArchivo').style.display = val === 'archivo'     ? '' : 'none';
    document.getElementById('seccionUrl').style.display     = val === 'url_externa' ? '' : 'none';
}

/* ════════════════════════════════════════════
   RENDER CHECKBOXES DE TUTORES
════════════════════════════════════════════ */
function renderTutores(tutores) {
    const lista = document.getElementById('tutores-lista');

    if (!tutores || tutores.ok === false || !tutores.length) {
        lista.innerHTML = '<p style="color:var(--texto-suave);padding:.5rem;font-size:.9rem">No hay tutores registrados en este colegio.</p>';
        return;
    }

    lista.innerHTML = tutores.map(t => `
        <div class="tutor-item">
            <input type="checkbox" name="tutores[]" value="${t.id}" id="tutor_${t.id}">
            <label for="tutor_${t.id}">${esc(t.nombre)}</label>
        </div>`).join('');
}

/* ════════════════════════════════════════════
   ENVÍO DEL FORMULARIO
════════════════════════════════════════════ */
document.getElementById('form-crear').addEventListener('submit', async e => {
    e.preventDefault();

    const btn  = document.getElementById('btn-submit');
    btn.disabled    = true;
    btn.textContent = 'Guardando...';

    // Usar FormData para enviar el archivo junto con el resto de campos
    const formData = new FormData(e.target);

    // El checkbox "publicado" no se incluye en FormData si no está marcado;
    // lo añadimos manualmente para que el backend siempre lo reciba
    if (!document.getElementById('chk-publicado').checked) {
        formData.set('publicado', '0');
    }

    const data = await apiFormData('/api/material-repaso', formData);

    btn.disabled    = false;
    btn.textContent = 'Crear Material';

    if (data?.ok) {
        sessionStorage.setItem('mat_flash', 'Material creado correctamente.');
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

// Fetch para JSON (GET, DELETE, PUT con JSON)
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

// Fetch para multipart/form-data (subida de archivos)
// No se setea Content-Type: el navegador lo hace solo con el boundary correcto
async function apiFormData(ruta, formData) {
    try {
        const res = await fetch(ruta, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: formData,
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            if (res.status === 422 && data.errors)
                return { ok: false, errors: data.errors };
            return { ok: false, mensaje: data.message || 'Error inesperado.' };
        }
        return data;
    } catch (e) {
        console.error('[API upload]', e);
        return { ok: false, mensaje: 'Error de conexión.' };
    }
}
