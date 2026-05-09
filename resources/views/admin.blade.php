<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Panel de Administración</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosPerfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosAdmin.css') }}">
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
                <li><a href="{{ route('configuracionPerfiles') }}">Configuración</a></li>

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
                    <label class="flabel">Dirección *</label>
                    <input class="finput" id="c-direccion" type="text" placeholder="Ej: Av. de la Constitución, 15 / Rotonda del Parque, s/n">
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
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
