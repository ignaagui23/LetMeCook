<?php
if(!empty($_POST["Enviar"])) {
    $Nombre = $_POST["Nombre"];
    $Correo = $_POST["Correo"];  // Asegúrate de que aquí sea 'Correo' y no 'email' o algo diferente
    $Telefono = $_POST["Telefono"];
    $Mensaje = $_POST["Mensaje"];
    
    // Asegúrate de que la conexión a la base de datos esté abierta
    include("conexion.php");  // Asegúrate de incluir el archivo de conexión

    // Consulta SQL para insertar los datos en la tabla 'contacto'
    $Consulta_Sql = "INSERT INTO `contacto` (nombre, email, telefono, mensaje) 
                     VALUES ('$Nombre', '$Correo', '$Telefono', '$Mensaje')";
    
    // Ejecuta la consulta SQL
    $Validacion = mysqli_query($Direccion, $Consulta_Sql);
    
    // Verifica si la consulta se ejecutó correctamente
    if ($Validacion) {
        echo "Se envió tu mensaje correctamente";
    } else {
        echo "No se envió tu mensaje. Error: " . mysqli_error($Direccion);  // Muestra el error si ocurre
    }
}
?>
