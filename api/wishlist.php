<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once '../includes/conexion.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT producto_id FROM wishlist WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = intval($row['producto_id']);
        }

        echo json_encode($ids);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $producto_id = intval($data['producto_id']);

        $stmt = $conn->prepare("INSERT IGNORE INTO wishlist (usuario_id, producto_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $usuario_id, $producto_id);
        $stmt->execute();

        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $producto_id = intval($data['producto_id']);

        $stmt = $conn->prepare("DELETE FROM wishlist WHERE usuario_id = ? AND producto_id = ?");
        $stmt->bind_param("ii", $usuario_id, $producto_id);
        $stmt->execute();

        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
