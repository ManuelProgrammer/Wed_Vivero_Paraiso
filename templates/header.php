<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once(__DIR__ . '/../config.php');

// Validar sesión
$isLoggedIn = isset($_SESSION['usuario']);
$isAdmin = $isLoggedIn && $_SESSION['usuario']['rol'] === 'admin';

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vivero El Paraíso</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/style/style_index.css">
</head>
<body>
<header class="header-bg py-2">
  <div class="container d-flex justify-content-between align-items-center" id="header">
    <div class="d-flex align-items-center">
      <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">
        <img src="<?= BASE_URL ?>/multimedia/LOGO%20SOLO.png" alt="Logo" height="50">
      </a>
      <h4 class="ms-2 header-text">Vivero el Paraíso</h4>
    </div>
    <div class="form-container d-flex align-items-center">
      <form class="d-flex align-items-center" action="<?= BASE_URL ?>/views/productos.php" method="get">
      <input name="busqueda" class="form-control me-1 search-input" type="search" placeholder="Busca productos y mucho más..." required>
      <button class="btn btn-outline-success" type="submit">Buscar</button>
      </form>
    </div>

    <!-- Sesión -->
    <?php if (!$isLoggedIn): ?>
      <button class="btn btn-outline-success" onclick="window.location.href='<?= BASE_URL ?>/views/auth.php?mode=login'">
        Iniciar Sesión <i class='bx bxs-user' style="color:#004d00"></i>
      </button>
    <?php else: ?>
      <div class="dropdown">
        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
        <i class='bx bxs-user'></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="<?= BASE_URL ?>/views/usuario/perfil.php">Ver Perfil</a></li>
        <li><a class="dropdown-item" href="<?= BASE_URL ?>/views/usuario/wishlist.php">Lista de deseos ❤️</a></li>
        <li><a class="dropdown-item" href="<?= BASE_URL ?>/views/usuario/carrito.php">🛒 Mi Carrito</a></li>
        <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/includes/logout.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</header>

<!-- Menú de navegación -->
<div class="navbar-shadow">
  <nav class="navbar navbar-expand-lg navbar-dark highlight-bg py-3">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item mx-3">
            <a class="nav-link fs-5" href="<?= BASE_URL ?>/index.php">Inicio</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link fs-5" href="<?= BASE_URL ?>/views/productos.php">Productos</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link fs-5" href="<?= BASE_URL ?>/nosotros.php">Acerca de Nosotros</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link fs-5" href="<?= BASE_URL ?>/soport.php">Soporte</a>
          </li>
          <li class="nav-item mx-3">
            <a class="nav-link fs-5" href="<?= BASE_URL ?>/contac.php">Contacto</a>
          </li>
          <?php if ($isAdmin): ?>
            <li class="nav-item mx-3">
              <a class="nav-link fs-5" href="<?= BASE_URL ?>/views/admin/panel.php">Panel Admin</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</div>