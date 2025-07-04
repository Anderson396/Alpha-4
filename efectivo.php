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
    <title>Pagar en Efectivo</title>
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
            width: 35%;
            padding: 10px;
           font-size: 1.3em;
            margin-top: 20px;
            font-weight: bold;
            background:  rgb(181, 123, 187);
        }
    </style>
</head>
<body>

<main class="container">
    <h1>Pago en Efectivo</h1>

    <div class="instructions">
        <p>Gracias por elegir pagar en efectivo.</p>
        <p>Para completar tu pedido, por favor realiza el pago en el momento de la entrega o cuando recojas tu pedido en tienda.</p>
        <p>Conserva tu número de pedido y preséntalo al pagar.</p>
    </div>

    <div class="total">Total a pagar: $<?php echo number_format($total_carrito, 2); ?></div>

    <form action="confirmar_efectivo.php" method="post">
        <button type="submit" class="btn-confirmar">Confirmar pedido en efectivo</button>
    </form>
</main>

</body>
</html>
