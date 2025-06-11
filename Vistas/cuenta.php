<?php
session_start();
// Verificar sesión
$username_sesion = $_SESSION['usuario'] ?? null;
if (!$username_sesion) {
    header('Location: login.php');
    exit;
}

// Conexión MySQLi
require_once '../Controlador/conexion.php'; // Debe definir $conn

// Obtener datos del usuario
$stmt = $conn->prepare('SELECT id, username, email, password FROM usuarios WHERE username = ?');
$stmt->bind_param('s', $username_sesion);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$user_id = $user['id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $current_pass = $_POST['current_password'] ?? '';

    // Verificar contraseña actual
    if (!password_verify($current_pass, $user['password'])) {
        $errors[] = 'Contraseña actual incorrecta.';
    } else {
        switch ($action) {
            case 'cambiar_username':
                $new_username = trim($_POST['new_username'] ?? '');
                if ($new_username === '') {
                    $errors[] = 'El nombre de usuario no puede estar vacío.';
                } else {
                    $chk = $conn->prepare('SELECT COUNT(*) AS cnt FROM usuarios WHERE username = ? AND id <> ?');
                    $chk->bind_param('si', $new_username, $user_id);
                    $chk->execute();
                    $cnt = $chk->get_result()->fetch_assoc()['cnt'];
                    $chk->close();
                    if ($cnt > 0) {
                        $errors[] = 'El nombre de usuario ya está en uso.';
                    } else {
                        $upd = $conn->prepare('UPDATE usuarios SET username = ? WHERE id = ?');
                        $upd->bind_param('si', $new_username, $user_id);
                        $upd->execute();
                        $upd->close();
                        $_SESSION['usuario'] = $new_username;
                        $user['username'] = $new_username;
                        $success = 'Nombre de usuario actualizado correctamente.';
                    }
                }
                break;
            case 'cambiar_password':
                $new_pass = $_POST['new_password'] ?? '';
                $confirm_pass = $_POST['confirm_password'] ?? '';
                if (strlen($new_pass) < 6) {
                    $errors[] = 'La nueva contraseña debe tener al menos 6 caracteres.';
                } elseif ($new_pass !== $confirm_pass) {
                    $errors[] = 'Las contraseñas no coinciden.';
                } else {
                    $hash = password_hash($new_pass, PASSWORD_BCRYPT);
                    $upd = $conn->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
                    $upd->bind_param('si', $hash, $user_id);
                    $upd->execute();
                    $upd->close();
                    $success = 'Contraseña actualizada correctamente.';
                }
                break;
            case 'eliminar_cuenta':
                $del = $conn->prepare('DELETE FROM usuarios WHERE id = ?');
                $del->bind_param('i', $user_id);
                $del->execute();
                $del->close();
                session_destroy();
                header('Location: login.php');
                exit;
        }
        // Refrescar datos
        $stmt = $conn->prepare('SELECT id, username, email, password FROM usuarios WHERE id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Estilos/style_cuenta.css">
    <link rel="stylesheet" href="style.css">
    <title>Mi Cuenta</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="cuenta-container">
        <h1>Configuración de Cuenta</h1>

        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="info-cuenta">
            <p><strong>Email actual:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </section>

        <section class="form-section">
            <h2>Cambiar Nombre de Usuario</h2>
            <form method="post">
                <input type="hidden" name="action" value="cambiar_username">
                <label>Nuevo nombre de usuario:<br>
                    <input type="text" name="new_username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </label><br>
                <label>Contraseña actual:<br>
                    <input type="password" name="current_password" required>
                </label><br>
                <button type="submit">Actualizar</button>
            </form>
        </section>

        <section class="form-section">
            <h2>Cambiar Contraseña</h2>
            <form method="post">
                <input type="hidden" name="action" value="cambiar_password">
                <label>Nueva contraseña:<br>
                    <input type="password" name="new_password" required>
                </label><br>
                <label>Confirmar nueva contraseña:<br>
                    <input type="password" name="confirm_password" required>
                </label><br>
                <label>Contraseña actual:<br>
                    <input type="password" name="current_password" required>
                </label><br>
                <button type="submit">Actualizar</button>
            </form>
        </section>

        <section class="form-section">
            <h2>Eliminar Cuenta</h2>
            <form method="post" onsubmit="return confirm('¿Estás seguro de eliminar tu cuenta? Esta acción no se puede deshacer.');">
                <input type="hidden" name="action" value="eliminar_cuenta">
                <label>Contraseña actual:<br>
                    <input type="password" name="current_password" required>
                </label><br>
                <button type="submit" class="btn-danger">Eliminar Cuenta</button>
            </form>
        </section>
    </main>
</body>
</html>
