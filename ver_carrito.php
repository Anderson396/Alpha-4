<?php
session_start();

// Verificamos si hay productos en el carrito
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../styles/Style2.CSS">
</head>
<body>
<main class="container carrito">
    <h2>🛒 Mi Carrito</h2>

    <?php if (empty($carrito)) : ?>
        <p>Tu carrito está vacío.</p>
    <?php else : ?>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_general = 0;
                foreach ($carrito as $id => $producto):
                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    $total_general += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <form method="post" action="eliminar_del_carrito.php">
                            <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: $<?php echo number_format($total_general, 2); ?></h3>
    <?php endif; ?>

    <nav>
        <a href="../index.php" class="btn">Seguir Comprando</a>
    </nav>
</main>

<footer>
    <h2>&copy; 2025 Mi Tienda</h2>
</footer>
</body>
</html>
