<?php
session_start();
require_once '../Controlador/conexion.php';

if (!isset($_SESSION['es_admin']) || !$_SESSION['es_admin']) {
    header("Location: index.php");
    exit;
}

$receta_id = intval($_GET['id'] ?? 0);
if ($receta_id === 0) {
    echo "ID inválido.";
    exit;
}

// Obtener receta + autor
$stmt = $conn->prepare("SELECT r.*, u.username, u.id as usuario_id FROM recetas r JOIN usuarios u ON r.usuario_id = u.id WHERE r.id = ?");
$stmt->bind_param("i", $receta_id);
$stmt->execute();
$resultado = $stmt->get_result();
$receta = $resultado->fetch_assoc();

if (!$receta) {
    echo "Receta no encontrada.";
    exit;
}

// Ingredientes
$ingredientes = $conn->query("
    SELECT i.nombre, ri.cantidad, ri.unidad
    FROM receta_ingrediente ri
    JOIN ingredientes i ON ri.ingrediente_id = i.id
    WHERE ri.receta_id = $receta_id
");

// Pasos
$pasos = $conn->query("
    SELECT numero_paso, descripcion
    FROM pasos
    WHERE receta_id = $receta_id
    ORDER BY numero_paso ASC
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aprobar'])) {
        $update = $conn->prepare("UPDATE recetas SET aprobada = 1 WHERE id = ?");
        if ($update) {
            $update->bind_param("i", $receta_id);
            $update->execute();
            $update->close();
            header("Location: dashboard_recetas.php");
            exit;
        } else {
            echo "Error en prepare(): " . $conn->error;
            exit;
        }
    }


    if (isset($_POST['rechazar'])) {
        $borrar = $conn->prepare("DELETE FROM recetas WHERE id = ?");
        if ($borrar) {
            $borrar->bind_param("i", $receta_id);
            $borrar->execute();
            $borrar->close();

            $penalizar = $conn->prepare("UPDATE usuarios SET penalizado_hasta = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
            $penalizar->bind_param("i", $receta['usuario_id']);
            $penalizar->execute();
            $penalizar->close();

            header("Location: dashboard_recetas.php");
            exit;
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aprobar receta | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../Estilos/style_receta.css?v=1.0">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="flex-center-body">
<?php include 'header.php'; ?>

<main>
  <div class="receta-layout">
    <div class="receta-top">
      <!-- Imagen -->
      <div class="receta-imagen">
        <img src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
      </div>

      <!-- Info -->
      <div class="receta-info">
        <h1><?= htmlspecialchars($receta['titulo']) ?></h1>
        <hr>
        <p class="descripcion"><?= nl2br(htmlspecialchars($receta['descripcion'])) ?></p>

        <div class="info-clave">
          <span><i class="material-icons">timer</i> <?= $receta['tiempo_preparacion'] ?> min</span>
          <span><i class="material-icons">thermostat</i> <?= $receta['dificultad'] ?? 'No especificada' ?></span>
          <span><i class="material-icons">person</i> <?= htmlspecialchars($receta['username']) ?></span>
        </div>
      </div>
    </div>

   <div class="receta-secciones">
  <div class="tarjeta-receta">
    <h2>Ingredientes</h2>
    <hr>
    <?php while($ing = $ingredientes->fetch_assoc()): ?>
      <p><b> • </b><?= $ing['cantidad'] . ' ' . $ing['unidad'] . ' de ' . htmlspecialchars($ing['nombre']) ?></p>
    <?php endwhile; ?>
  </div>

  <div class="tarjeta-receta">
    <h2>Pasos</h2>
    <hr>
    <ol>
      <?php while($paso = $pasos->fetch_assoc()): ?>
        <li><?= htmlspecialchars($paso['descripcion']) ?></li>
      <?php endwhile; ?>
    </ol>
  </div>
</div>

<!-- Botones de acción o mensaje -->
<div class="admin-actions" style="margin-top: 2rem; text-align: center;">
  <?php if (isset($receta['aprobada']) && $receta['aprobada'] == 1): ?>
    <p style="color: green; font-weight: bold;">Esta receta fue aprobada por un administrador.</p>
  <?php else: ?>
    <form method="post">
      <button name="aprobar" class="btn-aceptar">Aprobar receta</button>
      <button name="rechazar" class="btn-rechazar" onclick="return confirm('¿Seguro que querés eliminar esta receta y penalizar al usuario?')">Rechazar receta</button>
    </form>
  <?php endif; ?>
</div>

  </div>
</main>

<style>
.btn-aceptar,
.btn-rechazar {
  padding: 0.8rem 1.5rem;
  font-size: 1rem;
  font-weight: 600;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin: 0 0.5rem;
}
.btn-aceptar {
  background-color: #28a745;
  color: white;
}
.btn-aceptar:hover {
  background-color: #218838;
}
.btn-rechazar {
  background-color: #dc3545;
  color: white;
}
.btn-rechazar:hover {
  background-color: #c82333;
}
</style>

</body>
</html>
