<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let Me Cook - Login / Registro</title>
    <link rel="stylesheet" href="../Estilos/styles.css">
        <style>
        .oculto { display: none; }
    </style>
</head>
<body>
    <div class="formularios">
        <form id="form-login" action="../Modelos/login.php" method="POST">
            <h2>Iniciar Sesión</h2>
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="login">Ingresar</button>
            <button type="button" onclick="mostrarRegistro()">Crear cuenta</button>
        </form>

        <form id="form-registro" class="oculto" action="../Modelos/login.php" method="POST">
            <h2>Registrarse</h2>
            <input type="text" name="new_username" placeholder="Nuevo usuario" required>
            <input type="email" name="new_email" placeholder="Correo electrónico" required>
            <input type="password" name="new_password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
            <button type="submit" name="register">Crear cuenta</button>
            <button type="button" onclick="mostrarLogin()">Cancelar</button>
        </form>
    </div>

    <script>
        function mostrarRegistro() {
            document.getElementById('form-login').classList.add('oculto');
            document.getElementById('form-registro').classList.remove('oculto');
        }

        function mostrarLogin() {
            document.getElementById('form-registro').classList.add('oculto');
            document.getElementById('form-login').classList.remove('oculto');
        }
    </script>
</body>
</html>
