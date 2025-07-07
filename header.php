<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
  <nav>
    <ul class="menu">
      <img src="/pagina_pasteleria/imagenes/logo.jpg.jpeg" style="height: 155px; vertical-align: middle;">
      <li><a href="/pagina_pasteleria/index.php">Inicio</a></li>

     <?php if (isset($_SESSION['user'])): ?>
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <li><a href="/pagina_pasteleria/admin/listar_proveedores.php">Manejo poveedores</a></li>
            <li><a href="/pagina_pasteleria/admin/pedidos.php">Gestion pedidos</a></li>
            <li><a href="/pagina_pasteleria/admin/productos.php">Lista productos</a></li>
            <li><a href="/pagina_pasteleria/admin/empleados.php">Control empleados</a></li>
          <?php else: ?>
            <li><a href="../pagina_pasteleria/perfil.php">Mi Perfil</a></li>
            <li><a href="../pagina_pasteleria/carrito/carrito.php">Tú carrito</a></li>
          <?php endif; ?>
          <li><a href="../pagina_pasteleria/login/cerrar_sesion.php">Cerrar sesión</a></li>
      <?php else: ?>
        <li><a href="/pagina_pasteleria/login/login.php">Iniciar sesión</a></li>
        <li><a href="/pagina_pasteleria/login/registrar.php">Crear cuenta</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
