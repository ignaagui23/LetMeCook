<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../Modelos/login.php");
    exit();
}

require_once '../Controlador/conexion.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q === '') {
    echo "Por favor ingresa un término para buscar.";
    exit();
}

// MODIFICACIÓN CRÍTICA AQUÍ:
// Se agrega un LEFT JOIN a receta_ingrediente y se usa COUNT() para obtener la cantidad de ingredientes.
// También se renombra la columna para que coincida con lo que esperas ('cantidad_ingredientes').
$sql = "SELECT 
            r.id, 
            r.titulo, 
            r.descripcion, 
            r.imagen, 
            r.tiempo_preparacion, 
            COUNT(ri.ingrediente_id) AS cantidad_ingredientes, 
            u.username 
        FROM recetas r
        LEFT JOIN usuarios u ON r.usuario_id = u.id  -- Corregido de r.id_usuario a r.usuario_id
        LEFT JOIN receta_ingrediente ri ON r.id = ri.receta_id
        WHERE r.titulo LIKE ? OR r.descripcion LIKE ?
        GROUP BY r.id, r.titulo, r.descripcion, r.imagen, r.tiempo_preparacion, u.username"; // Agregamos GROUP BY para la función COUNT()

$param = '%' . $q . '%';

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $param, $param);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda | Let Me Cook</title>
    <link rel="stylesheet" href="../Estilos/style_recetas.css">
    <link rel="stylesheet" href="../Estilos/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="contenedor-recetas">
        <h2>Resultados para "<?= htmlspecialchars($q) ?>"</h2>

        <?php if ($resultado->num_rows > 0): ?>
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
                                <small>
                                    <?= htmlspecialchars($row['tiempo_preparacion']) ?> min |
                                    <?= htmlspecialchars($row['cantidad_ingredientes']) ?> ingredientes |
                                    por <strong><?= htmlspecialchars($row['username'] ?? 'Anónimo') ?></strong>
                                </small>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No se encontraron recetas para "<?= htmlspecialchars($q) ?>"</p>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>