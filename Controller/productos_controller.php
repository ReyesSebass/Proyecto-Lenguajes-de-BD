<?php
include_once '../Model/productos_model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'agregar':
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                $idCategoria = $_POST['idCategoria'];
                $rutaImagen = $_POST['rutaImagen'];
                
                $resultado = agregarProducto($nombre, $descripcion, $precio, $stock, $rutaImagen, $idCategoria);
                if (isset($resultado['success'])) {
                    header("Location: ../View/productos_crud.php?mensaje=Producto agregado exitosamente.");
                    exit();
                } else {
                    $error_message = $resultado['error_message'];
                }
                break;

                case (preg_match('/^actualizar_producto_(\d+)$/', $_POST['accion'], $matches) ? true : false):
                    $idProducto = $matches[1];
                    
                    $nombre = $_POST['nombre_' . $idProducto] ?? '';
                    $descripcion = $_POST['descripcion_' . $idProducto] ?? '';
                    $precio = $_POST['precio_' . $idProducto] ?? 0;
                    $stock = $_POST['stock_' . $idProducto] ?? 0;
                    $idCategoria = $_POST['idCategoria_' . $idProducto] ?? 0;
                    $rutaImagen = $_POST['rutaImagen_' . $idProducto] ?? '';
    
                    $resultado = actualizarProducto($idProducto, $nombre, $descripcion, $precio, $stock, $idCategoria, $rutaImagen);
    
                    if (!isset($resultado['success'])) {
                        $error_message = $resultado['error_message'];
                    } else {
                        header("Location: ../View/productos_actualizar.php?mensaje=Producto actualizado exitosamente.");
                        exit();
                    }
                    break;

            case 'eliminar':
                $idProducto = $_POST['idProducto'];

                $resultado = eliminarProducto($idProducto);
                if (isset($resultado['success'])) {
                    header("Location: ../View/productos_crud.php?mensaje=Producto eliminado exitosamente.");
                    exit();
                } else {
                    $error_message = $resultado['error_message'];
                }
                break;
        }
    }
} elseif (isset($_GET['categoria'])) {
    $idCategoria = $_GET['categoria'];
    $productos = obtenerProductosPorCategoria($idCategoria);

    if (isset($productos['error_message'])) {
        $error_message = $productos['error_message'];
    } else {
        include_once '../View/productos.php';
    }
} else {
    $categorias = obtenerTodasLasCategorias();
    if (isset($categorias['error_message'])) {
        $error_message = $categorias['error_message'];
    } else {
        $productos = obtenerTodosLosProductos();
        if (isset($productos['error_message'])) {
            $error_message = $productos['error_message'];
        } else {
            include_once '../View/productos_crud.php';
        }
    }
}

if (isset($error_message)) {
    include_once '../View/error.php';
}
?>