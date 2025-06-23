<?php
// Inicia la sesión para trabajar con variables de sesión
session_start();

// Verifica que el usuario esté logueado y sea administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    // Si no es admin o no está logueado, redirige al login
    header("Location: ../login.php");
    exit(); // Detiene la ejecución del script
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Admin</title>
</head>
<body>

    <!-- Contenedor principal del contenido -->
    <main class="container">
        <!-- Encabezado principal de la página web -->
        <header>
            <h1>Bienvenido al Panel de Administración</h1>
        </header>

        <!-- Sección de información para el administrador -->
        <section>
            <p>Usa el menú para administrar productos y empleados.</p>
        </section>
    </main>

</body>
</html>
