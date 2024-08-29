<?php 
include_once 'layout.php';
include_once '../Model/categorias_model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

$categorias = obtenerCategorias();

?>

<!DOCTYPE html>
<html>

<?php 
HeadCSS();
?>

<body class="d-flex flex-column min-vh-100">

<?php 
MostrarNav();
MostrarMenu();
?>

<div class="flex-grow-2 mb-5">
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="display-4 text-white">Categorías</h1>
                        <p class="text-white">Seleccione una categoría para ver los productos disponibles.</p>
                    </div>
                </div>
            </div> 
        </div>
    </div>   
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($categoria['NOMBRE_CATEGORIA']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($categoria['DESCRIPCION']); ?></p>
                                <a href="productos.php?categoria=<?php echo $categoria['ID_CATEGORIA']; ?>" class="btn btn-primary">Ver Productos</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-lg-12 text-center">
                    <p class="text-white">No hay categorías disponibles.</p>
                </div>
            <?php endif; ?>
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
