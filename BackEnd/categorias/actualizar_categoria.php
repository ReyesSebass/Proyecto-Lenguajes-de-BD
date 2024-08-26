<?php
include 'BackEnd/Dbconexion.php';

if (!$conn) {
    die("Conexión fallida: " . htmlentities(oci_error()['message'], ENT_QUOTES));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_categoria = $_POST['id_categoria'];
    $nombre_categoria = $_POST['nombre_categoria'];
    $descripcion = $_POST['descripcion'];

    if (empty($id_categoria) || empty($nombre_categoria) || empty($descripcion)) {
        die("Tiene que llenar todo el formulario.");
    }

    // Preparar la llamada al procedimiento almacenado
    $sql = 'BEGIN ACTUALIZAR_CATEGORIA(:id_categoria, :nombre_categoria, :descripcion); END;';
    $stid = oci_parse($conn, $sql);

    // Enlazar los parámetros
    oci_bind_by_name($stid, ':id_categoria', $id_categoria);
    oci_bind_by_name($stid, ':nombre_categoria', $nombre_categoria);
    oci_bind_by_name($stid, ':descripcion', $descripcion);

    // Ejecutar la llamada al procedimiento almacenado
    if (oci_execute($stid)) {
        header('Location: categorias.php?msg=Categoría actualizada con éxito');
    } else {
        $error = oci_error($stid);
        die("Error al actualizar la categoría: " . htmlentities($error['message'], ENT_QUOTES));
    }

    oci_free_statement($stid);
    oci_close($conn);
} else {
    die("Método de solicitud no válido.");
}
?>
