<?php
$host = "localhost";
$usuario = "root"; // o el usuario de tu servidor MySQL
$contrasena = ""; // o la contraseña si tiene
$baseDeDatos = "letmecookdb";

$conn = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
