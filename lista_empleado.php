<?php
session_start();
require_once '../including/conexion.php';

$query = "SELECT id_empleado, nombre, correo, rol FROM empleados";
$resultado = $conexion->query($query);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4b6c2;
            color: #5c2e2e;
        }

        tr:hover {
            background-color: #ffe5ec;
        }

        a[href*="agregar_empleado"] {
            display: inline-block;
            margin-bottom: 15px;
            background-color: #f38fb0;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        a[href*="agregar_empleado"]:hover {
            background-color: #e66a92;
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
    <h1>Empleados Registrados</h1>

    <?php if (isset($_GET['mensaje'])): ?>
        <p style="color: green; font-weight: bold;">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </p>
    <?php endif; ?>

    <a href="agregar_empleado.php">➕ Agregar Empleado</a>
    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($empleado = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $empleado['id_empleado']; ?></td>
                        <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($empleado['correo']); ?></td>
                        <td><?php echo htmlspecialchars($empleado['rol']); ?></td>
                        <td>
                            <a href="editar_empleado.php?id=<?php echo $empleado['id_empleado']; ?>">✏️ Editar</a> |
                            <a href="eliminar_empleado.php?id=<?php echo $empleado['id_empleado']; ?>"
                               onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">
                               🗑️ Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay empleados registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
