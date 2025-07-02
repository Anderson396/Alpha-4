<?php
session_start();
require_once '../including/conexion.php';

// Obtener todos los productos
$query = "SELECT * FROM productos";
$result = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="../styles/styles2.css" />
</head>
<body>
<main class="container">
    <section aria-labelledby="productos-title">
        <h1 id="productos-title">Lista de Productos</h1>
        <article>
            <table>
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($producto = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td>
                                    <?php if (!empty($producto['imagen'])):
                                        $imagen = htmlspecialchars($producto['imagen']);
                                        $ruta_imagen = "../imagenes/" . $imagen;
                                        $version = file_exists($ruta_imagen) ? filemtime($ruta_imagen) : time(); // asegura que se actualice
                                    ?>
                                        <img src="<?php echo $ruta_imagen . '?v=' . $version; ?>" 
                                             alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>" 
                                             width="60" />
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn" href="editar_productos.php?id=<?php echo (int)$producto['id_producto']; ?>">Editar</a>
                                    <a class="btn" href="eliminar_prodctos.php?id=<?php echo (int)$producto['id_producto']; ?>" 
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No hay productos registrados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </article>
        <nav aria-label="Acciones">
            <a class="btn" href="agregar_productos.php">Agregar Producto</a>
        </nav>
    </section>
</main>
<footer>
    <p>&copy; 2025 Mi Tienda</p>
</footer>
</body>
</html>
