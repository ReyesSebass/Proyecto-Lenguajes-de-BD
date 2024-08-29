<?php
$error_message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Ocurrió un error desconocido.';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
    <h1>Se produjo un error</h1>
    <p><?php echo $error_message; ?></p>
</body>
</html>

