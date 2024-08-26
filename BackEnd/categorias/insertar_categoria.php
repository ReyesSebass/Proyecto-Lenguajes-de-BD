<?php
include '../../../database/database.php';

if (!$conn) {
    die("No se pudo conectar a la base de datos.");
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$puesto = $_POST['puesto'];
$fecha_contratacion = $_POST['fecha_contratacion'];
$salario = $_POST['salario'];

try {
    // Convertir la fecha de contratación a formato DATE
    $fecha_contratacion_dt = new DateTime($fecha_contratacion);
    $fecha_contratacion_formateada = $fecha_contratacion_dt->format('d-M-Y'); // Formato esperado por Oracle
} catch (Exception $e) {
    die("Error al formatear la fecha: " . $e->getMessage());
}

// Obtener el siguiente valor de la secuencia para EmpleadoID
$query = 'SELECT Empleado_SEQ.NEXTVAL AS id_empleado FROM dual';
$stid = oci_parse($conn, $query);
oci_execute($stid);
$row = oci_fetch_assoc($stid);
$id_empleado = $row['ID_EMPLEADO'];
oci_free_statement($stid);

// Preparar la llamada al procedimiento almacenado
$sql = 'BEGIN INSERTAR_EMPLEADO(:id_empleado, :nombre, :apellido, :puesto, TO_DATE(:fecha_contratacion, \'DD-MON-YYYY\'), :salario); END;';
$stid = oci_parse($conn, $sql);

// Asociar los parámetros
oci_bind_by_name($stid, ':id_empleado', $id_empleado);
oci_bind_by_name($stid, ':nombre', $nombre);
oci_bind_by_name($stid, ':apellido', $apellido);
oci_bind_by_name($stid, ':puesto', $puesto);
oci_bind_by_name($stid, ':fecha_contratacion', $fecha_contratacion_formateada);
oci_bind_by_name($stid, ':salario', $salario);

// Ejecutar el procedimiento
$success = oci_execute($stid);

if ($success) {
    // Redirigir a la página de empleados con un mensaje de éxito
    header('Location: empleados.php?msg=Empleado agregado con éxito');
    exit();
} else {
    // Mostrar el mensaje de error
    $e = oci_error($stid);
    die("Error al agregar empleado: " . htmlentities($e['message'], ENT_QUOTES));
}

// Liberar los recursos y cerrar la conexión
oci_free_statement($stid);
oci_close($conn);
?>