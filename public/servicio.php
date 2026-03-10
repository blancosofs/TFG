<?php
  //conectamos
$servidor = "localhost";
$usuario = "root";
$password = "sopita666";
$baseDatos = "centro_educativo";

$conexion = new mysqli($servidor, $usuario, $password, $baseDatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


  // sacas los datos de input>name 
  //todos los datos segun la tabla Docente
$nombre = $_POST["nombre"];
$apellidos = $_POST["apellido"];
$fechaNacimiento = $_POST["fechaNacimiento"];
$email = $_POST["correo"];
$colegio = $_POST["colegio"];

//dentro  (quitale fecha)
$sql = "INSERT INTO docente (nombre, apellidos, email)
VALUES ('$nombre', '$apellidos', '$email')";
//ejecutar
if ($conexion->query($sql)) {
    echo "Docente guardado correctamente";
} else {
    echo "Error al guardar docente";
}

$conexion->close();
  
?>
