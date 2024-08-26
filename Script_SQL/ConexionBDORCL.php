<? php
$user = "Administrador": //Oracle username
$pass = "Administrador"; //Oracle password
Shost = "localhost/XE"; //server name or ip address
$dbconn = oci_connect ($user, $pass, $host);
if (!$dbconn) {
$e = oci_error(); trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} else (
echo "ORACLE DATABASE CONNECTED SUCCESSFULLY!!!";
)
?>