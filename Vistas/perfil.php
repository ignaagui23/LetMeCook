<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once '../Controlador/conexion.php';

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
  echo "ID inválido.";
  exit;
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT id, username, fecha_creacion, descripcion, foto_perfil FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
  echo "Usuario no encontrado.";
  exit;
}

// Obtener recetas del usuario
$stmtRecetas = $conn->prepare("SELECT id, titulo, descripcion, imagen, fecha_creacion FROM recetas WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
$stmtRecetas->bind_param("i", $id);
$stmtRecetas->execute();
$resultadoRecetas = $stmtRecetas->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Perfil de <?= htmlspecialchars($usuario['username']) ?></title>
  <link rel="stylesheet" href="../Estilos/styles.css">
  <link rel="stylesheet" href="../Estilos/style_recetas.css">
    <link rel="stylesheet" href="../Estilos/style_usuarios.css">

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
        <p><strong>Miembro desde:</strong> <?= date('d/m/Y', strtotime($usuario['fecha_creacion'])) ?></p>
            <p><em><?= htmlspecialchars($usuario['descripcion'] ?: "Amante de la cocina y explorador de sabores.") ?></em></p>
      </div>
    </div>

    <h2 style="margin: 2rem;">Recetas de <?= htmlspecialchars($usuario['username']) ?></h2>

    <div class="grid-recetas">
      <?php while ($receta = $resultadoRecetas->fetch_assoc()): ?>
        <a href="receta.php?id=<?= $receta['id'] ?>" class="card-link">
          <div class="card-receta">
            <div class="card-img">
              <img src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>" />
            </div>
            <div class="card-contenido">
              <h3><?= htmlspecialchars($receta['titulo']) ?></h3>
              <hr />
              <p><?= htmlspecialchars($receta['descripcion']) ?></p>
              <small><?= date('d/m/Y H:i', strtotime($receta['fecha_creacion'])) ?></small>
            </div>
          </div>
        </a>
      <?php endwhile; ?>
      <?php if ($resultadoRecetas->num_rows === 0): ?>
        <p>No hay recetas publicadas por este usuario.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
