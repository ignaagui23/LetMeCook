<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
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
</head>
<body>
  <?php include 'header.php'; ?>
  <main>
    <h2>Recomendadas</h2>
    <div class="carousel">
      <button class="arrow left">&#x276E;</button>
      <div class="card-container">
<!-- Tarta de jamón y queso -->
<a href="receta.php" class="card-link">
  <div class="card-receta">
    <div class="card-img">
      <img src="../Img/imgrecetas/receta_683f8d02e218d.jpg" alt="Tarta de jamón y queso">
    </div>
    <div class="card-contenido">
      <h3>Tarta de jamón y queso</h3>
      <hr>
      <p>Una tarta clásica, fácil y rápida de hacer, ideal para una merienda o cena liviana.</p>
    </div>
  </div>
</a>

<!-- Tarta de manzana -->
<a href="receta.php" class="card-link">
  <div class="card-receta card-destacada">
    <div class="card-img">
      <img src="../Img/imgrecetas/receta_683fa1d0039bd.jpg" alt="Tarta de manzana">
    </div>
    <div class="card-contenido">
      <h3>Tarta de manzana</h3>
      <hr>
      <p>Una delicia dulce con manzanas caramelizadas. Perfecta para la tarde o como postre.</p>
    </div>
  </div>
</a>

<!-- Panqueques salados rellenos -->
<a href="receta.php" class="card-link">
  <div class="card-receta">
    <div class="card-img">
      <img src="../Img/imgrecetas/receta_683fad864be9b.jpg" alt="Panqueques salados">
    </div>
    <div class="card-contenido">
      <h3>Panqueques salados rellenos</h3>
      <hr>
      <p>Una opción versátil y sabrosa. Rellenos con lo que quieras, siempre quedan bien.</p>
    </div>
  </div>
</a>
</div>

      <button class="arrow right">&#x276F;</button>
    </div>
  </main>
</body>
</html>