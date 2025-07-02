<?php
session_start();
// Las rutas deben ser correctas desde Vistas/
require_once('../Controlador/conexion.php'); // Asegúrate que esta ruta es correcta

if (!isset($_GET['id'])) {
    header("Location: recetas.php"); // Asegúrate que esta ruta es correcta para redirección
    exit();
}

$receta_id = intval($_GET['id']);
$usuario_id = $_SESSION["usuario"]["id"] ?? null;

// Consulta de la receta y el autor
$consulta = $conn->prepare("SELECT r.*, u.username FROM recetas r JOIN usuarios u ON r.usuario_id = u.id WHERE r.id = ?");
$consulta->bind_param("i", $receta_id);
$consulta->execute();
$resultado = $consulta->get_result();
$receta = $resultado->fetch_assoc();

if (!$receta) {
    echo "Receta no encontrada.";
    exit();
}

// Ingredientes de la receta
$ingredientes = $conn->query("
    SELECT i.nombre, ri.cantidad, ri.unidad
    FROM receta_ingrediente ri
    JOIN ingredientes i ON ri.ingrediente_id = i.id
    WHERE ri.receta_id = $receta_id
");

// Pasos de la receta
$pasos = $conn->query("
    SELECT numero_paso, descripcion
    FROM pasos
    WHERE receta_id = $receta_id
    ORDER BY numero_paso ASC
");

// Verificar si es favorito
$esFavorito = false;
if ($usuario_id) {
    $favCheck = $conn->prepare("SELECT * FROM favoritos WHERE usuario_id = ? AND receta_id = ?");
    $favCheck->bind_param("ii", $usuario_id, $receta_id);
    $favCheck->execute();
    $esFavorito = $favCheck->get_result()->num_rows > 0;
}

// Promedio de puntuación
$promedioQuery = $conn->query("SELECT AVG(puntuacion) as promedio FROM puntuaciones WHERE receta_id = $receta_id");
$promedio = round($promedioQuery->fetch_assoc()['promedio'] ?? 0, 1);

// Obtener la puntuación del usuario actual (si existe) para preseleccionar las estrellas
$puntuacion_usuario = 0;
if ($usuario_id) {
    $userPuntQuery = $conn->prepare("SELECT puntuacion FROM puntuaciones WHERE usuario_id = ? AND receta_id = ?");
    $userPuntQuery->bind_param("ii", $usuario_id, $receta_id);
    $userPuntQuery->execute();
    $userPuntResult = $userPuntQuery->get_result();
    if ($userPuntResult->num_rows > 0) {
        $puntuacion_usuario = $userPuntResult->fetch_assoc()['puntuacion'];
    }
}

// Obtener dificultad del usuario (si existe)
$dificultad_usuario = '';
if ($usuario_id) {

}

// Cerrar conexión (opcional, pero buena práctica si no se va a usar más)
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($receta['titulo']) ?> | Let Me Cook</title>
  <link rel="stylesheet" href="../Estilos/style_receta.css?v=1.0">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="flex-center-body"> <?php include 'header.php'; // Asegúrate que la ruta es correcta ?>

  <main>
  <div class="receta-layout">
<div class="receta-top">
    <!-- Imagen -->
    <div class="receta-imagen">
      <img src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
    </div>

    <!-- Información general -->
    <div class="receta-info">
      <h1><?= htmlspecialchars($receta['titulo']) ?></h1>
      <hr>
      <p class="descripcion"><?= nl2br(htmlspecialchars($receta['descripcion'])) ?></p>

      <div class="info-clave">
        <span><i class="material-icons">timer</i> <?= $receta['tiempo_preparacion'] ?> min</span>
        <span><i class="material-icons">thermostat</i> <?= $receta['dificultad'] ?? 'No especificada' ?></span>
        <span><i class="material-icons">person</i> <?= htmlspecialchars($receta['username']) ?></span>
        <span><i class="material-icons">star</i> <?= $promedio ?>/5</span>
      </div>

     

    

    </div>
    <!-- Fin de la información general -->
  </div>
    <!-- Tarjetas punteadas verticales -->
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

      <?php if (true): // El botón solo aparece si el usuario inició sesión ?>
        <form method="post" action="../Modelos/toggle_favorito.php">
            <input type="hidden" name="receta_id" value="<?= $receta_id ?>">
            <button type="submit" class="favorite-btn">
                <?= $esFavorito ? '★ Quitar de favoritos' : '☆ Añadir a favoritos' ?>
            </button>
        </form>
    <?php endif; ?>
  </div>
</body>
</html>