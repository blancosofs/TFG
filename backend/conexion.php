<?php

require 'credenciales.php';

// 2. Creamos la conexión usando el estilo orientado a objetos
$conexion = new mysqli($db_servidor, $db_usuario, $db_password, $db_nombre, $db_puerto);

// 3. Comprobamos si ha habido algún error
if ($conexion->connect_error) {
    die("Error terrible: No me he podido conectar. " . $conexion->connect_error);
} 

// 4. Si todo va bien, mostramos un mensaje de victoria
echo "<h1>¡Conexión exitosa a la base de datos centro_educativo!</h1>";
?>