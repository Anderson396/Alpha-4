<?php
session_start();
require_once '../including/conexion.php';

// Verificar si viene el ID
if (isset($_GET['id'])) {
    $id_empleado = intval($_GET['id']);

    // Preparar y ejecutar eliminación
    $stmt = $conexion->prepare("DELETE FROM empleados WHERE id_empleado = ?");
    $stmt->bind_param("i", $id_empleado);

    if ($stmt->execute()) {
        // Eliminado con éxito
        header("Location: empleados.php?mensaje=eliminado");
    } else {
        echo "Error al eliminar empleado: " . $conexion->error;
    }

    $stmt->close();
} else {
    echo "ID no proporcionado.";
}
?>
