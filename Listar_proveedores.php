<?php
session_start();
require_once '../including/conexion.php';

// Consulta a la base de datos
$query = "SELECT * FROM proveedores";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Proveedores</title>
    <link href="https://fonts.googleapis.com/css2?family=Savate&display=swap" rel="stylesheet"> <!-- Fuente bonita -->
    <link rel="stylesheet" href="../styles/Style4.css"> <!-- CSS externo -->
</head>
<body>
    <main class="contenedor">
        <h1>Listado de Proveedores</h1>

        <div class="encabezado-lista">
            <h2><span class="encabezado izquierda">Proveedor</span></h2>
            <h2><span class="encabezado derecha">Producto</span></h2>
        </div>

        <?php if ($resultado->num_rows > 0): ?>
            <?php while($proveedor = $resultado->fetch_assoc()): ?>
                <div class="fila-proveedor">
                    <h3><span class="izquierda"><?php echo htmlspecialchars($proveedor['nombre']); ?></span></h3>
                    <h3><span class="derecha"><?php echo htmlspecialchars($proveedor['producto']); ?></span></h3>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay proveedores registrados.</p>
        <?php endif; ?>
    </main>
</body>
</html>
