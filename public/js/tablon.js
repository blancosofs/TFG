const API  = '';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
          ?? window.csrfToken ?? '';

/* ── Estado ── */
let sesion            = window._sesion ?? null;
let anuncios          = [];
let anunciosFiltrados = [];
let categoriaActiva   = 'todos';
let idEliminar        = null;
let modoEdicion       = false;
let idEditando        = null;
let anuncioActivo     = null;
let comentarios       = {};

const CATEGORIAS = {
    general: { emoji: '📢', nombre: 'General',  clase: 'general' },
    examen:  { emoji: '📝', nombre: 'Examen',   clase: 'examen'  },
    evento:  { emoji: '🎉', nombre: 'Evento',   clase: 'evento'  },
    urgente: { emoji: '🚨', nombre: 'Urgente',  clase: 'urgente' },
    tarea:   { emoji: '📚', nombre: 'Tarea',    clase: 'tarea'   },
};

/* ════════════════════════════════════════════
   ARRANQUE
════════════════════════════════════════════ */
(async () => {
    if (!sesion?.id) { window.location.href = '/login'; return; }

    await cargarAnuncios();
    if (sesion.rol === 'docente' || sesion.rol === 'coordinador') cargarClasesEnModal();
})();

/* ════════════════════════════════════════════
   CARGAR ANUNCIOS
════════════════════════════════════════════ */
async function cargarAnuncios() {
    const data = await api('GET', '/api/tablon');
    if (data && !data.error) {
        anuncios = data.map(p => ({
            id:               p.id,
            titulo:           p.titulo,
            contenido:        p.contenido,
            categoria:        p.categoria?.toLowerCase() ?? 'general',
            dirigido_a:       p.dirigido_a === 'Solo familias' ? 'familias'
                            : p.dirigido_a === 'Solo docentes' ? 'docentes' : 'todos',
            clase:            p.clase?.nombre ?? null,
            fecha_limite:     p.fecha_limite,
            autor:            p.docente?.user?.name
                              ? `${p.docente.user.name} ${p.docente.user.apellidos ?? ''}`.trim()
                              : 'Autor desconocido',
            docente_user_id:  p.docente_user_id ?? null,
            fecha:            p.created_at?.slice(0, 10) ?? new Date().toISOString().slice(0, 10),
            comentarios_count: (p.comentarios ?? []).length,
        }));

        data.forEach(p => {
            comentarios[p.id] = (p.comentarios ?? []).map(c => ({
                id:      c.id,
                user_id: c.user_id,
                autor:   c.autor,
                rol:     c.rol,
                texto:   c.texto,
                fecha:   c.fecha,
            }));
        });
    } else {
        anuncios = [];
    }

    aplicarFiltros();
    actualizarStats();
}

/* ════════════════════════════════════════════
   RENDER ANUNCIOS
════════════════════════════════════════════ */
function renderAnuncios() {
    const lista = document.getElementById('anuncios-lista');
    const vacio = document.getElementById('anuncios-vacio');

    if (!anunciosFiltrados.length) {
        lista.innerHTML = '';
        vacio.style.display = 'flex';
        return;
    }

    vacio.style.display = 'none';
    lista.innerHTML = anunciosFiltrados.map(a => renderTarjeta(a)).join('');
}

function renderTarjeta(a) {
    const cat     = CATEGORIAS[a.categoria] || CATEGORIAS.general;
    const esAutor = (sesion.rol === 'docente' && a.docente_user_id === sesion.id)
                 || sesion.rol === 'coordinador';
    const textoCorto = a.contenido.length > 180
        ? a.contenido.slice(0, 180).trim() + '…'
        : a.contenido;
    const hoy   = new Date().toISOString().slice(0, 10);
    const esHoy = a.fecha === hoy;

    return `
    <div class="anuncio-card ${cat.clase}" id="anuncio-${a.id}">
        <div class="anuncio-body">
            <div class="anuncio-top">
                <span class="cat-badge ${cat.clase}">${cat.emoji} ${cat.nombre}</span>
                <div class="anuncio-acciones">
                    ${esHoy ? `<span style="font-size:11px;color:var(--acento);font-weight:700">● Nuevo</span>` : ''}
                    ${esAutor ? `
                        <button class="btn-accion" onclick="editarAnuncio(${a.id})">✏️ Editar</button>
                        <button class="btn-accion danger" onclick="pedirEliminar(${a.id})">🗑️</button>
                    ` : ''}
                </div>
            </div>
            <h3 class="anuncio-titulo">${a.titulo}</h3>
            <p class="anuncio-texto">${textoCorto.replace(/\n/g, '<br>')}</p>
            ${a.contenido.length > 180
                ? `<button class="btn-ver-mas" onclick="verAnuncio(${a.id})">Ver más ›</button>`
                : ''}
        </div>
        <div class="anuncio-footer">
            <div class="anuncio-autor">
                👤 <strong>${a.autor}</strong>
                ${a.clase ? `<span class="anuncio-clase">${a.clase}</span>` : ''}
            </div>
            <div style="display:flex;gap:10px;align-items:center">
                <button class="btn-comentarios-tarjeta comentarios-tarjeta-count"
                        onclick="verAnuncio(${a.id})" id="cnt-${a.id}">
                    💬 ${(a.comentarios_count || 0) > 0 ? a.comentarios_count : 'Comentar'}
                </button>
                ${a.fecha_limite
                    ? `<span class="fecha-limite">⏰ Límite: ${formatFecha(a.fecha_limite)}</span>`
                    : ''}
                <span class="anuncio-fecha">${formatFechaRelativa(a.fecha)}</span>
            </div>
        </div>
    </div>`;
}

/* ════════════════════════════════════════════
   VER ANUNCIO COMPLETO
════════════════════════════════════════════ */
function verAnuncio(id) {
    const a = anuncios.find(x => x.id === id);
    if (!a) return;
    const cat = CATEGORIAS[a.categoria] || CATEGORIAS.general;

    document.getElementById('ver-badge').className    = `cat-badge ${cat.clase}`;
    document.getElementById('ver-badge').textContent  = `${cat.emoji} ${cat.nombre}`;
    document.getElementById('ver-titulo').textContent = a.titulo;
    document.getElementById('ver-meta').innerHTML =
        `<span>👤 ${a.autor}</span>
         <span>📅 ${formatFechaRelativa(a.fecha)}</span>
         ${a.clase ? `<span>🏫 ${a.clase}</span>` : ''}
         ${a.dirigido_a !== 'todos' ? `<span>👥 Para: ${a.dirigido_a}</span>` : ''}`;
    document.getElementById('ver-contenido').textContent = a.contenido;
    document.getElementById('ver-footer').innerHTML =
        a.fecha_limite
            ? `⏰ Fecha límite: <strong>${formatFecha(a.fecha_limite)}</strong>`
            : '';

    anuncioActivo = id;
    const ini = (sesion.nombre.charAt(0) + sesion.apellidos.charAt(0)).toUpperCase();
    document.getElementById('nuevo-avatar').textContent = ini;
    document.getElementById('nuevo-avatar').className =
        `nuevo-comentario-avatar ${sesion.rol === 'tutor' ? 'tutor' : 'docente'}`;

    renderComentarios(id);
    abrirModalConScroll('modal-ver');
}

/* ════════════════════════════════════════════
   PUBLICAR / EDITAR ANUNCIO
════════════════════════════════════════════ */
function abrirModalPublicar() {
    modoEdicion = false; idEditando = null;
    document.getElementById('modal-pub-titulo').textContent = '✏️ Publicar anuncio';
    ['pub-titulo', 'pub-contenido'].forEach(id => { document.getElementById(id).value = ''; });
    set('pub-categoria', 'general');
    set('pub-dirigido', 'todos');
    set('pub-clase', '');
    set('pub-fecha-limite', '');
    document.getElementById('pub-fecha-limite').min = new Date().toISOString().slice(0, 10);
    actualizarAvisoNotificacion();
    document.getElementById('alert-publicar').innerHTML = '';
    abrirModalConScroll('modal-publicar');
}

function editarAnuncio(id) {
    const a = anuncios.find(x => x.id === id);
    if (!a) return;
    modoEdicion = true; idEditando = id;
    document.getElementById('modal-pub-titulo').textContent = '✏️ Editar anuncio';
    set('pub-titulo',       a.titulo);
    set('pub-contenido',    a.contenido);
    set('pub-categoria',    a.categoria);
    set('pub-dirigido',     a.dirigido_a);
    set('pub-clase',        a.clase || '');
    set('pub-fecha-limite', a.fecha_limite || '');
    document.getElementById('alert-publicar').innerHTML = '';
    abrirModalConScroll('modal-publicar');
}

function actualizarAvisoNotificacion() {
    const dirigido = document.getElementById('pub-dirigido').value;
    const textos = {
        todos:    'Se enviará una notificación a todas las familias y docentes del centro.',
        familias: 'Se enviará una notificación a todas las familias del centro.',
        docentes: 'Se enviará una notificación a todos los docentes del centro.',
    };
    document.getElementById('aviso-notificacion-texto').textContent =
        textos[dirigido] || textos.todos;
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

function limpiarErroresPublicar() {
    ['pub-titulo', 'pub-contenido', 'pub-fecha-limite'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('input-error');
        el.parentNode.querySelector('.campo-error')?.remove();
    });
    document.getElementById('alert-publicar').innerHTML = '';
}

function marcarError(id, mensaje) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('input-error');
    if (!el.parentNode.querySelector('.campo-error')) {
        const div = document.createElement('div');
        div.className = 'campo-error';
        div.textContent = mensaje;
        el.insertAdjacentElement('afterend', div);
    }
}

async function publicarAnuncio() {
    const titulo    = v('pub-titulo');
    const contenido = v('pub-contenido');
    const categoria = v('pub-categoria');
    const dirigido  = v('pub-dirigido');
    const clase     = v('pub-clase') || null;
    const limite    = v('pub-fecha-limite') || null;
    const hoy       = new Date().toISOString().slice(0, 10);

    limpiarErroresPublicar();
    let hayError = false;

    if (!titulo) {
        marcarError('pub-titulo', 'El título es obligatorio.');
        hayError = true;
    } else if (titulo.length < 5) {
        marcarError('pub-titulo', 'El título debe tener al menos 5 caracteres.');
        hayError = true;
    }

    if (!contenido) {
        marcarError('pub-contenido', 'El contenido es obligatorio.');
        hayError = true;
    } else if (contenido.length < 10) {
        marcarError('pub-contenido', 'El contenido debe tener al menos 10 caracteres.');
        hayError = true;
    }

    if (limite && limite < hoy) {
        marcarError('pub-fecha-limite', 'La fecha límite no puede ser anterior a hoy.');
        hayError = true;
    }

    if (hayError) return;

    const btn = document.querySelector('#modal-publicar .modal-actions button:last-child');
    setBtnLoading(btn, true);

    const dirigidoMap  = { todos: 'Todos', familias: 'Solo familias', docentes: 'Solo docentes' };
    const categoriaMap = { general: 'General', examen: 'Examen', evento: 'Evento', urgente: 'Urgente', tarea: 'Tarea' };

    const payload = {
        titulo,
        contenido,
        categoria:    categoriaMap[categoria] ?? 'General',
        dirigido_a:   dirigidoMap[dirigido]   ?? 'Todos',
        clase_id:     clase  || null,
        fecha_limite: limite || null,
    };

    try {
        if (modoEdicion) {
            const r = await api('PUT', `/tablon/${idEditando}`, payload);
            if (r?.ok === false) { toast('❌ ' + (r.mensaje || 'Error al actualizar')); return; }
            toast('✓ Anuncio actualizado');
            if (typeof audit === 'function') audit('anuncio_actualizado', 'anuncio', titulo);
        } else {
            const r = await api('POST', '/tablon', payload);
            if (r?.ok === false) { toast('❌ ' + (r.mensaje || 'Error al publicar')); return; }
            const dirigidoTexto = { todos: 'familias y docentes', familias: 'familias', docentes: 'docentes' };
            toast(`📢 Anuncio publicado · Enviado a ${dirigidoTexto[dirigido] || 'todos'}`);
            if (typeof audit === 'function') audit('anuncio_publicado', 'anuncio', titulo);
        }

        await cargarAnuncios();
        cerrarModal('modal-publicar');
        aplicarFiltros();
        actualizarStats();
        setTimeout(() => window.scrollTo({ top: 0, behavior: 'smooth' }), 100);
    } finally {
        setBtnLoading(btn, false);
    }
}

/* ════════════════════════════════════════════
   ELIMINAR ANUNCIO
════════════════════════════════════════════ */
function pedirEliminar(id) {
    idEliminar = id;
    document.getElementById('btn-confirmar-eliminar').onclick = async () => {
        const r = await api('DELETE', `/tablon/${id}`);
        if (r?.ok === false) { toast('❌ ' + (r.mensaje || 'Error al eliminar')); cerrarModal('modal-eliminar'); return; }
        const eliminado = anuncios.find(a => a.id === id);
        cerrarModal('modal-eliminar');
        await cargarAnuncios();
        toast('🗑️ Anuncio eliminado');
        if (typeof audit === 'function') audit('anuncio_eliminado', 'anuncio', eliminado?.titulo || '');
    };
    abrirModalConScroll('modal-eliminar');
}

/* ════════════════════════════════════════════
   FILTROS
════════════════════════════════════════════ */
function filtrarCategoria(cat, el) {
    categoriaActiva = cat;
    document.querySelectorAll('.chip').forEach(c => c.classList.remove('activo'));
    el.classList.add('activo');
    aplicarFiltros();
}

function filtrarBusqueda() { aplicarFiltros(); }

function aplicarFiltros() {
    const q = document.getElementById('buscador').value.toLowerCase();
    anunciosFiltrados = anuncios.filter(a => {
        const matchCat = categoriaActiva === 'todos' || a.categoria === categoriaActiva;
        const matchQ   = !q ||
            a.titulo.toLowerCase().includes(q) ||
            a.contenido.toLowerCase().includes(q) ||
            a.autor.toLowerCase().includes(q);
        return matchCat && matchQ;
    });
    renderAnuncios();
}

/* ════════════════════════════════════════════
   STATS
════════════════════════════════════════════ */
function actualizarStats() {
    const hoy = new Date().toISOString().slice(0, 10);
    document.getElementById('stat-total').textContent    = anuncios.length;
    document.getElementById('stat-urgentes').textContent = anuncios.filter(a => a.categoria === 'urgente').length;
    document.getElementById('stat-hoy').textContent      = anuncios.filter(a => a.fecha === hoy).length;
}

/* ════════════════════════════════════════════
   CARGAR CLASES EN MODAL
════════════════════════════════════════════ */
async function cargarClasesEnModal() {
    const clasesSel = document.getElementById('pub-clase');
    if (sesion.rol === 'coordinador') {
        const [clasesData, cursosData] = await Promise.all([
            api('GET', '/api/clases'),
            api('GET', '/api/cursos'),
        ]);
        const clases = Array.isArray(clasesData) ? clasesData : [];
        const cursos = Array.isArray(cursosData) ? cursosData : [];
        clasesSel.innerHTML = '<option value="">Todas las clases</option>' +
            clases.map(c => {
                const curso = cursos.find(x => x.id === c.curso_id);
                return `<option value="${c.id}">${curso ? curso.nombre + ' – ' : ''}${c.nombre}</option>`;
            }).join('');
    } else {
        const data = await api('GET', '/api/mis-clases');
        const clases = Array.isArray(data) ? data : [];
        clasesSel.innerHTML = '<option value="">Todas las clases</option>' +
            clases.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
    }
}

/* ════════════════════════════════════════════
   SISTEMA DE COMENTARIOS
════════════════════════════════════════════ */
function renderComentarios(anuncioId) {
    const lista = comentarios[anuncioId] || [];
    const el    = document.getElementById('comentarios-lista');
    const count = document.getElementById('comentarios-count');

    count.textContent = lista.length;

    if (!lista.length) {
        el.innerHTML = `<div class="sin-comentarios">💬 Sé el primero en comentar este anuncio.</div>`;
        return;
    }

    el.innerHTML = lista.map(c => {
        const ini      = c.autor.split(' ').map(p => p[0]).join('').slice(0, 2).toUpperCase();
        const esMio    = c.user_id === sesion.id || sesion.rol === 'coordinador';
        const rolLabel = c.rol === 'coordinador' ? 'Coordinador'
                       : c.rol === 'docente'     ? 'Docente' : 'Familiar';
        const hora     = new Date(c.fecha).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        const fechaRel = formatFechaRelativa(c.fecha.slice(0, 10));

        return `
        <div class="comentario-item">
            <div class="comentario-avatar ${c.rol}">${ini}</div>
            <div class="comentario-burbuja">
                <div class="comentario-cabecera">
                    <span class="comentario-autor">${c.autor}</span>
                    <span class="comentario-rol ${c.rol}">${rolLabel}</span>
                    <span class="comentario-fecha">${fechaRel} · ${hora}</span>
                </div>
                <p class="comentario-texto">${c.texto}</p>
                ${esMio ? `<button class="comentario-eliminar" onclick="eliminarComentario(${anuncioId}, ${c.id})">🗑️ Eliminar</button>` : ''}
            </div>
        </div>`;
    }).join('');
}

async function enviarComentario() {
    const texto = document.getElementById('comentario-texto').value.trim();
    if (!texto || !anuncioActivo) return;

    const btn = document.querySelector('.btn-comentar');
    setBtnLoading(btn, true);
    try {
        const r = await api('POST', `/tablon/${anuncioActivo}/comentarios`, { texto });
        if (r?.ok === false) { toast('❌ ' + (r?.mensaje || 'Error al enviar el comentario.')); return; }

        if (!comentarios[anuncioActivo]) comentarios[anuncioActivo] = [];
        comentarios[anuncioActivo].push({
            id:      r.comentario?.id ?? Date.now(),
            user_id: sesion.id,
            autor:   `${sesion.nombre} ${sesion.apellidos}`.trim(),
            rol:     sesion.rol,
            texto,
            fecha:   new Date().toISOString(),
        });

        document.getElementById('comentario-texto').value = '';
        renderComentarios(anuncioActivo);

        const anuncio = anuncios.find(a => a.id === anuncioActivo);
        if (anuncio) anuncio.comentarios_count = comentarios[anuncioActivo].length;
        actualizarContadorTarjeta(anuncioActivo);
        toast('💬 Comentario enviado');
    } finally {
        setBtnLoading(btn, false);
    }
}

async function eliminarComentario(anuncioId, comentarioId) {
    const r = await api('DELETE', `/comentarios/${comentarioId}`);
    if (r?.ok === false) { toast('❌ ' + (r?.mensaje || 'Error al eliminar el comentario.')); return; }
    comentarios[anuncioId] = (comentarios[anuncioId] || []).filter(c => c.id !== comentarioId);
    const anuncio = anuncios.find(a => a.id === anuncioId);
    if (anuncio) anuncio.comentarios_count = comentarios[anuncioId].length;
    renderComentarios(anuncioId);
    actualizarContadorTarjeta(anuncioId);
    toast('🗑️ Comentario eliminado');
}

function actualizarContadorTarjeta(anuncioId) {
    const count = (comentarios[anuncioId] || []).length;
    const el    = document.querySelector(`#anuncio-${anuncioId} .comentarios-tarjeta-count`);
    if (el) el.textContent = count > 0 ? `💬 ${count}` : '💬 Comentar';
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function formatFecha(f) {
    if (!f) return '—';
    const [y, m, d] = f.slice(0, 10).split('-');
    return `${d}/${m}/${y}`;
}

function formatFechaRelativa(f) {
    if (!f) return '';
    const hoy   = new Date(); hoy.setHours(0, 0, 0, 0);
    const fecha = new Date(f + 'T12:00:00');
    const diff  = Math.round((hoy - fecha) / 86400000);
    if (diff === 0) return 'Hoy';
    if (diff === 1) return 'Ayer';
    if (diff < 7)   return `Hace ${diff} días`;
    return formatFecha(f);
}

function v(id)        { return document.getElementById(id)?.value?.trim() || ''; }
function set(id, val) { const el = document.getElementById(id); if (el) el.value = val; }

function abrirModalConScroll(id) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    setTimeout(() => {
        document.body.style.overflow = 'hidden';
        const modal = document.getElementById(id);
        modal.classList.add('open');
        const inner = modal.querySelector('.modal');
        if (inner) inner.scrollTop = 0;
    }, 350);
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) cerrarModal(o.id); });
});

['pub-titulo', 'pub-contenido', 'pub-fecha-limite'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', function () {
        this.classList.remove('input-error');
        this.parentNode.querySelector('.campo-error')?.remove();
    });
});

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
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    };
    if (body) opts.body = JSON.stringify(body);

    try {
        const res  = await fetch(API + ruta, opts);
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

document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = '/login';
});
