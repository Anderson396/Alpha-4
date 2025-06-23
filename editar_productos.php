<?php
session_start();
require_once '../including/conexion.php';

$error = null;
$exito = false;
$producto = null;
$categorias = [];

// Obtener categorías desde la base de datos
$query_categorias = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC";
$result_categorias = $conexion->query($query_categorias);
if ($result_categorias) {
    while ($cat = $result_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
}

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $id_producto = intval($_POST['id_producto']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $stock = intval($_POST['stock']);
    $precio = floatval($_POST['precio']);
    $id_categoria = intval($_POST['id_categoria']);
    $imagen_actual_db = $_POST['imagen_actual'] ?? ''; // Imagen actual guardada en DB (viene del campo hidden)

    $imagen_a_guardar = $imagen_actual_db; // Por defecto se mantiene la imagen actual de la DB

    // Debugging: Verificar datos POST
    // error_log("Datos POST recibidos: " . print_r($_POST, true));
    // error_log("Imagen actual (del hidden field): " . $imagen_actual_db);
    // error_log("Archivos subidos: " . print_r($_FILES, true));

    // Si se subió una nueva imagen
    if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['imagen_nueva']['tmp_name'];
        $file_name = $_FILES['imagen_nueva']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif']; // Tipos permitidos

        if (in_array($file_ext, $allowed_ext)) {
            $unique_name = uniqid('prod_') . '.' . $file_ext; // Nombre único
            $dest_path = '../imagenes/' . $unique_name;

            // Debugging: Check paths
            // error_log("Ruta temporal: " . $file_tmp_path);
            // error_log("Ruta destino: " . $dest_path);

            // Mover imagen al directorio de imágenes
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                // Si se movió correctamente, eliminamos la imagen anterior SOLO SI ES DIFERENTE Y EXISTE
                if (!empty($imagen_actual_db) && $imagen_actual_db !== $unique_name && file_exists('../imagenes/' . $imagen_actual_db)) {
                    if (unlink('../imagenes/' . $imagen_actual_db)) {
                        // error_log("Imagen anterior eliminada: " . $imagen_actual_db);
                    } else {
                        // error_log("Error al eliminar la imagen anterior: " . $imagen_actual_db);
                        // No es un error crítico para la actualización, pero es bueno saberlo
                    }
                }
                $imagen_a_guardar = $unique_name; // Actualizar el nombre de la imagen a guardar
                // error_log("Nueva imagen guardada: " . $imagen_a_guardar);
            } else {
                $error = "❌ Error al mover la nueva imagen al directorio de destino. Asegúrate de que la carpeta 'imagenes' tenga permisos de escritura (chmod 775 o 777).";
                // error_log("Error de move_uploaded_file: " . $error);
            }
        } else {
            $error = "❌ Tipo de archivo no permitido. Solo se permiten JPG, JPEG, PNG, GIF.";
            // error_log("Error de tipo de archivo: " . $error);
        }
    } else if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Handle other upload errors (e.g., UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE)
        switch ($_FILES['imagen_nueva']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error = "❌ El archivo excede el tamaño máximo permitido.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $error = "❌ El archivo se subió parcialmente.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error = "❌ Falta una carpeta temporal en el servidor.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error = "❌ No se pudo escribir el archivo en el disco.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $error = "❌ Una extensión de PHP detuvo la subida del archivo.";
                break;
            default:
                $error = "❌ Error desconocido al subir la imagen.";
                break;
        }
        // error_log("Error en la subida de imagen (otro error): " . $error . " Código: " . $_FILES['imagen_nueva']['error']);
    }

    // Si no hubo error, actualizamos en la base de datos
    if (!$error) {
        $stmt = $conexion->prepare("UPDATE productos SET nombre=?, descripcion=?, stock=?, precio=?, imagen=?, id_categoria=? WHERE id_producto=?");
        // error_log("Query de actualización: UPDATE productos SET nombre=?, descripcion=?, stock=?, precio=?, imagen=?, id_categoria=? WHERE id_producto=?");
        // error_log("Parametros: " . $nombre . ", " . $descripcion . ", " . $stock . ", " . $precio . ", " . $imagen_a_guardar . ", " . $id_categoria . ", " . $id_producto);
        $stmt->bind_param("ssiidsi", $nombre, $descripcion, $stock, $precio, $imagen_a_guardar, $id_categoria, $id_producto);
        if ($stmt->execute()) {
            // error_log("Producto actualizado exitosamente. Redirigiendo...");
            header("Location: productos.php?status=success_edit");
            exit();
        } else {
            $error = "❌ Error al actualizar el producto en la base de datos: " . $stmt->error;
            // error_log("Error de base de datos: " . $error);
        }
    }
}

// Si se abrió la página con un ID (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_producto = intval($_GET['id']);
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();
    $stmt->close();

    if (!$producto) {
        $error = "❌ Producto no encontrado.";
    }
}
?>

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
            <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($producto): ?>
            <form method="POST" enctype="multipart/form-data">
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

                <label>Categoría:</label>
                <select name="id_categoria" required>
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id_categoria']; ?>"
                            <?php echo $producto['id_categoria'] == $cat['id_categoria'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label>Imagen actual:</label><br>
                <?php
                $current_image_path = '';
                if (!empty($producto['imagen'])) {
                    // Check if the file exists on the server
                    $full_image_path = '../imagenes/' . $producto['imagen'];
                    if (file_exists($full_image_path)) {
                        $current_image_path = htmlspecialchars($producto['imagen']);
                    }
                }
                ?>
                <?php if (!empty($current_image_path)): ?>
                    <img src="../imagenes/<?php echo $current_image_path; ?>" width="100" alt="Imagen actual del producto"><br>
                <?php else: ?>
                    <p>No hay imagen</p>
                <?php endif; ?>

                <label>Nueva Imagen (opcional):</label>
                <input type="file" name="imagen_nueva" id="imagen_nueva_input" accept="image/*"><br><br>
                <div id="imagen_nueva_preview_container">
                    <img id="imagen_nueva_preview" src="#" alt="Vista previa de nueva imagen" style="display: none; max-width: 200px; max-height: 200px;"><br>
                </div>

                <button type="submit">Actualizar</button>
            </form>
        <?php endif; ?>
    </main>

    <script>
        document.getElementById('imagen_nueva_input').addEventListener('change', function(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('imagen_nueva_preview');

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block'; // Show the image
            } else {
                // If no file is selected (e.g., user cancels selection), hide the preview
                preview.style.display = 'none';
                preview.src = '#'; // Clear the src
            }
        });
    </script>
</body>
</html>
