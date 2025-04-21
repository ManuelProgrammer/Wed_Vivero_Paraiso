<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
$host = "sql309.infinityfree.com";
$usuario = "if0_38795060";
$clave = "Ma2004nu28el"; // ✅ Contraseña corregida
$bd = "if0_38795060_tienda_virtual";
$puerto = 3306;

$conn = new mysqli($host, $usuario, $clave, $bd, $puerto);

// Verifica la conexión
if ($conn->connect_error) {
    die("❌ Error al conectar con la base de datos: " . $conn->connect_error);
}

// echo "✅ Conexión exitosa"; // (Usa esto solo para pruebas puntuales)
?>
