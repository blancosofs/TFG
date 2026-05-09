<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edunoly · Panel de Administración</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: var(--fondo);
            color: var(--texto);
            min-height: 100vh;
        }

        /* ── Navegación ── */
        .barraNav {
            background: linear-gradient(to right, var(--nav-inicio), var(--nav-fin));
            padding: 0 40px;
        }
        .menu { display: flex; align-items: center; list-style: none; margin: 0 auto; padding: 0; max-width: 1800px; }
        .menu .derecha { margin-left: auto; }
        .menu li a { text-decoration: none; color: var(--nav-texto); padding: 20px; display: block; transition: background .15s; }
        .menu li a:hover { background-color: var(--nav-hover); }
        .logo { display: inline-block; padding: 20px; }

        .badge-admin {
            display: inline-flex; align-items: center; gap: 6px;
            background: color-mix(in srgb, var(--acento) 20%, transparent);
            border: 1px solid color-mix(in srgb, var(--acento) 40%, transparent);
            color: var(--acento); font-size: 11px; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase;
            padding: 4px 10px; border-radius: 20px; margin-left: 16px;
        }

        /* ── Hero ── */
        .admin-hero {
            background: linear-gradient(135deg, var(--fondo-oscuro) 0%, var(--fondo) 100%);
            border-bottom: 1px solid color-mix(in srgb, var(--texto) 10%, transparent);
            padding: 2.5rem 40px 2rem;
        }
        .admin-hero-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
        .etiqueta { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--acento); margin-bottom: .3rem; }
        .admin-hero h1 { font-size: clamp(22px, 3vw, 30px); font-weight: 700; color: var(--texto); }
        .admin-hero p  { font-size: 13px; color: var(--texto-suave); margin-top: .3rem; }

        .stats-row { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-chip {
            background: color-mix(in srgb, var(--acento) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--acento) 25%, transparent);
            border-radius: 10px; padding: 10px 18px; text-align: center; min-width: 90px;
        }
        .stat-chip-num   { font-size: 22px; font-weight: 700; color: var(--acento); line-height: 1; }
        .stat-chip-label { font-size: 10px; color: var(--texto-suave); text-transform: uppercase; letter-spacing: .06em; margin-top: 3px; }

        /* ── Layout ── */
        .admin-layout {
            max-width: 1200px; margin: 0 auto;
            padding: 28px 40px;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 960px) {
            .admin-layout { grid-template-columns: 1fr; padding: 20px; }
            .admin-hero   { padding: 1.5rem 20px 1.2rem; }
        }

        /* ── Card ── */
        .card {
            background: color-mix(in srgb, var(--texto) 4%, transparent);
            border: 1px solid color-mix(in srgb, var(--texto) 10%, transparent);
            border-radius: 16px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid color-mix(in srgb, var(--texto) 8%, transparent);
            display: flex; align-items: center; justify-content: space-between;
        }

        .card-header h2 { font-size: 15px; font-weight: 700; color: var(--texto); display: flex; align-items: center; gap: 8px; }
        .card-header h2 span { font-size: 17px; }
        .card-body { padding: 22px; }

        /* ── Formulario ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-grid .full { grid-column: 1 / -1; }

        .fgroup { display: flex; flex-direction: column; gap: 5px; }

        .flabel {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .08em; color: var(--texto-suave);
        }

        .finput, .fselect, .ftextarea {
            background: color-mix(in srgb, var(--texto) 6%, transparent);
            border: 1.5px solid color-mix(in srgb, var(--texto) 15%, transparent);
            border-radius: 8px; padding: 9px 12px;
            color: var(--texto); font-family: inherit; font-size: 13px;
            outline: none; transition: border-color .15s, background .15s;
            width: 100%;
        }

        .finput::placeholder, .ftextarea::placeholder { color: var(--texto-muy-suave, rgba(255,255,255,.3)); }
        .finput:focus, .fselect:focus, .ftextarea:focus {
            border-color: var(--acento);
            background: color-mix(in srgb, var(--acento) 5%, transparent);
        }

        .fselect { cursor: pointer; }
        .fselect option { background: var(--fondo-oscuro); color: var(--texto); }

        .ftextarea { resize: vertical; min-height: 80px; }

        .form-sep {
            grid-column: 1 / -1;
            display: flex; align-items: center; gap: 10px;
            margin: 6px 0 2px;
        }

        .form-sep span {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .1em; color: var(--acento); white-space: nowrap;
        }

        .form-sep::before, .form-sep::after {
            content: ''; flex: 1; height: 1px;
            background: color-mix(in srgb, var(--texto) 10%, transparent);
        }

        /* ── Botones ── */
        .btn-primary {
            padding: 10px 22px; border-radius: 8px; border: none;
            background: var(--acento); color: var(--texto-sobre-claro);
            font-family: inherit; font-size: 13px; font-weight: 700;
            cursor: pointer; transition: opacity .15s, transform .12s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-primary:hover { opacity: .88; transform: translateY(-1px); }
        .btn-primary:disabled { opacity: .5; cursor: not-allowed; transform: none; }

        .btn-ghost {
            padding: 9px 18px; border-radius: 8px;
            border: 1px solid color-mix(in srgb, var(--texto) 20%, transparent);
            background: transparent; color: var(--texto-suave);
            font-family: inherit; font-size: 13px; font-weight: 500;
            cursor: pointer; transition: all .15s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-ghost:hover { color: var(--texto); border-color: color-mix(in srgb, var(--texto) 35%, transparent); }

        .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 18px; }

        /* ── Alerts ── */
        .alert {
            border-radius: 10px; padding: 14px 16px;
            display: flex; align-items: flex-start; gap: 10px;
            margin-bottom: 16px; font-size: 13px; animation: slideIn .3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-ok {
            background: color-mix(in srgb, var(--acento) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--acento) 30%, transparent);
            color: var(--acento);
        }

        .alert-err {
            background: rgba(231,76,60,.1);
            border: 1px solid rgba(231,76,60,.3);
            color: #e74c3c;
        }

        .alert-ico { font-size: 16px; flex-shrink: 0; }
        .alert-txt { flex: 1; line-height: 1.5; }
        .alert-txt strong { display: block; font-weight: 700; margin-bottom: 2px; }

        /* ── Lista de colegios ── */
        .colegio-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 14px; border-radius: 10px;
            border: 1px solid transparent;
        }

        .colegio-ico {
            width: 36px; height: 36px; border-radius: 8px;
            background: color-mix(in srgb, var(--acento) 15%, transparent);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }

        .colegio-info { flex: 1; min-width: 0; }
        .colegio-nombre { font-size: 13px; font-weight: 600; color: var(--texto); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .colegio-meta   { font-size: 11px; color: var(--texto-suave); margin-top: 1px; }
        .colegio-coord  { font-size: 11px; color: var(--acento); margin-top: 2px; }

        .colegios-empty {
            text-align: center; padding: 28px 16px;
            color: var(--texto-suave); font-size: 13px;
        }
        .colegios-empty span { font-size: 28px; display: block; margin-bottom: 8px; }

        /* ── Buscador ── */
        .buscador-wrap { position: relative; margin-bottom: 14px; }
        .buscador-ico  { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--texto-suave); font-size: 13px; pointer-events: none; }
        .buscador {
            width: 100%; padding: 8px 10px 8px 30px;
            background: color-mix(in srgb, var(--texto) 6%, transparent);
            border: 1px solid color-mix(in srgb, var(--texto) 12%, transparent);
            border-radius: 8px; color: var(--texto); font-family: inherit; font-size: 12px; outline: none;
        }
        .buscador:focus { border-color: var(--acento); }
        .buscador::placeholder { color: var(--texto-suave); }

        .colegios-lista { max-height: 480px; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
        .colegios-lista::-webkit-scrollbar { width: 4px; }
        .colegios-lista::-webkit-scrollbar-thumb { background: color-mix(in srgb, var(--texto) 20%, transparent); border-radius: 4px; }

        /* ── Toast ── */
        .toast {
            position: fixed; bottom: 20px; right: 20px;
            background: var(--fondo-oscuro); color: var(--texto);
            border: 1px solid color-mix(in srgb, var(--texto) 15%, transparent);
            border-radius: 10px; padding: 12px 18px; font-size: 13px; font-weight: 500;
            z-index: 200; transform: translateY(60px); opacity: 0; transition: all .3s;
            box-shadow: 0 4px 20px rgba(0,0,0,.3); max-width: 300px;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
    </style>
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav>
        <div class="barraNav">
            <ul class="menu">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>
                <li class="activo"><a href="{{ route('admin') }}">Panel Admin</a></li>
                <li><a href="{{ route('perfilAdmin') }}">Mi Perfil</a></li>
                <li><a href="{{ route('config') }}">Configuración</a></li>

                <li class="derecha menuSesion">
                    <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre">Administrador</span></li>
                        <li class="dropdown-rol">Sistema</li>
                        <li class="dropdown-sep"></li>
                        <li><a href="{{ route('perfilAdmin') }}">👤 Mi perfil</a></li>
                        <li><a href="{{ route('config') }}">⚙️ Configuración</a></li>
                        <li><a href="#" id="btn-logout">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- ── HERO ── -->
<div class="admin-hero">
    <div class="admin-hero-inner">
        <div>
            <p class="etiqueta">Panel de administración</p>
            <h1>Gestión de centros educativos</h1>
            <p>Registra un colegio y su coordinador en un solo paso.</p>
        </div>
        <div class="stats-row">
            <div class="stat-chip">
                <div class="stat-chip-num" id="stat-colegios">0</div>
                <div class="stat-chip-label">Colegios</div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-num" id="stat-coordinadores">0</div>
                <div class="stat-chip-label">Coordinadores</div>
            </div>
        </div>
    </div>
</div>

<!-- ── LAYOUT ── -->
<div class="admin-layout">

    <!-- COLUMNA IZQUIERDA: Formulario unificado -->
    <div class="card">
        <div class="card-header">
            <h2><span>🏫</span> Registrar nuevo colegio</h2>
        </div>
        <div class="card-body">

            <div id="alert-form"></div>

            <div class="form-grid">

                <!-- ── Datos del centro ── -->
                <div class="form-sep full"><span>Datos del centro</span></div>

                <div class="fgroup full">
                    <label class="flabel">Nombre del centro *</label>
                    <input class="finput" id="c-nombre" type="text" placeholder="Ej: Colegio Salesiano Santo Domingo Savio">
                </div>

                <div class="fgroup">
                    <label class="flabel">Tipo de centro *</label>
                    <select class="fselect" id="c-tipo">
                        <option value="">Seleccionar…</option>
                        <option>Colegio público</option>
                        <option>Colegio concertado</option>
                        <option>Colegio privado</option>
                        <option>Instituto público</option>
                        <option>Instituto privado</option>
                        <option>Centro de FP</option>
                        <option>Otro</option>
                    </select>
                </div>

                <div class="fgroup">
                    <label class="flabel">Etapas educativas</label>
                    <select class="fselect" id="c-etapas">
                        <option value="">Seleccionar…</option>
                        <option>Infantil</option>
                        <option>Primaria</option>
                        <option>Secundaria (ESO)</option>
                        <option>Bachillerato</option>
                        <option>Infantil + Primaria</option>
                        <option>Primaria + Secundaria</option>
                        <option>Infantil + Primaria + Secundaria</option>
                        <option>FP</option>
                        <option>Todas</option>
                    </select>
                </div>

                <!-- ── Ubicación ── -->
                <div class="form-sep full"><span>Ubicación</span></div>

                <div class="fgroup full">
                    <label class="flabel">Calle y número *</label>
                    <input class="finput" id="c-calle" type="text" placeholder="Ej: C/ Impresores, 2">
                </div>

                <div class="fgroup">
                    <label class="flabel">Ciudad *</label>
                    <input class="finput" id="c-ciudad" type="text" placeholder="Ej: Madrid">
                </div>

                <div class="fgroup">
                    <label class="flabel">Comunidad autónoma</label>
                    <select class="fselect" id="c-comunidad">
                        <option value="">Seleccionar…</option>
                        <option>Andalucía</option><option>Aragón</option>
                        <option>Asturias</option><option>Baleares</option>
                        <option>Canarias</option><option>Cantabria</option>
                        <option>Castilla-La Mancha</option><option>Castilla y León</option>
                        <option>Cataluña</option><option>Ceuta</option>
                        <option>Extremadura</option><option>Galicia</option>
                        <option>La Rioja</option><option>Madrid</option>
                        <option>Melilla</option><option>Murcia</option>
                        <option>Navarra</option><option>País Vasco</option>
                        <option>Valencia</option>
                    </select>
                </div>

                <div class="fgroup">
                    <label class="flabel">Código postal *</label>
                    <input class="finput" id="c-cp" type="text" placeholder="28016" maxlength="5" pattern="[0-9]{5}">
                </div>

                <!-- ── Contacto del centro ── -->
                <div class="form-sep full"><span>Contacto del centro</span></div>

                <div class="fgroup">
                    <label class="flabel">Teléfono *</label>
                    <input class="finput" id="c-telefono" type="tel" placeholder="91 422 88 00">
                </div>

                <div class="fgroup">
                    <label class="flabel">Email del centro *</label>
                    <input class="finput" id="c-email" type="email" placeholder="info@colegio.es">
                </div>

                <div class="fgroup full">
                    <label class="flabel">Sitio web</label>
                    <input class="finput" id="c-web" type="url" placeholder="https://www.colegio.es">
                </div>

                <div class="fgroup">
                    <label class="flabel">Número de alumnos aproximado</label>
                    <input class="finput" id="c-alumnos" type="number" placeholder="Ej: 800" min="1">
                </div>

                <div class="fgroup">
                    <label class="flabel">Notas adicionales</label>
                    <input class="finput" id="c-notas" type="text" placeholder="Observaciones opcionales">
                </div>

                <!-- ── Coordinador ── -->
                <div class="form-sep full"><span>Coordinador del centro</span></div>

                <div class="fgroup">
                    <label class="flabel">Nombre *</label>
                    <input class="finput" id="coord-nombre" type="text" placeholder="Nombre">
                </div>

                <div class="fgroup">
                    <label class="flabel">Apellidos *</label>
                    <input class="finput" id="coord-apellidos" type="text" placeholder="Apellidos">
                </div>

                <div class="fgroup full">
                    <label class="flabel">Email del coordinador (usuario de acceso) *</label>
                    <input class="finput" id="coord-email" type="email" placeholder="coordinador@colegio.es">
                </div>

                <div class="fgroup">
                    <label class="flabel">Teléfono</label>
                    <input class="finput" id="coord-telefono" type="tel" placeholder="600 000 000">
                </div>

                <div class="fgroup">
                    <label class="flabel">Contraseña inicial *</label>
                    <input class="finput" id="coord-password" type="password" placeholder="Mín. 8 caracteres">
                </div>

            </div>

            <div class="form-actions">
                <button class="btn-ghost" onclick="limpiarForm()">Limpiar</button>
                <button class="btn-primary" id="btn-guardar" onclick="guardar()">
                    💾 Registrar colegio y coordinador
                </button>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: Lista de colegios registrados -->
    <div class="card">
        <div class="card-header">
            <h2><span>📋</span> Colegios registrados</h2>
            <span id="badge-total" style="font-size:11px;color:var(--texto-suave)">0 centros</span>
        </div>
        <div class="card-body">

            <div class="buscador-wrap">
                <span class="buscador-ico">🔍</span>
                <input class="buscador" id="buscador" type="text" placeholder="Buscar colegio…" oninput="filtrarColegios()">
            </div>

            <div class="colegios-lista" id="colegios-lista">
                <div class="colegios-empty">
                    <span>🏫</span>
                    Aún no hay colegios registrados.<br>Crea el primero con el formulario.
                </div>
            </div>

        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/temas.js') }}"></script>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script>
const API  = '/api';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

let colegios = [];

async function api(method, ruta, body) {
    try {
        const opts = {
            method,
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
            },
        };
        if (body) opts.body = JSON.stringify(body);
        const r = await fetch(API + ruta, opts);
        return await r.json();
    } catch (e) {
        return { error: 'Error de conexión con el servidor.' };
    }
}

async function cargarColegios() {
    const data = await api('GET', '/admin/colegios');
    if (data.error) { toast('❌ ' + data.error); return; }
    colegios = data || [];
    renderColegios();
    actualizarStats();
}

async function guardar() {
    const nombre    = v('c-nombre');
    const tipo      = v('c-tipo');
    const calle     = v('c-calle');
    const ciudad    = v('c-ciudad');
    const cp        = v('c-cp');
    const telefono  = v('c-telefono');
    const email     = v('c-email');

    const coordNombre    = v('coord-nombre');
    const coordApellidos = v('coord-apellidos');
    const coordEmail     = v('coord-email');
    const coordPassword  = v('coord-password');

    if (!nombre || !tipo || !calle || !ciudad || !cp || !telefono || !email) {
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
    const resColegio = await api('POST', '/admin/colegios', {
        nombre, tipo,
        etapas   : v('c-etapas'),
        calle, ciudad,
        comunidad: v('c-comunidad'),
        cp, telefono, email,
        web      : v('c-web'),
        alumnos  : parseInt(v('c-alumnos')) || null,
        notas    : v('c-notas'),
    });

    if (resColegio.error) {
        mostrarAlert('err', 'Error al crear el colegio', resColegio.error);
        btn.disabled = false;
        btn.textContent = '💾 Registrar colegio y coordinador';
        return;
    }

    const colegioId = resColegio.id;

    // 2. Crear el coordinador ligado al colegio
    const resCoord = await api('POST', `/admin/colegios/${colegioId}/coordinador`, {
        nombre   : coordNombre,
        apellidos: coordApellidos,
        email    : coordEmail,
        telefono : v('coord-telefono'),
        password : coordPassword,
    });

    if (resCoord.error) {
        mostrarAlert('err', 'Colegio creado pero error en el coordinador', resCoord.error);
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
    ['c-nombre','c-tipo','c-etapas','c-calle','c-ciudad','c-comunidad',
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
</script>
</body>
</html>
