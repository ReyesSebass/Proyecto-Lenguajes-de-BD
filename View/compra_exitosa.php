<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Compra</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php 
    include_once 'layout.php';
    HeadCSS();
    MostrarNav();
    MostrarMenu();
    ?>
    <div class="container mt-4 d-flex justify-content-center">
        <div class="card text-center" style="width: 24rem;">
            <div class="card-body">
                <img src="assets/img/brand/imagen3.png" class="card-img-top mb-3" alt="Imagen de confirmación">
                <i class="fas fa-check-circle fa-3x text-success"></i>
                <h2 class="card-title mt-3">Gracias por su compra en nuestra tienda</h2>
                <p class="card-text">Su pedido ha sido procesado con éxito.</p>
                <a href="productos.php" class="btn btn-primary mt-3">
                    <i class="fa fa-arrow-left"></i> Volver a la tienda
                </a>
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
