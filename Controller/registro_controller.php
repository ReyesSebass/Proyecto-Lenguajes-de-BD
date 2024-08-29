<?php
include_once '../Model/procesar_registro.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $identificacion = $_POST['identificacion'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    $resultado = procesarRegistro($nombre, $identificacion, $email, $password, $telefono, $rol);

    if ($resultado === 'Usuario registrado con éxito.') {
        header("Location: login.php");
        exit();
    } else {
        $error_registro = $resultado;
    }
}
?>
