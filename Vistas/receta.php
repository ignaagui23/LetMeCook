<?php
session_start();
require_once('../Modelos/recetas.php');
require_once('../Controlador/conexion.php');

if (!isset($_GET['id'])) {
    header("Location: recetas.php");
    exit();
}

$receta_id = intval($_GET['id']);
$usuario_id = $_SESSION["usuario"]["id"] ?? null;

$consulta = $conn->prepare("SELECT r.*, u.username FROM recetas r JOIN usuarios u ON r.usuario_id = u.id WHERE r.id = ?");
$consulta->bind_param("i", $receta_id);
$consulta->execute();
$resultado = $consulta->get_result();
$receta = $resultado->fetch_assoc();

if (!$receta) {
    echo "Receta no encontrada.";
    exit();
}

$ingredientes = $conn->query("
    SELECT i.nombre, ri.cantidad, ri.unidad
    FROM receta_ingrediente ri
    JOIN ingredientes i ON ri.ingrediente_id = i.id
    WHERE ri.receta_id = $receta_id
");

$pasos = $conn->query("
    SELECT numero_paso, descripcion
    FROM pasos
    WHERE receta_id = $receta_id
    ORDER BY numero_paso ASC
");

$esFavorito = false;
if ($usuario_id) {
    $favCheck = $conn->prepare("SELECT * FROM favoritos WHERE usuario_id = ? AND receta_id = ?");
    $favCheck->bind_param("ii", $usuario_id, $receta_id);
    $favCheck->execute();
    $esFavorito = $favCheck->get_result()->num_rows > 0;
}

$promedioQuery = $conn->query("SELECT AVG(puntuacion) as promedio FROM puntuaciones WHERE receta_id = $receta_id");
$promedio = round($promedioQuery->fetch_assoc()['promedio'] ?? 0, 1);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($receta['titulo']) ?> | Let Me Cook</title>
  <link rel="stylesheet" href="../Estilos/styles.css">
  <link rel="stylesheet" href="../Estilos/style_receta.css">
</head>

<body>
  <?php include 'header.php'; ?>
  <main>
  <section class="contenedor-receta">
    <div class="receta-card">
      <div class="receta-top">
        <img class="imagen-receta" src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
        <div class="receta-info">
          <h1><?= htmlspecialchars($receta['titulo']) ?></h1>
          <p class="descripcion"><?= nl2br(htmlspecialchars($receta['descripcion'])) ?></p>
          <p><strong>Tiempo:</strong> <?= $receta['tiempo_preparacion'] ?> min</p>
          <p><strong>Dificultad:</strong> <?= $receta['dificultad'] ?? 'No especificada' ?></p>
          <p><strong>Autor:</strong> <?= htmlspecialchars($receta['username']) ?></p>
          <p><strong>Valoración Promedio:</strong> <?= $promedio ?>/5</p>

          <?php if ($usuario_id): ?>
            <form method="post" action="../Modelos/toggle_favorito.php">
              <input type="hidden" name="receta_id" value="<?= $receta_id ?>">
              <button type="submit" class="estrella-btn"><?= $esFavorito ? '★' : '☆' ?></button>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <div class="receta-detalle">
        <h2>Ingredientes</h2>
        <ul>
          <?php while($ing = $ingredientes->fetch_assoc()): ?>
            <li><?= $ing['cantidad'] . ' ' . $ing['unidad'] . ' de ' . htmlspecialchars($ing['nombre']) ?></li>
          <?php endwhile; ?>
        </ul>

        <h2>Pasos</h2>
        <ol>
          <?php while($paso = $pasos->fetch_assoc()): ?>
            <li><?= htmlspecialchars($paso['descripcion']) ?></li>
          <?php endwhile; ?>
        </ol>
      </div>

      <?php if ($usuario_id): ?>
        <div class="valoracion-form">
          <h2>Valorar receta</h2>
          <form method="post" action="../Modelos/valorar.php">
            <input type="hidden" name="receta_id" value="<?= $receta_id ?>">

            <label for="puntuacion">Tu valoración (1 a 5):</label><br>
            <div class="estrellas">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <label>
                  <input type="radio" name="puntuacion" value="<?= $i ?>"> <?= $i ?>
                </label>
              <?php endfor; ?>
            </div>

            <label for="dificultad">Dificultad:</label><br>
            <select name="dificultad">
              <option value="">Seleccionar</option>
              <option value="Fácil">Fácil</option>
              <option value="Media">Media</option>
              <option value="Difícil">Difícil</option>
            </select>

            <br><br>
            <button type="submit">Enviar valoración</button>
          </form>
        </div>
      <?php endif; ?>
    </div>
              </section>
  </main>
</body>
</html>
