<?php
session_start();
require_once('../Controlador/conexion.php');

$usuario_id = $_SESSION["usuario"]["id"] ?? null;
$receta_id = $_POST['receta_id'] ?? null;

if (!$usuario_id || !$receta_id) {
    header("Location: recetas.php");
    exit();
}

// Verificar si ya es favorito
$consulta = $conexion->prepare("SELECT * FROM favoritos WHERE usuario_id = ? AND receta_id = ?");
$consulta->bind_param("ii", $usuario_id, $receta_id);
$consulta->execute();
$resultado = $consulta->get_result();

if ($resultado->num_rows > 0) {
    // Eliminar favorito
    $conexion->query("DELETE FROM favoritos WHERE usuario_id = $usuario_id AND receta_id = $receta_id");
} else {
    // Agregar favorito
    $conexion->query("INSERT INTO favoritos (usuario_id, receta_id) VALUES ($usuario_id, $receta_id)");
}

exit();