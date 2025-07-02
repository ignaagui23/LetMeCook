<?php
session_start();
require_once '../Controlador/conexion.php';

if (!isset($_SESSION['es_admin']) || !$_SESSION['es_admin']) {
    header('Location: index.php');
    exit;
}

// Para filtro simple: mostrar pendientes o todas (puede expandirse)
$filtro = $_GET['filtro'] ?? 'pendientes';

$where = ($filtro === 'aprobadas') ? "r.aprobada = 1" : "r.aprobada = 0";

$sql = "SELECT r.id, r.titulo, r.descripcion, r.imagen, r.fecha_creacion, u.username 
        FROM recetas r
        JOIN usuarios u ON r.usuario_id = u.id
        WHERE $where
        ORDER BY r.fecha_creacion DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Admin | Aprobación de Recetas</title>
  <link rel="stylesheet" href="../Estilos/styles.css" />
  <link rel="stylesheet" href="../Estilos/style_recetas.css" />
</head>
<body>
  <?php include 'header.php'; ?>
  <main class="contenedor-recetas">
    <h2>Recetas para aprobación</h2>

    <div class="filtros-recetas">
      <form method="get">
        <label for="filtro">Mostrar:</label>
        <select name="filtro" id="filtro" onchange="this.form.submit()">
          <option value="pendientes" <?= $filtro === 'pendientes' ? 'selected' : '' ?>>Pendientes</option>
          <option value="aprobadas" <?= $filtro === 'aprobadas' ? 'selected' : '' ?>>Aprobadas</option>
        </select>
      </form>
    </div>

    <div class="grid-recetas">
      <?php while ($receta = $resultado->fetch_assoc()): ?>
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
      <?php if ($resultado->num_rows === 0): ?>
        <p>No hay recetas para mostrar.</p>
      <?php endif; ?>
    </div>
  </main>
  <?php include 'footer.php'; ?>
</body>
</html>
