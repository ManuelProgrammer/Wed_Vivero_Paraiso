<?php
session_start();

// === CORS Headers ===
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json');

require_once '../includes/conexion.php';

// ✅ Verificación de permisos
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

// 📦 Listar usuarios (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id, nombre, correo, rol, numeroTelefono, activo FROM usuario ORDER BY id DESC";
    $result = $conn->query($sql);

    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }

    echo json_encode($usuarios);
    exit;
}

// 🔄 Crear, actualizar o eliminar usuario (POST con acción)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $accion = $data['accion'] ?? '';
    $id = $data['id'] ?? null;
    $nombre = $data['nombre'] ?? '';
    $correo = $data['correo'] ?? '';
    $telefono = $data['numeroTelefono'] ?? null;
    $rol = $data['rol'] ?? 'cliente';
    $activo = $data['activo'] ?? 1;

    if ($accion === 'actualizar') {
        if ($id && $nombre && $correo && $rol !== '') {
            $stmt = $conn->prepare("UPDATE usuario SET nombre=?, correo=?, numeroTelefono=?, rol=?, activo=? WHERE id=?");
            $stmt->bind_param("ssssii", $nombre, $correo, $telefono, $rol, $activo, $id);
            $success = $stmt->execute();
            echo json_encode(['success' => $success]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            exit;
        }
    }

    if ($accion === 'eliminar') {
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM usuario WHERE id = ?");
            $stmt->bind_param("i", $id);
            $success = $stmt->execute();
            echo json_encode(['success' => $success]);
            exit;
        } else {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
            exit;
        }
    }

    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    exit;
}
