<?php
$host = "45.235.98.222:3306";
$usuario = "u953_r5rqbHLUKg";
$contrasena = "+eYL!XnE=XrRHivLr7LZxcc9";
$baseDeDatos = "s953_letmecookdb";

$conn = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

date_default_timezone_set('America/Argentina/Buenos_Aires');


?>


