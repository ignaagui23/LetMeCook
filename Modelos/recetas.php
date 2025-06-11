<?php
require_once('../Controlador/conexion.php');

function obtenerRecetas($orden = 'recientes') {
    $orderClause = match ($orden) {
        'alfabetico' => 'r.titulo ASC',
        'tiempo' => 'r.tiempo_preparacion ASC',
        'ingredientes' => 'r.cantidad_ingredientes ASC',
        default => 'r.id DESC'
    };

    $sql = "SELECT r.id, r.titulo, r.descripcion, r.imagen, r.tiempo_preparacion,
                   COUNT(ri.ingrediente_id) AS cantidad_ingredientes,
                   u.username
            FROM recetas r
            LEFT JOIN receta_ingrediente ri ON r.id = ri.receta_id
            JOIN usuarios u ON r.usuario_id = u.id
            GROUP BY r.id
            ORDER BY $orderClause";

    global $conn;
    return $conn->query($sql);
}
