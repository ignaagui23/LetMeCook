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
$consulta = $conn->prepare("SELECT 
                              r.id AS receta_id,
                              r.titulo,
                              r.descripcion AS descripcion_receta,
                              r.imagen,
                              r.fecha_creacion AS receta_fecha,
                              r.tiempo_preparacion,
                              r.dificultad,
                              u.id AS usuario_id,
                              u.username,
                              u.foto_perfil,
                              u.descripcion AS descripcion_usuario,
                              u.fecha_creacion AS usuario_fecha_creacion
                            FROM recetas r
                            JOIN usuarios u ON r.usuario_id = u.id
                            WHERE r.id = ?");
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
        <div class="receta-imagen">
        <img src="../Img/imgrecetas/<?= htmlspecialchars($receta['imagen']) ?>" alt="<?= htmlspecialchars($receta['titulo']) ?>">
        </div>

        <!-- Información general -->
        <div class="receta-info">
        <h1><?= htmlspecialchars($receta['titulo']) ?></h1>
        <hr>
        <p class="descripcion"><?= htmlspecialchars($receta['descripcion_receta']) ?>

</p>

        <div class="info-clave">
            <span><i class="material-icons">timer</i><?= htmlspecialchars($receta['tiempo_preparacion']) ?>
 min</span>
            <span><i class="material-icons">thermostat</i> <?= $receta['dificultad'] ?? 'No especificada' ?></span>
        </div>

        <a href="perfil.php?id=<?= $receta['usuario_id'] ?>" style="text-decoration: none; color: inherit;">
            <div class="perfil-flex">
                <div class="perfil-info">
                    <?php
                        $ruta_foto = (!empty($receta['foto_perfil']) && file_exists("../Img/pfp/" . $receta['foto_perfil']))
                            ? "../Img/pfp/" . htmlspecialchars($receta['foto_perfil'])
                            : "../Img/pfp.jpg";
                    ?>
                    <img src="<?= $ruta_foto ?>" alt="Foto de <?= htmlspecialchars($receta['username']) ?>" />
                    <div class="datos-usuario">
                        <h2><?= htmlspecialchars($receta['username']) ?></h2><br
                        <p><strong>Miembro desde:</strong> <?= date('d/m/Y', strtotime($receta['usuario_fecha_creacion'])) ?></p>
                                            </div>

                </div>
                                        <hr>

                                        <p><em><?= htmlspecialchars($receta['descripcion_usuario'] ?: "Amante de la cocina y explorador de sabores.") ?></em></p>

            </div>
        </a>    </div>
    </div>
    <br>
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
    </div>
    </main>

    </main>


    </body>
    </html>
