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
$direccion = ''; // Agregado: Variable para la dirección
$form_error = ''; // Para almacenar errores del formulario

// 1. Lógica para obtener los datos del proveedor (cuando la página se carga por primera vez o hay un error de POST)
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $id_proveedor = $_GET['id'];

    // Prepara la consulta para seleccionar los datos del proveedor, incluyendo la dirección
    $stmt = $conexion->prepare("SELECT id_proveedor, nombre, producto, telefono, direccion FROM proveedores WHERE id_proveedor = ?");

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
        $direccion = $proveedor['direccion']; // Asignar la dirección obtenida
    } else {
        $_SESSION['error'] = "Proveedor no encontrado.";
        header("Location: listar_proveedores.php");
        exit();
    }
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['id'])) {
    // Si no hay ID en la URL y no es un envío de formulario, es un acceso inválido
    $_SESSION['error'] = "No se especificó ningún proveedor para editar.";
    header("Location: listar_proveedores.php");
    exit();
}


// 2. Lógica para procesar el envío del formulario (cuando el usuario guarda los cambios)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanear los datos del formulario, incluyendo el ID oculto y la dirección
    $id_proveedor = trim($_POST['id_proveedor'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $producto = trim($_POST['producto'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? ''); // Recoger la dirección del POST

    // Validar los datos
    if (empty($id_proveedor) || !is_numeric($id_proveedor)) {
        $form_error = 'ID de proveedor inválido.';
    } elseif (empty($nombre)) {
        $form_error = 'El nombre del proveedor es obligatorio.';
    } elseif (empty($producto)) {
        $form_error = 'El producto principal es obligatorio.';
    } elseif (empty($telefono)) {
        $form_error = 'El número de teléfono es obligatorio.';
    } elseif (empty($direccion)) { // Nueva validación para la dirección
        $form_error = 'La dirección es obligatoria.';
    }
    // Puedes añadir más validaciones aquí

    // Si no hay errores de validación, intentar actualizar en la base de datos
    if (empty($form_error)) {
        // Prepara la consulta SQL para actualizar el proveedor, incluyendo la dirección
        // ¡Importante! Asegúrate de que tu tabla 'proveedores' tiene una columna llamada 'direccion'
        $stmt = $conexion->prepare("UPDATE proveedores SET nombre = ?, producto = ?, telefono = ?, direccion = ? WHERE id_proveedor = ?");

        if ($stmt === false) {
            $form_error = "Error al preparar la consulta de actualización: " . $conexion->error;
        } else {
            // Vincula los parámetros
            // 'ssssi' indica 4 strings y 1 entero (para el ID)
            $stmt->bind_param("ssssi", $nombre, $producto, $telefono, $direccion, $id_proveedor);

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
        body {
            background: linear-gradient(to right, #c1d7f1, #f8c5d9);
            font-family: 'Savate', sans-serif;
            font-size: 18px; /* Tamaño de fuente base más grande */
            color: #222; /* Color de texto casi negro */
        }

        .contenedor { /* Contenedor principal de la página, para el fondo */
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco semitransparente */
            padding: 40px; /* Más padding para espacio */
            border-radius: 12px; /* Bordes más redondeados */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra más pronunciada */
            max-width: 550px; /* Ancho máximo para el formulario */
            width: 90%; /* Ajuste de ancho responsivo */
            box-sizing: border-box; /* Asegura que padding y border se incluyan en el ancho */
        }
        .form-container h1 {
            text-align: center;
            color: #222; /* Título casi negro */
            margin-bottom: 35px; /* Más espacio debajo del título */
            font-size: 2.5em; /* Título grande */
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px; /* Más espacio entre grupos de formulario */
        }
        .form-group label {
            display: block;
            margin-bottom: 8px; /* Espacio debajo de la etiqueta */
            font-weight: bold; /* Etiquetas en negrita */
            color: #333; /* Color oscuro para las etiquetas */
            font-size: 1.1em; /* Etiquetas más grandes */
        }
        /* Estilo para los inputs de texto y el input ID (lectura) */
        .form-group input[type="text"],
        .form-group input[type="number"], /* si el ID fuera number */
        .form-group input[type="text"][readonly] {
            width: 100%; /* Ocupa el 100% del ancho del contenedor */
            padding: 12px 15px; /* Más padding para los inputs */
            border: 1px solid #ccc;
            border-radius: 6px; /* Bordes más redondeados */
            font-size: 1.05em; /* Texto dentro del input más grande */
            box-sizing: border-box;
            color: #333; /* Texto de input casi negro */
        }
        .form-group input[type="text"]:focus {
            border-color: #f8c5d9; /* Color de borde al enfocar, usando un color del fondo */
            outline: none;
            box-shadow: 0 0 8px rgba(248, 197, 217, 0.5); /* Sombra suave al enfocar */
        }
        /* Estilo específico para input de solo lectura */
        .form-group input[readonly] {
            background-color: #f0f0f0; /* Fondo ligeramente gris para indicar que es de solo lectura */
            cursor: not-allowed; /* Cambia el cursor para indicar que no se puede editar */
        }

        /* Botones: Estilo unificado y que combine con el fondo */
        .btn-submit,
        .btn-back {
            display: block;
            width: 100%;
            padding: 15px 20px; /* MISMO PADDING PARA AMBOS */
            border: none;
            border-radius: 8px; /* Bordes redondeados */
            cursor: pointer;
            font-size: 1.15em; /* Texto de los botones más grande */
            font-weight: bold;
            color: #222; /* Texto de los botones casi negro */
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-sizing: border-box; /* Fundamental para que el padding se incluya en el ancho total del 100% */
        }

        .btn-submit {
            background-color: rgba(238, 182, 203, 0.85); /* Tono rosado suave, similar a los botones de agregar */
            margin-top: 20px; /* Espacio entre el último input y el primer botón */
            margin-bottom: 15px; /* Espacio entre el botón de Guardar y Volver */
        }

        .btn-submit:hover {
            background-color: rgba(221, 157, 181, 0.95); /* Un poco más oscuro al pasar el ratón */
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: rgba(193, 215, 241, 0.85); /* Tono azul claro, similar al otro lado del fondo */
            text-decoration: none; /* Si es un enlace */
        }

        .btn-back:hover {
            background-color: rgba(170, 190, 220, 0.95); /* Un poco más oscuro al pasar el ratón */
            transform: translateY(-2px);
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1em;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="contenedor">
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
                    <label for="id_proveedor_display">ID de Proveedor:</label>
                    <input type="text" id="id_proveedor_display" value="<?php echo htmlspecialchars($id_proveedor); ?>" readonly>
                </div>

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

                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>" required>
                </div>

                <button type="submit" class="btn-submit">Guardar Cambios</button>
                <a href="listar_proveedores.php" class="btn-back">Cancelar y Volver</a>
            </form>
        </div>
    </div>
</body>
</html>
