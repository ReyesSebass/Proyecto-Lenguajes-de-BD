<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../View/login.php");
    exit();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $idProducto = $_POST['idProducto'] ?? '';
        $nombreProducto = $_POST['nombreProducto'] ?? '';
        $precioProducto = $_POST['precioProducto'] ?? 0;

        if ($idProducto && $nombreProducto && $precioProducto) {
            if (isset($_SESSION['carrito'][$idProducto])) {
                $_SESSION['carrito'][$idProducto]['cantidad'] += 1;
            } else {
                $_SESSION['carrito'][$idProducto] = [
                    'nombre' => $nombreProducto,
                    'precio' => $precioProducto,
                    'cantidad' => 1
                ];
            }
        }

        header("Location: ../View/productos.php");
        exit();
    } elseif ($accion === 'actualizar') {
        foreach ($_POST['cantidad'] as $idProducto => $cantidad) {
            $cantidad = intval($cantidad);
            if ($cantidad > 0) {
                if (isset($_SESSION['carrito'][$idProducto])) {
                    $_SESSION['carrito'][$idProducto]['cantidad'] = $cantidad;
                }
            } else {
                unset($_SESSION['carrito'][$idProducto]);
            }
        }

        header("Location: ../View/pago.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['action'] ?? '';

    if ($accion === 'remove' && isset($_GET['id'])) {
        $idProducto = $_GET['id'];

        if (isset($_SESSION['carrito'][$idProducto])) {
            unset($_SESSION['carrito'][$idProducto]);
        }

        header("Location: ../View/pago.php");
        exit();
    }
}
?>
