<?php
session_start();

// === CORS Headers ===
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

header('Content-Type: application/json');
require_once '../includes/conexion.php';

// 🔐 Verificar permisos de admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
  http_response_code(403);
  echo json_encode(['error' => 'Acceso denegado']);
  exit;
}

// 📁 Ruta de almacenamiento de imágenes
$upload_dir = realpath(__DIR__ . '/../multimedia');
if (!file_exists($upload_dir)) {
  mkdir($upload_dir, 0775, true);
}

// 📥 Procesar datos
$accion = $_POST['accion'] ?? null;

if ($accion === 'eliminar') {
  $id = $_POST['id'] ?? null;
  if ($id) {
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success]);
  } else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
  }
  exit;
}

if ($accion === 'crear' || $accion === 'actualizar') {
  $id           = $_POST['id'] ?? null;
  $nombre       = $_POST['nombre'] ?? '';
  $grupo        = $_POST['grupo'] ?? '';
  $subGrupo     = $_POST['subGrupo'] ?? '';
  $descripcion  = $_POST['descripcion'] ?? '';
  $destacado    = isset($_POST['destacado']) ? intval($_POST['destacado']) : 0;
  $precio       = floatval($_POST['precio'] ?? 0);
  $stock        = intval($_POST['stock'] ?? 0);
  $nombreImagen = null;

  // Manejo de imagen
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreOriginal = basename($_FILES['imagen']['name']);
    $ext = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nombreImagen = uniqid('img_', true) . '.' . strtolower($ext);
    $rutaDestino = $upload_dir . '/' . $nombreImagen;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
      echo json_encode(['success' => false, 'error' => 'Error al guardar la imagen']);
      exit;
    }
  }

  if ($accion === 'crear') {
    $stmt = $conn->prepare("INSERT INTO productos (nombre, grupo, subGrupo, descripcion, precio, stock, imagen, destacado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdisi", $nombre, $grupo, $subGrupo, $descripcion, $precio, $stock, $nombreImagen, $destacado);
  } else if ($accion === 'actualizar' && $id) {
    if ($nombreImagen) {
      $stmt = $conn->prepare("UPDATE productos SET nombre=?, grupo=?, subGrupo=?, descripcion=?, precio=?, stock=?, imagen=?, destacado=? WHERE id=?");
      $stmt->bind_param("ssssdisii", $nombre, $grupo, $subGrupo, $descripcion, $precio, $stock, $nombreImagen, $destacado, $id);
    } else {
      $stmt = $conn->prepare("UPDATE productos SET nombre=?, grupo=?, subGrupo=?, descripcion=?, precio=?, stock=?, destacado=? WHERE id=?");
      $stmt->bind_param("ssssdisi", $nombre, $grupo, $subGrupo, $descripcion, $precio, $stock, $destacado, $id);
    }
  }

  $success = $stmt->execute();
  echo json_encode(['success' => $success]);
  exit;
}

// 📤 Listar productos (GET vía fetch sin body no llega aquí, pero puedes usar otro endpoint si querés)
echo json_encode(['error' => 'Acción inválida o método incorrecto']);
exit;
