<?php

    require 'conexion.php'; 

    $tbl_name = "consultas";

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $perfil = $_POST['perfil'];
    $codigo_Postal = $_POST['codigo_Postal'];
    $centro = $_POST['centro'];
    $textarea = $_POST['textarea'];

    $sql = "INSERT INTO $tbl_name (nombre, apellido, correo, telefono, perfil, codigo_Postal, centro, textArea) 
            VALUES ('$nombre', '$apellido', '$correo', '$telefono', '$perfil', '$codigo_Postal', '$centro', '$textarea')";


    // 5. Ejecutamos la orden y comprobamos si ha ido bien
    if ($conexion->query($sql) === TRUE) {
        // He cambiado el mensaje para que tenga más sentido para un formulario de contacto
        echo "¡Consulta registrada correctamente! Nos pondremos en contacto contigo.";
    } else {
        echo "Error al insertar datos: " . $conexion->error;
    }

    // 6. Cerramos la conexión (usamos el estilo orientado a objetos que es más moderno)
    $conexion->close();
?>