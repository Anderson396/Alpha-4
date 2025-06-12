<?php
session_start();
//incluye la conecxion a la base de datos
require_once '../including/conexion.php';

//verifica que el usuario sea administrador
//if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
   // header('Location: ../index.php'); //redirige a la pagina principal
   //exit(); //fializa la ejecucion del script
//}

//verifica que se haya recibido el id por la URL
if (!isset($_GET['id'])) {
    echo "Proveedor no especificado."; //muestra eror si no hay id
    exit();
}

$id = (int) $_GET['id']; //convierte el id en un numero entero 
// variables para mostras mensajes al usuario
$error = ""; 
$mensaje = "";

//prepara la consulta para obtener datos actuales del proveedor
$stmt = $conexion->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
$stmt->bind_param("i", $id); //enlaza el id como parametro entero
$stmt->execute(); //ejecuta la consulta
$resultado = $stmt->get_result(); //obtiene el resultado de la consulta
$proveedor = $resultado->fetch_assoc();//extrae el primer registro de la consulta

//si no se encuentra ningun proveedor con el id especificado, muestra error
if (!$proveedor) {
    echo "Proveedor no encontrado.";
    exit();
}

// Si se envió el formulario mediente el POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del formulario y elimina los espacios en blanco
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    if ($nombre && $telefono && $direccion) {
        $stmt = $conexion->prepare("UPDATE proveedores SET nombre = ?, telefono = ?, direccion = ? WHERE id_proveedor = ?");
        $stmt->bind_param("sssi", $nombre, $telefono, $direccion, $id);

        if ($stmt->execute()) {
            $mensaje = "Proveedor actualizado correctamente.";
            // Actualizar datos en pantalla
            $proveedor['nombre'] = $nombre;
            $proveedor['telefono'] = $telefono;
            $proveedor['direccion'] = $direccion;
        } else {
            $error = "Error al actualizar proveedor.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Proveedor</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <?php include("../including/navbar.php"); ?>

    <main class="container">
        <h2>Modificar Proveedor</h2>

        <?php if ($mensaje): ?>
            <div class="success" style="color: green;"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error" style="color: red;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($proveedor['nombre']); ?>">

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" required value="<?php echo htmlspecialchars($proveedor['telefono']); ?>">

            <label for="direccion">Dirección:</label>
            <textarea name="direccion" id="direccion" rows="4" required><?php echo htmlspecialchars($proveedor['direccion']); ?></textarea>

            <button type="submit">Guardar Cambios</button>
        </form>

        <nav>
            <a href="listar_proveedores.php" class="btn">Volver a Proveedores</a>
        </nav>
    </main>

    <footer>
        <p>&copy; 2025 Mi Sistema</p>
    </footer>
</body>
</html>
