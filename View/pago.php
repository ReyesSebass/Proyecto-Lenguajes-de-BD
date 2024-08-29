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
    <a href="productos.php" class="btn btn-light mb-4">
        <i class="fa fa-arrow-left"></i> Seguir comprando
    </a>
    <h3>Carrito de Compras</h3>
    <form action="../Controller/carrito_controller.php" method="post">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $precioTotal = 0;
                foreach ($productosCarrito as $idProducto => $producto): 
                    $subtotal = $producto['precio'] * $producto['cantidad'];
                    $precioTotal += $subtotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($producto['precio'], 2)); ?></td>
                        <td>
                            <input type="number" name="cantidad[<?php echo htmlspecialchars($idProducto); ?>]" value="<?php echo htmlspecialchars($producto['cantidad']); ?>" min="1" class="form-control" style="width: 100px;">
                        </td>
                        <td>$<?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>
                        <td>
                            <div class="d-flex">
                                <a href="../Controller/carrito_controller.php?action=remove&id=<?php echo htmlspecialchars($idProducto); ?>" class="btn btn-danger btn-sm me-2">Eliminar</a>
                                <button type="submit" name="accion" value="actualizar" class="btn btn-warning btn-sm">Actualizar Cantidades</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4>Total: $<?php echo htmlspecialchars(number_format($precioTotal, 2)); ?></h4>
        <div class="d-flex justify-content-between mt-3">
            <a href="pago_final.php" class="btn btn-primary">Confirmar Compra</a>
        </div>
    </form>
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
