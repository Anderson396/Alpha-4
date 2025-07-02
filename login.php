<?php
session_start();
require_once '../including/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $query = 'SELECT * FROM usuarios WHERE correo = ?';
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $hash = $usuario['contrasena'];

        $esHashValido = (
            (strlen($hash) === 60 && (substr($hash, 0, 4) === '$2y$' || substr($hash, 0, 4) === '$2a$')) &&
            password_verify($contrasena, $hash)
        );
        $esMd5Valido = ($hash === md5($contrasena));

        if ($esHashValido || $esMd5Valido) {
            // Guardar datos en la sesión
            $_SESSION['usuario'] = $usuario['correo'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['telefono'] = $usuario['telefono'];
            $_SESSION['direccion'] = $usuario['direccion'];

            // Redireccionar según el rol
            if ($usuario['rol'] === 'admin') {
                header('Location: bienvenida.php'); // Aquí puedes redirigir a una página especial de admin si lo deseas
            } else {
                header('Location: ../index.php');
            }
            exit();
        } else {
            $error = 'Contraseña incorrecta.';
        }
    } else {
        $error = 'Usuario no encontrado.';
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pastelería</title>
</head>
<body>
<!-- Creamos un contenedor para el formulario del login -->
 <div class="login-contenedor">
    <h1>Iniciar sesión</h1>

<!-- Error de inicio de sesión -->
    <?php if (isset($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<!--  Creamos un formulario para el login -->
<!-- Correo -->
  <form method="POST" action="">
    <label form="correo">Correo eléctronico: </label>
    <input type="email" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required />

<!-- Contraseña -->
    <label form="contrasena">Contraseña: </label>
    <input type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required />

    <button type="submit">Entrar</button>
  </form>

<!-- Agregamos un enlace para aquellos usuarios que aún no tienen una cuenta registrada -->
        <div class="register-link">
            <p>¿No tienes una cuenta en está página? <a href="registrar.php">Regístrate aquí</a></p>
        </div>
    </div>
</body>
</html>
