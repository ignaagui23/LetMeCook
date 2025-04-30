<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let Me Cook - Iniciar Sesión</title>
    <link rel="stylesheet" href="../Estilos/style.css">
</head>
<body>
    <div>
        <h2>Iniciar sesión</h2>
        <form action="../Modelos/login.php" method="POST">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" name="enviar" value="Ingresar">
        </form>
    </div>
</body>
</html>
