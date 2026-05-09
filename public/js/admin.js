/* ══════════════════════════════════════════════════════════════
   Edunoly · admin.js
   Lógica del panel de administración
══════════════════════════════════════════════════════════════ */

const API = '';

let colegios = [];

const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

async function api(method, ruta, body) {
    try {
        const opts = {
            method,
            credentials: 'include',
            headers: {
                'Content-Type':  'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  CSRF,
            }
        };
        if (body) opts.body = JSON.stringify(body);
        const r = await fetch(API + ruta, opts);
        if (!r.ok && r.status === 419) return { error: 'Sesión expirada. Recarga la página.' };
        return await r.json();
    } catch (e) {
        return { error: 'Error de conexión con el servidor.' };
    }
}

async function cargarColegios() {
    const data = await api('GET', '/api/admin/colegios');
    if (data.error) { toast('❌ ' + data.error); return; }
    colegios = data || [];
    renderColegios();
    actualizarStats();
}

async function guardar() {
    const nombre    = v('c-nombre');
    const tipo      = v('c-tipo');
    const direccion     = v('c-direccion');
    const ciudad    = v('c-ciudad');
    const cp        = v('c-cp');
    const telefono  = v('c-telefono');
    const email     = v('c-email');

    const coordNombre    = v('coord-nombre');
    const coordApellidos = v('coord-apellidos');
    const coordEmail     = v('coord-email');
    const coordPassword  = v('coord-password');

    if (!nombre || !tipo || !direccion || !ciudad || !cp || !telefono || !email) {
        mostrarAlert('err', 'Campos obligatorios', 'Rellena todos los campos del centro marcados con *.');
        return;
    }
    if (!/^\d{5}$/.test(cp)) {
        mostrarAlert('err', 'Código postal inválido', 'Debe tener exactamente 5 dígitos.');
        return;
    }
    if (!coordNombre || !coordApellidos || !coordEmail || !coordPassword) {
        mostrarAlert('err', 'Campos obligatorios', 'Rellena todos los campos del coordinador marcados con *.');
        return;
    }
    if (coordPassword.length < 8) {
        mostrarAlert('err', 'Contraseña muy corta', 'La contraseña del coordinador debe tener al menos 8 caracteres.');
        return;
    }

    const btn = document.getElementById('btn-guardar');
    btn.disabled = true;
    btn.textContent = 'Guardando…';

    // 1. Crear el colegio
    const resColegio = await api('POST', '/api/admin/colegios', {
        nombre, tipo,
        etapas   : v('c-etapas'),
        direccion, ciudad,
        comunidad: v('c-comunidad'),
        cp, telefono, email,
        web      : v('c-web'),
        alumnos  : parseInt(v('c-alumnos')) || null,
        notas    : v('c-notas'),
    });

    if (!resColegio?.ok) {
        mostrarAlert('err', 'Error al crear el colegio', resColegio.mensaje || resColegio.message || 'Error desconocido.');
        btn.disabled = false;
        btn.textContent = '💾 Registrar colegio y coordinador';
        return;
    }

    const colegioId = resColegio.id;

    // 2. Crear el coordinador ligado al colegio recién creado
    const resCoord = await api('POST', `/api/admin/colegios/${colegioId}/coordinador`, {
        nombre   : coordNombre,
        apellidos: coordApellidos,
        email    : coordEmail,
        telefono : v('coord-telefono'),
        password : coordPassword,
    });

    if (!resCoord?.ok) {
        mostrarAlert('err', 'Colegio creado pero error en el coordinador', resCoord.mensaje || resCoord.message || 'Error desconocido.');
        btn.disabled = false;
        btn.textContent = '💾 Registrar colegio y coordinador';
        return;
    }

    mostrarAlert('ok', '✓ Registrado correctamente',
        `"${nombre}" y el coordinador ${coordNombre} ${coordApellidos} han sido creados.`);

    colegios.push({
        id: colegioId, nombre, tipo, ciudad, cp,
        coordinador: { nombre: coordNombre, apellidos: coordApellidos, email: coordEmail }
    });
    renderColegios();
    actualizarStats();
    limpiarForm();

    btn.disabled = false;
    btn.textContent = '💾 Registrar colegio y coordinador';
    toast('🏫 Colegio y coordinador registrados');
}

function limpiarForm() {
    ['c-nombre','c-tipo','c-etapas','c-direccion','c-ciudad','c-comunidad',
     'c-cp','c-telefono','c-email','c-web','c-alumnos','c-notas',
     'coord-nombre','coord-apellidos','coord-email','coord-telefono','coord-password']
        .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    document.getElementById('alert-form').innerHTML = '';
}

function renderColegios(filtro = '') {
    const lista = document.getElementById('colegios-lista');
    const q = filtro.toLowerCase();
    const filtrados = q
        ? colegios.filter(c => c.nombre.toLowerCase().includes(q) || c.ciudad?.toLowerCase().includes(q))
        : colegios;

    document.getElementById('badge-total').textContent = `${colegios.length} centro${colegios.length !== 1 ? 's' : ''}`;

    if (!filtrados.length) {
        lista.innerHTML = `<div class="colegios-empty"><span>${q ? '🔍' : '🏫'}</span>${q ? 'Sin resultados para "' + filtro + '"' : 'Aún no hay colegios registrados.<br>Crea el primero con el formulario.'}</div>`;
        return;
    }

    lista.innerHTML = filtrados.map(c => `
        <div class="colegio-item">
            <div class="colegio-ico">🏫</div>
            <div class="colegio-info">
                <div class="colegio-nombre">${c.nombre}</div>
                <div class="colegio-meta">${c.ciudad || '—'} · ${c.tipo || '—'}</div>
                ${c.coordinador
                    ? `<div class="colegio-coord">👤 ${c.coordinador.nombre} ${c.coordinador.apellidos}</div>`
                    : ''}
            </div>
        </div>`).join('');
}

function filtrarColegios() {
    renderColegios(document.getElementById('buscador').value);
}

function actualizarStats() {
    document.getElementById('stat-colegios').textContent      = colegios.length;
    document.getElementById('stat-coordinadores').textContent = colegios.filter(c => c.coordinador).length;
}

function v(id) { return document.getElementById(id)?.value.trim() || ''; }

function toast(msg, duracion = 2800) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), duracion);
}

function mostrarAlert(tipo, titulo, texto) {
    document.getElementById('alert-form').innerHTML = `
        <div class="alert alert-${tipo === 'ok' ? 'ok' : 'err'}">
            <span class="alert-ico">${tipo === 'ok' ? '✅' : '❌'}</span>
            <div class="alert-txt"><strong>${titulo}</strong> ${texto}</div>
        </div>`;
}

cargarColegios();
