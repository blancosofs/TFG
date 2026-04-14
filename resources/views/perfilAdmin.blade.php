<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Perfil Admin</title>
    <script src="{{ asset('js/temas.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/temas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosPerfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/EstilosPerfilAdmin.css') }}">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo"><img src="{{ asset('img/logo.svg') }}" alt="Edunoly"></li>
                <li><a href="{{ route('admin') }}">Panel Admin</a></li>
                <li class="activo"><a href="{{ route('perfilAdmin') }}">Mi Perfil</a></li>

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
<div class="perfil-hero">
    <div class="perfil-hero-inner">
        <p class="etiqueta verde">Panel de administración</p>
        <h1>Perfil del Administrador</h1>
        <p class="hero-sub">Gestiona tus credenciales y consulta el estado general del sistema.</p>
    </div>
</div>

<!-- ── CONTENIDO ── -->
<div class="perfil-layout">

    <!-- ══ SIDEBAR ══ -->
    <aside class="perfil-sidebar">

        <!-- Foto + datos -->
        <div class="card-perfil">
            <div class="foto-wrap">
                <img src="{{ asset('img/perfil.png') }}" alt="Admin" class="foto-grande" id="foto-preview">
                <label class="foto-btn foto-btn-admin" title="Cambiar foto">
                    📷
                    <input type="file" id="input-foto" accept="image/*" style="display:none" onchange="previsualizarFoto(this)">
                </label>
            </div>

            <div class="nombre-bloque">
                <h2>Administrador</h2>
                <span class="rol-badge rol-badge-admin">⚙ Sistema</span>
            </div>

            <div class="info-rapida">
                <div class="info-rapida-item">
                    <span class="info-ico">📧</span>
                    <span id="admin-email">—</span>
                </div>
                <div class="info-rapida-item">
                    <span class="info-ico">🕐</span>
                    <span id="admin-ultimo-acceso">—</span>
                </div>
            </div>

            <!-- Stats del sistema -->
            <div class="stats-perfil">
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-colegios">—</span>
                    <span class="stat-lbl">Colegios</span>
                </div>
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-coordinadores">—</span>
                    <span class="stat-lbl">Coordinadores</span>
                </div>
                <div class="stat-perfil">
                    <span class="stat-num" id="stat-usuarios">—</span>
                    <span class="stat-lbl">Usuarios</span>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos -->
        <div class="card-links">
            <p class="card-section-titulo">Accesos rápidos</p>
            <a href="{{ route('admin') }}" class="link-rapido">
                <span class="link-ico">🏫</span>
                <span>Gestión de colegios</span>
                <span class="link-arrow">›</span>
            </a>
            <a href="{{ route('config') }}" class="link-rapido">
                <span class="link-ico">⚙️</span>
                <span>Configuración</span>
                <span class="link-arrow">›</span>
            </a>
        </div>

    </aside>

    <!-- ══ CONTENIDO PRINCIPAL ══ -->
    <main class="perfil-main">

        <!-- ── Estado del sistema ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">📊 Estado del sistema</h3>
                    <p class="seccion-sub">Resumen general de la plataforma en tiempo real.</p>
                </div>
            </div>

            <div class="sistema-grid">
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Base de datos</div>
                        <div class="sistema-estado">Conectada</div>
                    </div>
                </div>
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Servidor</div>
                        <div class="sistema-estado">Operativo</div>
                    </div>
                </div>
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Sesiones</div>
                        <div class="sistema-estado">Activas</div>
                    </div>
                </div>
                <div class="sistema-item sistema-ok">
                    <span class="sistema-ico">✅</span>
                    <div>
                        <div class="sistema-titulo">Cifrado</div>
                        <div class="sistema-estado">bcrypt activo</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Resumen de colegios ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🏫 Resumen de colegios</h3>
                    <p class="seccion-sub">Últimos centros registrados en el sistema.</p>
                </div>
                <a href="{{ route('admin') }}" class="btn-editar">Ver todos ›</a>
            </div>

            <div id="colegios-resumen">
                <div class="dato-val" style="text-align:center;padding:16px">Cargando…</div>
            </div>
        </div>

        <!-- ── Seguridad ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🔒 Seguridad de la cuenta</h3>
                    <p class="seccion-sub">Cambia la contraseña de acceso al panel de administración.</p>
                </div>
                <button class="btn-editar" id="btn-editar-pass" onclick="togglePass()">
                    🔑 Cambiar contraseña
                </button>
            </div>

            <div id="vista-pass">
                <p class="pass-info">
                    ••••••••••••
                    <span class="pass-hint">Contraseña cifrada con bcrypt.</span>
                </p>
            </div>

            <div id="form-pass" class="form-edicion" style="display:none">
                <div class="form-edicion-grid">
                    <div class="fgroup full">
                        <label class="flabel">Contraseña actual</label>
                        <input class="finput" id="p-actual" type="password" placeholder="••••••••">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Nueva contraseña</label>
                        <input class="finput" id="p-nueva" type="password" placeholder="Mín. 8 caracteres">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Repetir contraseña</label>
                        <input class="finput" id="p-repetir" type="password" placeholder="Repite la contraseña">
                    </div>
                </div>
                <div id="alert-pass"></div>
                <div class="form-edicion-acciones">
                    <button class="btn-cancelar-edit" onclick="togglePass()">Cancelar</button>
                    <button class="btn-guardar-edit" onclick="guardarPassword()">🔒 Actualizar contraseña</button>
                </div>
            </div>
        </div>

        <!-- ── Auditoría ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">📋 Registro de auditoría</h3>
                    <p class="seccion-sub">Últimas acciones registradas en el sistema.</p>
                </div>
            </div>

            <div id="auditoria-lista">
                <div class="dato-val" style="text-align:center;padding:16px">Cargando…</div>
            </div>
        </div>

    </main>
</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/temas.js') }}"></script>
<script src="{{ asset('js/MenuSesion.js') }}"></script>
<script src="{{ asset('js/perfilAdmin.js') }}"></script>
</body>
</html>
