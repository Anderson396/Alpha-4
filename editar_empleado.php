<?php
session_start();
require_once '../including/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de empleado no válido.");
}

$id_empleado = intval($_GET['id']);
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $rol = trim($_POST['rol']);

    if (empty($nombre) || empty($correo) || empty($rol)) {
        $mensaje = "Por favor, completa todos los campos.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo electrónico no es válido.";
    } else {
        $stmt = $conexion->prepare("UPDATE empleados SET nombre = ?, correo = ?, rol = ? WHERE id_empleado = ?");
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }
        $stmt->bind_param("sssi", $nombre, $correo, $rol, $id_empleado);

        if ($stmt->execute()) {
            $mensaje = "Empleado actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar empleado: " . $stmt->error;
        }

        $stmt->close();
    }
}

$stmt = $conexion->prepare("SELECT nombre, correo, rol FROM empleados WHERE id_empleado = ?");
$stmt->bind_param("i", $id_empleado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Empleado no encontrado.");
}

$empleado = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Empleado</title>
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

        .mensaje {
            margin-bottom: 15px;
            font-weight: bold;
            color: green;
        }

        a[href*="lista_empleado"] {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #6b2f2f;
            font-weight: bold;
        }

        a[href*="lista_empleado"]:hover {
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
    <h1>Editar Empleado</h1>

    <?php if ($mensaje): ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <form method="post" action="editar_empleado.php?id=<?php echo $id_empleado; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>" required>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($empleado['correo']); ?>" required>

        <label for="rol">Rol:</label>
        <input type="text" id="rol" name="rol" value="<?php echo htmlspecialchars($empleado['rol']); ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>

    <a href="lista_empleado.php">⬅️ Volver a la lista de empleados</a>
</main>

</body>
</html>
