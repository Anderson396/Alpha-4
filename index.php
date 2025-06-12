<?php
// PÁGINA PRINCIPAL DE LA PASTELERÍA
session_start(); // inicia la sesión
require_once 'including/conexion.php'; // Conectar a la base de datos

$query = "SELECT * FROM productos"; // Consulta a la base de datos
$result = mysqli_query($conexion, $query); // Ejecuta la consulta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dulces Creaciones</title>
    <link rel="stylesheet" href="/pagina_pasteleria/styles/styles.css">
</head>
<body>

<main class="container">
    <h1>BIENVENIDOS A NUESTRA TIENDA VIRTUAL DE "DULCES CREACIONES"</h1>

    <!-- Botones de flecha para el carrusel -->
    <button class="flecha flecha-izquierda" onclick="moverCarrusel(-1)">&#10094;</button>
    <button class="flecha flecha-derecha" onclick="moverCarrusel(1)">&#10095;</button>

    <!-- Carrusel de productos -->
    <section class="productos">
        <form action="carrito.php" method="POST">
    <input type="hidden" name="id_producto" value="<?php echo (int)$producto['id_producto']; ?>">
    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
    <input type="hidden" name="precio" value="<?php echo htmlspecialchars($producto['precio']); ?>">
    <button type="submit" class="btn-agregar">Añadir al carrito</button>
</form>
        <?php while($producto = mysqli_fetch_assoc($result)): ?>
            <article class="producto" role="region" aria-labelledby="producto-<?php echo (int)$producto['id_producto']; ?>">
                <img src="/pagina_pasteleria/imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="300" height="300">

                <h2 id="producto-<?php echo (int)$producto['id_producto']; ?>">
                    <?php echo htmlspecialchars($producto['nombre']); ?>
                </h2>

                <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <p><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
            </article>
        <?php endwhile; ?>
    </section>
</main>

<footer role="contentinfo">
    <p>&copy; 2025 Tienda Virtual. Todos los derechos reservados.</p>
</footer>

<script>
function moverCarrusel(direccion) {
    const carrusel = document.querySelector('.productos');
    const anchoProducto = carrusel.querySelector('.producto').offsetWidth + 20; // 20px de separación
    carrusel.scrollBy({ left: direccion * anchoProducto, behavior: 'smooth' });
}
</script>

</body>
</html>

