<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unete a nosotros</title>
    <link rel="stylesheet" href="{{ asset('css/EstilosPaginaUnete.css') }}">
</head>

<body>

    <header>
        <!-- NavBar -->
        <nav id="Navegador">
            <div class="barraNav">
                <ul class="menu">
                    <li class="logo">
                        <img src="{{ asset('img/logo.svg') }}" alt="Edunoly">
                    </li>
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                    <li><a href="{{ route('config') }}">Configuracion</a></li>
                    <li><a href="{{ route('unete') }}">Unete</a></li>

                    <li class="derecha"><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    <li class="menuSesion">
                        <img src="{{ asset('img/perfil.png') }}" class="fotoPerfil" alt="Perfil">
                        <ul class="dropdown">
                            <li><a href="#">Mi perfil</a></li>
                            <li><a href="#">Configuración</a></li>
                            <li><a href="#">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- --------------------------------------- -->
    <div class="contenedor">
        <!-- --------------------------------------- -->
        <h2>Únete a Edunoly</h2>
        <form action="{{ route('solicitud.enviar') }}" method="POST">
            @csrf
            <h2>Registro Colegio</h2>
            <label for="centro_nombre">Nombre del centro*</label>
                <input type="text" id="colegio_nombre" name="colegio_nombre" placeholder="Santo Domingo Savio" maxlength="50" required>
            <label for="entidad">Entidad educativa*</label>
                <input type="text" id="colegio_entidad" name="colegio_entidad" placeholder="Colegio Salesiano" maxlength="50" required>
             <label for="direccion">Dirección del centro*</label>
                <input type="text" id="colegio_direccion" name="colegio_direccion" placeholder="C. Santo Domingo Savio, 2, Cdad. Lineal, 28017 Madrid" maxlength="100" required>
            <h2>Registro coordinador</h2>
            <label for="nombre">Nombre Coordinador*</label>
                <input type="text" id="coord_nombre" name="coord_nombre" placeholder="Ejemplo: Juan" maxlength="25" required>
            <label for="apellido">Apellido Coordinador*</label>
                <input type="text" id="coord_apellido" name="coord_apellido" placeholder="Ejemplo: Gutierrez" maxlength="25" required>
            <label for="correo">Email Coordinador*</label>
                <input type="email" id="coord_email" name="coord_email" placeholder="ejemplo@gmail.com" maxlength="60" required>
            <label for="telefono">Telefono Coordinador *</label>
               <input type="tel" id="coord_telefono" name="coord_telefono" placeholder="+34 698 251 235"pattern="^\+?[0-9\s]{9,15}$" required>

            <input class="boton" type="submit" value="Enviar Solicitud de Alta">
        </form>
    </div>
    </div>
    <!-- --------------------------------------- -->
    <div id="contInformacion">
        <div class="infoColumnas">
            <div class="infoCol">
                <h3 class="infoTitulo">Edunoly</h3>
                <ul class="infoLista">
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="#">Nuestro equipo</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>

            <div class="infoCol">
                <h3 class="infoTitulo">Legal</h3>
                <ul class="infoLista">
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Política de cookies</a></li>
                    <li><a href="#">Aviso legal</a></li>
                    <li><a href="#">Términos de uso</a></li>
                </ul>
            </div>

            <div class="infoCol">
                <h3 class="infoTitulo">Soporte</h3>
                <ul class="infoLista">
                    <li><a href="#">Centro de ayuda</a></li>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Accesibilidad</a></li>
                </ul>
            </div>

            <div class="infoCol">
                <h3 class="infoTitulo">Contacto</h3>
                <ul class="infoLista infoListaContacto">
                    <li>C/ Impresores, 2. 28660</li>
                    <li>Boadilla del Monte, Madrid</li>
                    <li><a href="tel:+34914228800">91 422 88 00</a></li>
                    <li><a href="/cdn-cgi/l/email-protection#137a7d757c537677667d7c7f6a3d7660"><span
                                class="__cf_email__"
                                data-cfemail="51383f373e113435243f3e3d287f3422">[email&#160;protected]</span></a></li>
                </ul>
            </div>

        </div>
        <div class="infoCopyright">
            <p>© 2026 Edunoly · Todos los derechos reservados</p>
        </div>
    </div>
    <script src="{{ asset('js/menuSesion.js') }}"></script>
</body>

</html>