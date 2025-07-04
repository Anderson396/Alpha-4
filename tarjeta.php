<?php
session_start();

$total_carrito = 0;
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $total_carrito += $item['precio'] * $item['cantidad'];
    }
} else {
    // Si el carrito está vacío, redirige al carrito
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar con Tarjeta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: sans-serif;
            background-color: rgb(248, 239, 247);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: rgb(136, 27, 128);
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="number"],
        input[type="month"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-pagar {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background-color: rgb(181, 123, 187);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-pagar:hover {
            background-color: rgb(164, 103, 172);
        }
        .total {
            text-align: right;
            font-size: 1.3em;
            margin-top: 20px;
            font-weight: bold;
        }
        nav{
            display: block;
            border-radius: 5px;
            margin-top: 20px;
            width: 150px;
            padding: 12px;
            font-size: 1.3em;
            font-weight: bold;
             background:rgb(187, 123, 162);
        }
    </style>
</head>
<body>

<main class="container">
    <h1>Pago con Tarjeta</h1>

    <form action="procesar_pago.php" method="post">
        <label for="nombre">Nombre en la tarjeta</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>

        <label for="numero">Número de tarjeta</label>
        <input type="text" id="numero" name="numero" placeholder="XXXX-XXXX-XXXX-XXXX" pattern="\d{13,19}" maxlength="19" required>

        <label for="expiracion">Fecha de expiración</label>
        <input type="month" id="expiracion" name="expiracion" required>

        <label for="cvv">CVV</label>
        <input type="number" id="cvv" name="cvv" placeholder="XXX" min="100" max="9999" required>

        <div class="total">Total: $<?php echo number_format($total_carrito, 2); ?></div>

        <button type="submit" class="btn-pagar" >Pagar Ahora</button>
    </form><br><br>
    <nav>
        <a href="carrito.php" style="color: black;">Volver a carrito</a>
    </nav>
</main>

</body>
</html>
