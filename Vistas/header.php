<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Header con menú y logo centrado</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #fff;
    }

    header {
      background-color: #DA443F;
      height: 2vh;
      min-height: 100px;
      position: relative;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      margin-bottom: 10vh;
    }

    .menu-toggle {
      position: absolute;
      top: 1rem;
      left: 1rem;
      background: none;
      border: none;
      cursor: pointer;
      z-index: 11;
    }

    .menu-toggle img {
      width: 60px;
      height: 60px;
    }

    .logo {
      position: absolute;
      bottom: -12vh;
      border-radius: 50%;
    }

    .logo img {
      height: 25vh;
      width: 25vh;
      min-height: 100px;
      min-width: 100px;
      object-fit: cover;
      border-radius: 50%;
    }

        /* Overlay que oscurece todo cuando el menú está abierto */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.4);
      z-index: 900;
      display: none;
    }

    .overlay.active {
      display: block;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: -250px;
      width: 250px;
      height: 100vh;
      background-color: #dc4b3e;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
      transition: left 0.3s ease;
      z-index: 1000;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 0,0, 10px, 10px;
    }

    .sidebar.open {
      left: 0;
    }

    .menu-options, .bottom-buttons {
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
    }

    .menu-options button,
    .bottom-buttons button {
      background-color: rgb(194, 20, 20);
      color: #fff;
      border: none;
      padding: 0.9rem;
      width: 100%;
      font-size: 1rem;
      border-radius: 2px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      transition: background-color 0.2s;
    }

    .menu-options button:hover,
    .bottom-buttons button:hover {
      background-color: #f1f1f1;
    }

    .bottom-buttons img {
      width: 20px;
      height: 20px;
    }
  </style>
</head>
<body>

  <header>
    <button class="menu-toggle" onclick="toggleSidebar()">
      <img src="../Img/Hamburger_icon.svg.png" alt="Menú">
    </button>
    <div class="logo">
      <img src="../Img/LogoLetMeCook.png" alt="Logo Let Me Cook">
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
