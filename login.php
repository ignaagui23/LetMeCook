<?php
session_start();

// Incluir el archivo de conexión
include_once('Controller/Conexion_BD.php');

if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0; // Inicializar los intentos
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Consultar para verificar el usuario
    $sql = "SELECT * FROM login WHERE usuario = ?";
    $stmt = $Direccion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contraseña, $fila['contraseña'])) {
            $_SESSION['usuario'] = $usuario; // Guardar el usuario en la sesión
            $_SESSION['intentos'] = 0; // Reiniciar los intentos
            header("Location: tienda.php"); // Redirigir a la página de tienda
            exit(); // Terminar el script después de redirigir
        } else {
            $_SESSION['intentos']++;
            echo "Contraseña incorrecta. Intentos fallidos: " . $_SESSION['intentos'];
        }
    } else {
        $_SESSION['intentos']++;
        echo "Usuario no encontrado. Intentos fallidos: " . $_SESSION['intentos'];
    }

    // Cerrar la página después de 3 intentos fallidos
    if ($_SESSION['intentos'] >= 3) {
        echo "<script>window.close();</script>";
        exit(); // Terminar el script después de cerrar la ventana
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<center>
<section>
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required maxlength="15"><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" id="contraseña" required maxlength="16"><br>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <h2>Si aún no tienes una cuenta, regístrate</h2>
    <a href="registro.php">Registrarse</a>
</section>
</center>
</body>
</html>
