<?php
include_once '../Model/recuperar_model.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function enviarCorreo($asunto, $contenido, $destinatario, $correoSalida, $contrasennaSalida) {
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';

    $mail->isSMTP();
    $mail->isHTML(true); 
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;                      
    $mail->SMTPAuth = true;
    $mail->Username = $correoSalida;               
    $mail->Password = $contrasennaSalida;                                
    
    $mail->setFrom($correoSalida);
    $mail->Subject = $asunto;
    $mail->MsgHTML($contenido);   
    $mail->addAddress($destinatario);

    if(!$mail->send()) {
        return "Error al enviar el correo: " . $mail->ErrorInfo;
    } else {
        return "Correo enviado exitosamente.";
    }
}

function GenerarCodigo() {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 6; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

if(isset($_POST["BotonRecAcceso"]))
{
    $email = $_POST['email'];
    $respuesta = ConsultarUsuarioXEmail($email);

    if($respuesta->num_rows > 0)
    {
        $datos = mysqli_fetch_array($respuesta);
        $codigo = GenerarCodigo();
        $resp = ActualizarContrasennaTemporal($datos["id"], $codigo);

        if($resp == true)
        {
            $contenido = "<html><body>
            Estimado(a) " . $datos["Nombre"] . "<br/><br/>
            Se ha generado el siguiente código de seguridad: <b>" . $codigo . "</b><br/>
            Recuerde realizar el cambio de contraseña una vez que ingrese a nuestro sistema.<br/><br/>
            Muchas gracias.
            </body></html>";

            $correoSalida = "parapruebasuniversidad@outlook.com";
            $contrasennaSalida = "Pruebas.*";
            $resultadoEnvio = enviarCorreo('Acceso al Sistema', $contenido, $datos["email"], $correoSalida, $contrasennaSalida);

            if ($resultadoEnvio === "Correo enviado exitosamente.") {
                header("location: ../View/login.php");
                exit();
            } else {
                $_POST["msj"] = $resultadoEnvio;
            }
        }
        else
        {
            $_POST["msj"] = "No se ha podido enviar su código de seguridad correctamente.";
        }
    }
    else
    {
        $_POST["msj"] = "Su información no se ha validado correctamente.";
    }
}
?>
