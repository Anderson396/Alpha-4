<?php
session_start();
require_once '../including/conexion.php';

$error = null;
$producto = null;
$categorias = [];

// Obtener categorías
$query_categorias = "SELECT id_categoria, nombre FROM categoria ORDER BY nombre ASC";
$result_categorias = $conexion->query($query_categorias);
if ($result_categorias) {
    while ($cat = $result_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
}

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = intval($_POST['id_producto']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $stock = intval($_POST['stock']);
    $precio = floatval($_POST['precio']);
    $id_categoria = intval($_POST['id_categoria']);
    $imagen_actual_db = $_POST['imagen_actual'] ?? '';
    $imagen_a_guardar = $imagen_actual_db;

    // Si se sube una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['imagen']['tmp_name'];
        $file_name = $_FILES['imagen']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $unique_name = uniqid('prod_') . '.' . $file_ext;
            $dest_path = '../imagenes/' . $unique_name;

            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                // Eliminar imagen anterior si existe
                if (!empty($imagen_actual_db) && file_exists('../imagenes/' . $imagen_actual_db)) {
                    unlink('../imagenes/' . $imagen_actual_db);
                }
                $imagen_a_guardar = $unique_name;
            } else {
                $error = "Error al mover la nueva imagen. Verifica permisos de la carpeta 'imagenes'.";
            }
        } else {
            $error = "Solo se permiten archivos JPG, JPEG, PNG o GIF.";
        }
    }

    // Ahora actualizamos si no hubo error
    if (!$error) {
        $stmt = $conexion->prepare("UPDATE productos SET nombre=?, descripcion=?, stock=?, precio=?, imagen=?, id_categoria=? WHERE id_producto=?");
        $stmt->bind_param("ssiidsi", $nombre, $descripcion, $stock, $precio, $imagen_a_guardar, $id_categoria, $id_producto);

        if ($stmt->execute()) {
            // Confirmamos que la imagen fue actualizada
            // Redirigimos con éxito
            header("Location: productos.php?status=success_edit");
            exit();
        } else {
            $error = "Error en la base de datos: " . $stmt->error;
        }
    }
}

// Cargar datos del producto
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();
    $stmt->close();

    if (!$producto) {
        $error = "Producto no encontrado.";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../styles/Style5.css">
</head>
<body>
<main class="container">
    <h1>Modificar Producto</h1>

    <?php if ($error): ?>
        <p style="color: red;"><strong><?php echo $error; ?></strong></p>
    <?php endif; ?>

    <?php if ($producto): ?>
        <form enctype="multipart/form-data"  method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($producto['imagen']); ?>">

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required><br>

            <label>Descripción:</label>
            <textarea name="descripcion" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea><br>

            <label>Stock:</label>
            <input type="number" name="stock" value="<?php echo $producto['stock']; ?>" required><br>

            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required><br>

           <label for="categoria">Categoría:</label>
            <select name="id_categoria" id="categoria" required>
            <?php
            // Cargar categorías
            $query_categorias = "SELECT * FROM categorias";
            $result_categorias = $conexion->query($query_categorias);
            if ($result_categorias && $result_categorias->num_rows > 0):
                while ($cat = $result_categorias->fetch_assoc()):
            $selected = ($producto['id_categoria'] == $cat['id_categoria']) ? 'selected' : '';
            ?>
        <option value="<?= htmlspecialchars($cat['id_categoria']) ?>" <?= $selected ?>>
            <?= htmlspecialchars($cat['nombre']) ?>
        </option>
    <?php endwhile; else: ?>
        <option value="">No hay categorías registradas</option>
    <?php endif; ?>
</select>

        <option value="<?= htmlspecialchars($cat['id_categoria']) ?>" <?= $selected ?>>
            <?= htmlspecialchars($cat['nombre']) ?>
        </option>
    <?php endwhile; else: ?>
        <option value="">No hay categorías registradas</option>
    <?php endif; ?>
</select>


            <label>Imagen actual:</label><br>
            <?php if (!empty($producto['imagen']) && file_exists('../imagenes/' . $producto['imagen'])): ?>
                <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" width="100"><br>
            <?php else: ?>
                <p>No hay imagen actual.</p>
            <?php endif; ?>

            <label>Nueva Imagen (opcional):</label>
            <input type="file" name="imagen_nueva" accept="image/*"><br><br>

            <button type="submit">Actualizar</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
