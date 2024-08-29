<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function MostrarMenu()
{
    echo '<div class="main-content" id="panel">
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav align-items-center ml-md-auto">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="assets/img/theme/usuario.png">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm font-weight-bold">' . htmlspecialchars($_SESSION['nombre']) . '</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Bienvenido</h6>
                </div>
                <div class="dropdown-divider"></div>
                <a href="login.php" class="dropdown-item">
                  <i class="ni ni-user-run"></i>
                  <span>Salir</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>';
}

function MostrarNav()
{
    echo '
    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="scrollbar-inner">
            <div class="sidenav-header align-items-center">
                <a class="navbar-brand" href="home.php">
                    <img src="assets/img/brand/imagen3.png" style="max-width: 20%; height: auto;">
                </a>
            </div>
            <div class="navbar-inner">
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <hr class="my-3">
                    <h6 class="navbar-heading p-0 text-muted">
                        <span class="docs-normal">Visualizaciones</span>
                    </h6>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="categorias.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Productos por categorías</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos.php">
                                <i class="ni ni-box-2 text-primary"></i>
                                <span class="nav-link-text">Todos los productos</span>
                            </a>
                        </li>
                    </ul>
                    <h6 class="navbar-heading p-0 text-muted">
                        <span class="docs-normal">Modificaciones</span>
                    </h6>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="productos_crud.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Agregar productos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos_actualizar.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Actualizar productos</span>
                            </a>
                        </li>
                    </ul>
                    <h6 class="navbar-heading p-0 text-muted">
                        <span class="docs-normal">Gestiones</span>
                    </h6>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="pago.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Ver detalles del carrito</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="miscompras.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Ver mis compras</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>';
}


function HeadCSS()
{
    echo '
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
      <meta name="author" content="Creative Tim">
      <title>Argon - Proyecto Ambiente Web</title>
      <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
      <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
      <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
      <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
      <style>
        .custom-img {
            height: 400px;
            object-fit: cover;
        }
      </style>
    </head>';
}

function HeadJS()
{
    echo '
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>';
}

function ValidarRolAdministrador()
{
    if ($_SESSION["RolUsuario"] != 2) {
        header("location: login.php");
        exit();
    }
}

function MostrarFooter()
{
    echo '
    <footer class="py-5 mt-auto" id="footer-main">
        <div class="container">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; 2024 <a class="font-weight-bold ml-1" target="_blank">Todos los derechos reservados</a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                        <li class="nav-item">
                            <a class="font-weight-bold ml-1" target="_blank">Proyecto Lenguajes de Base de Datos SC-504</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>';
}

?>
