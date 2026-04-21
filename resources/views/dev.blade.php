<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Panel de desarrollador</h1>
    <nav id="Navegador">
        <div class="barraNav">
            <ul class="menu">
                <li class="logo">
                    <img src="{{ asset('img/logo.svg') }}" alt="Edunoly">
                </li>
                <li><a href="#">Inicio</a></li>
                <li><a href="{{ route('unete') }}">Unete</a></li>


                <li class="derecha"><a href="{{ route('login') }}">Iniciar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <div style="margin: 20px; padding: 20px; border: 1px dashed #ccc;">
        <h3>Sesión de Desarrollador Activa</h3>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                style="background-color: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                Cerrar Sesión y salir
            </button>
        </form>
    </div>
</body>

</html>