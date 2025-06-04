<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

require_once('../Controlador/conexion.php');

// Lógica de ordenamiento
$orden = $_GET['orden'] ?? 'recientes';
$orderClause = match ($orden) {
    'alfabetico' => 'r.titulo ASC',
    'tiempo'     => 'r.tiempo_preparacion ASC',
    'ingredientes' => 'r.cantidad_ingredientes ASC',
    default      => 'r.id DESC'
};

$sql = "SELECT r.id, r.titulo, r.descripcion, r.imagen, r.tiempo_preparacion,
               COUNT(ri.ingrediente_id) AS cantidad_ingredientes
        FROM recetas r
        LEFT JOIN receta_ingrediente ri ON r.id = ri.receta_id
        GROUP BY r.id
        ORDER BY $orderClause";



$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recetas | Let Me Cook</title>
  <link rel="stylesheet" href="../Estilos/styles.css">
  <link rel="stylesheet" href="../Estilos/style_recetas.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <main class="contenedor-recetas">
    <h2>Recetas</h2>

    <div class="filtros-recetas">
      <form method="get">
        <label for="orden">Ordenar por:</label>
        <select name="orden" id="orden" onchange="this.form.submit()">
          <option value="recientes" <?= $orden == 'recientes' ? 'selected' : '' ?>>Más recientes</option>
          <option value="alfabetico" <?= $orden == 'alfabetico' ? 'selected' : '' ?>>Alfabéticamente</option>
          <option value="tiempo" <?= $orden == 'tiempo' ? 'selected' : '' ?>>Tiempo de preparación</option>
          <option value="ingredientes" <?= $orden == 'ingredientes' ? 'selected' : '' ?>>Cantidad de ingredientes</option>
        </select>
      </form>
    </div>

    <div class="grid-recetas">
      <?php while ($row = $resultado->fetch_assoc()): ?>
        <a href="receta.php?id=<?= $row['id'] ?>" class="card-link">
          <div class="card-receta">
            <div class="card-img">
              <img src="../Img/imgrecetas/<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['titulo']) ?>">
            </div>
            <div class="card-contenido">
              <h3><?= htmlspecialchars($row['titulo']) ?></h3>
              <hr>
              <p><?= htmlspecialchars($row['descripcion']) ?></p>
              <small><?= $row['tiempo_preparacion'] ?> min | <?= $row['cantidad_ingredientes'] ?> ingredientes</small>
            </div>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  </main>
</body>
</html>
