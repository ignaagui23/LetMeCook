<?php
session_start();
// Incluir el archivo de conexión
include_once('Controller/Conexion_BD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    // Comprobar si el usuario ya existe
    $sql = "SELECT * FROM login WHERE usuario = ?";
    $stmt = $Direccion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "El usuario ya existe.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO login (usuario, contraseña) VALUES (?, ?)";
        $stmt = $Direccion->prepare($sql);
        $stmt->bind_param("ss", $usuario, $contraseña);
        
        if ($stmt->execute()) {
            $_SESSION['usuario'] = $usuario;
            echo "Registro exitoso. Ahora puedes iniciar sesión.";
            $sql = "INSERT INTO login (tipo) VALUES ('cliente')";
            $stmt = $Direccion->prepare($sql);
            $stmt->execute();

            header("Location: tienda.php"); // Redirigir a la página de tienda
            
            exit(); // Terminar el script después de redirigir
        } else {
            echo "Error al registrar. Intenta nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>De Moura Enterprises</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<center><section>
<h2>Ingrese sus datos</h2>
<form method="POST" action="">
    <label for="usuario">Usuario:</label>
    <input type="text" name="usuario" id="usuario" required maxlength="15"><br>

    <label for="contraseña">Contraseña:</label>
    <input type="password" name="contraseña" id="contraseña" required maxlength="16"><br>

    <button type="submit">Registrar</button>
</form>

<h2>Si ya tenés una cuenta, iniciá sesión</h2>
<a href="login.php">Iniciar Sesión</a>
</section></center>
</body>
</html>
