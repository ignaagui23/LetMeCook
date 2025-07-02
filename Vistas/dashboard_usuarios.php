<?php
session_start();
require_once '../Controlador/conexion.php';

if (!isset($_SESSION['es_admin']) || !$_SESSION['es_admin']) {
    header('Location: index.php');
    exit;
}

$filtro = $_GET['filtro'] ?? 'todos';

switch ($filtro) {
    case 'penalizados':
        $where = "penalizado_hasta IS NOT NULL AND penalizado_hasta > NOW()";
        break;
    case 'normales':
        $where = "penalizado_hasta IS NULL OR penalizado_hasta < NOW()";
        break;
    default:
        $where = "1"; // Todos
        break;
}

// Agregamos foto_perfil en el SELECT
$sql = "SELECT id, username, email, fecha_creacion, penalizado_hasta, foto_perfil 
        FROM usuarios
        WHERE $where
        ORDER BY fecha_creacion DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Admin | Usuarios</title>
  <link rel="stylesheet" href="../Estilos/styles.css" />
<link rel="stylesheet" href="../Estilos/style_recetas.css">
</head>
<body>
  <?php include 'header.php'; ?>
  <main class="contenedor-recetas">
    <h2>Gestión de Usuarios</h2>

    <div class="filtros-recetas">
      <form method="get">
        <label for="filtro">Mostrar:</label>
        <select name="filtro" id="filtro" onchange="this.form.submit()">
          <option value="todos" <?= $filtro === 'todos' ? 'selected' : '' ?>>Todos</option>
          <option value="normales" <?= $filtro === 'normales' ? 'selected' : '' ?>>Normales</option>
          <option value="penalizados" <?= $filtro === 'penalizados' ? 'selected' : '' ?>>Penalizados</option>
        </select>
      </form>
    </div>

    <div class="grid-recetas">
      <?php while ($usuario = $resultado->fetch_assoc()): ?>
        <?php
          $ruta_foto = (!empty($usuario['foto_perfil']) && file_exists("../Img/pfp/" . $usuario['foto_perfil']))
            ? "../Img/pfp/" . htmlspecialchars($usuario['foto_perfil'])
            : "../Img/pfp.jpg";
        ?>
        <a href="aprobar_usuario.php?id=<?= $usuario['id'] ?>" class="card-link">
          <div class="card-receta">
            <div class="card-img">
              <img src="<?= $ruta_foto ?>" alt="Foto de <?= htmlspecialchars($usuario['username']) ?>" />
            </div>
            <div class="card-contenido">
              <h3><?= htmlspecialchars($usuario['username']) ?></h3>
              <hr />
              <p><?= htmlspecialchars($usuario['email']) ?></p>
              <small>Registrado el <?= date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])) ?></small>
              <?php if ($usuario['penalizado_hasta'] && strtotime($usuario['penalizado_hasta']) > time()): ?>
                <br><small style="color: red;">Penalizado hasta: <?= date('d/m/Y H:i', strtotime($usuario['penalizado_hasta'])) ?></small>
              <?php endif; ?>
            </div>
          </div>
        </a>
      <?php endwhile; ?>
      <?php if ($resultado->num_rows === 0): ?>
        <p>No hay usuarios para mostrar.</p>
      <?php endif; ?>
    </div>
  </main>
  <?php include 'footer.php'; ?>
<?php include 'footer.php'; ?>
</body>
</html>
