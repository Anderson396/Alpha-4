<?php
session_start();
require_once '../including/conexion.php';

// Verificar que la conexión a la base de datos sea válida
if (!$conexion) {
    $_SESSION['error'] = "Error de conexión a la base de datos en eliminar_proveedores.php.";
    header("Location: Listar_proveedores.php");
    exit();
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $proveedor_id = $_GET['id']; // Esta es la variable correcta

    // Prepara una declaración DELETE para prevenir SQL injection
    $query_delete = "DELETE FROM proveedores WHERE id_proveedor = ?";
    $stmt = $conexion->prepare($query_delete);

    // --- CÓDIGO DE DEPURACIÓN ---
    if ($stmt === false) {
        $_SESSION['error'] = "Error al preparar la consulta de eliminación: " . $conexion->error .
                             "<br>Consulta intentada: " . htmlspecialchars($query_delete);
        header("Location: Listar_proveedores.php");
        exit();
    }
    // --- FIN CÓDIGO DE DEPURACIÓN ---

    // ¡CAMBIO AQUÍ! Usar $proveedor_id en lugar de $id_proveedor
    $stmt->bind_param("i", $proveedor_id); // 'i' indicates integer type

    if ($stmt->execute()) {
        $_SESSION['message'] = "Proveedor eliminado exitosamente.";
        header("Location: Listar_proveedores.php");
        exit();
    } else {
        $_SESSION['error'] = "Error al eliminar el proveedor: " . $stmt->error;
        header("Location: Listar_proveedores.php");
        exit();
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "No se especificó ningún proveedor para eliminar.";
    header("Location: listar_proveedores.php");
    exit();
}

$conexion->close();
?>
