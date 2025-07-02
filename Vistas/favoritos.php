<?php
session_start();
require_once('../Controlador/conexion.php'); // Conexión a la DB

// Si el usuario no está logueado, redirigirlo a login
if (!isset($_SESSION["usuario"]["id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario"]["id"];

// Consulta para obtener las recetas favoritas del usuario
// Se une la tabla 'recetas' con 'favoritos'
$consulta = $conn->prepare("
    SELECT r.* FROM recetas r
    JOIN favoritos f ON r.id = f.receta_id
    WHERE f.usuario_id = ?
    ORDER BY r.fecha_creacion DESC
");
$consulta->bind_param("i", $usuario_id);
$consulta->execute();
$favoritos = $consulta->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Favoritos | Let Me Cook</title>
    <link rel="stylesheet" href="../Estilos/style_recetas.css"> 
</head>
<body class="flex-center-body">
    <?php include 'header.php'; ?>

    <main>
        <h1>Mis Recetas Favoritas ❤️</h1>
        
        <div class="recetas-grid">
            <?php if ($favoritos->num_rows > 0): ?>
                <?php while ($receta = $favoritos->fetch_assoc()): ?>
                    <div class="receta-card">
                        <a href="receta.php?id=<?= $receta['id'] ?>">
                            <img src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
                            <div class="receta-card-info">
                                <h3><?= htmlspecialchars($receta['titulo']) ?></h3>
                                <p><?= substr(htmlspecialchars($receta['descripcion']), 0, 80) . '...' ?></p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-results">Aún no has agregado ninguna receta a tus favoritos. ¡Explora y encuentra las que más te gusten!</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>