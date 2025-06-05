<?php
// Inicia la sesión si aún no ha sido iniciada.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Recupera el usuario y el rol desde la sesión, si existen.
$user = $_SESSION['user'] ?? null;
$rol = $_SESSION['rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tienda Virtual</title>
    <!-- Enlace al archivo de estilos CSS -->
    <link rel="stylesheet" href="/tienda_virtual/styles/style.css" />
</head>
<body>
    <header>
        <nav>
            <ul class="menu">
                <!-- Enlace a la página principal -->
                <li><a href="/index.php">Inicio</a></li>

                <!-- Enlace al carrito de compras -->
                <li><a href="/tienda_virtual/carrito/carrito.php">Carrito</a></li>
        </nav>
    </header>
