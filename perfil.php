<?php
session_start();
require_once 'including/conexion.php'; // Asegúrate de que esta ruta sea correcta

// Verificamos si el usuario ya está autenticado
// Las líneas comentadas fueron del código original, si necesitas esta verificación, descoméntala y ajusta la lógica si 'admin' también se usa.
/*
if (!isset($_SESSION['user'])) { // Se asume que 'user' es suficiente para la sesión de un usuario normal.
    header("Location: login/login.php");
    exit();
}
*/

$correo = $_SESSION['user'] ?? ''; // Usar operador null coalescing para evitar errores si 'user' no está seteado
$error = '';
$mensaje = '';

// Si no hay correo en la sesión, redirigir al login
if (empty($correo)) {
    session_destroy();
    header("Location: login/login.php");
    exit();
}

// Obtenemos sus datos actuales
$stmt = $conexion->prepare("SELECT nombre, direccion, telefono FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    session_destroy(); // Si no se encuentra el usuario, destruir sesión y redirigir
    header("Location: login/login.php");
    exit();
}

$usuario = $resultado->fetch_assoc();

// Preparamos el formulario si fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if (empty($direccion) || empty($telefono)) {
        $error = "Por favor, completa ambos campos.";
    } else {
        $stmt_update = $conexion->prepare("UPDATE usuarios SET direccion = ?, telefono = ? WHERE correo = ?");
        $stmt_update->bind_param("sss", $direccion, $telefono, $correo);
        if ($stmt_update->execute()) {
            // Actualizar la sesión con los nuevos datos (opcional, pero buena práctica si los usas en otros lugares)
            $_SESSION['direccion'] = $direccion;
            $_SESSION['telefono'] = $telefono;
            header("Location: perfil.php?actualizado=1"); // Redirige para evitar el reenvío del formulario
            exit();
        } else {
            $error = "Error al actualizar. Intenta de nuevo. " . $stmt_update->error; // Muestra el error de MySQL si lo hay
        }
        $stmt_update->close();
    }
}
$stmt->close(); // Cerrar el statement de selección inicial
$conexion->close(); // Cerrar la conexión a la base de datos al final
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mi Perfil - Dulces Creaciones</title>
  <style>
    /* ================================================== */
    /* ESTILOS GLOBALES Y BASE */
    /* ================================================== */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        display: flex;
        flex-direction: column; /* Para que el header, main y mensajes se apilen */
        align-items: center;
        min-height: 100vh; /* Ocupa al menos el alto completo de la ventana */
        margin: 0;
        color: #333;
    }

    /* ================================================== */
    /* ESTILOS DEL ENCABEZADO (HEADER) */
    /* ================================================== */
    header {
        width: 100%;
        background-color: #ffffff; /* Fondo blanco para el área del encabezado */
        padding: 20px 0; /* Espacio arriba y abajo del contenido del header */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sombra sutil debajo del header */
        display: flex; /* Usamos Flexbox para organizar el contenido */
        flex-direction: column; /* Apila los elementos (logo y nav) verticalmente */
        align-items: center; /* Centra los elementos hijos horizontalmente */
        justify-content: center; /* Centra los elementos hijos verticalmente si hay espacio */
    }

    /* Contenedor del logo y el título */
    .logo {
        text-align: center; /* Centra la imagen y el texto dentro de este div */
        margin-bottom: 20px; /* Espacio entre el área del logo/título y la navegación */
    }

    /* Estilo para la imagen del logo */
    .site-logo {
        height: 150px; /* Altura fija para el logo como en tu imagen */
        width: auto; /* Mantiene la proporción de la imagen */
        display: block; /* Hace que la imagen se comporte como un bloque para que el h2 vaya debajo */
        margin: 0 auto 5px auto; /* Centra la imagen horizontalmente y le da un pequeño margen inferior para separar del título */
    }

    /* Estilo para el título "Dulces Creaciones" */
    .site-title {
        color: #ff69b4; /* Color rosa fuerte */
        font-family: 'Arial', sans-serif; /* Puedes cambiar esto por una fuente más artística si la importas */
        font-size: 2.5em; /* Tamaño del texto del título */
        margin: 0; /* Elimina los márgenes predeterminados del h2 */
        line-height: 1.2; /* Ajusta el espaciado de línea si la fuente es muy grande */
    }

    /* Estilos para la navegación principal */
    .main-nav ul {
        list-style: none; /* Elimina los puntos de la lista */
        padding: 0;
        margin: 0;
        display: flex; /* Hace que los elementos de la lista se muestren en fila */
        justify-content: center; /* Centra los elementos de la navegación horizontalmente */
        flex-wrap: wrap; /* Permite que los elementos se envuelvan a la siguiente línea en pantallas pequeñas */
        gap: 20px; /* Espacio entre cada elemento de la navegación (Inicio, Mi Perfil, etc.) */
    }

    .main-nav a {
        text-decoration: none; /* Elimina el subrayado de los enlaces */
        color: #ff69b4; /* Color rosa fuerte para los enlaces */
        font-weight: bold;
        padding: 8px 15px; /* Espacio interno de los enlaces para que parezcan botones */
        transition: color 0.3s ease, background-color 0.3s ease; /* Transición suave para efectos hover */
        border-radius: 5px; /* Bordes ligeramente redondeados */
    }

    .main-nav a:hover {
        color: #ffffff; /* Texto blanco al pasar el ratón */
        background-color: #ff1493; /* Fondo rosa más oscuro al pasar el ratón */
    }

    /* ================================================== */
    /* ESTILOS DEL CONTENEDOR PRINCIPAL Y FORMULARIO (PERFIL) */
    /* ================================================== */
    .container {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px; /* Ancho un poco más grande para el perfil */
        text-align: center;
        margin-top: 30px; /* Espacio debajo del header */
        margin-bottom: 30px;
        box-sizing: border-box;
    }

    h1 {
        color: #333;
        margin-bottom: 30px;
        font-size: 32px;
        text-align: center;
    }

    /* Estilos para mensajes de error y éxito */
    .error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #f5c6cb;
        margin-bottom: 20px;
        text-align: left;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #c3e6cb;
        margin-bottom: 20px;
        text-align: left;
    }

    /* Contenedor específico para el formulario */
    .form-contenedor {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #eee;
        border-radius: 8px;
        background-color: #fdfdfd;
        text-align: left; /* Alinea el contenido del formulario a la izquierda */
    }

    fieldset {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    legend {
        font-size: 20px;
        font-weight: bold;
        color: #ff69b4; /* Color de pastelería */
        padding: 0 10px;
        margin-left: -10px; /* Ajuste para que la leyenda no tenga tanto espacio */
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px; /* Espacio entre elementos del formulario */
    }

    label {
        font-weight: bold;
        color: #555;
        margin-bottom: 5px;
        display: block; /* Asegura que la etiqueta esté en su propia línea */
    }

    input[type="text"],
    input[type="email"] {
        width: calc(100% - 24px); /* Ajusta el ancho para padding y borde (12px de padding a cada lado) */
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        box-sizing: border-box; /* Asegura que padding y borde se incluyan en el 'width' */
        margin-bottom: 10px; /* Espacio después de cada input */
    }

    input[type="text"]:disabled { /* Estilo para el campo de nombre deshabilitado */
        background-color: #e9e9e9;
        color: #777;
        cursor: not-allowed;
    }

    input[type="text"]:focus,
    input[type="email"]:focus {
        border-color: #ffb6c1; /* Rosa claro para el foco */
        outline: none;
        box-shadow: 0 0 5px rgba(255, 182, 193, 0.5);
    }

    /* Estilo del botón "Actualizar Datos" */
    .btn {
        background-color: #ff69b4; /* Rosa fuerte */
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 20px;
        align-self: center; /* Centra el botón si el padre es flex-direction column */
        min-width: 180px; /* Asegura un ancho mínimo para el botón */
    }

    .btn:hover {
        background-color: #ff1493; /* Rosa más oscuro al pasar el ratón */
    }

    /* Estilo para el mensaje de éxito del pedido (si se activa) */
    .mensaje-exito {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #c3e6cb;
        margin-top: 20px;
        width: 100%;
        max-width: 600px;
        text-align: center;
        box-sizing: border-box;
    }

    /* ================================================== */
    /* MEDIA QUERIES PARA RESPONSIVIDAD */
    /* ================================================== */
    @media (max-width: 768px) {
        /* Encabezado */
        header {
            padding: 15px 0;
        }
        .site-logo {
            height: 100px; /* Reduce el tamaño del logo en pantallas pequeñas */
            margin-bottom: 5px;
        }
        .site-title {
            font-size: 1.8em; /* Reduce el tamaño del título en pantallas pequeñas */
        }
        .main-nav ul {
            flex-direction: column; /* Apila los elementos de la navegación verticalmente */
            align-items: center; /* Centra los enlaces apilados */
            gap: 10px; /* Menos espacio vertical entre enlaces apilados */
            margin-top: 10px; /* Un poco de espacio extra arriba de la nav cuando está apilada */
        }
        .main-nav a {
            width: calc(100% - 30px); /* Asegura que el enlace ocupe casi todo el ancho disponible */
            text-align: center; /* Centra el texto dentro del enlace */
            max-width: 200px; /* Limita el ancho máximo para que no se extienda demasiado */
        }

        /* Contenido principal y formulario */
        .container {
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
        }
        .btn {
            width: 100%; /* Botón al 100% en pantallas pequeñas */
        }
    }
  </style>
</head>
<body>
<header>
    <div class="logo">
    <img src="/pagina_pasteleria/imagenes/logo.jpg.jpeg" alt="Dulces Creaciones Logo" class="site-logo">
    <h2 class="site-title">Dulces Creaciones</h2>
</div>
    <nav class="main-nav">
        <ul>
            <li><a href="/pagina_pasteleria/index.php">Inicio</a></li>
            <li><a href="/pagina_pasteleria/carrito/carrito.php">Tu Carrito</a></li>
            <li><a href="/pagina_pasteleria/login/cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>
<main class="container">
    <h1>Mi Perfil</h1>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif (isset($_GET['actualizado']) && $_GET['actualizado'] == 1): ?>
        <p class="success">¡Datos actualizados correctamente!</p>
    <?php endif; ?>

    <div class="form-contenedor">
        <form method="POST" action="" novalidate>
            <fieldset>
                <legend>Información personal</legend>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" disabled />

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
