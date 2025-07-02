<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    die("Debes iniciar sesión para crear recetas.");
}

require_once('../Controlador/conexion.php');

$usuario_id = $_SESSION['usuario_id'];
$titulo = $conn->real_escape_string($_POST['titulo']);
$descripcion = $conn->real_escape_string($_POST['descripcion']);
$tiempo_preparacion = (int)$_POST['tiempo_preparacion'];
$dificultad = $_POST['dificultad'];
$pasos = $_POST['pasos'] ?? [];
$ingredientes = $_POST['ingredientes'] ?? [];

// Verificar penalización
$stmt = $conn->prepare("SELECT penalizado_hasta FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$pen = $stmt->get_result()->fetch_assoc();

if ($pen['penalizado_hasta'] && strtotime($pen['penalizado_hasta']) > time()) {
    $fecha_formateada = date('d/m/Y H:i', strtotime($pen['penalizado_hasta']));
    header("Location: ../vistas/crear_receta.php?error=Estás penalizado hasta el $fecha_formateada y no podés subir recetas.");
    exit();
}
    

// Validar imagen
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    die("Error al subir la imagen.");
}

$img_tmp = $_FILES['imagen']['tmp_name'];
$img_name = basename($_FILES['imagen']['name']);
$img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
$allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!in_array($img_ext, $allowed_exts)) {
    die("Tipo de imagen no permitido. Solo se permiten: jpg, jpeg, png, gif, webp.");
}

// Guardar imagen en carpeta Img/imgrecetas con nombre único
$img_new_name = uniqid('receta_') . '.' . $img_ext;
$img_dest = __DIR__ . '/../Img/imgrecetas/' . $img_new_name;

if (!move_uploaded_file($img_tmp, $img_dest)) {
    die("Error al guardar la imagen.");
}

// Insertar receta (sin dificultad)
$sql = "INSERT INTO recetas (titulo, imagen, descripcion, tiempo_preparacion, dificultad, usuario_id) 
        VALUES (?, ?, ?, ?, ?, ?)";


$stmt = $conn->prepare($sql);
$stmt->bind_param('sssisi', $titulo, $img_new_name, $descripcion, $tiempo_preparacion, $dificultad, $usuario_id);

if (!$stmt->execute()) {
    die("Error al insertar receta: " . $stmt->error);
}

$receta_id = $stmt->insert_id;
$stmt->close();

// Insertar pasos
$sql_paso = "INSERT INTO pasos (receta_id, numero_paso, descripcion) VALUES (?, ?, ?)";
$stmt_paso = $conn->prepare($sql_paso);

$numero_paso = 1;
foreach ($pasos as $descripcion_paso) {
    $desc = $conn->real_escape_string($descripcion_paso);
    $stmt_paso->bind_param('iis', $receta_id, $numero_paso, $desc);
    if (!$stmt_paso->execute()) {
        die("Error al insertar pasos: " . $stmt_paso->error);
    }
    $numero_paso++;
}
$stmt_paso->close();

// Insertar ingredientes
$sql_ing = "INSERT INTO receta_ingrediente (receta_id, ingrediente_id, cantidad, unidad) VALUES (?, ?, ?, ?)";
$stmt_ing = $conn->prepare($sql_ing);

$sql_unidad = "SELECT m.nombre AS unidad FROM ingredientes i LEFT JOIN mediciones m ON i.medicion_id = m.id WHERE i.id = ?";
$stmt_unidad = $conn->prepare($sql_unidad);

$ingredientes_ids = $_POST['ingredientes'] ?? [];
$cantidades = $_POST['cantidades'] ?? [];

for ($i = 0; $i < count($ingredientes_ids); $i++) {
    $ingrediente_id = (int)$ingredientes_ids[$i];
    $cantidad = (float)$cantidades[$i];

    $stmt_unidad->bind_param('i', $ingrediente_id);
    $stmt_unidad->execute();
    $stmt_unidad->store_result();
    $stmt_unidad->bind_result($unidad);
    $stmt_unidad->fetch();
    $stmt_unidad->free_result();

    if (!$unidad) {
        $unidad = '';
    }

    $stmt_ing->bind_param('iids', $receta_id, $ingrediente_id, $cantidad, $unidad);
    if (!$stmt_ing->execute()) {
        die("Error al insertar ingrediente: " . $stmt_ing->error);
    }
}


$stmt_unidad->close();

    header("Location: ../vistas/crear_receta.php?mensaje=Receta creada correctamente");
    exit();


