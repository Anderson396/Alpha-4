<?php
//PÁGINA PRINCIPAL DE LA PASTELERÍA 
session_start(); //inicia la sesión

require_once 'including/conexion.php'; // Conectar a la base de datos

$query = "SELECT * FROM productos"; // Consulta a la base de datos
$result = mysqli_query($conexion, $query); // Ejecuta la consulta
?>

<main class="container">
    <h1>BIENVENIDOS A NUESTRA TIENDA VIRTUAL DE "DULCES CREACIONES"</h1>

<!-- Sección para guardar a todos los productos -->
 <sectoion class="productos">
    <?php while($producto = mysqli_fetch_assoc($result)): ?>
    <!-- Presentamos los productos como un artículo independiente -->
            <article class="producto" role="region" aria-labelledby="producto-<?php echo (int)$producto['id_producto']; ?>">
                <!-- Imagen que representa a el producto -->
                <img src="/pagina_pasteleria/imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="300" height="300">

                <!-- Presentamos el producto con su id -->
                <h2 id="producto-<?php echo (int)$producto['id_producto']; ?>">
                    <?php echo htmlspecialchars($producto['nombre']); ?>
                </h2>

                 <!-- Descripción breve del producto -->
                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>

                <!-- Precio del producto, destacado -->
                <p><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>

        <?php endwhile; ?>
    </section>
</main>

<footer role="contentinfo">
    <p>&copy; 2025 Tienda Virtual. Todos los derechos reservados.</p>
</footer>

</body>
</html>