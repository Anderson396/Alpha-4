<?php
session_start();
require_once '../including/conexion.php';

// Check if the user is logged in and has admin role.
// If not, redirect to the login page.
if (!isset($_SESSION['user']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

// Query to retrieve all orders, joining with the users table
// to get the client's name. Orders are sorted by ID in ascending order.
$query = "SELECT p.*, u.nombre AS nombre_usuario
          FROM pedidos p
          JOIN usuarios u ON p.id_usuario = u.id_usuario
          ORDER BY p.id_pedido ASC"; // Order from smallest to largest by ID

$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Pedidos Registrados</title>
    <style>
        /* General Body Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px; /* Added padding to body for overall spacing */
            background: linear-gradient(to right, #ADD8E6, #FFD1DC); /* Light blue to light pink gradient */
            color: #333; /* Dark gray text for readability */
            line-height: 1.6;
            min-height: 100vh; /* Ensure full height for gradient */
            box-sizing: border-box; /* Include padding in element's total width and height */
        }

        /* Container for Main Content */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 30px; /* Increased padding for container */
            background-color: #ffffff; /* White background for the content area */
            border-radius: 12px; /* More rounded corners */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* Stronger shadow for depth */
        }

        /* Main Heading */
        h1 {
            text-align: center;
            margin-bottom: 30px; /* More space below heading */
            color: #E60073; /* Vibrant pink for the title */
            font-size: 2.8em; /* Larger font size */
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1); /* Subtle text shadow */
        }

        /* Table Styles */
        .tabla-pedidos { /* Changed class from tabla-proveedores to tabla-pedidos for specificity */
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px; /* Space above the table */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Shadow for the table */
            border-radius: 8px; /* Rounded corners for the table itself */
            overflow: hidden; /* Ensures rounded corners are visible with overflow */
        }

        .tabla-pedidos th, .tabla-pedidos td {
            padding: 15px; /* Increased padding in cells */
            text-align: left;
            border-bottom: 1px solid #e0e0e0; /* Lighter border color */
            font-size: 0.95em; /* Slightly smaller font for table content */
            color: #444; /* Darker gray for table text */
        }

        .tabla-pedidos th {
            background-color: #ADD8E6; /* Light blue for table headers */
            color: #155724; /* Dark green-like color for header text to contrast */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px; /* Slight letter spacing */
            font-size: 1em;
        }

        .tabla-pedidos tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternate row color */
        }

        .tabla-pedidos tbody tr:hover {
            background-color: #eef7fc; /* Lighter blue on hover for rows */
            transition: background-color 0.3s ease;
        }

        /* Link Styles (general) */
        a {
            color: #E60073; /* Vibrant pink for general links */
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #CC0066; /* Darker pink on hover */
            text-decoration: underline;
        }

        /* Button Styles */
        .btn, .btn-editar, .btn-eliminar, .btn-agregar {
            display: inline-block;
            padding: 10px 18px; /* Larger padding for buttons */
            margin: 5px; /* Consistent margin */
            font-size: 1em; /* Standard font size for buttons */
            text-decoration: none;
            color: #fff; /* White text for buttons */
            border-radius: 6px; /* More rounded button corners */
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: none; /* Remove default border */
        }

        /* Specific Button Colors */
        .btn-editar { /* "Ver" button for order details */
            background-color: #FFB6C1; /* Light pink for 'Ver' button */
            color: #333; /* Dark text for light button */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-editar:hover {
            background-color: #FFA0B6; /* Slightly darker pink on hover */
            transform: translateY(-2px); /* Lift effect */
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Placeholder for potential future delete/add buttons (not in this specific context) */
        .btn-eliminar {
            background-color: #dc3545; /* Red for delete */
        }

        .btn-eliminar:hover {
            background-color: #c82333;
        }

        .btn-agregar {
            background-color: #28a745; /* Green for add */
        }

        .btn-agregar:hover {
            background-color: #218838;
        }

        /* Message for no orders */
        p {
            text-align: center;
            font-size: 1.1em;
            color: #555;
            margin-top: 30px;
        }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            .tabla-pedidos, .tabla-pedidos tbody, .tabla-pedidos tr, .tabla-pedidos th, .tabla-pedidos td {
                display: block; /* Make table elements stack */
            }

            .tabla-pedidos thead {
                display: none; /* Hide table header */
            }

            .tabla-pedidos tr {
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            }

            .tabla-pedidos td {
                text-align: right; /* Align cell content to the right */
                padding-left: 50%; /* Make space for pseudo-elements */
                position: relative;
            }

            .tabla-pedidos td::before {
                content: attr(data-label); /* Use data-label for content */
                position: absolute;
                left: 15px;
                width: calc(50% - 30px);
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #555;
            }
        }
    </style>
</head>
<body>
<?php include '../including/header.php'; // This would contain your main navigation ?>

<main class="container">
    <h1>Pedidos Realizados</h1>

    <?php if ($resultado->num_rows > 0): ?>
        <table class="tabla-pedidos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha Pedido</th>
                    <th>Entrega</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td data-label="ID"><?php echo $pedido['id_pedido']; ?></td>
                        <td data-label="Cliente"><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></td>
                        <td data-label="Fecha Pedido"><?php echo $pedido['fecha_pedido']; ?></td>
                        <td data-label="Entrega"><?php echo $pedido['fecha_entrega']; ?></td>
                        <td data-label="Estado"><?php echo $pedido['estado']; ?></td>
                        <td data-label="Total">$<?php echo number_format($pedido['total'], 2); ?></td>
                        <td data-label="Método de Pago"><?php echo $pedido['metodo_pago']; ?></td>
                        <td data-label="Dirección"><?php echo htmlspecialchars($pedido['direccion_entrega']); ?></td>
                        <td data-label="Teléfono"><?php echo htmlspecialchars($pedido['telefono_contacto']); ?></td>
                        <td data-label="Detalles">
                            <a class="btn btn-editar" href="detalle_pedido.php?id=<?php echo $pedido['id_pedido']; ?>">Ver</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay pedidos registrados.</p>
    <?php endif; ?>
</main>

<?php // include '../including/footer.php'; ?>

</body>
</html>
