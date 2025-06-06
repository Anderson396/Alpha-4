<?php
// Este archivo establece la conexión con la base de datos MySQL utilizando la extensión MySQLi.

// Variables de configuración de la base de datos:
$servidor = 'localhost';         // Dirección del servidor de base de datos (normalmente 'localhost' si es local).
$usuario = 'root';               // Nombre de usuario para conectarse a MySQL.
$contrasena = '';                // Contraseña del usuario (en blanco por defecto en instalaciones locales).
$base_datos = 'datos_pasteleria';  // Nombre de la base de datos que se va a utilizar.

// Crea una nueva conexión usando la clase mysqli.
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verifica si ocurrió un error al intentar conectar.
if ($conexion->connect_error) {
    // Si hay un error, detiene la ejecución del script y muestra el mensaje.
    die("Conexión fallida: " . $conexion->connect_error);
}

// Si todo está correcto, a partir de aquí $conexion se puede usar en otros archivos para hacer consultas.
?>
