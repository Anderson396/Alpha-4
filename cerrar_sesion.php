<?php
session_start();       // Iniciamos la sesión
session_unset();       // Limpiamos todas las variables de sesión
session_destroy();     // Destruimos la sesión
header("Location: /pagina_pasteleria/index.php"); // Redirigimos al inicio
exit();                // Terminamos el script
?>
