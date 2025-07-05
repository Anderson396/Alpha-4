<?php
session_start();
require_once '../including/conexion.php';

$mensaje = '';
$mensaje_clase = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $rol = $conexion->real_escape_string($_POST['rol']);

    if (empty($nombre) || empty($correo) || empty($rol)) {
        $mensaje = "Por favor, completa todos los campos.";
        $mensaje_clase = 'error';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico no es válido.";
        $mensaje_clase = 'error';
    } else {
        $sql = "INSERT INTO empleados (nombre, correo, rol) VALUES ('$nombre', '$correo', '$rol')";
        if ($conexion->query($sql) === TRUE) {
            $mensaje = "Empleado agregado correctamente.";
            $mensaje_clase = 'success';
            $_POST = []; // Limpiar campos
        } else {
            $mensaje = "Error al agregar empleado: " . $conexion->error;
            $mensaje_clase = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Empleado</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, #d8c2e0, #f5bdd3);
        }

        .sidebar {
            width: 200px;
            background-color: #f9d9e2;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            height: 100%;
            color: #6b2f2f;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: #6b2f2f;
            text-decoration: none;
            padding: 12px;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #f1c5d8;
        }

        main {
            margin-left: 200px;
            padding: 20px;
            flex-grow: 1;
            color: #5c2e2e;
        }

        h1 {
            color: #6b2f2f;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #f38fb0;
            color: white;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e66a92;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .volver {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #6b2f2f;
            font-weight: bold;
        }

        .volver:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

 <!-- Menú lateral -->
    <div class="sidebar">
        <img src="../img/logo.png" alt="Dulces Creaciones">
        <a href="../index.php">Inicio</a>
        <a href="carrito.php">Carrito</a>
        <a href="perfil.php">Mi Perfil</a>
        <a href="productos.php">Productos</a>
        <a href="proveedores.php">Proveedores</a>
        <a href="logout.php">Cerrar sesión</a>
    </div>

<main>
    <h1>Agregar Nuevo Empleado</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="<?php echo $mensaje_clase; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <form action="agregar_empleado.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">

        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" required value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">

        <label for="rol">Rol:</label>
        <input type="text" name="rol" id="rol" required value="<?php echo isset($_POST['rol']) ? htmlspecialchars($_POST['rol']) : ''; ?>">

        <button type="submit">Agregar Empleado</button>
    </form>

    <a href="lista_empleado.php" class="volver">⬅️ Volver a la lista de empleados</a>
</main>

</body>
</html>
