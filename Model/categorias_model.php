<?php
include_once 'baseDatosModel.php';

function obtenerCategorias() {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return [
            'error_message' => "ERROR: No se pudo conectar a la base de datos."
        ];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_CATEGORIAS(:resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return [
            'error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']
        ];
    }

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return [
            'error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']
        ];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return [
            'error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']
        ];
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
?>
