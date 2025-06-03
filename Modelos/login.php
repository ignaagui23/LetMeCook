<?php
session_start();
require_once "../Controlador/conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Iniciar sesión
    if (isset($_POST["login"])) {
        $username = trim($_POST["username"]);
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            echo "Por favor completá todos los campos.";
            exit();
        }

        $sql = "SELECT * FROM Usuarios WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($password, $usuario["password"])) {
                $_SESSION["usuario"] = $usuario["username"];
                $_SESSION["es_admin"] = $usuario["is_admin"];
                header("Location: ../Vistas/index.php");
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "Usuario no encontrado.";
        }

        $stmt->close();
    }

    // Registro
    if (isset($_POST["register"])) {
        $new_username = trim($_POST["new_username"]);
        $new_email = trim($_POST["new_email"]);
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        if (empty($new_username) || empty($new_email) || empty($new_password) || empty($confirm_password)) {
            echo "Todos los campos son obligatorios.";
            exit();
        }

        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            echo "El email no es válido.";
            exit();
        }

        if ($new_password !== $confirm_password) {
            echo "Las contraseñas no coinciden.";
            exit();
        }

        // Verificar duplicados
        $check_sql = "SELECT * FROM Usuarios WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $new_username, $new_email);
        $check_stmt->execute();
        $resultado = $check_stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo "El nombre de usuario o correo ya está registrado.";
            exit();
        }

        $check_stmt->close();

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Usuarios (username, email, password, is_admin) VALUES (?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $new_username, $new_email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION["usuario"] = $new_username;
            $_SESSION["es_admin"] = 0;
            header("Location: ../Vistas/index.php");
            exit();
        } else {
            echo "Error al registrar: " . $conn->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
