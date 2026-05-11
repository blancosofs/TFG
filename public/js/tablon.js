/* ══════════════════════════════════════════════════════════════
   Edunoly · tablon.js

   LÓGICA DEL TABLÓN DE ANUNCIOS:
   - Docente: puede ver Y publicar anuncios
   - Tutor/Familiar: solo puede ver anuncios

   Los anuncios tienen:
   - titulo, contenido, categoria, dirigido_a, clase, fecha_limite
   - autor (nombre del docente que lo publica)
   - fecha de publicación

   En la BD se guardarían en una tabla 'anuncios' con:
   - id, titulo, contenido, categoria, dirigido_a, clase_id,
     fecha_limite, docente_id, colegio_id, creado_en
══════════════════════════════════════════════════════════════ */

const API  = '';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

/* ── Estado ── */
let sesion           = null;
let anuncios         = [];       // todos los anuncios cargados
let anunciosFiltrados = [];      // los que se muestran ahora
let categoriaActiva  = 'todos';
let idEliminar       = null;
let modoEdicion      = false;
let idEditando       = null;

/* ── Emojis y nombres de categoría ── */
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
    const data = await api('GET', '/api/me');
    if (!data?.id) { window.location.href = '/login'; return; }
    sesion = data;

    configurarVistaPorRol();
    await cargarAnuncios();
    if (sesion.rol === 'docente' || sesion.rol === 'coordinador') cargarClasesEnModal();
})();

/* ════════════════════════════════════════════
   CONFIGURAR SEGÚN ROL
════════════════════════════════════════════ */
function configurarVistaPorRol() {
    const puedePublicar = sesion.rol === 'docente' || sesion.rol === 'coordinador';
    document.getElementById('hero-acciones').style.display = puedePublicar ? 'block' : 'none';
}

/* ════════════════════════════════════════════
   CARGAR ANUNCIOS
════════════════════════════════════════════ */
async function cargarAnuncios() {
    const data = await api('GET', '/api/tablon');
    if (data && !data.error) {
        anuncios = data.map(p => ({
            id:              p.id,
            titulo:          p.titulo,
            contenido:       p.contenido,
            categoria:       p.categoria?.toLowerCase() ?? 'general',
            dirigido_a:      p.dirigido_a === 'Solo familias' ? 'familias'
                           : p.dirigido_a === 'Solo docentes' ? 'docentes' : 'todos',
            clase:           p.clase?.nombre ?? null,
            fecha_limite:    p.fecha_limite,
            autor:           p.docente?.user?.name
                             ? `${p.docente.user.name} ${p.docente.user.apellidos ?? ''}`.trim()
                             : 'Autor desconocido',
            autor_rol:       'docente',
            docente_user_id: p.docente_user_id ?? null,
            fecha:           p.created_at?.slice(0, 10) ?? new Date().toISOString().slice(0, 10),
            comentarios_count: (p.comentarios ?? []).length,
        }));

        // Poblar el objeto de comentarios con los datos del servidor
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
    return;

    // Datos de prueba (desactivados)
    const hoy = new Date().toISOString().slice(0, 10);
    anuncios = [
        {
            id: 1,
            titulo: '🚨 Cambio de horario — Jueves 16 de mayo',
            contenido: 'Se informa a todas las familias y docentes que el próximo jueves 16 de mayo habrá un cambio de horario por el acto de fin de curso.\n\nLas clases comenzarán a las 10:00h en lugar de las 8:00h. Se ruega puntualidad.',
            categoria: 'urgente',
            dirigido_a: 'todos',
            clase: null,
            fecha_limite: null,
            autor: 'Ana Ruiz Sánchez',
            autor_rol: 'coordinador',
            fecha: hoy,
        },
        {
            id: 2,
            titulo: 'Examen de Matemáticas — 1ºA y 1ºB',
            contenido: 'El próximo lunes 20 de mayo tendrá lugar el examen de Matemáticas correspondiente al tercer trimestre.\n\nTemario: álgebra (tema 7), geometría (tema 8) y estadística (tema 9).\n\nSe recomienda repasar los ejercicios de clase y el libro de texto.',
            categoria: 'examen',
            dirigido_a: 'todos',
            clase: '1ºA y 1ºB',
            fecha_limite: '2026-05-20',
            autor: 'Pedro Fernández Gil',
            autor_rol: 'docente',
            fecha: hoy,
        },
        {
            id: 3,
            titulo: 'Reunión de padres — Tercer trimestre',
            contenido: 'Se convoca a las familias a la reunión de tutores del tercer trimestre.\n\nFecha: martes 21 de mayo\nHorario: 17:00 — 19:00h\nLugar: Sala de actos del centro\n\nSe tratarán temas relativos al rendimiento académico y la evaluación final.',
            categoria: 'evento',
            dirigido_a: 'familias',
            clase: null,
            fecha_limite: '2026-05-21',
            autor: 'Ana Ruiz Sánchez',
            autor_rol: 'coordinador',
            fecha: new Date(Date.now() - 86400000).toISOString().slice(0, 10),
        },
        {
            id: 4,
            titulo: 'Entrega de trabajo — Historia del Arte',
            contenido: 'Recordatorio: el trabajo sobre el Renacimiento italiano debe entregarse antes del viernes 24 de mayo.\n\nFormato: PDF, mínimo 5 páginas, con bibliografía.\n\nEntrega en el aula virtual o en papel antes de las 14:00h.',
            categoria: 'tarea',
            dirigido_a: 'docentes',
            clase: '3ºA',
            fecha_limite: '2026-05-24',
            autor: 'Roberto Iglesias Mora',
            autor_rol: 'docente',
            fecha: new Date(Date.now() - 172800000).toISOString().slice(0, 10),
        },
        {
            id: 5,
            titulo: 'Bienvenidos al tercer trimestre',
            contenido: 'Estimadas familias y docentes:\n\nComenzamos el tercer y último trimestre del curso 2025-2026. Queremos recordar la importancia del esfuerzo y la constancia en estos últimos meses.\n\nDesde el centro estamos a vuestra disposición para cualquier consulta.',
            categoria: 'general',
            dirigido_a: 'todos',
            clase: null,
            fecha_limite: null,
            autor: 'Ana Ruiz Sánchez',
            autor_rol: 'coordinador',
            fecha: new Date(Date.now() - 259200000).toISOString().slice(0, 10),
        },
    ];

    // Filtrar según el rol del usuario
    // Los tutores no ven anuncios dirigidos solo a docentes
    if (sesion.rol === 'tutor') {
        anuncios = anuncios.filter(a => a.dirigido_a !== 'docentes');
    }

    anunciosFiltrados = [...anuncios];
    renderAnuncios();
    actualizarStats();
}

/* ════════════════════════════════════════════
   RENDER ANUNCIOS
════════════════════════════════════════════ */
function renderAnuncios() {
    const lista  = document.getElementById('anuncios-lista');
    const vacio  = document.getElementById('anuncios-vacio');

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
    const esAutor = (sesion.rol === 'docente' && a.docente_user_id === sesion.id) || sesion.rol === 'coordinador';
    const textoCorto = a.contenido.length > 180
        ? a.contenido.slice(0, 180).trim() + '…'
        : a.contenido;
    const hoy     = new Date().toISOString().slice(0, 10);
    const esHoy   = a.fecha === hoy;

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
    const a   = anuncios.find(x => x.id === id);
    if (!a) return;
    const cat = CATEGORIAS[a.categoria] || CATEGORIAS.general;

    document.getElementById('ver-badge').className   = `cat-badge ${cat.clase}`;
    document.getElementById('ver-badge').textContent = `${cat.emoji} ${cat.nombre}`;
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

    abrirModalConScroll('modal-ver', id);
}

/* ════════════════════════════════════════════
   PUBLICAR / EDITAR ANUNCIO
════════════════════════════════════════════ */
function abrirModalPublicar() {
    modoEdicion = false; idEditando = null;
    document.getElementById('modal-pub-titulo').textContent = '✏️ Publicar anuncio';
    ['pub-titulo','pub-contenido'].forEach(id => {
        document.getElementById(id).value = '';
    });
    set('pub-categoria', 'general');
    set('pub-dirigido', 'todos');
    set('pub-clase', '');
    set('pub-fecha-limite', '');
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

/* ── Actualiza el aviso según a quién va dirigido ── */
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

async function publicarAnuncio() {
    const titulo    = v('pub-titulo');
    const contenido = v('pub-contenido');
    const categoria = v('pub-categoria');
    const dirigido  = v('pub-dirigido');
    const clase     = v('pub-clase') || null;
    const limite    = v('pub-fecha-limite') || null;

    if (!titulo || !contenido) {
        document.getElementById('alert-publicar').innerHTML =
            `<div class="alert-modal alert-err">⚠️ El título y el contenido son obligatorios.</div>`;
        return;
    }

    const hoy = new Date().toISOString().slice(0, 10);

    const dirigidoMap = { todos: 'Todos', familias: 'Solo familias', docentes: 'Solo docentes' };
    const categoriaMap = { general: 'General', examen: 'Examen', evento: 'Evento', urgente: 'Urgente', tarea: 'Tarea' };

    const payload = {
        titulo,
        contenido,
        categoria:   categoriaMap[categoria] ?? 'General',
        dirigido_a:  dirigidoMap[dirigido]   ?? 'Todos',
        clase_id:    clase  || null,
        fecha_limite: limite || null,
    };

    if (modoEdicion) {
        const r = await api('PUT', `/tablon/${idEditando}`, payload);
        if (r?.ok === false) { toast('❌ ' + (r.mensaje || 'Error al actualizar')); return; }
        toast('✓ Anuncio actualizado');
    } else {
        const r = await api('POST', '/tablon', payload);
        if (r?.ok === false) { toast('❌ ' + (r.mensaje || 'Error al publicar')); return; }
        const dirigidoTexto = { todos: 'familias y docentes', familias: 'familias', docentes: 'docentes' };
        toast(`📢 Anuncio publicado · Enviado a ${dirigidoTexto[dirigido] || 'todos'}`);
    }

    await cargarAnuncios();

    cerrarModal('modal-publicar');
    aplicarFiltros();
    actualizarStats();

    // Subir al principio tras publicar para ver el nuevo anuncio
    setTimeout(() => window.scrollTo({ top: 0, behavior: 'smooth' }), 100);
}

/* ════════════════════════════════════════════
   ELIMINAR ANUNCIO
════════════════════════════════════════════ */
function pedirEliminar(id) {
    idEliminar = id;
    document.getElementById('btn-confirmar-eliminar').onclick = async () => {
        const r = await api('DELETE', `/tablon/${id}`);
        if (r?.ok === false) { toast('❌ ' + (r.mensaje || 'Error al eliminar')); cerrarModal('modal-eliminar'); return; }
        cerrarModal('modal-eliminar');
        await cargarAnuncios();
        toast('🗑️ Anuncio eliminado');
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
        // Coordinadores: todas las clases del colegio con nombre del curso
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
        // Docentes: solo sus clases asignadas
        const data = await api('GET', '/api/mis-clases');
        const clases = Array.isArray(data) ? data : [];
        clasesSel.innerHTML = '<option value="">Todas las clases</option>' +
            clases.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('');
    }
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function formatFecha(f) {
    if (!f) return '—';
    const [y,m,d] = f.slice(0, 10).split('-');
    return `${d}/${m}/${y}`;
}

function formatFechaRelativa(f) {
    if (!f) return '';
    const hoy    = new Date(); hoy.setHours(0,0,0,0);
    const fecha  = new Date(f + 'T12:00:00');
    const diff   = Math.round((hoy - fecha) / 86400000);
    if (diff === 0)  return 'Hoy';
    if (diff === 1)  return 'Ayer';
    if (diff < 7)    return `Hace ${diff} días`;
    return formatFecha(f);
}

function v(id)        { return document.getElementById(id)?.value?.trim() || ''; }
function set(id, val) { const el = document.getElementById(id); if (el) el.value = val; }

function abrirModalConScroll(id, anuncioId) {
    const modal = document.getElementById(id);

    // Subir la página hasta arriba primero, luego abrir el modal
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Esperar a que el scroll termine y entonces abrir el modal
    setTimeout(() => {
        document.body.style.overflow = 'hidden';
        modal.classList.add('open');
        const inner = modal.querySelector('.modal');
        if (inner) inner.scrollTop = 0;
    }, 350);
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('open');
    // Restaurar scroll del body
    document.body.style.overflow = '';
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => {
        if (e.target === o) cerrarModal(o.id);
    });
});

function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
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
            if (res.status >= 500) { console.error(`[API ${method} ${ruta}]`, data); return { ok: false, mensaje: 'Error interno del servidor. Inténtalo de nuevo más tarde.' }; }
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

/* ══════════════════════════════════════════════════════════════
   SISTEMA DE COMENTARIOS
══════════════════════════════════════════════════════════════ */

let anuncioActivo = null; // id del anuncio que está abierto en el modal
let comentarios   = {};   // { anuncio_id: [ {id, user_id, autor, rol, texto, fecha} ] }

/* ── Actualizar verAnuncio para incluir comentarios ── */
const _verAnuncioOriginal = verAnuncio;
verAnuncio = function(id) {
    _verAnuncioOriginal(id);
    anuncioActivo = id;
    // El scroll ya lo gestiona verAnuncio internamente

    // Iniciales del usuario en el avatar del formulario
    const ini = sesion.nombre.charAt(0) + sesion.apellidos.charAt(0);
    document.getElementById('nuevo-avatar').textContent = ini;
    document.getElementById('nuevo-avatar').className =
        `nuevo-comentario-avatar ${sesion.rol === 'tutor' ? 'tutor' : 'docente'}`;

    renderComentarios(id);
};

/* ── Render comentarios ── */
function renderComentarios(anuncioId) {
    const lista = comentarios[anuncioId] || [];
    const el    = document.getElementById('comentarios-lista');
    const count = document.getElementById('comentarios-count');

    count.textContent = lista.length;

    if (!lista.length) {
        el.innerHTML = `<div class="sin-comentarios">
            💬 Sé el primero en comentar este anuncio.
        </div>`;
        return;
    }

    el.innerHTML = lista.map(c => {
        const ini      = c.autor.split(' ').map(p => p[0]).join('').slice(0, 2).toUpperCase();
        const esMio    = c.user_id === sesion.id || sesion.rol === 'coordinador';
        const rolLabel = c.rol === 'docente' ? 'Docente' : 'Familiar';
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

/* ── Enviar comentario ── */
async function enviarComentario() {
    const texto = document.getElementById('comentario-texto').value.trim();
    if (!texto) return;

    if (!anuncioActivo) return;

    const r = await api('POST', `/tablon/${anuncioActivo}/comentarios`, { texto });
    if (!r?.ok) { toast('❌ ' + (r?.mensaje || 'Error al enviar el comentario.')); return; }

    if (!comentarios[anuncioActivo]) comentarios[anuncioActivo] = [];

    comentarios[anuncioActivo].push({
        id:      r.comentario?.id ?? Date.now(),
        user_id: sesion.id,
        autor:   `${sesion.nombre} ${sesion.apellidos}`,
        rol:     sesion.rol,
        texto,
        fecha:   new Date().toISOString(),
    });

    document.getElementById('comentario-texto').value = '';
    renderComentarios(anuncioActivo);

    // Actualizar el contador en la tarjeta
    const anuncio = anuncios.find(a => a.id === anuncioActivo);
    if (anuncio) anuncio.comentarios_count = comentarios[anuncioActivo].length;
    actualizarContadorTarjeta(anuncioActivo);
    toast('💬 Comentario enviado');
}

/* ── Eliminar comentario ── */
async function eliminarComentario(anuncioId, comentarioId) {
    const r = await api('DELETE', `/comentarios/${comentarioId}`);
    if (!r?.ok) { toast('❌ ' + (r?.mensaje || 'Error al eliminar el comentario.')); return; }
    comentarios[anuncioId] = (comentarios[anuncioId] || []).filter(c => c.id !== comentarioId);
    const anuncio = anuncios.find(a => a.id === anuncioId);
    if (anuncio) anuncio.comentarios_count = comentarios[anuncioId].length;
    renderComentarios(anuncioId);
    actualizarContadorTarjeta(anuncioId);
    toast('🗑️ Comentario eliminado');
}

/* ── Actualizar el contador de comentarios en la tarjeta ── */
function actualizarContadorTarjeta(anuncioId) {
    const count = (comentarios[anuncioId] || []).length;
    const el    = document.querySelector(`#anuncio-${anuncioId} .comentarios-tarjeta-count`);
    if (el) el.textContent = count > 0 ? `💬 ${count}` : '💬 Comentar';
}
