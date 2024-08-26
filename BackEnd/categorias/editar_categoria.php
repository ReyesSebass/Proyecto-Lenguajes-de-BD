<?php
include '../../../database/database.php';

if (!$conn) {
    die("Conexión fallida: " . htmlentities(oci_error()['message'], ENT_QUOTES));
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de la categoría no proporcionado.");
}

$categoria_id = $_GET['id'];

// Preparar la llamada al procedimiento almacenado
$query = 'BEGIN obtener_categorias(:cursor); END;';
$stid = oci_parse($conn, $query);

// Crear un cursor para el resultado
$cursor = oci_new_cursor($conn);

// Bind variables
oci_bind_by_name($stid, ':cursor', $cursor, -1, OCI_B_CURSOR);

// Ejecutar la consulta
if (!oci_execute($stid)) {
    $e = oci_error($stid);
    die("Error en la ejecución de la consulta: " . htmlentities($e['message'], ENT_QUOTES));
}

// Ejecutar el cursor
if (!oci_execute($cursor)) {
    $e = oci_error($cursor);
    die("Error en la ejecución del cursor: " . htmlentities($e['message'], ENT_QUOTES));
}

// Buscar la categoría con el ID proporcionado
$categoria = null;
while (($row = oci_fetch_assoc($cursor)) !== false) {
    if ($row['ID_CATEGORIA'] == $categoria_id) {
        $categoria = $row;
        break;
    }
}

// Verificar si se encontró la categoría
if (!$categoria) {
    die("No se encontró la categoría.");
}

// Liberar los recursos
oci_free_statement($stid);
oci_free_statement($cursor);
oci_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Categoría - Mountain-Bliss-Resort</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../public/build/css/styles.css" />
    <link rel="icon" href="../../../public/build/img/icon.png" type="image/x-icon" />
    <link rel="shortcut icon" href="../../../public/build/img/icon.png" type="image/x-icon" />
</head>
<body>
    <!-- Content -->
    <div class="content">
        <!-- Header -->
        <header class="header_area">
            <a href="../../../public/index.php" class="header_link">
                <h1>Mountain-Bliss-Resort</h1>
            </a>
        </header>

        <!-- Main Content -->
        <section class="options_area">
            <div class="container mt-5">
                <h1 style="color: #333">Editar Categoría</h1>
                <form action="actualizar_categoria.php" method="POST">
                    <input type="hidden" name="categoria_id" value="<?php echo htmlspecialchars($categoria['ID_CATEGORIA'], ENT_QUOTES); ?>">
                    <div class="form-group">
                        <label for="nombre_categoria">Nombre de la Categoría</label>
                        <input type="text" id="nombre_categoria" name="nombre_categoria" class="form-control" value="<?php echo htmlspecialchars($categoria['NOMBRE_CATEGORIA'], ENT_QUOTES); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control"><?php echo htmlspecialchars($categoria['DESCRIPCION'], ENT_QUOTES); ?></textarea>
                    </div>
                    <button type="submit" class="btn" style="background-color: #013e6a; color: white; margin-bottom: 2rem;">Actualizar Categoría</button>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer_area">
            <p class="footer_text">&copy; 2024 Mountain-Bliss-Resort. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
