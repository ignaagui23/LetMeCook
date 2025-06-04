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
        <div class="card"></div>
        <div class="card center image"></div>
        <div class="card"></div>
      </div>
      <button class="arrow right">&#x276F;</button>
    </div>
  </main>
</body>
</html>
