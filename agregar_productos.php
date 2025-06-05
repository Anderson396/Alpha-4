<?php
//Iniciamos sesión para poder usar las variables de sesión
session_start();

//Conectamos a la base de datos
require_once '../including/conexion.php';

// Verificamos si el usuraio está registrado y si es administrador
if (!isset($SESSION ['rol']) || $SESSION ['rol'] != 'admin') {
   // Y si esto no es así, lo redirigiremos a la página de inicio
   header("Location ../index.php");
   exit; // Terminamos el script despues de redirigir
}

// Obtenemos los datos del formulario mediante POST
if ($SERVER['REQUEST_METHOD'] === 'POST') {
   // Tomamos los datos enviados del formulario
   $nombre = $_POST['nombre'];
   $descripcion = $_POST['descripcion'];
   $precio = $_POST['precio'];
// Obtiene la imagen cargada por el usuario 
   $imagen_nombre = $_FILES['imagen']['name']; 
    $imagen_tmp = $_FILES['imagen']['tmp_name']; 
    $ruta_destino = '../imagenes/' . basename($imagen_nombre);

   // Mueve la imagen desde su ubicacion a la carpeta destino 
   if (move_uploaded_file($imagen_tmp, $ruta_destino)) {
      // Si la imagen se subio correctamente, se inserta el producto de la base de datos
       $query = "INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);

   // Asocia los valores con los placeholders (?)
      $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $imagen_nombre);

      // Ejecuta y verifica si se insertó correctamente
        if ($stmt->execute()) {
            // Redirige a la lista de productos
            header("Location: productos.php");
            exit();
            } else {
            // Error al guardar en la base de datos
            $error = "Error al agregar el producto.";
        }
     } else {
        // Error al subir la imagen
        $error = "Error al subir la imagen.";
    }
}
?>

<!-- Comienza el HTML del sitio -->
 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../styles/style.css"> <!-- Estilos del sitio -->
</head>
<body>
<!-- Incluye el menú de navegación -->
    <?php include('../index.php'); ?>

    <!-- Contenido principal -->
    <main class="container producto-form">
        <!-- Encabezado de la sección -->
        <header>
            <h2>Agregar Producto</h2>
        </header>
 <!-- Muestra errores si existen -->
        <?php if (isset($error)) : ?>
            <section class="error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </section>
        <?php endif; ?>

 <!-- Sección para agregar un producto del formulario -->
        <section>
            <!-- Formulario con campos para ingresar los datos del nuevo producto -->
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required>

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="imagen/*" required>

                <!-- Botón para enviar el formulario -->
                <button type="submit">Agregar Producto</button>
            </form>
        </section>
       
               <!-- Enlace para regresar a la lista de productos -->
        <nav>
            <a href="productos.php" class="btn">Volver a Productos</a>
        </nav>
    </main>

    <!-- Pie de página del sitio -->
    <footer>
        <p>&copy; 2025 Mi Tienda</p>
    </footer>
</body>
</html>
