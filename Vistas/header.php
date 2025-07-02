<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');


if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: login.php");
  exit(); 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Header con menú y logo centrado</title>
  <link rel="stylesheet" href="../Estilos/style_header.css">
  <link rel="stylesheet" href="../Estilos/styles.css">
</head>
<body>

  <header>
    <?php if (isset($_SESSION["usuario"])): ?>
      <button class="menu-toggle" onclick="toggleSidebar()">
        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 100 80" fill="white">
          <rect width="100" height="10"></rect>
          <rect y="30" width="100" height="10"></rect>
          <rect y="60" width="100" height="10"></rect>
        </svg>
      </button>

      <form class="buscar-form" action="../Vistas/resultados.php" method="GET">
  <input type="text" name="q" class="buscar-input" placeholder="Buscar receta...">
  <button type="submit" class="buscar-btn">Buscar</button>
</form>


    <?php endif; ?>

    <div class="logo">
      <img src="../Img/LogoLetMeCook.png" alt="Logo Let Me Cook">
    </div>

    <?php if (isset($_SESSION["usuario"])): ?>
      <div class="user-info">
<a href="perfil.php?id=<?php echo $_SESSION['usuario_id']; ?>">
  <span class="username"><?php echo htmlspecialchars($_SESSION["usuario"]); ?></span>
</a>

<?php
// Ruta por defecto si no hay foto
$foto = !empty($_SESSION['foto_perfil']) && file_exists("../Img/pfp/" . $_SESSION['foto_perfil'])
  ? "../Img/pfp/" . $_SESSION['foto_perfil']
  : "../Img/pfp.jpg";
?>

<img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil" class="user-pic">
      </div>
    <?php endif; ?>

  </header>

  <?php if (isset($_SESSION["usuario"])): ?>
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    
    <div class="sidebar" id="sidebar">
      <div class="menu-options">
        <img src="../Img/LogoLetMeCook.png" alt="Logo Let Me Cook">

<button onclick="window.location.href='index.php'" class="btn-agregar-receta">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
    <path d="M3 12L12 3l9 9v9a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1v-9z" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  Inicio
</button>

<button onclick="window.location.href='crear_receta.php'" class="btn-agregar-receta">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  Crear receta
</button>
<button onclick="window.location.href='recetas.php'" class="btn-agregar-receta">
 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/></svg>
  Recetas
</button>

<button onclick="window.location.href='favoritos.php'" class="btn-agregar-receta">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
      </svg>
      Favoritos
    </button> 

<?php if (isset($_SESSION["es_admin"]) && $_SESSION["es_admin"] == 1): ?>
  <button onclick="window.location.href='admin_dashboard.php'" class="btn-agregar-receta">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3 3h18v18H3z"/>
      <path d="M9 9h6v6H9z"/>
    </svg>
    Admin Dashboard
  </button>
<?php endif; ?>


      </div>

      <div class="bottom-buttons">
        <button class="config-btn" onclick="window.location.href='cuenta.php'">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
        </button>

        <button class="logout-btn" onclick="window.location.href='?logout=1'">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="12" height="18" rx="2" ry="2" />
            <path d="M15 12h6" />
            <path d="M18 9l3 3-3 3" />
          </svg>
        </button>
      </div>
    </div>
  <?php endif; ?>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      sidebar.classList.toggle('open');
      overlay.classList.toggle('active');
    }

    function closeSidebar() {
      document.getElementById('sidebar').classList.remove('open');
      document.getElementById('overlay').classList.remove('active');
    }
  </script>

</body>
</html>
