<?php
include_once '../Model/baseDatosModel.php';

function obtenerComprasUsuario($id_usuario) {
    $conexion = AbrirOrcl();
    $compras = [];

    if (!$conexion) {
        return ['error_message' => 'Error de conexión a la base de datos.'];
    }

    $stmt = oci_parse($conexion, 'BEGIN OBTENER_COMPRAS_USUARIO(:id_usuario, :resultado); END;');
    oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
    $resultado = oci_new_cursor($conexion);
    oci_bind_by_name($stmt, ':resultado', $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt) || !oci_execute($resultado)) {
        $error = oci_error($stmt);
        CerrarOrcl($conexion);
        return ['error_message' => 'Error al obtener las compras: ' . $error['message']];
    }

    while ($row = oci_fetch_assoc($resultado)) {
        $compras[] = $row;
    }

    CerrarOrcl($conexion);
    return $compras;
}

function obtenerProductosFactura($id_factura) {
    $conexion = AbrirOrcl();
    $productos = [];

    if (!$conexion) {
        return ['error_message' => 'Error de conexión a la base de datos.'];
    }

    $stmt = oci_parse($conexion, 'BEGIN OBTENER_PRODUCTOS_FACTURA(:id_factura, :resultado); END;');
    oci_bind_by_name($stmt, ':id_factura', $id_factura);
    $resultado = oci_new_cursor($conexion);
    oci_bind_by_name($stmt, ':resultado', $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt) || !oci_execute($resultado)) {
        $error = oci_error($stmt);
        CerrarOrcl($conexion);
        return ['error_message' => 'Error al obtener los productos: ' . $error['message']];
    }

    while ($row = oci_fetch_assoc($resultado)) {
        $productos[] = $row;
    }

    CerrarOrcl($conexion);
    return $productos;
}

function insertarFactura($id_usuario, $total) {
    $conexion = AbrirOrcl();

    if (!$conexion) {
        return ['error_message' => 'Error de conexión a la base de datos.'];
    }

    $stmt = oci_parse($conexion, 'SELECT ID_PEDIDO FROM PEDIDOS WHERE ID_USUARIO = :id_usuario AND FECHA = SYSDATE AND ROWNUM = 1');
    oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    $id_pedido = $row['ID_PEDIDO'];

    if (!$id_pedido) {
        CerrarOrcl($conexion);
        return ['error_message' => 'No se encontró un ID_PEDIDO válido para el usuario.'];
    }

    $stmt = oci_parse($conexion, 'INSERT INTO FACTURAS (ID_PEDIDO, ID_USUARIO, FECHA_EMISION, TOTAL) VALUES (:id_pedido, :id_usuario, SYSDATE, :total)');
    oci_bind_by_name($stmt, ':id_pedido', $id_pedido);
    oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
    oci_bind_by_name($stmt, ':total', $total);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        CerrarOrcl($conexion);
        return ['error_message' => 'Error al insertar la factura: ' . $error['message']];
    }

    CerrarOrcl($conexion);
    return ['success_message' => 'Factura insertada correctamente.'];
}

?>
