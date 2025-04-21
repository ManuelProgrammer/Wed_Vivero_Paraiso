<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once '../includes/conexion.php';

$sql = "SELECT id, nombre, grupo, subGrupo, descripcion, precio, stock, imagen, destacado
        FROM productos
        WHERE stock > 0
        ORDER BY destacado DESC, nombre ASC";

$result = $conn->query($sql);

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);
exit;
