<?php
session_start();
require_once '../including/conexion.php'; // Asegúrate de que esta ruta sea correcta

// Inicializa variables para el estado y los mensajes
$error = null;
$exito = false; // Bandera para indicar si la eliminación fue exitosa

// Verifica si se recibió un ID de producto válido a través de GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_producto = (int)$_GET['id']; // Convierte a entero por seguridad

    // 1. Obtiene el nombre del archivo de imagen de la base de datos antes de eliminar el registro del producto
    $stmt_select_imagen = $conexion->prepare("SELECT imagen FROM productos WHERE id_producto = ?");
    if ($stmt_select_imagen) {
        $stmt_select_imagen->bind_param("i", $id_producto);
        $stmt_select_imagen->execute();
        $resultado_imagen = $stmt_select_imagen->get_result();
        $producto_data = $resultado_imagen->fetch_assoc();
        $stmt_select_imagen->close(); // Cierra la declaración SELECT

        // Verifica si el producto existe y tiene una imagen
        if ($producto_data && !empty($producto_data['imagen'])) {
            $rutaImagen = "../imagenes/" . $producto_data['imagen'];

            // 2. Elimina el archivo de imagen del servidor
            if (file_exists($rutaImagen)) {
                if (!unlink($rutaImagen)) {
                    // Esto es una advertencia, no un error fatal para la eliminación del producto
                    $error = "❌ Advertencia: No se pudo eliminar la imagen del servidor: " . htmlspecialchars($producto_data['imagen']);
                }
            } else {
                // Si la ruta de la imagen estaba en la DB pero el archivo no se encontró en el disco
                $error = "❌ Advertencia: La imagen " . htmlspecialchars($producto_data['imagen']) . " no se encontró en el servidor.";
            }
        }

        // 3. Elimina el registro del producto de la base de datos
        $stmt_delete_producto = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
        if ($stmt_delete_producto) {
            $stmt_delete_producto->bind_param("i", $id_producto);
            if ($stmt_delete_producto->execute()) {
                // Verifica si se afectaron filas (es decir, si se eliminó un producto)
                if ($stmt_delete_producto->affected_rows > 0) {
                    $exito = true; // Establece la bandera de éxito
                } else {
                    $error = "❌ No se encontró ningún producto con ID " . $id_producto . " para eliminar.";
                }
            } else {
                $error = "❌ Error al ejecutar la consulta de eliminación: " . $stmt_delete_producto->error;
            }
            $stmt_delete_producto->close(); // Cierra la declaración DELETE
        } else {
            $error = "❌ Error al preparar la consulta de eliminación: " . $conexion->error;
        }

    } else {
        $error = "❌ Error al obtener la información del producto para eliminar (consulta de imagen): " . $conexion->error;
    }

} else {
    $error = "❌ ID de producto no válido o no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Eliminar Producto</title>
    <link rel="stylesheet" href="../styles/style.css" /> <script>
        // Este script se ejecuta al cargar la página eliminar_productos.php
        window.onload = function () {
            // Muestra el cuadro de diálogo de confirmación solo si no se ha realizado ninguna operación (ej. carga inicial
            // antes de que el script haya procesado el ID de eliminación, o si el ID era inválido).
            // Esto evita que aparezca después de que la eliminación haya ocurrido y se muestre el mensaje.
            <?php if (!isset($_GET['id']) || ($error && strpos($error, 'ID de producto no válido') !== false) || (!$exito && !$error)): ?>
                if (!confirm("¿Estás seguro/a que deseas eliminar este producto?")) {
                    window.location.href = "productos.php"; // Redirige si se cancela
                }
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <?php include("../including/navbar.php"); // Asegúrate de que esta ruta sea correcta ?>

    <main class="container">
        <header>
            <h1>Eliminar Producto</h1>
        </header>

        <?php if ($error): ?>
            <section class="error-message" style="color: red;">
                <p><?php echo htmlspecialchars($error); ?></p>
            </section>
        <?php elseif ($exito): ?>
            <section class="confirmation-message" style="color: green;">
                <p>✅ El producto se eliminó correctamente.</p>
            </section>
        <?php else: ?>
            <section class="info-message" style="color: blue;">
                <p>Esperando confirmación...</p>
            </section>
        <?php endif; ?>

        <nav>
            <a href="productos.php" class="btn">Volver a Productos</a>
        </nav>
    </main>
</body>
</html>
