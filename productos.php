<?php
session_start();
require_once '../including/conexion.php';

// Verificamos que el usuario sea administrador para dar acceso a esta página
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Obtenemos todos los productos de la base de datos
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

<!-- HEADER: Contiene la barra de navegación común en todas las páginas.
    Es útil para que los usuarios puedan moverse fácilmente entre secciones. -->
<header>
    <?php include('../including/navbar.php'); ?>
</header>

<!-- MAIN: El contenido principal visible en la página.
    Aquí agrupamos la lista de productos para que sea el foco principal. -->
<main class="container">

    <!-- SECTION: Agrupa la lista de productos.
     Usamos aria-labelledby para mejorar la accesibilidad y enlazarlo con el título. -->
        <section aria-labelledby="productos-title">

    <!-- H1: Título principal de la página o sección.
     El id se enlaza con aria-labelledby para lectores de pantalla. -->
            <h1 id="productos-title">Lista de Productos</h1>

        <!-- ARTICLE: Representa un contenido autónomo dentro del sitio.
        Aquí utilizamos para contener la tabla de productos. -->
            <article>
                <table>
                    <thead>
                        <tr>
                            <!-- scope="col": Define que estas celdas son encabezados de columna,
                              mejorando la navegación para lectores de pantalla. -->
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
                                    <!-- htmlspecialchars previene vulnerabilidades XSS -->
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                    <td><?php echo number_format($producto['precio'], 2); ?></td>
                                    <td>
                                        <?php if (!empty($producto['imagen'])): ?>
                                            <!-- Alt descriptivo para accesibilidad para que muestre la imagen del producto -->
                                            <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                                 alt="Imagen del producto <?php echo htmlspecialchars($producto['nombre']); ?>" 
                                                 width="60" />
                                        <?php else: ?>
                                            Sin imagen
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <!-- Enlaces para modificar y eliminar con confirmación -->
                                        <a class="btn" href="modificar_producto.php?id=<?php echo (int)$producto['id_producto']; ?>">Modificar</a>
                                        <a class="btn" href="eliminar_producto.php?id=<?php echo (int)$producto['id_producto']; ?>" 
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

            <!-- NAV: Sección dedicada a navegación o acciones relacionadas.
              Aquí colocamos el botón para agregar un nuevo producto. -->
            <nav aria-label="Acciones">
                <a class="btn" href="agregar_producto.php">Agregar Producto</a>
            </nav>

        </section>
    </main>

    <!-- FOOTER: Pie de página con información adicional o legal -->
    <footer>
        <p>&copy; 2025 Mi Tienda</p>
    </footer>

</body>
</html>
