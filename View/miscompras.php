<?php
include_once 'layout.php';
include_once '../Controller/miscompras_controller.php';
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

    <div class="container-fluid my-5">
        <h2>Mis Compras</h2>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($facturas)): ?>
            <div class="table-responsive mx-auto" style="max-width: 95%;">
                <?php foreach ($facturas as $factura): ?>
                    <h3>Factura ID: <?php echo htmlspecialchars($factura['ID_FACTURA']); ?></h3>
                    <p>Fecha: <?php echo htmlspecialchars($factura['FECHA_EMISION']); ?></p>
                    <p>Total: $<?php echo number_format($factura['TOTAL'], 2); ?></p>

                    <table class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($factura['productos'])): ?>
                                <?php foreach ($factura['productos'] as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['CANTIDAD']); ?></td>
                                        <td>$<?php echo number_format($producto['PRECIO_UNITARIO'], 2); ?></td>
                                        <td>$<?php echo number_format($producto['TOTAL'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No hay productos para esta factura.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <hr>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No has realizado ninguna compra aún.
            </div>
        <?php endif; ?>
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
