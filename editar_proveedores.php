<?php
session_start();
require_once '../including/conexion.php'; // Incluye tu archivo de conexión a la base de datos

// Verificar si la conexión a la base de datos es válida
if (!$conexion) {
    $_SESSION['error'] = "Error de conexión a la base de datos.";
    header("Location: listar_proveedores.php");
    exit();
}

// Inicializar variables para el formulario
$id_proveedor = null;
$nombre = '';
$producto = '';
$telefono = '';
$form_error = ''; // Para almacenar errores del formulario

// 1. Lógica para obtener los datos del proveedor (cuando la página se carga por primera vez)
if (isset($_GET['id'])) {
    $id_proveedor = $_GET['id'];

    // Prepara la consulta para seleccionar los datos del proveedor
    $stmt = $conexion->prepare("SELECT id_proveedor, nombre, producto, telefono FROM proveedores WHERE id_proveedor = ?");

    if ($stmt === false) {
        $_SESSION['error'] = "Error al preparar la consulta de selección: " . $conexion->error;
        header("Location: listar_proveedores.php");
        exit();
    }

    $stmt->bind_param("i", $id_proveedor); // 'i' porque id_proveedor es entero
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $proveedor = $resultado->fetch_assoc();
        $nombre = $proveedor['nombre'];
        $producto = $proveedor['producto'];
        $telefono = $proveedor['telefono'];
    } else {
        $_SESSION['error'] = "Proveedor no encontrado.";
        header("Location: listar_proveedores.php");
        exit();
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si no hay ID en la URL y no es un envío de formulario, es un acceso inválido
    $_SESSION['error'] = "No se especificó ningún proveedor para editar.";
    header("Location: listar_proveedores.php");
    exit();
}


// 2. Lógica para procesar el envío del formulario (cuando el usuario guarda los cambios)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanear los datos del formulario, incluyendo el ID oculto
    $id_proveedor = trim($_POST['id_proveedor'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $producto = trim($_POST['producto'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    // Validar los datos
    if (empty($id_proveedor) || !is_numeric($id_proveedor)) {
        $form_error = 'ID de proveedor inválido.';
    } elseif (empty($nombre)) {
        $form_error = 'El nombre del proveedor es obligatorio.';
    } elseif (empty($producto)) {
        $form_error = 'El producto principal es obligatorio.';
    } elseif (empty($telefono)) {
        $form_error = 'El número de teléfono es obligatorio.';
    }
    // Puedes añadir más validaciones aquí

    // Si no hay errores de validación, intentar actualizar en la base de datos
    if (empty($form_error)) {
        // Prepara la consulta SQL para actualizar el proveedor
        // ASEGÚRATE DE QUE LOS NOMBRES DE LAS COLUMNAS coincidan con tu DB
        $stmt = $conexion->prepare("UPDATE proveedores SET nombre = ?, producto = ?, telefono = ? WHERE id_proveedor = ?");

        if ($stmt === false) {
            $form_error = "Error al preparar la consulta de actualización: " . $conexion->error;
        } else {
            // Vincula los parámetros
            // 'sssi' indica 3 strings y 1 entero
            $stmt->bind_param("sssi", $nombre, $producto, $telefono, $id_proveedor);

            // Ejecuta la consulta
            if ($stmt->execute()) {
                $_SESSION['message'] = "Proveedor '" . htmlspecialchars($nombre) . "' actualizado exitosamente.";
                header("Location: listar_proveedores.php"); // Redirige de vuelta a la lista
                exit();
            } else {
                $form_error = "Error al actualizar el proveedor: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
// La conexión se cierra al final del script si no hay redirección
// $conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <link href="https://fonts.googleapis.com/css2?family=Savate&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/Style4.css">
    <style>
        /* Estilos específicos para el formulario de editar */
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 30px auto;
        }
        .form-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }
        .btn-submit {
            background-color: #007bff; /* Azul para guardar cambios */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #0056b3; /* Azul más oscuro al pasar el ratón */
        }
        .btn-back {
            display: block;
            width: calc(100% - 40px);
            text-align: center;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #6c757d; /* Gris para volver */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="contenedor">
        <div class="form-container">
            <h1>Editar Proveedor</h1>

            <?php if (!empty($form_error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($form_error); ?>
                </div>
            <?php endif; ?>

            <form action="editar_proveedores.php" method="POST">
                <input type="hidden" name="id_proveedor" value="<?php echo htmlspecialchars($id_proveedor); ?>">

                <div class="form-group">
                    <label for="nombre">Nombre del Proveedor:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                </div>

                <div class="form-group">
                    <label for="producto">Producto Principal:</label>
                    <input type="text" id="producto" name="producto" value="<?php echo htmlspecialchars($producto); ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" required>
                </div>

                <button type="submit" class="btn-submit">Guardar Cambios</button>
                <a href="listar_proveedores.php" class="btn-back">Cancelar y Volver</a>
            </form>
        </div>
    </main>
</body>
</html>
