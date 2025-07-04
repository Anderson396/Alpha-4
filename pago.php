<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo = $_POST['metodo_pago'] ?? '';
    
    // Redirigir según el método seleccionado
    if ($metodo === 'tarjeta') {
        header("Location: pago_tarjeta.php");
        exit();
    } elseif ($metodo === 'efectivo') {
        header("Location: pago_efectivo.php");
        exit();
    } else {
        $error = "Por favor, selecciona un método de pago válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Selecciona tu método de pago</title>
  <link rel="stylesheet" href="../styles/pago.css">
</head>
<body>
  <main class="container">
    <h1>Selecciona tu método de pago</h1>
    
    <?php if (!empty($error)): ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <label>
        <input type="radio" name="metodo_pago" value="tarjeta" required>
        Tarjeta de Crédito/Débito
      </label><br>

      <label>
        <input type="radio" name="metodo_pago" value="efectivo">
        Efectivo (pago al entregar)
      </label><br><br>

      <button type="submit">Continuar</button>
    </form>
  </main>
</body>
</html>
