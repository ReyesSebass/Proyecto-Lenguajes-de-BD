<?php
include_once '../Controller/registro_controller.php';
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
    <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="login.php">
                <img src="assets/img/brand/imagen4.png" style="max-width: 20%; height: auto;">
            </a>
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
    </nav>

    <div class="main-content">
        <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                            <h1 class="text-white">Crear cuenta</h1>
                            <p class="text-lead text-white">Registrate para realizar tus gestiones médicas</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
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
                                <h1 class="text-black">Registrate</h1>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="POST" action="registro.php">
                                <?php if (isset($error_registro)) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $error_registro; ?>
                                </div>
                                <?php endif; ?>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Nombre" type="text" name="nombre" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Identificación sin guiones" type="text" name="identificacion" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Email" type="email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Teléfono" type="text" name="telefono" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Contraseña" type="password" name="password" required>
                                    </div>
                                </div>
                                <input type="hidden" name="rol" value="1">
                                <div class="row my-4">
                                    <div class="col-12">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="customCheckRegister" type="checkbox" required>
                                            <label class="custom-control-label" for="customCheckRegister">
                                                <span class="text-muted">Estoy de acuerdo con la <a href="#!">Privacy Policy</a></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">Crear cuenta</button>
                                </div>
                            </form>
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
