<?php
include_once 'layout.php';
include_once '../Model/productos_model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

$productos = obtenerTodosLosProductos();

$categorias = obtenerTodasLasCategorias();
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
        <h2>Actualizar Productos</h2>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive mx-auto" style="max-width: 95%;">
            <form action="../Controller/productos_controller.php" method="POST">
                <table class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>ID Producto</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Ruta Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productos) && is_array($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?></td>
                                    <td><input type="text" class="form-control" name="nombre_<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>" value="<?php echo htmlspecialchars($producto['NOMBRE_PRODUCTO']); ?>"></td>
                                    <td><textarea class="form-control" name="descripcion_<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>"><?php echo htmlspecialchars($producto['DESCRIPCION']); ?></textarea></td>
                                    <td><input type="number" step="0.01" class="form-control" name="precio_<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>" value="<?php echo htmlspecialchars($producto['PRECIO']); ?>"></td>
                                    <td><input type="number" class="form-control" name="stock_<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>" value="<?php echo htmlspecialchars($producto['STOCK']); ?>"></td>
                                    <td>
                                        <select class="form-control" name="idCategoria_<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>">
                                            <?php foreach ($categorias as $categoria): ?>
                                                <option value="<?php echo htmlspecialchars($categoria['ID_CATEGORIA']); ?>" <?php echo $categoria['ID_CATEGORIA'] == $producto['ID_CATEGORIA'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($categoria['NOMBRE_CATEGORIA']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="rutaImagen_<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>" value="<?php echo htmlspecialchars($producto['RUTA_IMAGEN']); ?>"></td>
                                    <td>
                                        <button type="submit" name="accion" value="actualizar_producto_<?php echo htmlspecialchars($producto['ID_PRODUCTO']); ?>" class="btn btn-warning">Actualizar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No hay productos disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
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
