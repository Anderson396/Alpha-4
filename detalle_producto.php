<?php
session_start();
require_once '../including/conexion.php';

// Mensajes por defecto
$mensaje = null;
$error = null;

// Validar que venga el ID del producto por GET
if (!isset($_GET['id'])) {
    echo "Producto no válido.";
    exit;
}

$id = $_GET['id'];

// Consultar producto desde la base de datos
$query = "SELECT * FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

// Validar que el producto exista
if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}

// Verificamos si el campo 'stock' existe y tiene valor
$stock_disponible = isset($producto['stock']) ? (int)$producto['stock'] : 0;

// Procesar envío del formulario para agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_carrito'])) {
    $cantidad = (int) $_POST['cantidad'];

    if ($cantidad > $stock_disponible) {
        $error = "La cantidad solicitada excede el stock disponible.";
    } else {
        $id = $_POST['id_producto'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $imagen = $producto['imagen']; // <--- ESTA ES LA LÍNEA CRUCIAL AÑADIDA O MODIFICADA

        // Crear carrito si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Agregar o actualizar cantidad del producto en el carrito
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
            // Si el producto ya está, asegúrate de que la imagen también esté si no estaba antes
            if (!isset($_SESSION['carrito'][$id]['imagen'])) {
                $_SESSION['carrito'][$id]['imagen'] = $imagen;
            }
        } else {
            $_SESSION['carrito'][$id] = [
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'imagen' => $imagen // <--- ASEGURARSE DE GUARDAR LA IMAGEN AQUÍ
            ];
        }
        $mensaje = "Producto agregado al carrito.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($producto['nombre']); ?></title>
    <style>
        /* Estilos generales del cuerpo y contenedor principal */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Un gris claro para el fondo de la página */
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Asegura que el footer se quede abajo */
        }

        /* El estilo que ya tienes para el contenedor principal de la página,
           que engloba el contenido de cada vista (productos, proveedores, etc.) */
        .container {
            max-width: 960px; /* Ancho máximo del contenedor principal */
            margin: 20px auto; /* Centra el contenedor y le da espacio arriba/abajo */
            padding: 20px;
            background-color: rgb(248, 239, 247); /* Color de fondo claro, similar a tu imagen */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            flex-grow: 1; /* Permite que el contenedor crezca para empujar el footer */
        }

        /* Encabezado y pie de página (adaptados de tu CSS) */
        header {
            text-align: center;
            padding: 15px 0;
            background-color: #333;
            color: white;
            margin-bottom: 20px; /* Añadido margen inferior para separar del contenido */
        }
        header h1, footer p {
            margin: 0;
        }
        footer {
            text-align: center;
            padding: 15px 0;
            background-color: #333;
            color: white;
            margin-top: auto; /* Empuja el footer hacia abajo */
        }

        /* Estilos específicos para la sección de detalle del producto */
        .detalle {
            display: flex; /* Usa flexbox para organizar los elementos internos (imagen, texto, etc.) */
            align-items: flex-start; /* Alinea los elementos al inicio de su eje cruzado */
            gap: 30px; /* Espacio entre la imagen y el texto del producto */
            margin-top: 20px; /* Espacio arriba del recuadro del producto */
            padding: 25px; /* Espaciado interno del recuadro */
            border: 1px solid #e0e0e0; /* Borde suave para el recuadro */
            border-radius: 10px; /* Esquinas redondeadas */
            background-color: #ffffff; /* Fondo blanco para el recuadro del producto */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Sombra sutil */
            flex-wrap: wrap; /* Permite que los elementos se envuelvan en pantallas pequeñas */
        }

        .detalle img {
            max-width: 280px; /* Ancho máximo de la imagen del producto */
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            object-fit: cover; /* Asegura que la imagen cubra el área sin distorsión */
            flex-shrink: 0; /* Evita que la imagen se encoja si no hay espacio */
        }

        .detalle h2 { /* Título del producto */
            font-size: 2.2em;
            color: #333;
            margin-top: 0;
            margin-bottom: 15px;
            text-align: center; /* Centrar el título dentro del contenedor */
            width: 100%; /* Asegura que el título ocupe todo el ancho disponible */
        }

        /* Contenedor para el texto del producto (descripción, precio, stock) */
        .detalle-info {
            flex-grow: 1; /* Permite que este div ocupe el espacio restante */
            display: flex;
            flex-direction: column;
            gap: 10px; /* Espacio entre los párrafos de info */
        }

        .detalle p {
            margin: 0;
            font-size: 1.1em;
            line-height: 1.5;
        }

        .detalle p strong {
            color: #000;
        }

        .detalle p:nth-of-type(1) { /* Para la descripción */
            font-style: italic;
            color: #555;
        }

        .detalle p:nth-of-type(2) { /* Para el precio */
            font-size: 1.4em;
            font-weight: bold;
            color: #28a745; /* Color verde para el precio */
        }

        /* Estilo para el formulario de cantidad y botón */
        .detalle form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap; /* Permite que los elementos del formulario se envuelvan */
        }

        .detalle form label {
            font-weight: bold;
            font-size: 1.1em;
        }

        .detalle form input[type="number"] {
            width: 70px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            text-align: center;
            -moz-appearance: textfield; /* Para Firefox */
        }
        /* Ocultar flechas en input number para Chrome/Safari/Edge */
        .detalle form input[type="number"]::-webkit-outer-spin-button,
        .detalle form input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .detalle form button[type="submit"] {
            background-color: rgb(196, 147, 201); /* Color morado suave similar al de tu carrito */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.05em;
            transition: background-color 0.3s ease;
        }

        .detalle form button[type="submit"]:hover {
            background-color: #8c5a94; /* Tono más oscuro al pasar el ratón */
        }

        /* Estilos para los mensajes de stock agotado */
        .detalle p.no-stock {
            color: #dc3545; /* Rojo para "sin stock" */
            font-weight: bold;
            margin-top: 15px;
        }

        /* Estilos para los enlaces de navegación inferior */
        .container nav {
            margin-top: 30px;
            text-align: center;
        }

        .container nav .btn {
            display: inline-block; /* Para que los botones estén en línea */
            background-color: #007bff; /* Azul estándar, puedes cambiarlo */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            margin: 0 10px; /* Espacio entre los botones */
            transition: background-color 0.3s ease;
        }

        .container nav .btn:hover {
            background-color: #0056b3;
        }

        /* Estilos para mensajes de éxito/error (usando tus estilos existentes) */
        /* Nota: estos estilos de <p style="..."> sobrescriben el CSS.
           Es mejor usar clases para estos mensajes. */
        .container p[style="color: green;"] {
            color: #155724;
            background-color: rgb(235, 206, 229);
            border: 1px solid rgb(172, 212, 202);
            padding: 10px;
            border-radius: .25rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .container p[style="color: red;"] {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: .25rem;
            margin-bottom: 20px;
            text-align: center;
        }


        /* Media Queries para responsividad (ajuste para pantallas pequeñas) */
        @media (max-width: 768px) {
            .detalle {
                flex-direction: column; /* Apila imagen y texto en pantallas pequeñas */
                align-items: center; /* Centra los elementos apilados */
                gap: 20px;
            }
            .detalle img {
                max-width: 80%; /* Imagen un poco más pequeña */
            }
            .detalle h2, .detalle p {
                text-align: center; /* Centra el texto en pantallas pequeñas */
            }
            .detalle-info {
                align-items: center; /* Centra los párrafos de info */
            }
            .detalle form {
                justify-content: center; /* Centra el formulario */
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 15px;
                padding: 15px;
            }
            .detalle {
                padding: 15px;
                gap: 15px;
            }
            .detalle h2 {
                font-size: 1.8em;
            }
            .detalle form {
                flex-direction: column;
                width: 100%;
            }
            .detalle form input[type="number"] {
                width: 100%;
            }
            .detalle form button[type="submit"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<main class="container">
    <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>

    <?php if ($mensaje): ?>
        <p style="color: green;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <section class="detalle">
       <img src="../imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
        <div class="detalle-info">
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
            <p><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
            <p><strong>Disponible:</strong> <?php echo $stock_disponible; ?></p>

            <?php if ($stock_disponible > 0): ?>
            <form method="post" action="">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" value="1" min="1" max="<?php echo $stock_disponible; ?>" required>
                <button type="submit" name="agregar_carrito">Agregar al carrito</button>
            </form>
            <?php else: ?>
                <p class="no-stock"><strong>Este producto no tiene stock disponible.</strong></p>
            <?php endif; ?>
        </div>
    </section>

    <nav>
        <a href="../index.php" class="btn">Volver a inicio</a>
        <a href="../carrito/carrito.php" class="btn">Ver carrito</a>
    </nav>
</main>

<footer>
    <h2>&copy; 2025 Mi Tienda</h2>
</footer>
</body>
</html>
