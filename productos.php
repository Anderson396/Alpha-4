<?php
session_start();
require_once '../including/conexion.php';

// Verificamos que el usuario sea administrador para dar acceso a esta página
// Si esta sección está comentada, significa que no estás aplicando la restricción de rol.
// if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
//     header("Location: ../index.php"); // Asegúrate de que esta sea la ruta correcta de redirección
//     exit();
// }

// Obtenemos todos los productos de la base de datos
// Asegúrate de que tu tabla 'productos' ahora tenga las columnas 'stock' y 'id_categoria'
$query = "SELECT id_producto, nombre, precio, imagen, stock FROM productos"; // Selecciona stock
$result = $conexion->query($query);

// Mensajes de estado (si vienen de editar_productos.php o agregar_productos.php)
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success_edit') {
        $status_message = '<p class="success-message">✔️ Producto actualizado correctamente.</p>';
    } elseif ($_GET['status'] === 'success_add') {
        $status_message = '<p class="success-message">✔️ Producto agregado correctamente.</p>';
    }
    // Puedes añadir más mensajes aquí para eliminar_productos.php si lo deseas
    // if ($_GET['status'] === 'success_delete') {
    //     $status_message = '<p class="success-message">✔️ Producto eliminado correctamente.</p>';
    // }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="../styles/style3.CSS" /><br>
        <style>
        /* Estilos adicionales para la columna de "Añadir al Carrito" si no están en Styles3.CSS */
        .add-to-cart-cell {
            text-align: center;
            vertical-align: middle;
        }
        .add-to-cart-cell form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px; /* Espacio entre el input y el botón */
        }
        .add-to-cart-cell input[type="number"] {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <?php include('../including/header.php'); ?>
</header>

<main class="container">
    <section aria-labelledby="productos-title">
        <h1 id="productos-title">Lista de Productos</h1>

        <?php if ($status_message): ?>
            <?php echo $status_message; ?>
        <?php endif; ?>

        <article>
            <table>
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Acciones de Admin</th> <th scope="col">Añadir al Carrito</th> </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($producto = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td>
                                    <?php if (!empty($producto['imagen'])): ?>
                                        <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>"
                                            alt="Imagen del producto <?php echo htmlspecialchars($producto['nombre']); ?>"
                                            width="60" />
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                                <td class="actions-cell">
                                    <a class="btn" href="editar_productos.php?id=<?php echo (int)$producto['id_producto']; ?>">Editar</a>
                                    <a class="btn delete" href="eliminar_productos.php?id=<?php echo (int)$producto['id_producto']; ?>"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                                </td>
                                <td class="add-to-cart-cell">
                                    <form action="../carrito/carrito.php" method="post">
                                        <input type="hidden" name="action" value="add_to_cart">
                                        <input type="hidden" name="id_producto" value="<?php echo (int)$producto['id_producto']; ?>">
                                        <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                        <input type="hidden" name="precio" value="<?php echo (float)$producto['precio']; ?>">
                                        <input type="hidden" name="imagen" value="<?php echo htmlspecialchars($producto['imagen']); ?>">
                                        <input type="number" name="cantidad" value="1" min="1" <?php echo ($producto['stock'] <= 0) ? 'disabled' : ''; ?>>
                                        <button type="submit" class="btn" <?php echo ($producto['stock'] <= 0) ? 'disabled' : ''; ?>>
                                            <?php echo ($producto['stock'] <= 0) ? 'Sin Stock' : 'Añadir al Carrito'; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No hay productos registrados.</td></tr> <?php endif; ?>
                </tbody>
            </table>
        </article><br>

        <nav aria-label="Acciones">
            <a href="agregar_productos.php" class="btn">Agregar productos</a>
            <a href="../carrito/carrito.php" class="btn">Ver Carrito</a>
        </nav>

    </section>
</main>

<footer>
    <p>&copy; 2025 Mi Tienda</p>
</footer>

</body>
</html>
