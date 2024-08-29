<?php
include_once '../Model/categorias_model.php';

$categorias = obtenerCategorias();

if (isset($categorias['error_message'])) {
    $error_message = $categorias['error_message'];
} else {
    include_once '../View/categorias.php';
}
?>
