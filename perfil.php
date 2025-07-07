<?php
session_start();
require_once 'including/conexion.php';

// Verificamos si el usuario ya está autenticado
//if (!isset($_SESSION['user' && 'admin'])) {
//    header("Location: login/login.php");
  //  exit();
//}

$correo = $_SESSION['user'];
$error = '';
$mensaje = '';

// Obtenemos sus datos actuales que este tenga
$stmt = $conexion->prepare("SELECT nombre, direccion, telefono FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    session_destroy();
    header("Location: login/login.php");
    exit();
}

$usuario = $resultado->fetch_assoc();

// Preparamos el formulario si fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);

    if (empty($direccion) || empty($telefono)) {
        $error = "Por favor, completa ambos campos.";
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET direccion = ?, telefono = ? WHERE correo = ?");
        $stmt->bind_param("sss", $direccion, $telefono, $correo);
        if ($stmt->execute()) {
            header("Location: perfil.php?actualizado=1");
            exit();
        } else {
            $error = "Error al actualizar. Intenta de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mi Perfil - Dulces Creaciones</title>
  <link rel="stylesheet" href="styles/estilo_unificado.css" />
</head>
<body>

<?php include('including/header.php'); ?>

<main class="container">
    <h1>Mi Perfil</h1>

    <!-- Mostrar errores -->
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif ($mensaje): ?>
        <p class="success"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <!-- Formulario dentro de contenedor estilizado -->
    <div class="form-contenedor">
        <form method="POST" action="" novalidate>
            <fieldset>
                <legend>Información personal</legend>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" disabled />

                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" required
                       value="<?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?>" />

                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" required
                       pattern="[0-9+\-\s]+" title="Ingrese un número válido"
                       value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" />
            </fieldset>

            <button type="submit" class="btn">Actualizar Datos</button>
        </form>
    </div>
</main>
<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'pedido_exitoso'): ?>
    <div class="mensaje-exito">
        <p>¡Gracias por su compra! Haremos el envío lo antes posible a su dirección.</p>
    </div>
<?php endif; ?>
</body>
</html>
