<?php
// PÁGINA PRINCIPAL DE LA PASTELERÍA 
session_start(); // Inicia la sesión

require_once 'including/conexion.php'; // Conectar a la base de datos

include 'including/header.php'; // Encabezado común con <head> y menú de navegación

$query = "SELECT * FROM productos"; // Consulta a la base de datos
$result = mysqli_query($conexion, $query); // Ejecuta la consulta
?>

<main class="container">
    <h1>BIENVENIDOS A NUESTRA TIENDA VIRTUAL DE "DULCES CREACIONES"</h1>
    <link rel="stylesheet" href="/pagina_pasteleria/styles/styles.css" />

    <!-- Sección para mostrar todos los productos -->
    <section class="productos">
        <?php while($producto = mysqli_fetch_assoc($result)): ?>
            <!-- Producto individual -->
            <article class="producto" role="region" aria-labelledby="producto-<?php echo (int)$producto['id_producto']; ?>">
                
                <!-- Imagen del producto -->
                <img src="/pagina_pasteleria/imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="300" height="300">

                <!-- Nombre del producto -->
                <h2 id="producto-<?php echo (int)$producto['id_producto']; ?>">
                    <?php echo htmlspecialchars($producto['nombre']); ?>
                </h2>

                <!-- Descripción -->
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>

                <!-- Precio -->
                <p><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>

                <!-- Botón Ver más -->
                <a href="carrito/detalle_producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn">Ver más</a>
            </article>
        <?php endwhile; ?>
    </section>
</main>

<footer role="contentinfo">
    <p>&copy; 2025 Tienda Virtual. Todos los derechos reservados.</p>
</footer>

</body>
</html>
