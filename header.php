<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$user = $_SESSION['usuario'] ?? null;
$rol  = $_SESSION['rol']    ?? null;
?>
<header>
  <nav>
    <ul class="menu">
      <li><a href="/pagina_pasteleria/index.php">Inicio</a></li>
      <li><a href="/pagina_pasteleria/carrito/carrito.php">Carrito</a></li>

      <?php if (!$user): ?>
        <li><a href="/pagina_pasteleria/login/login.php">Iniciar sesión</a></li>
        <li><a href="/pagina_pasteleria/login/registrar.php">Crear cuenta</a></li>
      <?php else: ?>
        <li><a href="/pagina_pasteleria/perfil.php">Mi Perfil</a></li>
        <?php if ($rol === 'admin'): ?>
          <li><a href="/pagina_pasteleria/admin/productos.php">Productos</a></li>
          <li><a href="/pagina_pasteleria/admin/listar_proveedores.php">Proveedores</a></li>
        <?php endif; ?>
        <li><a href="/pagina_pasteleria/login/cerrar_sesion.php">Cerrar sesión</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
