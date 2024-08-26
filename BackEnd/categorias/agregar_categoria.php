<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agregar Categoría - Mountain-Bliss-Resort</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../public/build/css/styles.css" />
    <link rel="icon" href="../public/build/img/icon.png" type="image/x-icon" />
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
                <div class="row">
                    <div class="container mt-5">
                        <h1 style="color: #333">Agregar Nueva Categoría</h1>
                        <form action="insertar_categoria.php" method="POST">
                            <div class="form-group">
                                <label for="nombre_categoria">Nombre de la Categoría</label>
                                <input type="text" id="nombre_categoria" name="nombre_categoria" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" class="form-control" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn" style="background-color: #013e6a; color: white; margin-bottom: 2rem;">Agregar Categoría</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer_area">
            <p class="footer_text">&copy; 2024 Mountain-Bliss-Resort. Todos los derechos reservados.</p>
        </footer>
    </div>

</body>
</html>
