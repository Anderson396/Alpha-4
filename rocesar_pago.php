<?php
session_start();

// Aquí iría el procesamiento real con un gateway de pago

// Para simular: asumimos que el pago es exitoso y vaciamos el carrito
$_SESSION['carrito'] = [];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago Exitoso</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: rgb(248, 239, 247);
        }
        .success-message {
            font-size: 1.5em;
            color: green;
        }
        .btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: rgb(196, 147, 201);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="success-message">✅ ¡Pago realizado con éxito!</div>
    <a href="../index.php" class="btn">Volver a la tienda</a>
</body>
</html>
