<?php
session_start();
require '../conexion.php';

$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$fechaNacimiento = $_POST['fecha_nacimiento'];
$email = $_POST['email'];
$colegio_id = 1; // Por poner algo xd
$usuario = $_POST['usuario'];
$password_plana = $_POST['password'];


$password_cifrada = password_hash($password_plana, PASSWORD_DEFAULT);

//Se hacen PreparedStatements para evitar inyecciones SQL.
$sql = "INSERT INTO docente (nombre, apellidos, fechaNacimiento, email, colegio_idColegio, usuario, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);


// "ssssiss" significa: String, String, String, String, Integer, String, String
$stmt->bind_param("ssssiss", $nombre, $apellidos, $fechaNacimiento, $email, $colegio_id, $usuario, $password_cifrada);

// 5. Ejecutamos
if ($stmt->execute()) {
    echo "Docente creado correctamente.";
    // header("Location: ../../ver_docentes.php");
} else {
    echo "Error al crear docente: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>