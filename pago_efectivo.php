<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar en Efectivo</title>
    <link rel="stylesheet" href="../styles/pago.css"> <!-- Opcional -->
</head>
<body>
    <main class="container">
        <h1>Pago contra entrega</h1>
        <form action="finalizar_pago.php" method="post">
            <input type="hidden" name="metodo_pago" value="efectivo">

            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="direccion">Dirección de entrega:</label>
            <textarea id="direccion" name="direccion" required></textarea><br>

            <label for="telefono">Teléfono de contacto:</label>
            <input type="tel" id="telefono" name="telefono" required><br><br>

            <button type="submit">Confirmar pedido</button>
        </form>
    </main>
</body>
</html>
