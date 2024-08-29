<?php
include_once 'baseDatosModel.php';

function procesarRegistro($nombre, $identificacion, $email, $password, $telefono, $rol) {
    $conn = AbrirOrcl();

    if ($conn === false) {
        return "ERROR: No se pudo conectar a la base de datos.";
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $sql_call = "BEGIN RegistrarUsuario(:nombre, :identificacion, :email, :password_hash, :telefono, :rol, :resultado); END;";
    $stmt_call = oci_parse($conn, $sql_call);

    if (!$stmt_call) {
        $error = oci_error($conn);
        CerrarOrcl($conn);
        return "Error al preparar la declaración: " . $error['message'];
    }

    oci_bind_by_name($stmt_call, ':nombre', $nombre);
    oci_bind_by_name($stmt_call, ':identificacion', $identificacion);
    oci_bind_by_name($stmt_call, ':email', $email);
    oci_bind_by_name($stmt_call, ':password_hash', $password_hash);
    oci_bind_by_name($stmt_call, ':telefono', $telefono);
    oci_bind_by_name($stmt_call, ':rol', $rol);
    oci_bind_by_name($stmt_call, ':resultado', $resultado, 255);

    if (oci_execute($stmt_call, OCI_NO_AUTO_COMMIT)) {
        oci_commit($conn);
        oci_free_statement($stmt_call);
        CerrarOrcl($conn);
        return $resultado;
    } else {
        $error = oci_error($stmt_call);
        oci_free_statement($stmt_call);
        CerrarOrcl($conn);
        return "Error al ejecutar la consulta: " . $error['message'];
    }
}
?>
