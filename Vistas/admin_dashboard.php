<?php
session_start();
require_once '../Controlador/conexion.php';

if (!isset($_SESSION['es_admin']) || !$_SESSION['es_admin']) {
    header('Location: index.php');
    exit;
}

// Consultas para los datos del dashboard
$totalRecetas = $conn->query("SELECT COUNT(*) as total FROM recetas")->fetch_assoc()['total'];
$totalUsuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc()['total'];

$ultimaReceta = $conn->query("SELECT titulo, fecha_creacion FROM recetas ORDER BY fecha_creacion DESC LIMIT 1")->fetch_assoc();
$ultimoUsuario = $conn->query("SELECT username, fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC LIMIT 1")->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel de Control | Admin</title>
  <link rel="stylesheet" href="../Estilos/styles.css" />
  <style>
    main.contenedor-dashboard {
      max-width: 900px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
      background: #f8f9fa;
      padding: 1.5rem;
    }
    .dashboard-card {
      background-color: rgb(226, 226, 226);
      border-radius: 8px;  
      border: 3px dashed #DA443F;
      box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
      padding: 1rem;
      text-align: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .dashboard-card h3 {
      margin-bottom: 0.5rem;
      font-size: 1.3rem;
      color: #333;
    }
    .dashboard-card p {
      font-size: 1.8rem;
      font-weight: bold;
      margin: 0;
      color: #007bff;
    }
    .dashboard-footer {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-top: 1rem;
    }
    .dashboard-footer a {
      background: #007bff;
      color: white;
      padding: 0.8rem 1.5rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }
    .dashboard-footer a:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <main class="contenedor-dashboard">
    <h2>Panel de Control General</h2>
    <div class="dashboard-grid">
      <div class="dashboard-card">
        <h3>Total de recetas</h3>
        <p><?= $totalRecetas ?></p>
      </div>
      <div class="dashboard-card">
        <h3>Total de usuarios</h3>
        <p><?= $totalUsuarios ?></p>
      </div>
      <div class="dashboard-card">
        <h3>Última receta creada</h3>
        <p><?= htmlspecialchars($ultimaReceta['titulo'] ?? 'Ninguna') ?></p>
        <small><?= isset($ultimaReceta['fecha_creacion']) ? date('d/m/Y H:i', strtotime($ultimaReceta['fecha_creacion'])) : '' ?></small>
      </div>
      <div class="dashboard-card">
        <h3>Último usuario creado</h3>
        <p><?= htmlspecialchars($ultimoUsuario['username'] ?? 'Ninguno') ?></p>
        <small><?= isset($ultimoUsuario['fecha_creacion']) ? date('d/m/Y H:i', strtotime($ultimoUsuario['fecha_creacion'])) : '' ?></small>
      </div>
    </div>

    <div class="dashboard-footer">
      <a href="dashboard_recetas.php">Gestionar Recetas</a>
      <a href="dashboard_usuarios.php">Gestionar Usuarios</a>
    </div>
  </main>
  <?php include 'footer.php'; ?>
<?php include 'footer.php'; ?>
</body>
</html>
