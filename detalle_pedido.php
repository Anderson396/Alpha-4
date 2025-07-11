<?php
session_start();
require_once '../including/conexion.php';

// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador.
// Si no, redirigir a la página de inicio de sesión.
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

// Verificar si se proporcionó el ID del pedido.
if (!isset($_GET['id'])) {
    echo "Error: No se proporcionó el ID del pedido.";
    exit;
}

$id_pedido = (int)$_GET['id'];

// Obtener información del pedido principal.
// Se unen las tablas 'pedidos' y 'usuarios' para obtener el nombre y correo del cliente.
$query = "SELECT p.*, u.nombre AS nombre_cliente, u.correo 
          FROM pedidos p 
          JOIN usuarios u ON p.id_usuario = u.id_usuario 
          WHERE p.id_pedido = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$resultado = $stmt->get_result();

// Si el pedido no se encuentra, mostrar un mensaje de error.
if ($resultado->num_rows === 0) {
    echo "Pedido no encontrado.";
    exit;
}
$pedido = $resultado->fetch_assoc();

// Obtener detalles de los productos del pedido.
// ¡IMPORTANTE! Se une la tabla 'detalle_pedido' con 'productos' para obtener el nombre
// y la imagen del producto.
$query_detalle = "SELECT dp.*, pr.nombre AS nombre_producto, pr.imagen AS imagen_producto 
                  FROM detalle_pedido dp 
                  JOIN productos pr ON dp.id_producto = pr.id_producto 
                  WHERE dp.id_pedido = ?";
$stmt_detalle = $conexion->prepare($query_detalle);
$stmt_detalle->bind_param("i", $id_pedido);
$stmt_detalle->execute();
$resultado_detalle = $stmt_detalle->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido #<?php echo $id_pedido; ?></title>
    <link rel="stylesheet" href="../styles/styles-login.css">
    <style>
        /* Estilos generales del cuerpo */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ADD8E6, #FFD1DC); /* Degradado pastel de azul a rosa */
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        /* Estilos del Header (simulado o desde header.php) */
        header {
            width: 100%;
            background: radial-gradient(circle, #FFB6C1 0%, #ADD8E6 100%); /* Degradado radial pastel */
            padding: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .site-logo {
            height: 150px;
            width: auto;
            display: block;
            margin: 0 auto 5px auto;
        }

        .site-title {
            color: #E60073; /* Rosa vibrante */
            font-family: 'Arial', sans-serif;
            font-size: 2.5em;
            margin: 0;
            line-height: 1.2;
        }

        .main-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .main-nav a {
            text-decoration: none;
            color: #E60073;
            font-weight: bold;
            padding: 8px 15px;
            transition: color 0.3s ease, background-color 0.3s ease;
            border-radius: 5px;
        }

        .main-nav a:hover {
            color: #ffffff;
            background-color: #CC0066;
        }


        /* Contenedor principal para el contenido */
        .container {
            max-width: 900px; /* Ancho ajustado para mejor visualización */
            margin: 20px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            flex-grow: 1;
        }

        /* Títulos */
        h1, h2 {
            text-align: center;
            color: #E60073; /* Rosa vibrante */
            margin-bottom: 25px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        h1 {
            font-size: 2.8em;
        }

        h2 {
            font-size: 2em;
            margin-top: 40px;
            border-bottom: 2px solid #FFD1DC; /* Línea de división sutil */
            padding-bottom: 10px;
            display: inline-block; /* Para que la línea se ajuste al ancho del texto */
            margin-left: auto;
            margin-right: auto;
            width: fit-content; /* Para centrar el h2 con la línea */
        }

        /* Información del pedido */
        .order-details-info {
            background-color: #fce4ec; /* Rosa muy claro */
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: grid; /* Usar CSS Grid para organizar la información */
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Columnas responsivas */
            gap: 15px; /* Espacio entre los elementos */
        }

        .order-details-info p {
            margin: 0; /* Eliminar margen predeterminado */
            font-size: 1.1em;
            color: #444;
            display: flex; /* Para alinear etiquetas y valores */
            align-items: baseline;
            flex-wrap: wrap; /* Permite que el texto se envuelva en pantallas pequeñas */
        }

        .order-details-info p strong {
            color: #CC0066; /* Rosa oscuro para etiquetas */
            display: inline-block;
            min-width: 140px; /* Ancho fijo para alinear las etiquetas */
            margin-right: 10px;
        }

        /* Estilos de tabla de productos */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Sombra más pronunciada */
            border-radius: 10px;
            overflow: hidden; /* Para que border-radius funcione con el overflow */
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }

        th {
            background-color: #ADD8E6; /* Azul claro */
            color: #155724; /* Verde oscuro */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.95em;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #eef7fc; /* Azul claro al pasar el ratón */
            transition: background-color 0.3s ease;
        }

        tfoot {
            background-color: #FFB6C1; /* Rosa claro para el pie de la tabla */
            font-weight: bold;
            color: #333;
        }

        tfoot th, tfoot td {
            padding: 15px;
            border-top: 2px solid #FFC0CB; /* Borde superior más fuerte */
        }

        /* Estilos para la imagen del producto */
        .product-image {
            width: 70px; /* Tamaño más manejable para la imagen */
            height: 70px;
            object-fit: cover; /* Recorta la imagen para que quepa sin distorsionarse */
            border-radius: 5px;
            border: 1px solid #ddd;
            vertical-align: middle; /* Alinea la imagen con el texto en la celda */
        }

        /* Botón de volver */
        .btn-volver {
            display: inline-block;
            background-color: #FFB6C1; /* Rosa claro */
            color: #333;
            padding: 12px 25px;
            margin-top: 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-volver:hover {
            background-color: #FFA0B6; /* Rosa ligeramente más oscuro */
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Mensaje de "No hay productos" */
        .no-products-message {
            text-align: center;
            font-size: 1.2em;
            color: #888;
            margin-top: 30px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 8px;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 40px;
            background-color: #ADD8E6; /* Fondo azul claro del pie de página */
            color: #333;
            border-top: 1px solid #99CADD;
            font-size: 0.9em;
            border-radius: 8px 8px 0 0;
            width: 100%;
        }

        /* Ajustes responsivos */
        @media (max-width: 768px) {
            .order-details-info {
                grid-template-columns: 1fr; /* Una sola columna en pantallas pequeñas */
            }

            table, tbody, tr, th, td {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #eee;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: calc(50% - 30px);
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #666;
            }

            /* Estilos específicos para la celda de imagen en móvil */
            td:nth-of-type(2) { /* Ajusta el número según la columna de imagen */
                text-align: center; /* Centra la imagen */
            }
            td:nth-of-type(2)::before {
                content: "Imagen"; /* Etiqueta específica para la imagen */
                text-align: left;
            }
        }
    </style>
</head>
<body>
<?php
// Incluye el archivo de encabezado. Si tienes un archivo `../including/header.php`,
// descomenta la siguiente línea y elimina el bloque <header> de HTML directo de abajo.
include '../including/header.php';
?>
<main class="container">
    <h1>Detalle del Pedido #<?php echo $id_pedido; ?></h1>

    <div class="order-details-info">
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre_cliente']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($pedido['correo']); ?></p>
        <p><strong>Fecha del Pedido:</strong> <?php echo $pedido['fecha_pedido']; ?></p>
        <p><strong>Fecha de Entrega:</strong> <?php echo $pedido['fecha_entrega']; ?></p>
        <p><strong>Método de Pago:</strong> <?php echo $pedido['metodo_pago']; ?></p>
        <p><strong>Estado:</strong> <?php echo $pedido['estado']; ?></p>
        <p><strong>Dirección de Entrega:</strong> <?php echo htmlspecialchars($pedido['direccion_entrega']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono_contacto']); ?></p>
        <p><strong>Total del Pedido:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
    </div>

    <h2>Productos del Pedido</h2>
    <?php if ($resultado_detalle->num_rows > 0): ?>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Imagen</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_productos = 0; // Se renombra para evitar conflicto con el total del pedido
                while ($detalle = $resultado_detalle->fetch_assoc()): 
                    $subtotal_producto = $detalle['cantidad'] * $detalle['precio_unitario'];
                    $total_productos += $subtotal_producto;
                ?>
                    <tr>
                        <td data-label="Producto"><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                        <td data-label="Imagen">
                            <?php if (!empty($detalle['imagen_producto'])): ?>
                                <img src="../imagenes/productos/<?php echo htmlspecialchars($detalle['imagen_producto']); ?>" alt="<?php echo htmlspecialchars($detalle['nombre_producto']); ?>" class="product-image">
                            <?php else: ?>
                                <span>No disponible</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Cantidad"><?php echo $detalle['cantidad']; ?></td>
                        <td data-label="Precio Unitario">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                        <td data-label="Subtotal">$<?php echo number_format($subtotal_producto, 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Total de Productos</th>
                    <th>$<?php echo number_format($total_productos, 2); ?></th>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p class="no-products-message">No hay productos registrados para este pedido.</p>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 30px;">
        <a href="pedidos_registrados.php" class="btn-volver">← Volver a la lista de pedidos</a>
    </div>
</main>

<?php
// Incluye el archivo de pie de página. Si tienes un archivo `../including/footer.php`,
// descomenta la siguiente línea y elimina el bloque <footer> de HTML directo de abajo.
include '../including/footer.php';
?>
</body>
</html>
