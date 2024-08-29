<?php
session_start();
include_once '../Model/procesar_login.php';

function verificarRol($rol_permiso) {
    if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != $rol_permiso) {
        header("Location: ../View/login.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login_result = procesarLogin($email, $password);

    if (isset($login_result['error_message'])) {
        $_SESSION['error_message'] = $login_result['error_message'];
        header("Location: ../View/login.php");
        exit();
    } else {
        $_SESSION['id'] = $login_result['id'];
        $_SESSION['email'] = $login_result['email'];
        $_SESSION['rol_id'] = $login_result['rol_id'];
        $_SESSION['nombre'] = $login_result['nombre'];

        switch ($_SESSION['rol_id']) {
            case 1:
                header("Location: ../View/home.php");
                exit();
            case 2:
                header("Location: ../View/home.php");
                exit();
            case 3:
                header("Location: ../View/home.php");
                exit();
            default:
                $_SESSION['error_message'] = "ERROR: Rol desconocido.";
                header("Location: ../View/login.php");
                exit();
        }
    }
}
?>
