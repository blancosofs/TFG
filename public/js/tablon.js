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

const API = '';

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
    // const data = await api('GET', '/api/me');
    // if (!data?.id) { window.location.href = 'login.html'; return; }
    // if (data.rol !== 'docente' && data.rol !== 'tutor') { window.location.href = 'login.html'; return; }
    // sesion = data;

    // Datos de prueba — cambiar según quien prueba
    // Para probar como TUTOR cambia rol a 'tutor'
    sesion = {
        id: 1,
        nombre: 'Pedro',
        apellidos: 'Fernández Gil',
        rol: 'docente',          // 'docente' | 'tutor'
        colegio: 'IES Ejemplo Madrid',
        colegio_id: 1
    };

    configurarVistaPorRol();
    await cargarAnuncios();
    cargarClasesEnModal();
})();

/* ════════════════════════════════════════════
   CONFIGURAR SEGÚN ROL
════════════════════════════════════════════ */
function configurarVistaPorRol() {
    const nombre = `${sesion.nombre} ${sesion.apellidos}`;
    document.getElementById('nav-nombre').textContent    = nombre;
    document.getElementById('nav-rol-label').textContent = sesion.rol === 'docente' ? 'Docente' : 'Tutor legal';

    // Links de navegación según rol
    if (sesion.rol === 'docente') {
        document.getElementById('nav-inicio').href      = 'calendario.html';
        document.getElementById('nav-perfil-link').href = 'perfil.html';
        document.getElementById('nav-mi-perfil').href   = 'perfil.html';
        // Solo el docente puede publicar
        document.getElementById('hero-acciones').style.display = 'block';
    } else {
        document.getElementById('nav-inicio').href      = 'perfilFamilia.html';
        document.getElementById('nav-perfil-link').href = 'perfilFamilia.html';
        document.getElementById('nav-mi-perfil').href   = 'perfilFamilia.html';
        document.getElementById('hero-acciones').style.display = 'none';
    }
}

/* ════════════════════════════════════════════
   CARGAR ANUNCIOS
════════════════════════════════════════════ */
async function cargarAnuncios() {
    // const data = await api('GET', '/api/anuncios');
    // anuncios = data || [];

    // Datos de prueba
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
    const esAutor = sesion.rol === 'docente' && a.autor_rol !== 'tutor';
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

    if (modoEdicion) {
        // await api('PUT', `/api/anuncios/${idEditando}`, { titulo, contenido, categoria, dirigido_a: dirigido, clase, fecha_limite: limite });
        const idx = anuncios.findIndex(a => a.id === idEditando);
        if (idx !== -1) {
            anuncios[idx] = { ...anuncios[idx], titulo, contenido, categoria, dirigido_a: dirigido, clase, fecha_limite: limite };
        }
        toast('✓ Anuncio actualizado');
    } else {
        // const data = await api('POST', '/api/anuncios', { titulo, contenido, categoria, dirigido_a: dirigido, clase, fecha_limite: limite });
        const nuevo = {
            id: Date.now(),
            titulo, contenido, categoria,
            dirigido_a: dirigido,
            clase, fecha_limite: limite,
            autor: `${sesion.nombre} ${sesion.apellidos}`,
            autor_rol: sesion.rol,
            fecha: hoy,
        };
        anuncios.unshift(nuevo);
        const dirigidoTexto = { todos: 'familias y docentes', familias: 'familias', docentes: 'docentes' };
        toast(`📢 Anuncio publicado · 🔔 Notificación enviada a ${dirigidoTexto[dirigido] || 'todos'}`);
    }

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
        // await api('DELETE', `/api/anuncios/${id}`);
        anuncios = anuncios.filter(a => a.id !== id);
        cerrarModal('modal-eliminar');
        aplicarFiltros();
        actualizarStats();
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
function cargarClasesEnModal() {
    // En producción: cargar desde /api/clases
    const clasesSel = document.getElementById('pub-clase');
    const clases = ['1ºA','1ºB','2ºA','2ºB','3ºA','4ºA'];
    clasesSel.innerHTML = '<option value="">Todas las clases</option>' +
        clases.map(c => `<option value="${c}">${c}</option>`).join('');
}

/* ════════════════════════════════════════════
   UTILIDADES
════════════════════════════════════════════ */
function formatFecha(f) {
    if (!f) return '—';
    const [y,m,d] = f.split('-');
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

async function api(method, ruta, body) {
    try {
        const opts = { method, credentials: 'include', headers: { 'Content-Type': 'application/json' } };
        if (body) opts.body = JSON.stringify(body);
        const r = await fetch(API + ruta, opts);
        return await r.json();
    } catch (e) { return { error: 'Error de conexión.' }; }
}

document.getElementById('btn-logout')?.addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = 'login.html';
});

/* ══════════════════════════════════════════════════════════════
   SISTEMA DE COMENTARIOS
══════════════════════════════════════════════════════════════ */

let anuncioActivo = null; // id del anuncio que está abierto en el modal
let comentarios   = {};   // { anuncio_id: [ {id, autor, rol, texto, fecha} ] }

/* ── Datos de prueba de comentarios ── */
comentarios = {
    1: [
        { id: 1, autor: 'María López', rol: 'tutor',   texto: 'Gracias por avisar con tiempo. ¿Las actividades extraescolares también cambian?', fecha: new Date().toISOString() },
        { id: 2, autor: 'Pedro Fernández', rol: 'docente', texto: 'Las extraescolares mantienen su horario habitual, solo cambian las clases ordinarias.', fecha: new Date().toISOString() },
    ],
    2: [
        { id: 3, autor: 'Juan Martínez', rol: 'tutor', texto: '¿El examen incluye los ejercicios del libro o solo los de clase?', fecha: new Date().toISOString() },
    ],
};

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
        const esMio    = c.autor === `${sesion.nombre} ${sesion.apellidos}`;
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

    // await api('POST', `/api/anuncios/${anuncioActivo}/comentarios`, { texto });

    if (!comentarios[anuncioActivo]) comentarios[anuncioActivo] = [];

    comentarios[anuncioActivo].push({
        id:     Date.now(),
        autor:  `${sesion.nombre} ${sesion.apellidos}`,
        rol:    sesion.rol,
        texto,
        fecha:  new Date().toISOString(),
    });

    document.getElementById('comentario-texto').value = '';
    renderComentarios(anuncioActivo);

    // Actualizar el contador en la tarjeta
    actualizarContadorTarjeta(anuncioActivo);
    toast('💬 Comentario enviado');
}

/* ── Eliminar comentario ── */
async function eliminarComentario(anuncioId, comentarioId) {
    // await api('DELETE', `/api/anuncios/${anuncioId}/comentarios/${comentarioId}`);
    comentarios[anuncioId] = (comentarios[anuncioId] || []).filter(c => c.id !== comentarioId);
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
