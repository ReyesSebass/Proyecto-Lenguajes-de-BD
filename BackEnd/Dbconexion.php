<?php
$username = '';
$password = '';
$dbname = '';
$hostname = '';

//Establecer la conexion
$conn = oci_connect($username, $passwor, $hostname;);

if(!$conn){
    $e = oci_error();
    echo htmlentities($e['message'], ENT_QUOTES);
    exit;
}
?>