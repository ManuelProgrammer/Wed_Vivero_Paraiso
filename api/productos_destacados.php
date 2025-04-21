<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


require_once '../includes/conexion.php';
header('Content-Type: application/json');

// Obtener productos destacados manualmente o por ventas
$sql = "SELECT * FROM producto WHERE destacado = 1 ORDER BY id DESC LIMIT 6";
$resultado = $conn->query($sql);

$productos = [];
while ($row = $resultado->fetch_assoc()) {
  $productos[] = $row;
}

echo json_encode($productos);
