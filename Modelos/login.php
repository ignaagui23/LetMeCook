<?php
session_start();
require_once "../Controlador/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Usuarios WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION["usuario"] = $usuario["username"];
        $_SESSION["es_admin"] = $usuario["is_admin"];
        header("Location: ../Vistas/pagina_principal.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
    $conn->close();
}
?>
