<?php
session_start();

$metodo = $_POST['metodo_pago'] ?? '';

// Verificamos de qué formulario provienen los datos
if ($metodo === 'tarjeta') {
    $nombre = $_POST['nombre'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $vencimiento = $_POST['vencimiento'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Aquí podrías agregar validaciones más profundas y conexión a una pasarela real de pago

    $mensaje = "✅ ¡Pago realizado con tarjeta exitosamente! Gracias por tu compra, $nombre.";

} elseif ($metodo === 'efectivo') {
    $nombre = $_POST['nombre'] ?? '';
    $direccion = $_POST['direccion'] ?? '';

    $mensaje = "🧾 Se ha registrado tu pedido para pago en efectivo. El pedido será entregado en: $direccion. ¡Gracias, $nombre!";
} else {
    $mensaje = "❌ Error: Método de pago no válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesar Pago</title>
    <link rel="stylesheet" href="../styles/pago.css">
</head>
<body>
    <main class="container">
        <h1>Resultado del Pago</h1>
        <p><?php echo $mensaje; ?></p>
        <a href="../index.php">Volver al inicio</a>
    </main>
</body>
</html>
