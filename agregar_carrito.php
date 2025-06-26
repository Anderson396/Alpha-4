<?php
session_start();
require_once '../including/conexion.php';

// Agregar producto al carrito si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    $id = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    // Si ya está en el carrito, solo aumentar cantidad
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$id] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
    }

    $mensaje = "Producto agregado al carrito.";
}

// Obtener productos de la base de datos
$query = "SELECT * FROM productos";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda - Productos</title>
    <link rel="stylesheet" href="../styles/Style2.CSS">
</head>
<body>
<main class="container">
    <h2>Productos Disponibles</h2>

    <?php if (isset($mensaje)) : ?>
        <p style="color: green;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <section class="productos">
        <?php while ($producto = $resultado->fetch_assoc()) : ?>
            <article class="producto">
                <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" width="150" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
                
                <form method="post" action="">
                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad" value="1" min="1" max="<?php  ?>">
                    <button type="submit" name="agregar_carrito">Agregar al carrito</button>

                    <?php while ($producto = $resultado->fetch_assoc()) : ?>
    <article class="producto">
        <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" width="150">
        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
        <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>

        <!-- Botón de ver más --> 
        <a href="ver_carrito.php?id=<?php echo $producto['id_producto']; ?>" class="btn">Ver más</a>

        <!-- Botón para agregar al carrito -->
        <form method="post" action="">
            <!-- ...inputs para agregar al carrito... -->
        </form>
    </article>
<?php endwhile; ?>
                </form>
            </article>
        <?php endwhile; ?>
    </section>

    <nav>
        <a href="ver-carrito.php" class="btn">Ver Carrito</a>
    </nav>
</main>

<footer>
    <h2>&copy; 2025 Mi Tienda</h2>
</footer>
</body>
</html>
