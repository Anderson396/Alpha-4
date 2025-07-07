<?php
session_start();
require_once '../including/conexion.php';

// Obtenemos todos los productos
$query = "SELECT id_producto, nombre, precio, imagen, stock FROM productos";
$result = $conexion->query($query);

// Mensajes de estado
$status_message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success_edit') {
        $status_message = '<p class="success-message">✔ Producto actualizado correctamente.</p>';
    } elseif ($_GET['status'] === 'success_add') {
        $status_message = '<p class="success-message">✔ Producto agregado correctamente.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="../styles/styles3.CSS" />
    <style>
        .add-to-cart-cell {
            text-align: center;
            vertical-align: middle;
        }
        .add-to-cart-cell form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .add-to-cart-cell input[type="number"] {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            text-align: center;
        }
       
       
        /* ESTILOS DEL ENCABEZADO (HEADER) ❤❤❤❤❤❤❤*/

header {
    width: 100%;
    background: radial-gradient(circle,rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
    padding: 20px 0; /* Espacio arriba y abajo del contenido del header */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra sutil debajo del header */
    display: flex; /* Usamos Flexbox para organizar el contenido */
    flex-direction: column; /* Apila los elementos (logo y nav) verticalmente */
    align-items: center; /* Centra los elementos hijos horizontalmente */
    justify-content: center; /* Centra los elementos hijos verticalmente si hay espacio */
}

/* Contenedor del logo y el título */
.logo {
    text-align: center; /* Centra la imagen y el texto dentro de este div */
    margin-bottom: 20px; /* Espacio entre el área del logo/título y la navegación */
}

/* Estilo para la imagen del logo */
.site-logo {
    height: 150px; /* Altura fija para el logo como en tu imagen */
    width: auto; /* Mantiene la proporción de la imagen */
    display: block; /* Hace que la imagen se comporte como un bloque para que el h2 vaya debajo */
    margin: 0 auto 5px auto; /* Centra la imagen horizontalmente y le da un pequeño margen inferior para separar del título */
}

/* Estilo para el título "Dulces Creaciones" */
.site-title {
    color:rgb(214, 45, 129); /* Color rosa fuerte */
    font-family: 'Arial', sans-serif; /* Puedes cambiar esto por una fuente más artística si la importas */
    font-size: 2.5em; /* Tamaño del texto del título */
    margin: 0; /* Elimina los márgenes predeterminados del h2 */
    line-height: 1.2; /* Ajusta el espaciado de línea si la fuente es muy grande */
}

/* Estilos para la navegación principal */
.main-nav ul {
    list-style: none; /* Elimina los puntos de la lista */
    padding: 0;
    margin: 0;
    display: flex; /* Hace que los elementos de la lista se muestren en fila */
    justify-content: center; /* Centra los elementos de la navegación horizontalmente */
    flex-wrap: wrap; /* Permite que los elementos se envuelvan a la siguiente línea en pantallas pequeñas */
    gap: 20px; /* Espacio entre cada elemento de la navegación (Inicio, Mi Perfil, etc.) */
}

.main-nav li {
    /* No necesita margen lateral si usas 'gap' en el 'ul' */
}

.main-nav a {
    text-decoration: none; /* Elimina el subrayado de los enlaces */
    color:rgb(230, 24, 127); /* Color rosa fuerte para los enlaces */
    font-weight: bold;
    padding: 8px 15px; /* Espacio interno de los enlaces para que parezcan botones */
    transition: color 0.3s ease, background-color 0.3s ease; /* Transición suave para efectos hover */
    border-radius: 5px; /* Bordes ligeramente redondeados */
}

.main-nav a:hover {
    color: #ffffff; /* Texto blanco al pasar el ratón */
    background-color: #ff1493; /* Fondo rosa más oscuro al pasar el ratón */
}

/* Media Queries para responsividad (ajustes para pantallas más pequeñas) */
@media (max-width: 768px) {
    header {
        padding: 15px 0;
    }

    .site-logo {
        height: 100px; /* Reduce el tamaño del logo en pantallas pequeñas */
        margin-bottom: 5px;
    }

    .site-title {
        font-size: 1.8em; /* Reduce el tamaño del título en pantallas pequeñas */
    }

    .main-nav ul {
        flex-direction: column; /* Apila los elementos de la navegación verticalmente */
        align-items: center; /* Centra los enlaces apilados */
        gap: 10px; /* Menos espacio vertical entre enlaces apilados */
        margin-top: 10px; /* Un poco de espacio extra arriba de la nav cuando está apilada */
    }

    .main-nav a {
        width: calc(100% - 30px); /* Asegura que el enlace ocupe casi todo el ancho disponible */
        text-align: center; /* Centra el texto dentro del enlace */
        max-width: 200px; /* Limita el ancho máximo para que no se extienda demasiado */
    }
}
    </style>
</head>
<body>

<header>
    <div class="logo">
    <img src="/pagina_pasteleria/imagenes/logo.jpg.jpeg" alt="Dulces Creaciones Logo" class="site-logo">
    <h2 class="site-title">Dulces Creaciones</h2>
</div>
    <nav class="main-nav">
        <ul>
            <li><a href="/pagina_pasteleria/index.php">Inicio</a></li>
            <li><a href="/pagina_pasteleria/carrito/carrito.php">Tu Carrito</a></li>
            <li><a href="/pagina_pasteleria/login/logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
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
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Stock</th>
                        <th>Acciones de Admin</th>
                        <th>Añadir al Carrito</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($producto = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td>
                                    <?php if (!empty($producto['imagen'])): ?>
                                        <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>" width="60">
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
                        <tr><td colspan="6">No hay productos registrados.</td></tr>
                    <?php endif; ?>
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
