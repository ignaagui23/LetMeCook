<?php
session_start();
// Las rutas deben ser correctas desde Vistas/
require_once('../Modelos/recetas.php'); // Asegúrate que esta ruta es correcta
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
    // La dificultad en la tabla 'recetas' es la dificultad general de la receta.
    // Si quieres que el usuario pueda valorar la dificultad, necesitarías una columna en 'puntuaciones' para eso,
    // o un mecanismo diferente. Por ahora, el select mostrará la dificultad de la receta, no la valoración del usuario.
    // Si la dificultad se envía en el formulario, es una nueva valoración o actualización de la dificultad general.
    // Si la idea es que el usuario califique la dificultad percibida, el campo de dificultad debería ser parte de la tabla 'puntuaciones'.
    // Para simplificar, asumiremos que se muestra la dificultad ya establecida en la receta.
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
    <div class="recipe-card-modern"> <div class="image-section">
            <div class="hero-image-container-modern">
                <img alt="<?= htmlspecialchars($receta['titulo']) ?>" class="recipe-image-modern" src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>">
            </div>
        </div>
        <div class="details-section">
            <h1 class="recipe-title-modern"><?= htmlspecialchars($receta['titulo']) ?></h1>
            <p class="recipe-description-modern"><?= nl2br(htmlspecialchars($receta['descripcion'])) ?></p>

            <div class="recipe-details-grid">
                <div class="detail-item">
                    <span class="material-icons">timer</span>
                    <span>Tiempo: <?= $receta['tiempo_preparacion'] ?> min</span>
                </div>
                <div class="detail-item">
                    <span class="material-icons">thermostat</span>
                    <span>Dificultad: <?= $receta['dificultad'] ?? 'No especificada' ?></span>
                </div>
                <div class="detail-item">
                    <span class="material-icons">person</span>
                    <span>Autor: <?= htmlspecialchars($receta['username']) ?></span>
                </div>
                <div class="detail-item rating">
                    <span class="material-icons">star</span>
                    <span><?= $promedio ?>/5</span>
                </div>
            </div>

            <?php if ($usuario_id): ?>
                <div class="favorite-section">
                    <form method="post" action="../Modelos/toggle_favorito.php">
                        <input type="hidden" name="receta_id" value="<?= $receta_id ?>">
                        <button type="submit" class="favorite-btn"><?= $esFavorito ? '★ Favorito' : '☆ Añadir a favoritos' ?></button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="ingredients-section">
                <h2 class="section-title-modern">Ingredientes</h2>
                <ul class="ingredient-list-modern">
                    <?php while($ing = $ingredientes->fetch_assoc()): ?>
                        <li><span class="material-icons">fiber_manual_record</span><?= $ing['cantidad'] . ' ' . $ing['unidad'] . ' de ' . htmlspecialchars($ing['nombre']) ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="steps-section">
                <h2 class="section-title-modern">Pasos</h2>
                <ol class="steps-list-modern">
                    <?php while($paso = $pasos->fetch_assoc()): ?>
                        <li>
                            <span class="step-number"><?= htmlspecialchars($paso['numero_paso']) ?>.</span>
                            <span><?= htmlspecialchars($paso['descripcion']) ?></span>
                        </li>
                    <?php endwhile; ?>
                </ol>
            </div>

            <?php if ($usuario_id): ?>
                <div class="rating-form-modern">
                    <h2 class="section-title-modern">Valorar receta</h2>
                    <form method="post" action="../Modelos/valorar.php">
                        <input type="hidden" name="receta_id" value="<?= $receta_id ?>">

                        <label for="puntuacion" class="form-label">Tu valoración (1 a 5):</label>
                        <div class="stars-selection">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="puntuacion" value="<?= $i ?>" <?= ($i == $puntuacion_usuario) ? 'checked' : '' ?>>
                                    <span class="material-icons star-icon"><?= ($i <= $puntuacion_usuario) ? 'star' : 'star_border' ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>

                        <label for="dificultad" class="form-label">Dificultad:</label>
                        <select name="dificultad" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="Fácil" <?= ($receta['dificultad'] == 'Fácil') ? 'selected' : '' ?>>Fácil</option>
                            <option value="Media" <?= ($receta['dificultad'] == 'Media') ? 'selected' : '' ?>>Media</option>
                            <option value="Difícil" <?= ($receta['dificultad'] == 'Difícil') ? 'selected' : '' ?>>Difícil</option>
                        </select>

                        <button type="submit" class="submit-btn">Enviar valoración</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
  </main>


</body>
</html>