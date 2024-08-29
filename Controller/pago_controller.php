<?php
include_once '../Model/baseDatosModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || !isset($_SESSION['carrito'])) {
    echo "ID del usuario o del carrito no está definido.";
    exit();
}

$id_usuario = $_SESSION['id'];
$productosCarrito = $_SESSION['carrito'];

$conexion = AbrirOrcl();

if (!$conexion) {
    echo "Error de conexión a la base de datos.";
    exit();
}

try {
    oci_parse($conexion, 'BEGIN NULL; END;');

    $totalFactura = 0;
    foreach ($productosCarrito as $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];
        $totalFactura += $subtotal;
    }

    $stmt = oci_parse($conexion, 'BEGIN REGISTRAR_FACTURA(:id_usuario, :total, :id_factura); END;');
    oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
    oci_bind_by_name($stmt, ':total', $totalFactura);
    oci_bind_by_name($stmt, ':id_factura', $id_factura, 32);

    if (!oci_execute($stmt)) {
        throw new Exception("Error al insertar en FACTURAS: " . oci_error($stmt)['message']);
    }

    foreach ($productosCarrito as $idProducto => $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];

        $stmt = oci_parse($conexion, 'BEGIN REGISTRAR_PEDIDO(:id_usuario, :precio, :id_producto, :cantidad, :total); END;');
        oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
        oci_bind_by_name($stmt, ':precio', $producto['precio']);
        oci_bind_by_name($stmt, ':id_producto', $idProducto);
        oci_bind_by_name($stmt, ':cantidad', $producto['cantidad']);
        oci_bind_by_name($stmt, ':total', $subtotal);
        
        if (!oci_execute($stmt)) {
            throw new Exception("Error al insertar en PEDIDOS: " . oci_error($stmt)['message']);
        }

        $stmt = oci_parse($conexion, 'BEGIN ACTUALIZAR_INVENTARIO(:id_producto, :cantidad_comprada); END;');
        oci_bind_by_name($stmt, ':id_producto', $idProducto);
        oci_bind_by_name($stmt, ':cantidad_comprada', $producto['cantidad']);

        if (!oci_execute($stmt)) {
            throw new Exception("Error al actualizar el inventario: " . oci_error($stmt)['message']);
        }
    }

    oci_commit($conexion);

    $_SESSION['carrito'] = [];

    header("Location: ../View/compra_exitosa.php");
    exit();

} catch (Exception $e) {
    oci_rollback($conexion);
    echo "Error en la transacción: " . $e->getMessage();
} finally {
    CerrarOrcl($conexion);
}
?>
