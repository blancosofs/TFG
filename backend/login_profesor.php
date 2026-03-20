<?php

session_start();
require 'backend/conexion.php';

$usuario_intento = $_POST['usuario']; 
$password_intento = $_POST['password'];


$sql = "SELECT idDocente, usuario, password FROM docente WHERE usuario = '$usuario_intento'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc(); //fetch_assoc() devuelve un array asociativo con los datos del usuario

    if (password_verify($password_intento, $usuario['password'])) {
        
        $_SESSION['usuario_id'] = $usuario['idDocente'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = "Docente";
        
        echo "¡Login correcto! Bienvenido " . $_SESSION['usuario_nombre'];
        
        // Aquí luego se pondrá un header("Location: panel_profesor.php");
        
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Ese email no existe.";
}
?>