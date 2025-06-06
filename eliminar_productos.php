<?php
//iniciamos sesion
session_start();

//Incluimos la conexion a la base de datos
require_once ".. including/conexion.php";

//Solo el administrador podra eliminar productos
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {

       // Si no es administrados, se redirigira a la pagina principal
    header("Location: ../index.php");
    exit();
}

//Verificamos que reciba el ID válido por una URL
if (isset($_GET["id"])) {
    $id = (int)$_GET["id"]; // Convierte por seguridad 

 // Primer paso: Obtiene el nombre de la imagen asociada al productos
$stmt = $conexion->prepare("SELECT imagen FROM productos WHERE id_producto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

//Segundo paso: Si el producto tiene imagen que existe en el archivo, lo eliminara del servidor 
if ($producto && !empty($producto["imagen"])) {
    $rutaImagen = "../uploads/" . $producto["imagen"];

    //Si el archivo es existente, lo eliminara para evitar errores 
    if (file_exists($rutaImagen)) {
            unlink($rutaImagen); // eliminara los archivos
    }
}

//Tecer paso: Prepara la consulta para eliminar el prducto de la base de datos 
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "El producto se elimino correctamente.";
 else {
    echo "No se encontro este producto.";
}
// Cuarto paso: Ejecutamos la consulta y validamos el resultado
if ($stmt->execute()) {

    // Si salió bien, se redirige a la lista de productos
    header("Location: productos.php");
    exit();
} else {
    $error = "No se puede eliminar este producto.";
}

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Eliminar Producto</title>
    <link rel="stylesheet" href="../styles/style.css" />

<script>
    // Función que ejecuta al cargar la pagina 
    window.onload = function () {
        // Se muestra una alerta antes de iniciar
        if (!confirm("¿Estás seguro/a que deseas eliminar este producto?")) {
            //Si el usuario cancela lo llevara de nuevo a la lista de productos
            window.location.href = "productos.php";
            }
        };
</script>

</head>
<body>
    <?php include("../including/navbar.php"); ?>

    <main class="container">
        <header>
            <h1>Eliminar Producto</h1>
        </header>

                <!-- Mensaje de error si existe -->
        <?php if (isset($error)) : ?>
            <section class="error-message" style="color: red;">
                <p><?php echo htmlspecialchars($error); ?></p>
            </section>

             <?php else: ?>
            <!-- Confirmación que el producto fue eliminado -->
            <section class="confirmation-message">
                <p>El producto se elimino correctamente.</p>
            </section>
        <?php endif; ?>

         <!-- Enlace que regresa a la lista de productos -->
        <nav>
            <a href="productos.php" class="btn">Volver a Productos</a>
        </nav>
    </main>
</body>
</html>
