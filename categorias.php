<?php
session_start();
require_once '../including/conexion.php';

// Obtener todas las categorías
$categorias_query = "SELECT * FROM categoria";
$categorias_result = $conexion->query($categorias_query);

// Obtener el ID de la categoría seleccionada (si existe)
$categoria_id = isset($_GET['id_categoria']) ? (int)$_GET['id_categoria'] : 0;

if ($categoria_id) {
    // Obtener nombre de la categoría
    $stmt_cat = $conexion->prepare("SELECT nombre FROM categoria WHERE id_categoria = ?");
    $stmt_cat->bind_param("i", $categoria_id);
    $stmt_cat->execute();
    $stmt_cat->bind_result($categoria_nombre);
    $stmt_cat->fetch();
    $stmt_cat->close();

    // Obtener productos de esa categoría
    $stmt_prod = $conexion->prepare("SELECT * FROM productos WHERE id_categoria = ?");
    $stmt_prod->bind_param("i", $categoria_id);
    $stmt_prod->execute();
    $result_prod = $stmt_prod->get_result();
    while ($row = $result_prod->fetch_assoc()) {
        $productos[] = $row;
    }
    $stmt_prod->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías de Productos</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <main class="container">
        <h1>Selecciona una Categoría</h1>

        <!-- Mostrar botones/enlaces de categorías -->
        <nav>
            <?php while ($categoria = $categorias_result->fetch_assoc()): ?>
                <a class="btn" href="?id_categoria=<?php echo (int)$categoria['id_categoria']; ?>">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </a>
            <?php endwhile; ?>
        </nav>

        <!-- Mostrar productos si se seleccionó una categoría -->
        <?php if ($categoria_id > 0): ?>
            <h2>Productos de la categoría: <?php echo htmlspecialchars($categoria_nombre); ?></h2>
            <div class="productos">
                <?php foreach ($productos as $producto): ?>
                    <article class="producto">
                        <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <p><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
