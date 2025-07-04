<?php
// Inicia una sesión PHP (permite usar variables de sesión como $_SESSION)
session_start();

// Incluye el archivo de conexión a la base de datos (debe tener $conexion listo para usar)
require_once '../including/conexion.php';

// Prepara la consulta SQL para obtener los datos de la tabla "empleados"
$query = "SELECT id_empleado, nombre, correo, rol FROM empleados";

// Ejecuta la consulta y guarda el resultado en la variable $resultado
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<!-- Define el tipo de documento HTML5 -->

<html lang="es">
<!-- Inicia el documento HTML y define que está en español -->

<head>
    <meta charset="UTF-8">
    <!-- Establece la codificación de caracteres en UTF-8 para permitir caracteres latinos -->

    <title>Lista de Empleados</title>
    <!-- Título que aparecerá en la pestaña del navegador -->

    <link rel="stylesheet" href="../styles/empleados.css">
    <!-- Vincula el archivo CSS externo para dar estilo a la página -->
</head>

<body>
    <!-- Cuerpo principal de la página -->

    <h1>Empleados Registrados</h1>
    <!-- Encabezado principal de la página -->

    <table border="1">
        <!-- Crea una tabla con borde -->

        <thead>
            <!-- Encabezado de la tabla -->
            <tr>
                <!-- Fila de títulos -->
                <th>ID</th>
                <!-- Columna de ID del empleado -->
                <th>Nombre</th>
                <!-- Columna de nombre del empleado -->
                <th>Correo</th>
                <!-- Columna de correo electrónico -->
                <th>Rol</th>
                <!-- Columna del rol (ejemplo: admin, vendedor, etc.) -->
                <th>Acciones</th>
                <!-- Columna para botones o enlaces de acciones -->
            </tr>
        </thead>

        <tbody>
            <!-- Cuerpo de la tabla (donde se mostrarán los datos) -->

            <?php while ($empleado = $resultado->fetch_assoc()): ?>
            <!-- Bucle que recorre todos los resultados obtenidos de la base de datos.
                 fetch_assoc() devuelve cada fila como un arreglo asociativo con nombres de columnas. -->

                <tr>
                    <!-- Nueva fila para cada empleado -->

                    <td><?php echo $empleado['id_empleado']; ?></td>
                    <!-- Imprime el ID del empleado -->

                    <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                    <!-- Imprime el nombre, usando htmlspecialchars para evitar problemas de seguridad (XSS) -->

                    <td><?php echo htmlspecialchars($empleado['correo']); ?></td>
                    <!-- Imprime el correo -->

                    <td><?php echo htmlspecialchars($empleado['rol']); ?></td>
                    <!-- Imprime el rol -->

                    <td>
                        <!-- Columna para las acciones -->
                        <a href="eliminar_empleado.php?id=<?php echo $empleado['id_empleado']; ?>"
                           <!-- Enlace que redirige al archivo eliminar_empleado.php con el ID del empleado en la URL -->
                           
                           onclick="return confirm('¿Seguro que quieres eliminar este empleado?');">
                           <!-- Muestra un cuadro de confirmación antes de eliminar -->
                           
                            Eliminar
                        </a>
                    </td>
                </tr>

            <?php endwhile; ?>
            <!-- Fin del bucle -->
        </tbody>
    </table>
</body>
</html>
