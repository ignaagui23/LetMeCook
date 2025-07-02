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
$stmt = $conn->prepare('SELECT id, username, email, password, foto_perfil, descripcion FROM usuarios WHERE username = ?');
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

    // Verificar contraseña actual (excepto para cambiar foto)
    if ($action !== 'cambiar_foto' && !password_verify($current_pass, $user['password'])) {
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

                case 'cambiar_descripcion':
    $nueva_descripcion = trim($_POST['nueva_descripcion'] ?? '');

    // Limitar largo o limpiar si querés más control
    if (strlen($nueva_descripcion) > 1000) {
        $errors[] = 'La descripción es demasiado larga (máx. 1000 caracteres).';
    } else {
        $upd = $conn->prepare('UPDATE usuarios SET descripcion = ? WHERE id = ?');
        $upd->bind_param('si', $nueva_descripcion, $user_id);
        $upd->execute();
        $upd->close();
        $success = 'Descripción actualizada correctamente.';
    }
    break;


case 'cambiar_foto':
    if (isset($_FILES['nueva_foto']) && $_FILES['nueva_foto']['error'] === 0) {
        $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        $tipo = $_FILES['nueva_foto']['type'];

        if (!array_key_exists($tipo, $permitidos)) {
            $errors[] = 'Formato de imagen no permitido. Solo JPG o PNG.';
        } else {
            $ext = $permitidos[$tipo];
            $nombre_archivo = "pfp_" . $user_id . ".jpg"; // Guardamos siempre como JPG
            $ruta_destino = "../Img/pfp/" . $nombre_archivo;
            $tmp = $_FILES['nueva_foto']['tmp_name'];

            // Borrar imagen anterior si existe y no es la imagen por defecto
            if (!empty($user['foto_perfil']) && $user['foto_perfil'] !== 'pfp.jpg') {
                $ruta_vieja = "../Img/pfp/" . $user['foto_perfil'];
                if (file_exists($ruta_vieja)) {
                    unlink($ruta_vieja); // Borra la imagen anterior
                }
            }

            // Mover directamente el archivo (sin convertirlo)
            if (move_uploaded_file($tmp, $ruta_destino)) {
                // Guardar el nombre en la base de datos
                $upd = $conn->prepare('UPDATE usuarios SET foto_perfil = ? WHERE id = ?');
                $upd->bind_param('si', $nombre_archivo, $user_id);
                $upd->execute();
                $upd->close();

                $_SESSION['foto_perfil'] = $nombre_archivo;
                $success = 'Foto de perfil actualizada correctamente.';
            } else {
                $errors[] = 'Error al subir la imagen.';
            }
        }
    } else {
        $errors[] = 'No se seleccionó ninguna imagen o hubo un error al subirla.';
    }
    break;


                break;
        }

        // Refrescar datos del usuario
        $stmt = $conn->prepare('SELECT id, username, email, password, foto_perfil FROM usuarios WHERE id = ?');
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
    <h2>Editar Descripción</h2>
    <form method="post">
        <input type="hidden" name="action" value="cambiar_descripcion">
        <label>Nueva descripción:<br>
            <textarea name="nueva_descripcion" rows="4" style="width:100%; resize: vertical;"><?php echo htmlspecialchars($user['descripcion'] ?? ''); ?></textarea>
        </label><br>
        <label>Contraseña actual:<br>
            <input type="password" name="current_password" required>
        </label><br>
        <button type="submit">Actualizar Descripción</button>
    </form>
</section>


        <section class="form-section">
    <h2>Foto de Perfil</h2>
    <?php
        $foto_url = "../Img/pfp/pfp_" . $user_id . ".jpg";
        $foto_url = file_exists($foto_url) ? $foto_url : "../Img/pfp/default.jpg"; // Usar una por defecto si no hay
    ?>
    <img src="<?php echo htmlspecialchars($foto_url); ?>" alt="Foto de perfil" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 2px solid #c6382e; margin-bottom: 1rem;">
    
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="cambiar_foto">
        <label>Subir nueva foto:<br>
            <input type="file" name="nueva_foto" accept="image/jpeg,image/png" required>
        </label><br><br>
        <button type="submit">Actualizar Foto</button>
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
    <?php include 'footer.php'; ?>
</body>
</html>
