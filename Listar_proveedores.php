<?php
session_start();
require_once '../including/conexion.php';

// Consulta a la base de datos
// Asegúrate de que la columna 'id_proveedor' exista en tu tabla 'proveedores'
$query = "SELECT id_proveedor, nombre, producto, telefono FROM proveedores";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Proveedores</title>
    <link href="https://fonts.googleapis.com/css2?family=Savate&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/Style4.css">
    <style>
        /* Estilos básicos para la tabla y los botones */
        .tabla-proveedores {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .tabla-proveedores th, .tabla-proveedores td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .tabla-proveedores th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .acciones {
            white-space: nowrap; /* Evita que los botones se envuelvan */
            min-width: 120px; /* Asegura espacio mínimo para los botones */
        }
        .acciones a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 2px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }
        .acciones .btn-editar {
            background-color: #007bff; /* Azul para editar */
        }
        .acciones .btn-eliminar {
            background-color: #dc3545; /* Rojo para eliminar */
        }
        .acciones a:hover {
            opacity: 0.8;
        }
        .mensaje {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .mensaje.exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .contenedor-botones-arriba {
            margin-bottom: 20px;
            text-align: right; /* Alinea el botón a la derecha */
        }
        .btn-agregar {
            background-color: #28a745; /* Verde para agregar */
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-agregar:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <main class="contenedor">
        <h1>Listado de Proveedores</h1>

        <?php
        // Mostrar mensajes de sesión (éxito o error)
        if (isset($_SESSION['message'])) {
            echo '<div class="mensaje exito">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']); // Limpiar el mensaje después de mostrarlo
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="mensaje error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']); // Limpiar el error después de mostrarlo
        }
        ?>

        <?php
        // Agrega esta verificación para depurar el error "num_rows on bool"
        if (!$resultado) {
            echo '<div class="mensaje error">';
            echo '<strong>Error en la consulta a la base de datos:</strong> ' . $conexion->error;
            echo '<br>Asegúrate de que la tabla "proveedores" y las columnas "id_proveedor", "nombre", "producto" existen.';
            echo '</div>';
        }
        ?>

        <div class="contenedor-botones-arriba">
            <a href="agregar_proveedor.php" class="btn-agregar">Agregar Nuevo Proveedor</a>
        </div>

        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <table class="tabla-proveedores">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Producto</th>
                        <th>telefono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($proveedor = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['producto']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['telefono']); ?></td>
                            <td class="acciones">
                                <a href="editar_proveedor.php?id=<?php echo htmlspecialchars($proveedor['id_proveedor']); ?>" class="btn-editar">Editar</a>
                                <a href="eliminar_proveedores.php?id=<?php echo htmlspecialchars($proveedor['id_proveedor']); ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este proveedor?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay proveedores registrados.</p>
        <?php endif; ?>
    </main>
</body>
</html>
