<?php
include 'BackEnd/Dbconexion.php';

if (!$conn) {
    die("Conexión fallida: " . htmlentities(oci_error()['message'], ENT_QUOTES));
}

$stid = oci_parse($conn, 'BEGIN obtener_categorias(:p_cursor); END;');

// Crear y asociar el cursor de salida
$cursor = oci_new_cursor($conn);
oci_bind_by_name($stid, ':p_cursor', $cursor, -1, OCI_B_CURSOR);

oci_execute($stid);
oci_execute($cursor);
?>

<!-- Modificar html -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Mountain-Bliss-Resort</title>
    <link rel="stylesheet" href="../css/estilos.css">
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
            <div class="container">
                <h1 style="color: #333">Categorías</h1>
                <a href="agregar_categoria.php" class="button">Agregar Nueva Categoría</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Categoría</th>
                            <th>Nombre de la Categoría</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while (($row = oci_fetch_assoc($cursor)) !== false): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ID_CATEGORIA'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($row['NOMBRE_CATEGORIA'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($row['DESCRIPCION'], ENT_QUOTES); ?></td>
                                <td>
                                    <a href="editar_categoria.php?id=<?php echo htmlspecialchars($row['ID_CATEGORIA'], ENT_QUOTES); ?>" class="btn btn-sm" style="background-color: #013e6a; color: white;">Editar</a>
                                    <a href="eliminar_categoria.php?id=<?php echo htmlspecialchars($row['ID_CATEGORIA'], ENT_QUOTES); ?>" class="btn btn-sm" style="background-color: #013e6a; color: white;" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer_area">
            <p class="footer_text">
                &copy; 2024 Mountain-Bliss-Resort. Todos los derechos reservados.
            </p>
        </footer>
    </div>

    <?php 
    oci_free_statement($stid);
    oci_free_statement($cursor);
    oci_close($conn); 
    ?>
</body>
</html>
