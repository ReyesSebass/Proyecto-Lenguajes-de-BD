<?php
include_once 'baseDatosModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function procesarLogin($email, $password) {
    $conn = AbrirOrcl();

    if ($conn === false) {
        return [
            'error_message' => "ERROR: No se pudo conectar a la base de datos."
        ];
    }

    $stmt = oci_parse($conn, "BEGIN SP_PROCESAR_LOGIN(:p_email, :p_id_usuario, :p_correo, :p_password, :p_id_rol, :p_nombre); END;");

    if (!$stmt) {
        $error = oci_error($conn);
        return [
            'error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']
        ];
    }

    oci_bind_by_name($stmt, ":p_email", $email);
    oci_bind_by_name($stmt, ":p_id_usuario", $id, 32);
    oci_bind_by_name($stmt, ":p_correo", $correo, 50);
    oci_bind_by_name($stmt, ":p_password", $hashed_password, 255);
    oci_bind_by_name($stmt, ":p_id_rol", $rol_id, 32);
    oci_bind_by_name($stmt, ":p_nombre", $nombre, 50);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        oci_free_statement($stmt);
        CerrarOrcl($conn);
        return [
            'error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']
        ];
    }

    if ($id !== null) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $correo;
            $_SESSION['rol_id'] = $rol_id;
            $_SESSION['nombre'] = $nombre;
            oci_free_statement($stmt);
            CerrarOrcl($conn);
            return [
                'id' => $id,
                'email' => $correo,
                'rol_id' => $rol_id,
                'nombre' => $nombre
            ];
        } else {
            oci_free_statement($stmt);
            CerrarOrcl($conn);
            return [
                'error_message' => "La contraseña ingresada es incorrecta."
            ];
        }
    } else {
        oci_free_statement($stmt);
        CerrarOrcl($conn);
        return [
            'error_message' => "No se encontró una cuenta con ese correo electrónico."
        ];
    }
}
?>


