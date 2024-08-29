<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'Administrador';
$password = 'Administrador';
$dbname = 'XE';
$hostname = 'localhost';

$connString = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$hostname)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=$dbname)))";

$conn = oci_connect($username, $password, $connString);

if (!$conn) {
    $e = oci_error();
    echo "Error de conexión: " . htmlentities($e['message'], ENT_QUOTES);
    exit;
} else {
    echo "ORACLE DATABASE CONNECTED SUCCESSFULLY!!!";
}
?>
