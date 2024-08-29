<?php

function AbrirOrcl()
{
    $username = 'Administrador';
    $password = 'Administrador';
    $dbname = 'XE';
    $hostname = 'localhost';
    $connString = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$hostname)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=$dbname)))";
    $conexion = oci_connect($username, $password, $connString);
    if (!$conexion) {
        $error = oci_error();
        echo "Error de conexión: " . $error['message'];
        return false;
    }
    return $conexion;
}

function CerrarOrcl($conexion)
{
    if ($conexion) {
        oci_close($conexion);
    }
}

?>

