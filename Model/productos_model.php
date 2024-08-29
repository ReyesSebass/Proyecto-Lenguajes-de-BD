<?php
include_once 'baseDatosModel.php';

function obtenerProductosPorCategoria($idCategoria) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_PRODUCTOS_POR_CATEGORIA(:id_categoria, :resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return ['error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']];
    }

    oci_bind_by_name($stmt, ":id_categoria", $idCategoria);

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return ['error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']];
    }

    $productos = [];
    while ($row = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $productos[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($resultado);
    CerrarOrcl($conn);
    return $productos;
}

function obtenerTodosLosProductos() {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_TODOS_LOS_PRODUCTOS(:resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return ['error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']];
    }

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return ['error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']];
    }

    $productos = [];
    while ($row = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $productos[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($resultado);
    CerrarOrcl($conn);
    return $productos;
}

function obtenerTodasLasCategorias() {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_TODAS_LAS_CATEGORIAS(:resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return ['error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']];
    }

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return ['error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']];
    }

    $categorias = [];
    while ($row = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $categorias[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($resultado);
    CerrarOrcl($conn);
    return $categorias;
}

function agregarProducto($nombre, $descripcion, $precio, $stock, $rutaImagen, $idCategoria) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $precio = floatval($precio);
    $stock = intval($stock);
    $idCategoria = intval($idCategoria);

    $stmt = oci_parse($conn, "BEGIN AGREGAR_PRODUCTO(:nombre, :descripcion, :precio, :stock, :id_categoria, :ruta_imagen); END;");
    
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":descripcion", $descripcion);
    oci_bind_by_name($stmt, ":precio", $precio);
    oci_bind_by_name($stmt, ":stock", $stock);
    oci_bind_by_name($stmt, ":id_categoria", $idCategoria);
    oci_bind_by_name($stmt, ":ruta_imagen", $rutaImagen);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_free_statement($stmt);
    CerrarOrcl($conn);
    return ['success' => true];
}

function actualizarProducto($idProducto, $nombre, $descripcion, $precio, $stock, $idCategoria, $rutaImagen) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN ACTUALIZAR_PRODUCTO(:idProducto, :nombre, :descripcion, :precio, :stock, :idCategoria, :rutaImagen); END;");
    
    $idProducto = is_array($idProducto) ? 0 : (int)$idProducto;
    $nombre = is_array($nombre) ? '' : (string)$nombre;
    $descripcion = is_array($descripcion) ? '' : (string)$descripcion;
    $precio = is_array($precio) ? 0 : (float)$precio;
    $stock = is_array($stock) ? 0 : (int)$stock;
    $idCategoria = is_array($idCategoria) ? 0 : (int)$idCategoria;
    $rutaImagen = is_array($rutaImagen) ? '' : (string)$rutaImagen;

    oci_bind_by_name($stmt, ":idProducto", $idProducto);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":descripcion", $descripcion);
    oci_bind_by_name($stmt, ":precio", $precio);
    oci_bind_by_name($stmt, ":stock", $stock);
    oci_bind_by_name($stmt, ":idCategoria", $idCategoria);
    oci_bind_by_name($stmt, ":rutaImagen", $rutaImagen);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_commit($conn);

    oci_free_statement($stmt);
    CerrarOrcl($conn);
    return ['success' => true];
}


function eliminarProducto($idProducto) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN ELIMINAR_PRODUCTO(:id_producto); END;");
    
    oci_bind_by_name($stmt, ":id_producto", $idProducto);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_free_statement($stmt);
    CerrarOrcl($conn);
    return ['success' => true];
}

function obtenerProductoPorId($idProducto) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $nombreProducto = '';
    $descripcion = '';
    $precio = 0;
    $stock = 0;
    $idCategoria = 0;
    $rutaImagen = '';

    $stmt = oci_parse($conn, 'BEGIN ObtenerProductoPorId(:id_producto, :nombre_producto, :descripcion, :precio, :stock, :id_categoria, :ruta_imagen); END;');
    
    oci_bind_by_name($stmt, ':id_producto', $idProducto, -1, OCI_B_INT);
    oci_bind_by_name($stmt, ':nombre_producto', $nombreProducto, 1000);
    oci_bind_by_name($stmt, ':descripcion', $descripcion, 1000);
    oci_bind_by_name($stmt, ':precio', $precio);
    oci_bind_by_name($stmt, ':stock', $stock);
    oci_bind_by_name($stmt, ':id_categoria', $idCategoria);
    oci_bind_by_name($stmt, ':ruta_imagen', $rutaImagen, 1000);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        oci_free_statement($stmt);
        CerrarOrcl($conn);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_free_statement($stmt);
    CerrarOrcl($conn);

    return [
        'ID_PRODUCTO' => $idProducto,
        'NOMBRE_PRODUCTO' => $nombreProducto,
        'DESCRIPCION' => $descripcion,
        'PRECIO' => $precio,
        'STOCK' => $stock,
        'ID_CATEGORIA' => $idCategoria,
        'RUTA_IMAGEN' => $rutaImagen
    ];
}

?>
