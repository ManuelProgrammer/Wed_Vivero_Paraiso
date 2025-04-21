<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "tienda_virtual";
$puerto = 3306; // Asegúrate que sea tu puerto real de MySQL

$conn = new mysqli($host, $usuario, $clave, $base_datos, $puerto);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

