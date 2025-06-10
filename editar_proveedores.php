<?php
session_start();

// conectamos esta página a conexion.php para obtener la conexión a la base de datos
require_once '../including/conexion.php';

// Nos aseguramos que el usuario sea un administrador 
// if (isset($_SESSION ['rol'] || $_SESSION ['rol'] !== 'admin')) {
//    header('Location: index.php')
//    exit;
// }

// Si resivimos el id y es valido por el metodo $_GET y obtenmos los datos del producto
if (isset($_GET['id'])) {
    $id = (int) $_GET['id']; // Convertimos el id a entero
    $query = "SELECT * FROM proveedor WHERE id_proveedor = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", "id");
    $stm->exute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();
}

// Traemos los datos del formulario 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$nombre = $_POST = ['nombre'];
$producto= $_POST = ['producto'];
}
?>

<!-- El html del sitio -->
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 </head>
 <body>

<!-- Main que contiene toda la informacón que mostrara la página a los usuarios -->
 <main class="container proveedores-from">

<!-- titulo de la página -->
 <header>
        <h2>Modificar proveedores</h2>
    </header>

<!-- Mostramos le error con un alert para que sea inmediato para los lectors -->
 <?php if (isset($error)) : ?>
    <div class= "error" role="alert" <?php echo htmlspecialchars($error)?>></div>
    <?php endif; ?>

 <!-- Formulario para poder editar los datos del proveedor -->
  <form method="POST" enctype="multipart/form-data" novalidate> 
    <label for="nombre">Nombre: </label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($proveedor["nombre"]); ?>" require></input>
    <labe for="producto">Producto: </label>
       <input type="text" id="producto" name="productoS" value="<?php echo htmlspecialchars($proveedor["producTo"]); ?>" require></input>
  </form>


        <!-- nav para volver a los proveedores -->
        <nav>
            <a href="proveedor.php" class="btn">Volver al listado de Proveedor</a>
        </nav>
    </main>
    
    <footer>
        <p>&copy; 2025 Mi Tienda</p>
         </footer>
</body>
</html>
