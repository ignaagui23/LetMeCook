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
      <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 100 80" fill="white">
  <rect width="100" height="10"></rect>
  <rect y="30" width="100" height="10"></rect>
  <rect y="60" width="100" height="10"></rect>
</svg>

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
        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <circle cx="12" cy="12" r="3"></circle>
  <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
</svg>
        Configuración
      </button>
      <button>
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <rect x="3" y="3" width="12" height="18" rx="2" ry="2" />
  <path d="M15 12h6" />
  <path d="M18 9l3 3-3 3" />
</svg>

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
