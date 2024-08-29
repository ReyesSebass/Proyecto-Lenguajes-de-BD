<?php 
include_once 'layout.php';
include_once '../Model/productos_model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

if (isset($_GET['categoria'])) {
    $idCategoria = $_GET['categoria'];
    $productos = obtenerProductosPorCategoria($idCategoria);
} else {
    $productos = obtenerTodosLosProductos();
}
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

<div class="container">
    <div class="position-fixed" style="top: 0; left: 50%; transform: translateX(-50%); z-index: 1050;">
        <div class="card" style="max-width: 350px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fa fa-shopping-cart fa-2x me-2"></i>
                    <div style="font-size: 0.9em;">
                        <h4 class="mb-0">Carrito de Compras</h4>
                        <p class="mb-0">
                            <?php 
                            $cantidadTotal = 0;
                            $precioTotal = 0;
                            if (!empty($_SESSION['carrito'])): 
                                foreach ($_SESSION['carrito'] as $producto): 
                                    $cantidadTotal += $producto['cantidad'];
                                    $precioTotal += $producto['precio'] * $producto['cantidad'];
                                endforeach;
                            endif;
                            ?>
                            Artículos: <?php echo htmlspecialchars($cantidadTotal); ?> - 
                            Precio Total: $<?php echo htmlspecialchars(number_format($precioTotal, 2)); ?>
                        </p>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-2">
                    <a href="pago.php" class="btn btn-success">Ir a Pagar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex-grow-2 mb-5">
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="display-4 text-white">Productos</h1>
                        <p class="text-white">Todos los productos disponibles.</p>
                    </div>
                </div>
                <div class="row">
                    <?php 
                    if (isset($productos['error_message'])): ?>
                        <div class="col-lg-12">
                            <p class="text-white"><?php echo htmlspecialchars($productos['error_message']); ?></p>
                        </div>
                    <?php else: ?>
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <img src="<?php echo htmlspecialchars($producto['RUTA_IMAGEN']); ?>" class="card-img-top img-fluid custom-img" alt="Producto">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($producto['DESCRIPCION']); ?></p>
                                            <p class="card-text">Cantidad Disponible: <?php echo htmlspecialchars($producto['STOCK']); ?></p>
                                            <p class="card-text mt-auto">Precio: $<?php echo htmlspecialchars(number_format($producto['PRECIO'], 2)); ?></p>
                                            <form action="../Controller/carrito_controller.php" method="post">
                                                <input type="hidden" name="accion" value="agregar">
                                                <input type="hidden" name="idProducto" value="<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>">
                                                <input type="hidden" name="nombreProducto" value="<?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?>">
                                                <input type="hidden" name="precioProducto" value="<?php echo htmlspecialchars($producto['PRECIO']); ?>">
                                                <button type="submit" class="btn btn-primary mt-2">Añadir al carrito</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-lg-12">
                                <p class="text-white">No hay productos disponibles.</p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
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
