<?php
include_once 'baseDatosModel.php';

function ConsultarUsuarioXEmail($email)
{
    $conexion = AbrirBaseDatos();
    $sentencia = "CALL ConsultarUsuarioXEmail('$email')";
    $respuesta = $conexion->query($sentencia);
    CerrarBaseDatos($conexion);
    return $respuesta;
}

function ActualizarContrasennaTemporal($id, $password)
{
    $conexion = AbrirBaseDatos();
    $sentencia = "CALL ActualizarContrasennaTemporal('$id', '$password')";
    $respuesta = $conexion->query($sentencia);
    CerrarBaseDatos($conexion);
    return $respuesta;
}
?>
