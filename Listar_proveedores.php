<?php
session_start();
require_once '../including/conexion.php';

$query = "SELECT id_proveedor, nombre, producto, telefono, direccion FROM proveedores";
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
        body {
            background: linear-gradient(to right, #c1d7f1, #f8c5d9);
            font-family: 'Savate', sans-serif;
            font-size: 18px; /* Aumentado el tamaño de fuente base para todo el cuerpo */
            color: #222; /* Color de texto más oscuro (casi negro) para mejor contraste */
        }

        .contenedor {
            background-color: rgb(250, 233, 240);
            padding: 35px; /* Aumentado el padding del contenedor */
            border-radius: 12px; /* Bordes más redondeados */
            width: 90%; /* Un poco más de ancho */
            max-width: 1000px; /* Establecer un ancho máximo */
            margin: 60px auto; /* Centrar y más margen superior/inferior */
            box-shadow: 0 6px 12px rgba(0,0,0,0.15); /* Sombra más pronunciada */
        }

        h1 {
            text-align: center;
            color: #222; /* Color de título más oscuro */
            margin-bottom: 30px; /* Más espacio debajo del título */
            font-size: 3em; /* Título aún más grande */
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1); /* Ligera sombra de texto para que destaque */
        }

        .btn-agregar {
            background-color: rgba(240, 190, 210, 0.8); /* Tono rosado suave, que de con el fondo */
            color: #222; /* Texto negro para el botón */
            padding: 16px 30px; /* Botón más grande */
            text-decoration: none;
            border-radius: 10px; /* Bordes más redondeados */
            font-size: 20px; /* Letra más grande en el botón */
            font-weight: bold;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            transition: all 0.3s ease-in-out;
            display: inline-block;
            border: 1px solid rgba(220, 170, 190, 0.5); /* Borde sutil */
        }

        .btn-agregar:hover {
            background-color: rgba(230, 170, 190, 0.9); /* Un poco más oscuro al pasar el ratón */
            transform: translateY(-3px) scale(1.02); /* Efecto al pasar el ratón */
            box-shadow: 0 5px 10px rgba(0,0,0,0.25);
        }

        .tabla-proveedores {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px; /* Más espacio sobre la tabla */
            background-color: white; /* Fondo blanco para la tabla para que resalte más */
            border-radius: 8px; /* Bordes redondeados para la tabla */
            overflow: hidden; /* Asegura que los bordes redondeados se apliquen bien */
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); /* Sombra suave para la tabla */
        }

        .tabla-proveedores th, .tabla-proveedores td {
            border: 1px solid #eee; /* Bordes más claros en la tabla */
            padding: 14px; /* Aumentado el padding para más espacio */
            text-align: left;
            color: #222; /* Texto negro para las celdas */
            font-size: 18px; /* Letra más grande para el contenido de las celdas */
            font-weight: 500; /* Grosor de fuente intermedio */
        }

        .tabla-proveedores th {
            background-color: #e9ecef; /* Fondo ligeramente gris para los encabezados */
            font-weight: bold;
            font-size: 19px; /* Encabezados de tabla más grandes */
            color: #333; /* Texto más oscuro para los encabezados */
        }

        .tabla-proveedores tr:nth-child(even) {
            background-color: #f9f9f9; /* Rayado suave para las filas impares */
        }

        .tabla-proveedores tr:hover {
            background-color: #f0f0f0; /* Color al pasar el ratón por las filas */
        }

        .acciones {
            white-space: nowrap;
            min-width: 140px; /* Ancho mínimo para la columna de acciones */
            text-align: center; /* Centrar los botones de acción */
        }

        .acciones a {
            display: inline-block;
            padding: 8px 16px; /* Más padding para los botones de acción */
            margin: 0 6px; /* Más margen entre botones */
            text-decoration: none;
            color: #222; /* Texto negro para los botones de acción */
            border-radius: 6px; /* Bordes más redondeados */
            font-size: 16px; /* Letra más grande en los botones de acción */
            font-weight: bold;
            transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
            border: 1px solid rgba(0,0,0,0.1); /* Borde sutil para los botones de acción */
        }

        .acciones .btn-editar {
            background-color: rgba(240, 190, 210, 0.8); /* Tono rosado suave, similar al agregar */
        }

        .acciones .btn-eliminar {
            background-color: rgba(220, 200, 230, 0.8); /* Tono morado muy suave, diferente pero que combine */
        }

        .acciones a:hover {
            background-color: rgba(200, 150, 170, 0.9); /* Más oscuro al pasar el ratón */
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        /* Removiendo los estilos inline del HTML */
        .btn-agregar[style="color: black;"] {
            color: #222 !important;
        }
        .acciones .btn-editar[style="color: white;"] {
            color: #222 !important; /* Forzar color negro para Editar */
        }
        .acciones .btn-eliminar[style="color: black;"] {
            color: #222 !important; /* Forzar color negro para Eliminar */
        }

        .mensaje {
            padding: 15px; /* Más padding para los mensajes */
            margin-bottom: 20px;
            border-radius: 8px; /* Bordes redondeados para los mensajes */
            font-size: 17px; /* Mensajes más grandes */
            font-weight: 500;
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

        .contenedor-boton-final {
            text-align: center;
            margin-top: 40px; /* Más margen superior */
        }
        /* Estilo para el mensaje "No hay proveedores registrados" */
        p {
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
            font-weight: 500;
            color: #444; /* Asegurar que este texto también sea oscuro */
        }

        /* Estilos para el botón "Volver a la tienda" */
nav[aria-label="Volver a la tienda"] {
    display: flex; /* Usar flexbox para centrar el botón */
    margin-top: 30px; /* Un poco más de margen superior para separarlo de la tabla/formulario */
    margin-bottom: 30px; /* Margen inferior para darle espacio si hay más contenido abajo */
}

.btn.checkout-btn {
    display: inline-block; /* Asegura que el padding y el ancho funcionen correctamente */
    width: auto; /* Ancho automático para que se ajuste al contenido, o puedes poner un ancho fijo como 250px */
    min-width: 200px; /* Ancho mínimo para que no sea demasiado pequeño */
    padding: 15px 30px; /* Padding generoso para un botón destacado */
    background-color: rgba(193, 215, 241, 0.85); /* Tono azul claro pastel (como el botón "Volver al Listado") */
    color: #222; /* Texto del botón casi negro */
    text-decoration: none; /* Quitar el subrayado del enlace */
    border: none;
    border-radius: 8px; /* Bordes redondeados */
    font-size: 1.2em; /* Tamaño de fuente ligeramente más grande para que sea prominente */
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil para darle profundidad */
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease; /* Transiciones suaves */
    box-sizing: border-box; /* Incluir padding y borde en el ancho/alto total */
}

.btn.checkout-btn:hover {
    background-color: rgba(170, 190, 220, 0.95); /* Tono más oscuro al pasar el ratón */
    transform: translateY(-2px); /* Ligero levantamiento al pasar el ratón */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Sombra más pronunciada al pasar el ratón */
}
    </style>
</head>
<body>
    <main class="contenedor">
        <h1>Listado de Proveedores</h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="mensaje exito">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="mensaje error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }

        if (!$resultado) {
            echo '<div class="mensaje error">';
            echo '<strong>Error en la consulta a la base de datos:</strong> ' . $conexion->error;
            echo '<br>Asegúrate de que la tabla "proveedores" y las columnas "id_proveedor", "nombre", "producto", "telefono", "direccion" existen.';
            echo '</div>';
        }
        ?>

        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <table class="tabla-proveedores">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
                        <th>Producto</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($proveedor = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proveedor['id_proveedor']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['producto']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['direccion']); ?></td>
                            <td class="acciones">
                                <a href="editar_proveedores.php?id=<?php echo htmlspecialchars($proveedor['id_proveedor']); ?>" class="btn-editar">Editar</a>
                                <a href="eliminar_proveedores.php?id=<?php echo htmlspecialchars($proveedor['id_proveedor']); ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar este proveedor?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay proveedores registrados.</p>
        <?php endif; ?>

        <div class="contenedor-boton-final">
            <a href="agregar_proveedor.php" class="btn-agregar"> Agregar Nuevo Proveedor</a>
        </div>
        <nav aria-label="Volver a la tienda" style=" margin-top: 20px;">
                <a href="../index.php" class="btn checkout-btn">Volver a la tienda</a>
        </nav>
    </main>
</body>
</html>
