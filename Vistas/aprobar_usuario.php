<?php
session_start();
require_once '../Controlador/conexion.php';

if (!isset($_SESSION['es_admin']) || !$_SESSION['es_admin']) {
  header('Location: index.php');
  exit;
}

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
  echo "ID inválido.";
  exit;
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT id, username, email, fecha_creacion, foto_perfil, penalizado_hasta FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
  echo "Usuario no encontrado.";
  exit;
}

// Obtener recetas del usuario
$stmtRecetas = $conn->prepare("SELECT r.id, r.titulo, r.descripcion, r.imagen, r.fecha_creacion, u.username 
                FROM recetas r 
                JOIN usuarios u ON r.usuario_id = u.id 
                WHERE u.id = ? 
                ORDER BY r.fecha_creacion DESC");
$stmtRecetas->bind_param("i", $id);
$stmtRecetas->execute();
$resultadoRecetas = $stmtRecetas->get_result();

// POST: penalizar, despenalizar, borrar usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['penalizar'])) {
    $horas = intval($_POST['horas'] ?? 1);
    $penalizar = $conn->prepare("UPDATE usuarios SET penalizado_hasta = DATE_ADD(NOW(), INTERVAL ? HOUR) WHERE id = ?");
    $penalizar->bind_param("ii", $horas, $id);
    $penalizar->execute();
    header("Location: aprobar_usuario.php?id=$id");
    exit;
  }

  if (isset($_POST['despenalizar'])) {
    $despenalizar = $conn->prepare("UPDATE usuarios SET penalizado_hasta = NULL WHERE id = ?");
    $despenalizar->bind_param("i", $id);
    $despenalizar->execute();
    header("Location: aprobar_usuario.php?id=$id");
    exit;
  }

  if (isset($_POST['borrar'])) {
    $borrarRecetas = $conn->prepare("DELETE FROM recetas WHERE usuario_id = ?");
    $borrarRecetas->bind_param("i", $id);
    $borrarRecetas->execute();

    $borrarUsuario = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $borrarUsuario->bind_param("i", $id);
    $borrarUsuario->execute();

    header("Location: dashboard_usuarios.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Admin | Usuario <?= htmlspecialchars($usuario['username']) ?></title>
  <link rel="stylesheet" href="../Estilos/styles.css">
  <link rel="stylesheet" href="../Estilos/style_usuarios.css">
  <link rel="stylesheet" href="../Estilos/style_recetas.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <main>

  <div class="perfil-flex">
<?php
          $ruta_foto = (!empty($usuario['foto_perfil']) && file_exists("../Img/pfp/" . $usuario['foto_perfil']))
            ? "../Img/pfp/" . htmlspecialchars($usuario['foto_perfil'])
            : "../Img/pfp.jpg";
        ?>

              <img src="<?= $ruta_foto ?>" alt="Foto de <?= htmlspecialchars($usuario['username']) ?>" />
  <div class="datos-usuario">
    <h2><?= htmlspecialchars($usuario['username']) ?></h2>
    <hr>
    <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
    <p><strong>Fecha de registro:</strong> <?= date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])) ?></p>
    <?php if ($usuario['penalizado_hasta'] && strtotime($usuario['penalizado_hasta']) > time()): ?>
    <p style="color:red;"><strong>Penalizado hasta:</strong> <?= date('d/m/Y H:i', strtotime($usuario['penalizado_hasta'])) ?></p>
    <?php else: ?>
    <p style="color:green;"><strong>Estado:</strong> No penalizado</p>
    <?php endif; ?>
    <div class="acciones-admin">
  <form method="post">
    <label for="horas">Penalizar por:</label>
    <select name="horas" id="horas">
    <option value="1">1 hora</option>
    <option value="3">3 horas</option>
    <option value="6">6 horas</option>
    <option value="12">12 horas</option>
    <option value="24">1 día</option>
    </select>
    <button type="submit" name="penalizar">Penalizar</button>
    <button type="submit" name="despenalizar" onclick="return confirm('¿Seguro que querés quitar la penalización?')">Quitar penalización</button>
    <button type="submit" name="borrar" style="background-color: #e74c3c; color: white;" onclick="return confirm('¡Atención! Esto borrará la cuenta y sus recetas. ¿Querés continuar?')">Borrar usuario</button>
  </form>
  </div>

  </div>
  
  </div>
    <h2 style="margin: 2rem;">Recetas creadas por <?= htmlspecialchars($usuario['username']) ?></h3>


  <div class="grid-recetas">
    
  <?php while ($receta = $resultadoRecetas->fetch_assoc()): ?>
    <a href="aprobar_receta.php?id=<?= $receta['id'] ?>" class="card-link">
    <div class="card-receta">
      <div class="card-img">
      <img src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>" />
      </div>
      <div class="card-contenido">
      <h3><?= htmlspecialchars($receta['titulo']) ?></h3>
      <hr />
      <p><?= htmlspecialchars($receta['descripcion']) ?></p>
      <small>Por <strong><?= htmlspecialchars($receta['username']) ?></strong> | <?= date('d/m/Y H:i', strtotime($receta['fecha_creacion'])) ?></small>
      </div>
    </div>
    </a>
  <?php endwhile; ?>
  <?php if ($resultadoRecetas->num_rows === 0): ?>
    <p>No hay recetas para mostrar.</p>
  <?php endif; ?>
  </div>

  <p style="margin-top: 1rem;"><a href="dashboard_usuarios.php">← Volver al panel</a></p>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
