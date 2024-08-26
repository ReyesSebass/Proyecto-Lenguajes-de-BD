<?php
include '../../../database/database.php';

if (!$conn) {
    die("Conexión fallida: " . htmlentities(oci_error()['message'], ENT_QUOTES));
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID del empleado no proporcionado.");
}

$empleado_id = $_GET['id'];

// Preparar la llamada al procedimiento almacenado
$sql = 'BEGIN ELIMINAR_EMPLEADO(:empleado_id); END;';
$stid = oci_parse($conn, $sql);

// Enlazar el parámetro
oci_bind_by_name($stid, ':empleado_id', $empleado_id);

// Ejecutar la llamada al procedimiento almacenado
if (oci_execute($stid)) {
    header('Location: empleados.php?msg=Empleado eliminado con éxito');
} else {
    $error = oci_error($stid);
    die("Error al eliminar el empleado: " . htmlentities($error['message'], ENT_QUOTES));
}

oci_free_statement($stid);
oci_close($conn);
?>