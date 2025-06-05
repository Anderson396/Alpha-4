<?php
//Iniciamos sesión para poder usar las varables de sesión
session_start();

//Conectamos a la base de datos
require_once '../including/conexion.php';

// Verificamos si el usuraio está registrado y si es administrador
if (!isset($SESSION ['rol']) || $SESSION ['rol'] != 'admin') {
   // Y si esto no es así, lo redirigiremos a la página de inicio
   header("Location ../index.php");
   exit; // Terminamos el script despues de redirigir
}

// Obtenemos los datos del formulario mediante POST
if ($SERVER['REQUEST_METHOD'] === 'POST') {
   // Tomamos los datos enviados del formulario
   $nombre = $_POST['nombre'];
   $descripcion = $_POST['descripcion'];
   $precio = $_POST['precio'];

   // Obtene la imagen cargada por el usuario 
   
}
?>