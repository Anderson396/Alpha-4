<?php
// Iniciamos sesión para poder usar las variables de sesión
session_start();

// Conectamos a la base de datos
require_once '../including/conexion.php';

// Verificamos si el usuario está registrado y si es administrador
// if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
//    // Y si esto no es así, lo redirigiremos a la página de inicio
//    header("Location: ../index.php");
//    exit; // Terminamos el script después de redirigir
// }

// Obtenemos los datos del formulario mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tomamos los datos enviados del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion']; // <-- Corrected line
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Obtener la imagen cargada por el usuario
    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $ruta_destino = '../imagenes/' . basename($imagen_nombre);

    // Mueve la imagen desde su ubicación a la carpeta destino
    if (move_uploaded_file($imagen_tmp, $ruta_destino)) {

        // Si la imagen subió correctamente, se inserta el producto en la base de datos
        $query = "INSERT INTO productos (nombre, descripcion, precio, imagen, stock) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);

        // Asocia los valores con los placeholders (?)
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $imagen_nombre, $stock);

        // Ejecuta y verifica si se insertó correctamente
        if ($stmt->execute()) {
            // Redirige a la lista de productos
            header("Location: productos.php");
            exit();
        } else {
            // Error al guardar en la base de datos
            $error = "Error al agregar el producto: " . $stmt->error;
        }
    } else {
        // Error al subir la imagen
        $error = "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../styles/Style2.CSS"> </head>
<body>

    <main class="container producto-form">
        <header>
            <h2>Agregar Producto</h2>
        </header>
        <?php if (isset($error)) : ?>
            <section class="error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </section>
        <?php endif; ?>

        <section>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required>

                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" min="0" required>

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>

                <button type="submit">Agregar Producto</button><br><br>
            </form>
        </section>

        <nav>
            <a href="productos.php" class="btn">Volver a Productos</a>
        </nav>
    </main>

    <footer>
        <h2>&copy; 2025 Mi Tienda</h2>
    </footer>
</body>
</html>
