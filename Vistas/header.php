<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Header con menú y logo centrado</title>
  <link rel="stylesheet" href="../Estilos/headerstyle.css">
</head>
<body>

  <header>
    <button class="menu-toggle" onclick="toggleSidebar()">
      <img src="../Img/Hamburger_icon.svg.png" alt="Menú">
    </button>
    <div class="logo">
      <img src="../Img/LogoLetMeCook.png" alt="Logo Let Me Cook">
    </div>
     <div class="user-info">
    <span class="username">
      <?php echo isset($_SESSION["usuario"]) ? htmlspecialchars($_SESSION["usuario"]) : "Invitado"; ?>
    </span>
        <img src="../Img/pfp.jpg" alt="Foto de perfil" class="user-pic">
  </div>
  </header>

  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

  <div class="sidebar" id="sidebar">
    <div class="menu-options">
            <img src="../Img/LogoLetMeCook.png" alt="Logo Let Me Cook">

      <button>Opción 1</button>
      <button>Opción 2</button>
      <button>Opción 3</button>
    </div>
    <div class="bottom-buttons">
      <button>
        <img src="gear.png" alt="Config">
        Configuración
      </button>
      <button>
        <img src="logout.png" alt="Cerrar sesión">
        Cerrar sesión
      </button>
    </div>
  </div>

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
