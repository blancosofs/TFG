/* ══════════════════════════════════════════════════════════════
   CONFIG
   Cambia API_BASE solo si el backend está en otro dominio/puerto
══════════════════════════════════════════════════════════════ */
const API_BASE = '';

/* ══════════════════════════════════════════════════════════════
   CONSTANTES
══════════════════════════════════════════════════════════════ */
const MESES   = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
const DIAS_ES = ['L','M','X','J','V','S','D'];

// Cada materia recibe un color distinto automáticamente
const PALETA  = ['#2a7a55','#c9a02f','#2f6bc9','#c92f2f','#7a4fc9',
                 '#c96b2f','#2f9ec9','#4f8a2a','#c92f7a','#2f7ac9'];
const coloresMateria = {};
let paletaIdx = 0;

function colorMateria(nombre) {
    if (!coloresMateria[nombre]) {
        coloresMateria[nombre] = PALETA[paletaIdx % PALETA.length];
        paletaIdx++;
    }
    return coloresMateria[nombre];
}

/* ══════════════════════════════════════════════════════════════
   ESTADO
══════════════════════════════════════════════════════════════ */
const hoy        = new Date();
let fechaVista   = new Date(hoy);
let fechaMini    = new Date(hoy);
let vistaActual  = 'month';
let fechaSel     = null;
let usuario      = null;   // datos del docente logueado
let clases       = [];     // todas las clases del período visible

function fmtFecha(d) {
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

/* ══════════════════════════════════════════════════════════════
   API
══════════════════════════════════════════════════════════════ */
async function api(method, ruta, body) {
    const opts = { method, credentials: 'include', headers: {'Content-Type':'application/json'} };
    if (body) opts.body = JSON.stringify(body);
    const r = await fetch(API_BASE + ruta, opts);
    return r.json();
}

/* ══════════════════════════════════════════════════════════════
   ARRANQUE — comprueba sesión y rol
══════════════════════════════════════════════════════════════ */
(async () => {
    const data = await api('GET', '/api/me');

    // Sin sesión → login
    if (!data || !data.id) {
        window.location.href = '/login';
        return;
    }

    // Rol incorrecto → login (no es docente)
    if (data.rol !== 'docente') {
        window.location.href = '/login';
        return;
    }

    usuario = data;
    iniciarApp();
})();

/* ══════════════════════════════════════════════════════════════
   INICIO
══════════════════════════════════════════════════════════════ */
function iniciarApp() {
    // Rellenar datos del docente en la UI
    const nombreCompleto = `${usuario.nombre} ${usuario.apellidos}`;
    document.getElementById('profesor-nombre').textContent = nombreCompleto;
    document.getElementById('nav-nombre').textContent      = nombreCompleto;
    document.getElementById('nav-rol').textContent         = 'Docente';
    document.getElementById('profesor-dot').style.background = usuario.color || '#47ad79';
    document.getElementById('hero-sub').textContent =
        `Bienvenido/a, ${usuario.nombre}. Aquí tienes tu horario semanal.`;

    cargarYRenderizar();
}

/* ══════════════════════════════════════════════════════════════
   LOGOUT
══════════════════════════════════════════════════════════════ */
document.getElementById('btn-logout').addEventListener('click', async e => {
    e.preventDefault();
    await api('POST', '/api/logout');
    window.location.href = '/login';
});

/* ══════════════════════════════════════════════════════════════
   CARGA DE DATOS DESDE LA BASE DE DATOS
   GET /api/clases devuelve las clases del docente logueado
   expandidas en fechas concretas para el período pedido.
   Cada elemento tiene:
     { fecha, hora_inicio, hora_fin, materia, grupo, aula, clase_id }
══════════════════════════════════════════════════════════════ */
async function cargarYRenderizar() {
    const { desde, hasta } = getRango();
    document.getElementById('cal-vista').innerHTML =
        '<div class="cargando"><div class="spinner"></div><span>Cargando…</span></div>';

    const resultado = await api('GET', `/api/clases?desde=${desde}&hasta=${hasta}`);
    clases = (resultado || []).map(c => ({
        ...c,
        color: colorMateria(c.materia),
    }));

    renderizar();
}

function getRango() {
    if (vistaActual === 'month') {
        const y = fechaVista.getFullYear(), m = fechaVista.getMonth();
        // Traemos también la semana anterior y posterior para rellenar los huecos del grid
        return {
            desde: `${y}-${String(m).padStart(2,'0')}-01`,
            hasta: `${y}-${String(m+2).padStart(2,'0')}-07`,
        };
    }
    const d = new Date(fechaVista);
    let dow = d.getDay(); dow = dow === 0 ? 6 : dow - 1;
    const lun = new Date(d); lun.setDate(d.getDate() - dow - 7);
    const dom = new Date(lun); dom.setDate(lun.getDate() + 20);
    return { desde: fmtFecha(lun), hasta: fmtFecha(dom) };
}

/* ══════════════════════════════════════════════════════════════
   RENDER GENERAL
══════════════════════════════════════════════════════════════ */
function renderizar() {
    renderMiniCal();
    renderProximas();
    renderCalendario();
}

/* ── Mini calendario ── */
function renderMiniCal() {
    const y = fechaMini.getFullYear(), m = fechaMini.getMonth();
    document.getElementById('mini-titulo').textContent = `${MESES[m].slice(0,3)} ${y}`;
    const grid = document.getElementById('mini-grid');
    grid.innerHTML = DIAS_ES.map(d => `<div class="mini-dl">${d}</div>`).join('');

    let first = new Date(y,m,1).getDay(); first = first===0?6:first-1;
    const prev = new Date(y,m,0).getDate();
    const dias = new Date(y,m+1,0).getDate();

    for (let i=first-1;i>=0;i--) grid.innerHTML += miniCelda(prev-i,true,false,false,false,null);
    for (let d=1;d<=dias;d++) {
        const ds = `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const isH = y===hoy.getFullYear()&&m===hoy.getMonth()&&d===hoy.getDate();
        const tieneClase = clases.some(c=>c.fecha===ds);
        grid.innerHTML += miniCelda(d,false,isH,fechaSel===ds,tieneClase,ds);
    }
    const rem = (first+dias)%7;
    for (let d=1;d<=(rem?7-rem:0);d++) grid.innerHTML += miniCelda(d,true,false,false,false,null);
}

function miniCelda(d, otro, isH, isSel, tieneClase, ds) {
    let c = 'mini-day';
    if (otro) c += ' otro'; if (isH) c += ' hoy'; if (isSel) c += ' sel'; if (tieneClase) c += ' con-ev';
    const click = ds ? `onclick="clickMiniDia('${ds}')"` : '';
    return `<div class="${c}" ${click}>${d}</div>`;
}

function clickMiniDia(ds) {
    fechaSel = ds;
    const [y,m,d] = ds.split('-').map(Number);
    fechaVista = new Date(y,m-1,d);
    fechaMini  = new Date(y,m-1,1);
    cargarYRenderizar();
}

/* ── Próximas clases ── */
function renderProximas() {
    const hoyStr = fmtFecha(hoy);
    const prox = clases
        .filter(c => c.fecha >= hoyStr)
        .sort((a,b) => a.fecha.localeCompare(b.fecha) || a.hora_inicio.localeCompare(b.hora_inicio))
        .slice(0, 6);

    const el = document.getElementById('prox-lista');
    if (!prox.length) {
        el.innerHTML = '<p class="sin-clases">Sin clases próximas</p>';
        return;
    }

    el.innerHTML = prox.map(c => {
        const [,mesN,dN] = c.fecha.split('-');
        const mes = MESES[parseInt(mesN)-1].slice(0,3);
        return `<div class="prox-item" onclick="verDetalle('${c.fecha}','${c.hora_inicio}')">
            <div class="prox-fecha">
                <span class="prox-num">${parseInt(dN)}</span>
                <span class="prox-mes">${mes}</span>
            </div>
            <div class="prox-info">
                <div class="prox-titulo">${c.materia}</div>
                <div class="prox-sub">${c.hora_inicio.slice(0,5)} – ${c.hora_fin.slice(0,5)}${c.grupo ? ' · '+c.grupo : ''}</div>
            </div>
            <div class="prox-punto" style="background:${c.color}"></div>
        </div>`;
    }).join('');
}

/* ══════════════════════════════════════════════════════════════
   VISTAS DEL CALENDARIO
══════════════════════════════════════════════════════════════ */
function renderCalendario() {
    vistaActual === 'month' ? renderMes() : renderSemana();
}

/* ── Vista mes ── */
function renderMes() {
    const y = fechaVista.getFullYear(), m = fechaVista.getMonth();
    document.getElementById('cal-titulo').textContent = `${MESES[m]} ${y}`;

    let first = new Date(y,m,1).getDay(); first = first===0?6:first-1;
    const dias = new Date(y,m+1,0).getDate();
    const prev = new Date(y,m,0).getDate();

    function celda(d, ds, esOtro, esHoy, esSel) {
        const clasesDelDia = clases
            .filter(c => c.fecha === ds)
            .sort((a,b) => a.hora_inicio.localeCompare(b.hora_inicio));

        let pills = '';
        clasesDelDia.slice(0,3).forEach(c => {
            pills += `<div class="ev-pill"
                style="background:${c.color}20;color:${c.color};border-left:2px solid ${c.color}"
                onclick="event.stopPropagation();verDetalle('${c.fecha}','${c.hora_inicio}')">
                ${c.hora_inicio.slice(0,5)} ${c.materia}
            </div>`;
        });
        if (clasesDelDia.length > 3)
            pills += `<div class="ev-mas">+${clasesDelDia.length-3} más</div>`;

        let cls = 'mes-celda';
        if (esOtro) cls += ' otro-mes';
        if (esHoy)  cls += ' hoy-celda';
        if (esSel)  cls += ' sel-celda';

        return `<div class="${cls}" onclick="clickDia('${ds}')">
            <div class="celda-num">${d}</div>
            <div class="celda-evs">${pills}</div>
        </div>`;
    }

    let html = `<div class="mes-wrap">
        <div class="mes-wd">
            ${['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'].map(d=>`<div class="mes-wd-c">${d}</div>`).join('')}
        </div>
        <div class="mes-grid">`;

    for (let i=first-1;i>=0;i--) {
        const d=prev-i, ds=`${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        html += celda(d,ds,true,false,false);
    }
    for (let d=1;d<=dias;d++) {
        const ds=`${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const isH=y===hoy.getFullYear()&&m===hoy.getMonth()&&d===hoy.getDate();
        html += celda(d,ds,false,isH,fechaSel===ds);
    }
    const rem=(first+dias)%7;
    for (let d=1;d<=(rem?7-rem:0);d++) {
        const ds=`${y}-${String(m+2).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        html += celda(d,ds,true,false,false);
    }

    html += `</div></div>`;
    document.getElementById('cal-vista').innerHTML = html;
}

/* ── Vista semana ── */
function renderSemana() {
    const d = new Date(fechaVista);
    let dow = d.getDay(); dow = dow===0?6:dow-1;
    const lun = new Date(d); lun.setDate(d.getDate()-dow);
    const semDias = Array.from({length:7}, (_,i) => {
        const x = new Date(lun); x.setDate(lun.getDate()+i); return x;
    });

    const m0=semDias[0].getMonth(), m1=semDias[6].getMonth(), y0=semDias[0].getFullYear();
    document.getElementById('cal-titulo').textContent =
        m0===m1 ? `${MESES[m0]} ${y0}` : `${MESES[m0]} – ${MESES[m1]} ${y0}`;

    const horas = Array.from({length:13}, (_,i) => i+7); // 7:00 – 19:00

    let html = `<div class="sem-wrap">
        <div class="sem-head">
            <div class="sem-head-c"></div>
            ${semDias.map(dia => {
                const isH = fmtFecha(dia)===fmtFecha(hoy);
                return `<div class="sem-head-c ${isH?'hoy-col':''}">
                    <div class="sem-wd">${DIAS_ES[dia.getDay()===0?6:dia.getDay()-1]}</div>
                    <div class="sem-num">${dia.getDate()}</div>
                </div>`;
            }).join('')}
        </div>
        <div class="sem-body">
            <div class="sem-horas">
                ${horas.map(h=>`<div class="sem-hora-slot">${h}:00</div>`).join('')}
            </div>
            ${semDias.map(dia => {
                const ds = fmtFecha(dia);
                const clasesDelDia = clases.filter(c => c.fecha===ds);
                let evHtml = '';

                clasesDelDia.forEach(c => {
                    const [sh, sm] = c.hora_inicio.slice(0,5).split(':').map(Number);
                    const [eh, em] = c.hora_fin.slice(0,5).split(':').map(Number);
                    const top    = (sh-7)*60 + sm;
                    const height = Math.max((eh-sh)*60 + (em-sm), 30);
                    const durMin = (eh-sh)*60+(em-sm);

                    evHtml += `<div class="sem-event"
                        style="top:${top}px;height:${height}px;
                               background:${c.color}18;color:${c.color};
                               border-left:3px solid ${c.color}"
                        onclick="verDetalle('${c.fecha}','${c.hora_inicio}')">
                        <div class="sem-ev-titulo">${c.materia}</div>
                        <div class="sem-ev-sub">
                            ${c.hora_inicio.slice(0,5)} – ${c.hora_fin.slice(0,5)}
                            ${c.grupo ? '· '+c.grupo : ''}
                        </div>
                        ${height >= 50 && c.aula ? `<div class="sem-ev-aula">🚪 ${c.aula}</div>` : ''}
                    </div>`;
                });

                return `<div class="sem-dia-col">
                    ${horas.map(()=>`<div class="sem-slot"></div>`).join('')}
                    ${evHtml}
                </div>`;
            }).join('')}
        </div>
    </div>`;

    document.getElementById('cal-vista').innerHTML = html;
}

/* ══════════════════════════════════════════════════════════════
   DETALLE DE CLASE
══════════════════════════════════════════════════════════════ */
function verDetalle(fecha, horaInicio) {
    const c = clases.find(x => x.fecha===fecha && x.hora_inicio===horaInicio);
    if (!c) return;

    const [y,m,d] = fecha.split('-').map(Number);
    const fechaLabel = `${d} de ${MESES[m-1]} de ${y}`;
    const durMin = (() => {
        const [sh,sm] = c.hora_inicio.slice(0,5).split(':').map(Number);
        const [eh,em] = c.hora_fin.slice(0,5).split(':').map(Number);
        return (eh-sh)*60+(em-sm);
    })();

    document.getElementById('det-titulo').textContent = c.materia;

    document.getElementById('det-cuerpo').innerHTML = `
        <span class="tipo-badge" style="background:${c.color}18;color:${c.color};border:1px solid ${c.color}40">Clase</span>

        <div class="det-fila">
            <span class="det-ico">📅</span>
            <div><div class="det-lbl">Fecha</div><div class="det-val">${fechaLabel}</div></div>
        </div>
        <div class="det-fila">
            <span class="det-ico">🕐</span>
            <div>
                <div class="det-lbl">Horario</div>
                <div class="det-val">${c.hora_inicio.slice(0,5)} – ${c.hora_fin.slice(0,5)}
                    <span class="det-dur">(${durMin} min)</span>
                </div>
            </div>
        </div>
        ${c.grupo ? `<div class="det-fila">
            <span class="det-ico">👥</span>
            <div><div class="det-lbl">Grupo</div><div class="det-val">${c.grupo}</div></div>
        </div>` : ''}
        ${c.aula ? `<div class="det-fila">
            <span class="det-ico">🚪</span>
            <div><div class="det-lbl">Aula</div><div class="det-val">${c.aula}</div></div>
        </div>` : ''}
    `;

    abrirModal('modal-detalle');
}

/* ══════════════════════════════════════════════════════════════
   NAVEGACIÓN
══════════════════════════════════════════════════════════════ */
function miniNav(dir) { fechaMini.setMonth(fechaMini.getMonth()+dir); renderMiniCal(); }

function mainNav(dir) {
    vistaActual==='month'
        ? fechaVista.setMonth(fechaVista.getMonth()+dir)
        : fechaVista.setDate(fechaVista.getDate()+dir*7);
    cargarYRenderizar();
}

function irHoy()  { fechaVista=new Date(hoy); fechaMini=new Date(hoy); fechaSel=null; cargarYRenderizar(); }
function clickDia(ds) { fechaSel=ds; renderMiniCal(); renderCalendario(); }

function setView(v, btn) {
    vistaActual = v;
    document.querySelectorAll('.vbtn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    cargarYRenderizar();
}

/* ══════════════════════════════════════════════════════════════
   MODALES / TOAST
══════════════════════════════════════════════════════════════ */
function abrirModal(id)  { document.getElementById(id).classList.add('open'); }
function cerrarModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if(e.target===o) o.classList.remove('open'); });
});

function mostrarToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(()=>t.classList.remove('show'), 2800);
}

/* ── Menú desplegable perfil ── */
document.addEventListener('DOMContentLoaded', () => {
    const perfil = document.querySelector('.fotoPerfil');
    const menu   = document.querySelector('.dropdown');
    if (perfil && menu) {
        perfil.addEventListener('click', e => { e.stopPropagation(); menu.classList.toggle('show'); });
        document.addEventListener('click', () => menu.classList.remove('show'));
    }
});
