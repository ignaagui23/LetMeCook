<?php
session_start();
$_SESSION['usuario_id'] = 11; 
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Crear receta</title>
    <link rel="stylesheet" href="../Estilos/styles.css">
    <link rel="stylesheet" href="../Estilos/style_crear.css">

    <script>
function agregarPaso() {
  const contenedor = document.getElementById('pasos-container');
  const index = contenedor.children.length + 1;

  const div = document.createElement('div');
  div.className = 'campo-paso';

  const label = document.createElement('label');
  label.textContent = `Paso ${index}:`;

  const textarea = document.createElement('textarea');
  textarea.name = 'pasos[]';
  textarea.rows = 3;
  textarea.required = true;

  const btnEliminar = document.createElement('button');
  btnEliminar.type = 'button';
  btnEliminar.textContent = '🗑️ Eliminar paso';
  btnEliminar.className = 'btn btn-eliminar';
  btnEliminar.onclick = () => contenedor.removeChild(div);

  div.appendChild(label);
  div.appendChild(document.createElement('br'));
  div.appendChild(textarea);
  div.appendChild(btnEliminar);

  contenedor.appendChild(div);
}

function agregarIngrediente() {
  const container = document.getElementById("ingredientes-container");

  const div = document.createElement("div");
  div.classList.add("tarjeta-ingrediente"); // borde rojo, etc.

    const label = document.createElement('label');
  label.textContent = `Ingrediente: `;

  // Select de ingredientes
  const select = document.createElement("select");
  select.name = "ingredientes[]";
  select.innerHTML = document.getElementById("ingrediente-select-template").innerHTML;

  // Input de cantidad
  const inputCantidad = document.createElement("input");
  inputCantidad.type = "number";
  inputCantidad.name = "cantidades[]";
  inputCantidad.placeholder = "Cantidad";

  // Unidad al lado
  const unidadSpan = document.createElement("span");
  unidadSpan.classList.add("unidad-span");
  unidadSpan.style.marginLeft = "10px";

  // Contenedor combinado cantidad + unidad
  const filaCantidad = document.createElement("div");
  filaCantidad.className = "fila-ingrediente";
  filaCantidad.appendChild(inputCantidad);
  filaCantidad.appendChild(unidadSpan);

  // Eliminar botón dentro de la tarjeta
  const btnEliminar = document.createElement("button");
  btnEliminar.type = "button";
  btnEliminar.textContent = "Eliminar ingrediente🗑️";
  btnEliminar.classList.add("btn", "btn-eliminar");
  btnEliminar.onclick = () => container.removeChild(div);

  // Armar tarjeta
  div.appendChild(select);
  div.appendChild(filaCantidad);
  div.appendChild(btnEliminar);

  container.appendChild(div);

  // Actualizar unidad al cambiar el select
  select.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    unidadSpan.textContent = selectedOption.getAttribute("data-unidad") || "";
  });

  // Lanzar el evento para que cargue la unidad al instante
  select.dispatchEvent(new Event("change"));
}


        window.onload = function() {
            agregarPaso();
            agregarIngrediente();
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
<main>
    <h2>Crear una nueva receta</h2>

    <form action="../Modelos/crear_receta.php" method="post" enctype="multipart/form-data" class="contenedor-horizontal">

        <section class="formulario" class="datos-principales">
            <h3>Datos principales</h3>
            <hr>
            <label for="titulo">Nombre de la receta:</label>
            <input type="text" name="titulo" id="titulo" required>

            <label for="imagen">Imagen de la receta:</label>
            <input type="file" name="imagen" id="imagen" accept="image/*" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" rows="4" required></textarea>

            <label for="tiempo">Tiempo estimado (minutos):</label>
            <input type="number" name="tiempo_preparacion" id="tiempo" min="1" required>
                <button type="submit" class="btn btn-crear">Crear Receta</button>
            
        </section>

        <section class="formulario tarjeta-scroll">
            <h3>Pasos</h3>
                        <hr>

            <div id="pasos-container"></div>
            <button type="button" class="btn btn-agregar" onclick="agregarPaso()">Agregar paso</button>
        </section>

        <section class="formulario tarjeta-scroll">
            <h3>Ingredientes</h3>
                        <hr>

            <div id="ingredientes-container"></div>
            <button type="button" class="btn btn-agregar" onclick="agregarIngrediente()">Agregar ingrediente</button>

        </section>

    </form>

      </main>
    <select id="ingrediente-select-template" style="display:none;">
        <?php
        require_once('../Controlador/conexion.php');
        $sql = "SELECT id, nombre, medicion_id FROM ingredientes ORDER BY nombre";
        $result = $conn->query($sql);
        $mediciones = [];
        if ($result) {
            $med_query = $conn->query("SELECT id, nombre FROM mediciones");
            while ($m = $med_query->fetch_assoc()) {
                $mediciones[$m['id']] = $m['nombre'];
            }
            while ($row = $result->fetch_assoc()) {
                $unidad = isset($mediciones[$row['medicion_id']]) ? $mediciones[$row['medicion_id']] : '';
                echo "<option value='{$row['id']}' data-unidad='{$unidad}'>" . htmlspecialchars($row['nombre']) . "</option>";
            }
        }
        ?>
    </select>

</body>
</html>
