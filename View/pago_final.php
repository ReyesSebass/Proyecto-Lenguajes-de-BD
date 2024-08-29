<?php 
include_once 'layout.php';

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

$productosCarrito = $_SESSION['carrito'];
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

<div class="container mt-4">
    <a href="pago.php" class="btn btn-light mb-4">
        <i class="fa fa-arrow-left"></i> Regresar
    </a>
    
    <h3>Confirmación de compra</h3>
    <div class="row">
        <div class="col-lg-12">
            <?php if (!empty($productosCarrito)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $precioTotal = 0;
                        foreach ($productosCarrito as $producto): 
                            $subtotal = $producto['precio'] * $producto['cantidad'];
                            $precioTotal += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>$<?php echo htmlspecialchars(number_format($producto['precio'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                                <td>$<?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h4>Total: $<?php echo htmlspecialchars(number_format($precioTotal, 2)); ?></h4>
                <form action="../Controller/pago_controller.php" method="post">
                    <button type="submit" class="btn btn-primary mt-3">Realizar pago</button>
                </form>
            <?php else: ?>
                <p>Tu carrito está vacío.</p>
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
