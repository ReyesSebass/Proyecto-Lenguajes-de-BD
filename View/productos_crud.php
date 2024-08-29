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

    <div class="container my-5">
        <h2>Agregar/Eliminar Producto</h2>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="../Controller/productos_controller.php" method="POST">
            <input type="hidden" name="accion" value="agregar">
            <div class="form-group">
                <label for="nombre">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <div class="form-group">
                <label for="idCategoria">Categoría</label>
                <select class="form-control" id="idCategoria" name="idCategoria" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['ID_CATEGORIA']; ?>"><?php echo $categoria['NOMBRE_CATEGORIA']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rutaImagen">Ruta de Imagen</label>
                <input type="text" class="form-control" id="rutaImagen" name="rutaImagen" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
        </form>

        <h2 class="mt-5">Eliminar Producto</h2>
        <form action="../Controller/productos_controller.php" method="POST">
            <input type="hidden" name="accion" value="eliminar">
            <div class="form-group">
                <label for="idProductoEliminar">ID del Producto a eliminar</label>
                <input type="text" class="form-control" id="idProductoEliminar" name="idProducto" required>
            </div>
            <button type="submit" class="btn btn-danger">Eliminar Producto</button>
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
