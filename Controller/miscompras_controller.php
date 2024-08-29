<?php
include_once '../Model/miscompras_model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id'];
$facturas = obtenerComprasUsuario($id_usuario);

if (isset($facturas['error_message'])) {
    $error_message = $facturas['error_message'];
    $facturas = [];
}

foreach ($facturas as &$factura) {
    $productos = obtenerProductosFactura($factura['ID_FACTURA']);
    if (isset($productos['error_message'])) {
        $error_message = $productos['error_message'];
        $factura['productos'] = [];
    } else {
        $factura['productos'] = $productos;
    }
}
?>
