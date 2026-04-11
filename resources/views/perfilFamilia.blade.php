<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edunoly · Mi Perfil Familiar</title>
    <script src="temas.js"></script>
    <link rel="stylesheet" href="temas.css">
    <link rel="stylesheet" href="EstilosPerfil.css">
    <link rel="stylesheet" href="EstilosPerfilFamilia.css">
</head>
<body>

<!-- ── NAVEGACIÓN ── -->
<header>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo">
                    <img src="logo.svg" alt="Edunoly">
                </li>
                <li><a href="PaginaInicio.html">Inicio</a></li>
                <li><a href="PaginaContacto.html">Contacto</a></li>
                <li class="activo"><a href="perfilFamilia.html">Mi Perfil</a></li>

                <li class="derecha menuSesion">
                    <img src="perfil.png" class="fotoPerfil" alt="Perfil">
                    <ul class="dropdown">
                        <li class="dropdown-nombre"><span id="nav-nombre"></span></li>
                        <li class="dropdown-rol"><span id="nav-rol">Tutor legal</span></li>
                        <li class="dropdown-sep"></li>
                        <li><a href="perfilFamilia.html">👤 Mi perfil</a></li>
                        <li><a href="configuracion.html">⚙️ Configuración</a></li>
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
        <p class="etiqueta verde">Mi espacio familiar</p>
        <h1>Mi Perfil</h1>
        <p class="hero-sub">Consulta la información de tus hijos y gestiona tus datos personales.</p>
    </div>
</div>

<!-- ── CONTENIDO ── -->
<div class="perfil-layout">

    <!-- ══ COLUMNA IZQUIERDA ══ -->
    <aside class="perfil-sidebar">

        <!-- Foto + datos del tutor -->
        <div class="card-perfil">
            <div class="foto-wrap">
                <img src="perfil.png" alt="Foto de perfil" class="foto-grande" id="foto-preview">
                <label class="foto-btn" title="Cambiar foto">
                    📷
                    <input type="file" id="input-foto" accept="image/*" style="display:none" onchange="previsualizarFoto(this)">
                </label>
            </div>

            <div class="nombre-bloque">
                <h2 id="perfil-nombre-completo">Cargando…</h2>
                <span class="rol-badge">Tutor legal</span>
            </div>

            <div class="info-rapida">
                <div class="info-rapida-item">
                    <span class="info-ico">🏫</span>
                    <span id="perfil-colegio">—</span>
                </div>
                <div class="info-rapida-item">
                    <span class="info-ico">📧</span>
                    <span id="perfil-email-corto">—</span>
                </div>
                <div class="info-rapida-item">
                    <span class="info-ico">📞</span>
                    <span id="perfil-telefono-corto">—</span>
                </div>
            </div>

            <!-- Estadística: nº de hijos -->
            <div class="stats-perfil">
                <div class="stat-perfil" style="grid-column:1/-1">
                    <span class="stat-num" id="stat-hijos">—</span>
                    <span class="stat-lbl">Hijos registrados</span>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos -->
        <div class="card-links">
            <p class="card-section-titulo">Accesos rápidos</p>
            <a href="configuracion.html" class="link-rapido">
                <span class="link-ico">⚙️</span>
                <span>Configuración</span>
                <span class="link-arrow">›</span>
            </a>
            <a href="PaginaContacto.html" class="link-rapido">
                <span class="link-ico">✉️</span>
                <span>Contactar con el centro</span>
                <span class="link-arrow">›</span>
            </a>
        </div>

    </aside>

    <!-- ══ COLUMNA DERECHA ══ -->
    <main class="perfil-main">

        <!-- ── MIS HIJOS ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">👨‍👩‍👧‍👦 Mis hijos</h3>
                    <p class="seccion-sub">Información académica de los menores a tu cargo.</p>
                </div>
            </div>
            <div id="hijos-lista">
                <div class="cargando-txt">Cargando…</div>
            </div>
        </div>

        <!-- ── DATOS PERSONALES ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">👤 Mis datos personales</h3>
                    <p class="seccion-sub">Tu información de contacto en la plataforma.</p>
                </div>
                <button class="btn-editar" id="btn-editar-personal" onclick="toggleEditar('personal')">
                    ✏️ Editar
                </button>
            </div>

            <!-- Vista -->
            <div id="vista-personal" class="datos-grid">
                <div class="dato-item">
                    <span class="dato-lbl">Nombre</span>
                    <span class="dato-val" id="v-nombre">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Apellidos</span>
                    <span class="dato-val" id="v-apellidos">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Correo electrónico</span>
                    <span class="dato-val" id="v-email">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Teléfono</span>
                    <span class="dato-val" id="v-telefono">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Usuario de acceso</span>
                    <span class="dato-val" id="v-usuario">—</span>
                </div>
                <div class="dato-item">
                    <span class="dato-lbl">Centro educativo</span>
                    <span class="dato-val" id="v-colegio">—</span>
                </div>
            </div>

            <!-- Edición -->
            <div id="form-personal" class="form-edicion" style="display:none">
                <div class="form-edicion-grid">
                    <div class="fgroup">
                        <label class="flabel">Nombre</label>
                        <input class="finput" id="e-nombre" type="text">
                    </div>
                    <div class="fgroup">
                        <label class="flabel">Apellidos</label>
                        <input class="finput" id="e-apellidos" type="text">
                    </div>
                    <div class="fgroup full">
                        <label class="flabel">Teléfono</label>
                        <input class="finput" id="e-telefono" type="tel">
                    </div>
                </div>
                <div class="form-edicion-acciones">
                    <button class="btn-cancelar-edit" onclick="toggleEditar('personal')">Cancelar</button>
                    <button class="btn-guardar-edit" onclick="guardarPersonal()">💾 Guardar cambios</button>
                </div>
            </div>
        </div>

        <!-- ── SEGURIDAD ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🔒 Seguridad</h3>
                    <p class="seccion-sub">Gestiona tu contraseña de acceso.</p>
                </div>
                <button class="btn-editar" id="btn-editar-pass" onclick="toggleEditar('pass')">
                    🔑 Cambiar contraseña
                </button>
            </div>

            <div id="vista-pass">
                <p class="pass-info">••••••••••••
                    <span class="pass-hint">Tu contraseña está cifrada y protegida.</span>
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
                        <label class="flabel">Repetir nueva contraseña</label>
                        <input class="finput" id="p-repetir" type="password" placeholder="Repite la contraseña">
                    </div>
                </div>
                <div id="alert-pass"></div>
                <div class="form-edicion-acciones">
                    <button class="btn-cancelar-edit" onclick="toggleEditar('pass')">Cancelar</button>
                    <button class="btn-guardar-edit" onclick="guardarPassword()">🔒 Actualizar contraseña</button>
                </div>
            </div>
        </div>

        <!-- ── ACTIVIDAD RECIENTE ── -->
        <div class="seccion-card">
            <div class="seccion-head">
                <div>
                    <h3 class="seccion-titulo">🕐 Actividad reciente</h3>
                    <p class="seccion-sub">Últimas acciones registradas en tu cuenta.</p>
                </div>
            </div>
            <div id="actividad-lista">
                <div class="actividad-item">
                    <span class="actividad-ico">🔑</span>
                    <div class="actividad-info">
                        <span class="actividad-texto">Inicio de sesión</span>
                        <span class="actividad-fecha" id="ultimo-acceso">—</span>
                    </div>
                </div>
                <div class="actividad-item">
                    <span class="actividad-ico">👁️</span>
                    <div class="actividad-info">
                        <span class="actividad-texto">Consulta de perfil de hijo</span>
                        <span class="actividad-fecha">Hoy</span>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<div class="toast" id="toast"></div>

<script src="temas.js"></script>
<script src="MenuSesion.js"></script>
<script src="perfilFamilia.js"></script>
</body>
</html>
