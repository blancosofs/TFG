<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Panel de Administración</title>
    <script src="temas.js"></script>
    <link rel="stylesheet" href="temas.css">
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

        /* Badge admin en la nav */
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

        /* ── Layout de dos columnas ── */
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

        /* ── Card base ── */
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

        /* Separador de sección dentro del form */
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

        .btn-ghost {
            padding: 9px 18px; border-radius: 8px;
            border: 1px solid color-mix(in srgb, var(--texto) 20%, transparent);
            background: transparent; color: var(--texto-suave);
            font-family: inherit; font-size: 13px; font-weight: 500;
            cursor: pointer; transition: all .15s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-ghost:hover { color: var(--texto); border-color: color-mix(in srgb, var(--texto) 35%, transparent); }

        .btn-danger {
            padding: 9px 18px; border-radius: 8px;
            border: 1px solid rgba(231,76,60,.35);
            background: rgba(231,76,60,.08); color: #e74c3c;
            font-family: inherit; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all .15s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-danger:hover { background: rgba(231,76,60,.16); }

        .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 18px; }

        /* ── Alert de éxito ── */
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

        /* ── Lista de colegios (sidebar) ── */
        .colegio-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 14px; border-radius: 10px;
            cursor: pointer; transition: background .15s;
            border: 1px solid transparent;
        }

        .colegio-item:hover { background: color-mix(in srgb, var(--texto) 5%, transparent); }

        .colegio-item.activo {
            background: color-mix(in srgb, var(--acento) 10%, transparent);
            border-color: color-mix(in srgb, var(--acento) 30%, transparent);
        }

        .colegio-ico {
            width: 36px; height: 36px; border-radius: 8px;
            background: color-mix(in srgb, var(--acento) 15%, transparent);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }

        .colegio-item.activo .colegio-ico {
            background: var(--acento); font-size: 15px;
        }

        .colegio-info { flex: 1; min-width: 0; }
        .colegio-nombre { font-size: 13px; font-weight: 600; color: var(--texto); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .colegio-meta   { font-size: 11px; color: var(--texto-suave); margin-top: 1px; }

        .colegio-arrow { color: var(--texto-suave); font-size: 12px; flex-shrink: 0; }
        .colegio-item.activo .colegio-arrow { color: var(--acento); }

        .colegios-empty {
            text-align: center; padding: 28px 16px;
            color: var(--texto-suave); font-size: 13px;
        }
        .colegios-empty span { font-size: 28px; display: block; margin-bottom: 8px; }

        /* ── Panel de acciones del colegio seleccionado ── */
        .panel-colegio {
            display: none;
            flex-direction: column;
            gap: 14px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid color-mix(in srgb, var(--texto) 8%, transparent);
            animation: slideIn .25s ease;
        }

        .panel-colegio.visible { display: flex; }

        .panel-colegio-titulo {
            font-size: 12px; font-weight: 700; color: var(--texto-suave);
            text-transform: uppercase; letter-spacing: .08em; margin-bottom: 4px;
        }

        .accion-btn {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 14px; border-radius: 10px;
            border: 1px solid color-mix(in srgb, var(--texto) 12%, transparent);
            background: color-mix(in srgb, var(--texto) 3%, transparent);
            cursor: pointer; transition: all .15s; text-align: left; width: 100%;
            font-family: inherit;
        }

        .accion-btn:hover {
            background: color-mix(in srgb, var(--acento) 8%, transparent);
            border-color: color-mix(in srgb, var(--acento) 30%, transparent);
        }

        .accion-btn.danger:hover {
            background: rgba(231,76,60,.08);
            border-color: rgba(231,76,60,.3);
        }

        .accion-ico {
            width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 15px;
        }

        .accion-ico.verde { background: color-mix(in srgb, var(--acento) 15%, transparent); }
        .accion-ico.ambar { background: rgba(201,160,47,.15); }
        .accion-ico.rojo  { background: rgba(231,76,60,.12); }

        .accion-txt h4 { font-size: 13px; font-weight: 600; color: var(--texto); }
        .accion-txt p  { font-size: 11px; color: var(--texto-suave); margin-top: 2px; }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.65); backdrop-filter: blur(5px);
            z-index: 100; display: flex; align-items: center; justify-content: center;
            padding: 20px; opacity: 0; pointer-events: none; transition: opacity .2s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }

        .modal {
            background: var(--fondo-oscuro);
            border: 1px solid color-mix(in srgb, var(--acento) 25%, transparent);
            border-radius: 18px; padding: 28px; width: 100%; max-width: 480px;
            box-shadow: 0 24px 64px rgba(0,0,0,.5);
            transform: translateY(16px); transition: transform .2s;
        }
        .modal-overlay.open .modal { transform: translateY(0); }

        .modal-head {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px;
        }
        .modal-titulo { font-size: 17px; font-weight: 700; color: var(--texto); display: flex; align-items: center; gap: 8px; }
        .modal-cerrar {
            width: 28px; height: 28px; border-radius: 50%;
            background: color-mix(in srgb, var(--texto) 8%, transparent);
            border: none; cursor: pointer; color: var(--texto-suave);
            font-size: 14px; display: flex; align-items: center; justify-content: center;
            transition: all .15s;
        }
        .modal-cerrar:hover { background: color-mix(in srgb, var(--texto) 15%, transparent); color: var(--texto); }

        .modal-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .modal-form-grid .full { grid-column: 1 / -1; }
        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 20px; }

        /* Modal de confirmación de eliminación */
        .confirm-body { text-align: center; padding: 8px 0 4px; }
        .confirm-ico  { font-size: 40px; margin-bottom: 12px; }
        .confirm-body h3 { font-size: 17px; font-weight: 700; color: var(--texto); margin-bottom: 8px; }
        .confirm-body p  { font-size: 13px; color: var(--texto-suave); line-height: 1.6; }
        .confirm-body .nombre-dest { color: #e74c3c; font-weight: 700; }
        .confirm-actions { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }

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

        /* Scrollable list */
        .colegios-lista { max-height: 420px; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
        .colegios-lista::-webkit-scrollbar { width: 4px; }
        .colegios-lista::-webkit-scrollbar-thumb { background: color-mix(in srgb, var(--texto) 20%, transparent); border-radius: 4px; }
    </style>
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav>
        <div class="barraNav">
            <ul class="menu">
                <li class="logo"><img src="logo.svg" alt="Edunoly"></li>
                <li><a href="PaginaInicio.html">Inicio</a></li>
                <li><span class="badge-admin">⚙ Admin</span></li>
                <li class="derecha" style="display:flex;align-items:center;padding-right:20px">
                    <span style="font-size:13px;color:var(--nav-texto);opacity:.7">Panel de Administración</span>
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
            <p>Registra colegios en el sistema y gestiona sus coordinadores.</p>
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

    <!-- COLUMNA IZQUIERDA: Formulario de nuevo colegio -->
    <div style="display:flex;flex-direction:column;gap:20px">

        <!-- Formulario -->
        <div class="card">
            <div class="card-header">
                <h2><span>🏫</span> Registrar nuevo colegio</h2>
            </div>
            <div class="card-body">

                <div id="alert-colegio"></div>

                <div class="form-grid">

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
                        <label class="flabel">Etapas educativas *</label>
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
                        <label class="flabel">Comunidad autónoma *</label>
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

                    <div class="form-sep full"><span>Contacto</span></div>

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

                    <div class="fgroup full">
                        <label class="flabel">Número de alumnos aproximado</label>
                        <input class="finput" id="c-alumnos" type="number" placeholder="Ej: 800" min="1">
                    </div>

                    <div class="fgroup full">
                        <label class="flabel">Notas adicionales</label>
                        <textarea class="ftextarea" id="c-notas" placeholder="Información adicional sobre el centro…"></textarea>
                    </div>

                </div>

                <div class="form-actions">
                    <button class="btn-ghost" onclick="limpiarFormColegio()">Limpiar</button>
                    <button class="btn-primary" onclick="guardarColegio()">
                        💾 Guardar colegio
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- COLUMNA DERECHA: Lista de colegios + acciones -->
    <div style="display:flex;flex-direction:column;gap:16px">

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

                <!-- Panel de acciones — aparece al seleccionar un colegio -->
                <div class="panel-colegio" id="panel-acciones">
                    <div class="panel-colegio-titulo" id="panel-titulo">Acciones para el colegio</div>

                    <button class="accion-btn" onclick="abrirModalCoord('añadir')">
                        <div class="accion-ico verde">➕</div>
                        <div class="accion-txt">
                            <h4>Añadir coordinador</h4>
                            <p>Asignar un nuevo coordinador a este centro</p>
                        </div>
                    </button>

                    <button class="accion-btn" onclick="abrirModalCoord('modificar')">
                        <div class="accion-ico ambar">✏️</div>
                        <div class="accion-txt">
                            <h4>Modificar coordinador</h4>
                            <p>Editar los datos del coordinador actual</p>
                        </div>
                    </button>

                    <button class="accion-btn danger" onclick="abrirConfirmElimCoord()">
                        <div class="accion-ico rojo">🗑️</div>
                        <div class="accion-txt">
                            <h4>Eliminar coordinador</h4>
                            <p>Desasignar el coordinador de este centro</p>
                        </div>
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- ══ MODAL: Añadir / Modificar coordinador ══ -->
<div class="modal-overlay" id="modal-coord">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-titulo" id="modal-coord-titulo">➕ Añadir coordinador</div>
            <button class="modal-cerrar" onclick="cerrarModal('modal-coord')">✕</button>
        </div>

        <div id="alert-coord"></div>

        <div class="modal-form-grid">
            <div class="fgroup">
                <label class="flabel">Nombre *</label>
                <input class="finput" id="coord-nombre" type="text" placeholder="Nombre">
            </div>
            <div class="fgroup">
                <label class="flabel">Apellidos *</label>
                <input class="finput" id="coord-apellidos" type="text" placeholder="Apellidos">
            </div>
            <div class="fgroup full">
                <label class="flabel">Email (usuario de acceso) *</label>
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
            <div class="fgroup full">
                <label class="flabel">Notas</label>
                <input class="finput" id="coord-notas" type="text" placeholder="Observaciones opcionales">
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-coord')">Cancelar</button>
            <button class="btn-primary" id="btn-guardar-coord" onclick="guardarCoordinador()">
                💾 Guardar coordinador
            </button>
        </div>
    </div>
</div>

<!-- ══ MODAL: Confirmar eliminar coordinador ══ -->
<div class="modal-overlay" id="modal-confirm-coord">
    <div class="modal" style="max-width:400px">
        <div class="confirm-body">
            <div class="confirm-ico">⚠️</div>
            <h3>¿Eliminar coordinador?</h3>
            <p>Vas a desasignar al coordinador<br>
               <span class="nombre-dest" id="coord-nombre-dest">—</span><br>
               del colegio <strong id="colegio-dest-nombre">—</strong>.<br><br>
               Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="confirm-actions">
            <button class="btn-ghost" onclick="cerrarModal('modal-confirm-coord')">Cancelar</button>
            <button class="btn-danger" onclick="eliminarCoordinador()">🗑️ Eliminar</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script src="temas.js"></script>
<script src="MenuSesion.js"></script>
<script>
/* ══════════════════════════════════════════════════════════════
   CONFIG
══════════════════════════════════════════════════════════════ */
const API = '';

/* ══════════════════════════════════════════════════════════════
   ESTADO LOCAL
   En producción todo viene de la API. Aquí mantenemos una copia
   en memoria para refrescar la UI sin recargar.
══════════════════════════════════════════════════════════════ */
let colegios       = [];   // [{ id, nombre, tipo, ciudad, cp, telefono, email, coordinador }]
let colegioActivo  = null; // id del colegio seleccionado en la lista
let modoCoord      = 'añadir'; // 'añadir' | 'modificar'

/* ══════════════════════════════════════════════════════════════
   API
══════════════════════════════════════════════════════════════ */
async function api(method, ruta, body) {
    try {
        const opts = { method, credentials: 'include', headers: { 'Content-Type': 'application/json' } };
        if (body) opts.body = JSON.stringify(body);
        const r = await fetch(API + ruta, opts);
        return await r.json();
    } catch (e) {
        return { error: 'Error de conexión con el servidor.' };
    }
}

/* ══════════════════════════════════════════════════════════════
   CARGA INICIAL
══════════════════════════════════════════════════════════════ */
async function cargarColegios() {
    const data = await api('GET', '/api/admin/colegios');
    if (data.error) { toast('❌ ' + data.error); return; }
    colegios = data || [];
    renderColegios();
    actualizarStats();
}

/* ══════════════════════════════════════════════════════════════
   GUARDAR COLEGIO
══════════════════════════════════════════════════════════════ */
async function guardarColegio() {
    const nombre    = v('c-nombre');
    const tipo      = v('c-tipo');
    const etapas    = v('c-etapas');
    const calle     = v('c-calle');
    const ciudad    = v('c-ciudad');
    const comunidad = v('c-comunidad');
    const cp        = v('c-cp');
    const telefono  = v('c-telefono');
    const email     = v('c-email');
    const web       = v('c-web');
    const alumnos   = v('c-alumnos');
    const notas     = v('c-notas');

    // Validación
    if (!nombre || !tipo || !calle || !ciudad || !cp || !telefono || !email) {
        mostrarAlert('alert-colegio', 'err', 'Campos obligatorios', 'Rellena todos los campos marcados con *.');
        return;
    }

    if (!/^\d{5}$/.test(cp)) {
        mostrarAlert('alert-colegio', 'err', 'Código postal inválido', 'Debe tener exactamente 5 dígitos.');
        return;
    }

    const payload = { nombre, tipo, etapas, calle, ciudad, comunidad, cp, telefono, email, web, alumnos: parseInt(alumnos) || null, notas };
    const data = await api('POST', '/api/admin/colegios', payload);

    if (data.error) {
        mostrarAlert('alert-colegio', 'err', 'Error al guardar', data.error);
        return;
    }

    // Éxito
    mostrarAlert('alert-colegio', 'ok', '✓ Colegio guardado correctamente',
        `"${nombre}" ha sido registrado en la base de datos con ID #${data.id}.`);

    // Añadir a la lista local
    colegios.push({ id: data.id, nombre, tipo, ciudad, cp, telefono, email, etapas, coordinador: null });
    renderColegios();
    actualizarStats();
    limpiarFormColegio();
    toast('🏫 Colegio registrado');
}

function limpiarFormColegio() {
    ['c-nombre','c-tipo','c-etapas','c-calle','c-ciudad','c-comunidad',
     'c-cp','c-telefono','c-email','c-web','c-alumnos','c-notas'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.getElementById('alert-colegio').innerHTML = '';
}

/* ══════════════════════════════════════════════════════════════
   RENDER LISTA DE COLEGIOS
══════════════════════════════════════════════════════════════ */
function renderColegios(filtro = '') {
    const lista = document.getElementById('colegios-lista');
    const q = filtro.toLowerCase();
    const filtrados = q ? colegios.filter(c => c.nombre.toLowerCase().includes(q) || c.ciudad?.toLowerCase().includes(q)) : colegios;

    document.getElementById('badge-total').textContent = `${colegios.length} centro${colegios.length !== 1 ? 's' : ''}`;

    if (!filtrados.length) {
        lista.innerHTML = `<div class="colegios-empty"><span>${q ? '🔍' : '🏫'}</span>${q ? 'Sin resultados para "' + filtro + '"' : 'Aún no hay colegios registrados.<br>Crea el primero con el formulario.'}</div>`;
        return;
    }

    lista.innerHTML = filtrados.map(c => `
        <div class="colegio-item ${colegioActivo === c.id ? 'activo' : ''}" onclick="seleccionarColegio(${c.id})">
            <div class="colegio-ico">${colegioActivo === c.id ? '✓' : '🏫'}</div>
            <div class="colegio-info">
                <div class="colegio-nombre">${c.nombre}</div>
                <div class="colegio-meta">${c.ciudad || '—'} · ${c.tipo || '—'}${c.coordinador ? ' · <span style="color:var(--acento)">✓ Coord.</span>' : ''}</div>
            </div>
            <span class="colegio-arrow">${colegioActivo === c.id ? '◀' : '▶'}</span>
        </div>`).join('');
}

function filtrarColegios() {
    renderColegios(document.getElementById('buscador').value);
}

/* ══════════════════════════════════════════════════════════════
   SELECCIONAR COLEGIO
══════════════════════════════════════════════════════════════ */
function seleccionarColegio(id) {
    colegioActivo = colegioActivo === id ? null : id;
    renderColegios(document.getElementById('buscador').value);

    const panel = document.getElementById('panel-acciones');
    if (colegioActivo) {
        const c = colegios.find(x => x.id === id);
        document.getElementById('panel-titulo').textContent = `Acciones · ${c.nombre}`;
        panel.classList.add('visible');
    } else {
        panel.classList.remove('visible');
    }
}

/* ══════════════════════════════════════════════════════════════
   COORDINADOR — MODALES
══════════════════════════════════════════════════════════════ */
function abrirModalCoord(modo) {
    if (!colegioActivo) return;
    modoCoord = modo;
    const c = colegios.find(x => x.id === colegioActivo);

    document.getElementById('modal-coord-titulo').textContent =
        modo === 'añadir' ? '➕ Añadir coordinador' : '✏️ Modificar coordinador';

    document.getElementById('btn-guardar-coord').textContent =
        modo === 'añadir' ? '💾 Guardar coordinador' : '💾 Actualizar coordinador';

    // Si es modificar y hay coordinador, prefill
    if (modo === 'modificar' && c?.coordinador) {
        const coord = c.coordinador;
        set('coord-nombre',    coord.nombre    || '');
        set('coord-apellidos', coord.apellidos || '');
        set('coord-email',     coord.email     || '');
        set('coord-telefono',  coord.telefono  || '');
        set('coord-password',  '');
        set('coord-notas',     coord.notas     || '');
    } else {
        ['coord-nombre','coord-apellidos','coord-email','coord-telefono','coord-password','coord-notas']
            .forEach(id => set(id, ''));
    }

    document.getElementById('alert-coord').innerHTML = '';
    abrirModal('modal-coord');
}

async function guardarCoordinador() {
    const nombre    = v('coord-nombre');
    const apellidos = v('coord-apellidos');
    const email     = v('coord-email');
    const password  = v('coord-password');
    const telefono  = v('coord-telefono');
    const notas     = v('coord-notas');

    if (!nombre || !apellidos || !email) {
        mostrarAlert('alert-coord', 'err', 'Campos obligatorios', 'Nombre, apellidos y email son obligatorios.');
        return;
    }

    if (modoCoord === 'añadir' && !password) {
        mostrarAlert('alert-coord', 'err', 'Contraseña requerida', 'Debes establecer una contraseña inicial.');
        return;
    }

    if (password && password.length < 8) {
        mostrarAlert('alert-coord', 'err', 'Contraseña muy corta', 'La contraseña debe tener al menos 8 caracteres.');
        return;
    }

    const payload = { nombre, apellidos, email, telefono, notas, colegioId: colegioActivo };
    if (password) payload.password = password;

    const ruta = modoCoord === 'añadir'
        ? `/api/admin/colegios/${colegioActivo}/coordinador`
        : `/api/admin/colegios/${colegioActivo}/coordinador`;
    const method = modoCoord === 'añadir' ? 'POST' : 'PUT';

    const data = await api(method, ruta, payload);

    if (data.error) {
        mostrarAlert('alert-coord', 'err', 'Error', data.error);
        return;
    }

    // Actualizar estado local
    const c = colegios.find(x => x.id === colegioActivo);
    if (c) c.coordinador = { nombre, apellidos, email, telefono, notas };

    cerrarModal('modal-coord');
    renderColegios(document.getElementById('buscador').value);
    actualizarStats();
    toast(modoCoord === 'añadir' ? '✓ Coordinador añadido' : '✓ Coordinador actualizado');
}

function abrirConfirmElimCoord() {
    if (!colegioActivo) return;
    const c = colegios.find(x => x.id === colegioActivo);

    if (!c?.coordinador) {
        toast('⚠️ Este colegio no tiene coordinador asignado.');
        return;
    }

    document.getElementById('coord-nombre-dest').textContent =
        `${c.coordinador.nombre} ${c.coordinador.apellidos}`;
    document.getElementById('colegio-dest-nombre').textContent = c.nombre;
    abrirModal('modal-confirm-coord');
}

async function eliminarCoordinador() {
    const data = await api('DELETE', `/api/admin/colegios/${colegioActivo}/coordinador`);

    if (data.error) { toast('❌ ' + data.error); return; }

    const c = colegios.find(x => x.id === colegioActivo);
    if (c) c.coordinador = null;

    cerrarModal('modal-confirm-coord');
    renderColegios(document.getElementById('buscador').value);
    actualizarStats();
    toast('🗑️ Coordinador eliminado');
}

/* ══════════════════════════════════════════════════════════════
   STATS
══════════════════════════════════════════════════════════════ */
function actualizarStats() {
    document.getElementById('stat-colegios').textContent      = colegios.length;
    document.getElementById('stat-coordinadores').textContent = colegios.filter(c => c.coordinador).length;
}

/* ══════════════════════════════════════════════════════════════
   UTILIDADES
══════════════════════════════════════════════════════════════ */
function v(id)       { return document.getElementById(id)?.value.trim() || ''; }
function set(id, val){ const el = document.getElementById(id); if(el) el.value = val; }

function abrirModal(id)  { document.getElementById(id).classList.add('open'); }
function cerrarModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});

function toast(msg, duracion = 2800) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), duracion);
}

function mostrarAlert(contenedorId, tipo, titulo, texto) {
    document.getElementById(contenedorId).innerHTML = `
        <div class="alert alert-${tipo === 'ok' ? 'ok' : 'err'}">
            <span class="alert-ico">${tipo === 'ok' ? '✅' : '❌'}</span>
            <div class="alert-txt"><strong>${titulo}</strong>${texto}</div>
        </div>`;
}

/* ══════════════════════════════════════════════════════════════
   INIT
══════════════════════════════════════════════════════════════ */
cargarColegios();
</script>
</body>
</html>
