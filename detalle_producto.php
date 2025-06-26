<?php
session_start();
require_once '../including/conexion.php';

// Mensajes por defecto
$mensaje = null;
$error = null;

// Validar que venga el ID del producto por GET
if (!isset($_GET['id'])) {
    echo "Producto no válido.";
    exit;
}

$id = $_GET['id'];

// Consultar producto desde la base de datos
$query = "SELECT * FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

// Validar que el producto exista
if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}

// Verificamos si el campo 'stock' existe y tiene valor
$stock_disponible = isset($producto['stock']) ? (int)$producto['stock'] : 0;

// Procesar envío del formulario para agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    $cantidad = (int) $_POST['cantidad'];

    if ($cantidad > $stock_disponible) {
        $error = "La cantidad solicitada excede el stock disponible.";
    } else {
        $id = $_POST['id_producto'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];

        // Crear carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Agregar o actualizar cantidad del producto en el carrito
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
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($producto['nombre']); ?></title>
    <link rel="stylesheet" href="styles/Style2.CSS">
</head>
<body>
<main class="container">
    <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>

    <?php if ($mensaje): ?>
        <p style="color: green;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <section class="detalle">
        <img src="imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" width="250">
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
        <p><strong>Disponible:</strong> <?php echo $stock_disponible; ?></p>

        <?php if ($stock_disponible > 0): ?>
        <form method="post" action="">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
            <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" value="1" min="1" max="<?php echo $stock_disponible; ?>" required>
            <button type="submit" name="agregar_carrito">Agregar al carrito</button>
        </form>
        <?php else: ?>
            <p style="color: red;"><strong>Este producto no tiene stock disponible.</strong></p>
        <?php endif; ?>
    </section>

    <nav>
        <a href="../index.php" class="btn">Volver a inicio</a>
        <a href="ver_carrito.php" class="btn">Ver carrito</a>
    </nav>
</main>

<footer>
    <h2>&copy; 2025 Mi Tienda</h2>
</footer>
</body>
</html>
