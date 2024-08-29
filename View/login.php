<?php
session_start();
include_once '../Model/procesar_login.php';
include_once 'layout.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
</head>

<body class="bg-default">
    <nav id="navbar-main"
        class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="login.php">
                <img src="assets/img/brand/imagen4.png" style="max-width: 20%; height: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse"
                aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="login.php" class="nav-link">
                            <span class="nav-link-inner--text">Iniciar Sesion</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="registro.php" class="nav-link">
                            <span class="nav-link-inner--text">Registrarse</span>
                        </a>
                    </li>
                </ul>
                <hr class="d-lg-none" />
            </div>
        </div>
    </nav>

    <div class="main-content">

        <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <h1 class="text-white">Bienvenido</h1>
                        <p class="text-lead text-white">Ingresa en la pagina de compras de Chillyouknow para realizar tus compras.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        
        <div class="container mt--8 pb-5">
            
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card bg-secondary border-0">
                        <div class="card-header bg-transparent pb-5">
                            <div class="text-muted text-center mt-2 mb-3">
                                <h1 class="text-black">Iniciar Sesión</h1>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="POST" action="../Controller/login_controller.php">
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Email" type="email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Contraseña" type="password" name="password" required>
                                    </div>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck" name="remember_me">
                                    <label class="custom-control-label" for="customCheck">Recuérdame</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">Entrar</button>
                                </div>
                                <?php
                                if (isset($_SESSION['error_message'])) {
                                    echo '<div class="alert alert-danger mt-4">' . $_SESSION['error_message'] . '</div>';
                                    unset($_SESSION['error_message']);
                                }
                                
                                ?>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-6">
                        <a href="recuperar.php" class="text-light"><small>Recuperar contraseña</small></a>
                      </div>
                      <div class="col-6 text-right">
                        <a href="registro.php" class="text-light"><small>Crear una cuenta</small></a>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php MostrarFooter(); ?>

    <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/js-cookie/js.cookie.js"></script>
    <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <script src="assets/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="assets/vendor/chart.js/dist/Chart.extension.js"></script>
    <script src="assets/js/argon.js?v=1.2.0"></script>
</body>

</html>