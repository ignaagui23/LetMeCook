<?php
session_start();
require_once "../Controlador/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Inicio de sesión
    if (isset($_POST["login"])) {
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
            header("Location: ../Vistas/index.php");
            exit();
        } else {
            echo "Usuario o contraseña incorrectos.";
        }

        $stmt->close();
    }

    // Registro
    if (isset($_POST["register"])) {
        $new_username = $_POST["new_username"];
        $new_email = $_POST["new_email"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        if ($new_password !== $confirm_password) {
            echo "Las contraseñas no coinciden.";
            exit();
        }

        $sql = "INSERT INTO Usuarios (username, email, password, is_admin) VALUES (?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $new_username, $new_email, $new_password);

        if ($stmt->execute()) {
            echo "Registro exitoso. Ahora podés iniciar sesión.";
        } else {
            echo "Error al registrar: " . $conn->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
