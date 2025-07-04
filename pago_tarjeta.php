<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar con Tarjeta</title>
    
</head>
<body>
    <main class="container">
        <h1>Pago con Tarjeta</h1>
        <form action="finalizar_pago.php" method="post">
            <input type="hidden" name="metodo_pago" value="tarjeta">

            <label for="nombre">Nombre en la tarjeta:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="numero">Número de tarjeta:</label>
            <input type="text" id="numero" name="numero" maxlength="16" required><br>

            <label for="vencimiento">Fecha de vencimiento:</label>
            <input type="month" id="vencimiento" name="vencimiento" required><br>

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" maxlength="4" required><br><br>

            <button type="submit">Confirmar pago</button>
        </form>
    </main>
</body>
</html>
