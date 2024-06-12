<?php
require 'config.php';

// Asegurar que la conexión sea HTTPS
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Validación y saneamiento de entrada
$num1 = filter_input(INPUT_POST, 'num1', FILTER_VALIDATE_FLOAT);
$num2 = filter_input(INPUT_POST, 'num2', FILTER_VALIDATE_FLOAT);
$operation = filter_input(INPUT_POST, 'operation', FILTER_SANITIZE_STRING);

// Depuración: Verificar valores recibidos
// if (APP_DEBUG) {
//     echo "num1: " . var_export($num1, true) . "<br>";
//     echo "num2: " . var_export($num2, true) . "<br>";
//     echo "operation: " . var_export($operation, true) . "<br>";
// }

// Validacida adicional
if ($num1 === false || $num2 === false || !in_array($operation, ['sum', 'subtract'])) {
    handle_error(E_USER_ERROR, 'Entrada no válida.', __FILE__, __LINE__);
    exit();
}

// Realiza la operación
$result = 0;
if ($operation == 'sum') {
    $result = $num1 + $num2;
} elseif ($operation == 'subtract') {
    $result = $num1 - $num2;
} else {
    handle_error(E_USER_ERROR, 'Operación no válida.', __FILE__, __LINE__);
    exit();
}

// Encriptación
$encrypted_result = encrypt_data((string)$result, ENCRYPTION_KEY);
$decrypted_result = decrypt_data($encrypted_result, ENCRYPTION_KEY);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de la Calculadora</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Resultado</h1>
        <div class="result">
            <p>El resultado de la operación es: <strong><?php echo htmlspecialchars($decrypted_result, ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <a href="index.php" class="btn">Realizar otra operación</a>
        </div>
    </div>
</body>
</html>