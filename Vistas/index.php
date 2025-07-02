<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

include '../Controlador/conexion.php'; // Asegurate de tener conexión a la BD

$query = "SELECT id, titulo, imagen, descripcion FROM recetas ORDER BY RAND() LIMIT 3";
$resultado = mysqli_query($conn, $query);

$recetas = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
  $recetas[] = $fila;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Let Me Cook</title>
  <link rel="stylesheet" href="../Estilos/styles.css">
  <link rel="stylesheet" href="../Estilos/style_index.css">
  <script>
function scrollCarousel(direction) {
  const container = document.getElementById('carouselContainer');
  const scrollAmount = 320; // ajustá al ancho de tus cards

  container.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });
}
</script>


</head>
<body>
  <?php include 'header.php'; ?>
  <main>
<div class="titulo-recuadro">
  <h2>Recomendadas</h2>
</div>

    <div class="carousel">
  <button class="arrow left" onclick="scrollCarousel(-1)">&#x276E;</button>
  <div class="card-container" id="carouselContainer">
    <?php foreach ($recetas as $receta): ?>
      <a href="receta.php?id=<?= $receta['id'] ?>" class="card-link">
        <div class="card-receta">
          <div class="card-img">
            <img src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
          </div>
          <div class="card-contenido">
            <h3><?= htmlspecialchars($receta['titulo']) ?></h3>
            <hr>
            <p><?= htmlspecialchars($receta['descripcion']) ?></p>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <button class="arrow right" onclick="scrollCarousel(1)">&#x276F;</button>
</div>

  </main>

  <section class="hero-receta">
  <div class="hero-contenido">
    <h2>¡Crea tu propia receta!</h2>
    <p>¿Tenés una idea deliciosa? Compartila con el mundo.</p>
    <a href="crear_receta.php" class="btn-crear">Crear receta</a>
  </div>
</section>

<?php include 'footer.php'; ?>


</body>
</html>