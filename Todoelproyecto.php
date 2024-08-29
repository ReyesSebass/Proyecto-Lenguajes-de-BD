Model
- baseDatosModel.php
<?php

function AbrirOrcl()
{
    $username = 'Administrador';
    $password = 'Administrador';
    $dbname = 'XE';
    $hostname = 'localhost';
    $connString = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$hostname)(PORT=1521))(CONNECT_DATA=(SERVICE_NAME=$dbname)))";
    $conexion = oci_connect($username, $password, $connString, 'AL32UTF8');
    if (!$conexion) {
        $error = oci_error();
        echo "Error de conexión: " . $error['message'];
        return false;
    }
    return $conexion;
}

function CerrarOrcl($conexion)
{
    if ($conexion) {
        oci_close($conexion);
    }
}

?>
- categorias_model.php
<?php
include_once 'baseDatosModel.php';

function obtenerCategorias() {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return [
            'error_message' => "ERROR: No se pudo conectar a la base de datos."
        ];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_CATEGORIAS(:resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return [
            'error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']
        ];
    }

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return [
            'error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']
        ];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return [
            'error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']
        ];
    }

    $categorias = [];
    while ($row = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $categorias[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($resultado);
    CerrarOrcl($conn);
    return $categorias;
}
?>
- procesar_login.php
<?php
include_once 'baseDatosModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function procesarLogin($email, $password) {
    $conn = AbrirOrcl();

    if ($conn === false) {
        return [
            'error_message' => "ERROR: No se pudo conectar a la base de datos."
        ];
    }

    $stmt = oci_parse($conn, "BEGIN SP_PROCESAR_LOGIN(:p_email, :p_id_usuario, :p_correo, :p_password, :p_id_rol, :p_nombre); END;");

    if (!$stmt) {
        $error = oci_error($conn);
        return [
            'error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']
        ];
    }

    oci_bind_by_name($stmt, ":p_email", $email);
    oci_bind_by_name($stmt, ":p_id_usuario", $id, 32);
    oci_bind_by_name($stmt, ":p_correo", $correo, 50);
    oci_bind_by_name($stmt, ":p_password", $hashed_password, 255);
    oci_bind_by_name($stmt, ":p_id_rol", $rol_id, 32);
    oci_bind_by_name($stmt, ":p_nombre", $nombre, 50);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        oci_free_statement($stmt);
        CerrarOrcl($conn);
        return [
            'error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']
        ];
    }

    if ($id !== null) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $correo;
            $_SESSION['rol_id'] = $rol_id;
            $_SESSION['nombre'] = $nombre;
            oci_free_statement($stmt);
            CerrarOrcl($conn);
            return [
                'id' => $id,
                'email' => $correo,
                'rol_id' => $rol_id,
                'nombre' => $nombre
            ];
        } else {
            oci_free_statement($stmt);
            CerrarOrcl($conn);
            return [
                'error_message' => "La contraseña ingresada es incorrecta."
            ];
        }
    } else {
        oci_free_statement($stmt);
        CerrarOrcl($conn);
        return [
            'error_message' => "No se encontró una cuenta con ese correo electrónico."
        ];
    }
}
?>
- procesar_registro.php
<?php
include_once 'baseDatosModel.php';

function procesarRegistro($nombre, $identificacion, $email, $password, $telefono, $rol) {
    $conn = AbrirOrcl();

    if ($conn === false) {
        return "ERROR: No se pudo conectar a la base de datos.";
    }

    // Hashear la contraseña
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Preparar la llamada al procedimiento almacenado
    $sql_call = "BEGIN RegistrarUsuario(:nombre, :identificacion, :email, :password_hash, :telefono, :rol, :resultado); END;";
    $stmt_call = oci_parse($conn, $sql_call);

    if (!$stmt_call) {
        $error = oci_error($conn);
        CerrarOrcl($conn);
        return "Error al preparar la declaración: " . $error['message'];
    }

    // Vincular los parámetros con los valores correspondientes
    oci_bind_by_name($stmt_call, ':nombre', $nombre);
    oci_bind_by_name($stmt_call, ':identificacion', $identificacion);
    oci_bind_by_name($stmt_call, ':email', $email);
    oci_bind_by_name($stmt_call, ':password_hash', $password_hash);
    oci_bind_by_name($stmt_call, ':telefono', $telefono);
    oci_bind_by_name($stmt_call, ':rol', $rol);
    oci_bind_by_name($stmt_call, ':resultado', $resultado, 255);

    // Ejecutar la llamada al procedimiento almacenado
    if (oci_execute($stmt_call, OCI_NO_AUTO_COMMIT)) {
        oci_commit($conn);
        oci_free_statement($stmt_call);
        CerrarOrcl($conn);
        return $resultado; // El resultado del procedimiento
    } else {
        $error = oci_error($stmt_call);
        oci_free_statement($stmt_call);
        CerrarOrcl($conn);
        return "Error al ejecutar la consulta: " . $error['message'];
    }
}
?>

- productos_model.php
<?php
include_once 'baseDatosModel.php';

function obtenerProductosPorCategoria($idCategoria) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_PRODUCTOS_POR_CATEGORIA(:id_categoria, :resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return ['error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']];
    }

    oci_bind_by_name($stmt, ":id_categoria", $idCategoria);

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return ['error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']];
    }

    $productos = [];
    while ($row = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $productos[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($resultado);
    CerrarOrcl($conn);
    return $productos;
}

function obtenerTodosLosProductos() {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_TODOS_LOS_PRODUCTOS(:resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return ['error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']];
    }

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return ['error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']];
    }

    $productos = [];
    while ($row = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $productos[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($resultado);
    CerrarOrcl($conn);
    return $productos;
}

function obtenerTodasLasCategorias() {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN OBTENER_TODAS_LAS_CATEGORIAS(:resultado); END;");
    
    if (!$stmt) {
        $error = oci_error($conn);
        return ['error_message' => "ERROR: No se pudo preparar la declaración: " . $error['message']];
    }

    $resultado = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ":resultado", $resultado, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    if (!oci_execute($resultado)) {
        $error = oci_error($resultado);
        return ['error_message' => "ERROR: No se pudo ejecutar el cursor: " . $error['message']];
    }

    $categorias = [];
    while ($row = oci_fetch_array($resultado, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $categorias[] = $row;
    }

    oci_free_statement($stmt);
    oci_free_statement($resultado);
    CerrarOrcl($conn);
    return $categorias;
}

function agregarProducto($nombre, $descripcion, $precio, $stock, $rutaImagen, $idCategoria) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $precio = floatval($precio);
    $stock = intval($stock);
    $idCategoria = intval($idCategoria);

    $stmt = oci_parse($conn, "BEGIN AGREGAR_PRODUCTO(:nombre, :descripcion, :precio, :stock, :id_categoria, :ruta_imagen); END;");
    
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":descripcion", $descripcion);
    oci_bind_by_name($stmt, ":precio", $precio);
    oci_bind_by_name($stmt, ":stock", $stock);
    oci_bind_by_name($stmt, ":id_categoria", $idCategoria);
    oci_bind_by_name($stmt, ":ruta_imagen", $rutaImagen);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_free_statement($stmt);
    CerrarOrcl($conn);
    return ['success' => true];
}

function actualizarProducto($idProducto, $nombre, $descripcion, $precio, $stock, $idCategoria, $rutaImagen) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN ACTUALIZAR_PRODUCTO(:idProducto, :nombre, :descripcion, :precio, :stock, :idCategoria, :rutaImagen); END;");
    
    $idProducto = is_array($idProducto) ? 0 : (int)$idProducto;
    $nombre = is_array($nombre) ? '' : (string)$nombre;
    $descripcion = is_array($descripcion) ? '' : (string)$descripcion;
    $precio = is_array($precio) ? 0 : (float)$precio;
    $stock = is_array($stock) ? 0 : (int)$stock;
    $idCategoria = is_array($idCategoria) ? 0 : (int)$idCategoria;
    $rutaImagen = is_array($rutaImagen) ? '' : (string)$rutaImagen;

    oci_bind_by_name($stmt, ":idProducto", $idProducto);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":descripcion", $descripcion);
    oci_bind_by_name($stmt, ":precio", $precio);
    oci_bind_by_name($stmt, ":stock", $stock);
    oci_bind_by_name($stmt, ":idCategoria", $idCategoria);
    oci_bind_by_name($stmt, ":rutaImagen", $rutaImagen);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_commit($conn);

    oci_free_statement($stmt);
    CerrarOrcl($conn);
    return ['success' => true];
}


function eliminarProducto($idProducto) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $stmt = oci_parse($conn, "BEGIN ELIMINAR_PRODUCTO(:id_producto); END;");
    
    oci_bind_by_name($stmt, ":id_producto", $idProducto);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_free_statement($stmt);
    CerrarOrcl($conn);
    return ['success' => true];
}

function obtenerProductoPorId($idProducto) {
    $conn = AbrirOrcl();
    if ($conn === false) {
        return ['error_message' => "ERROR: No se pudo conectar a la base de datos."];
    }

    $nombreProducto = '';
    $descripcion = '';
    $precio = 0;
    $stock = 0;
    $idCategoria = 0;
    $rutaImagen = '';

    $stmt = oci_parse($conn, 'BEGIN ObtenerProductoPorId(:id_producto, :nombre_producto, :descripcion, :precio, :stock, :id_categoria, :ruta_imagen); END;');
    
    oci_bind_by_name($stmt, ':id_producto', $idProducto, -1, OCI_B_INT);
    oci_bind_by_name($stmt, ':nombre_producto', $nombreProducto, 1000);
    oci_bind_by_name($stmt, ':descripcion', $descripcion, 1000);
    oci_bind_by_name($stmt, ':precio', $precio);
    oci_bind_by_name($stmt, ':stock', $stock);
    oci_bind_by_name($stmt, ':id_categoria', $idCategoria);
    oci_bind_by_name($stmt, ':ruta_imagen', $rutaImagen, 1000);

    if (!oci_execute($stmt)) {
        $error = oci_error($stmt);
        oci_free_statement($stmt);
        CerrarOrcl($conn);
        return ['error_message' => "ERROR: No se pudo ejecutar el procedimiento almacenado: " . $error['message']];
    }

    oci_free_statement($stmt);
    CerrarOrcl($conn);

    return [
        'ID_PRODUCTO' => $idProducto,
        'NOMBRE_PRODUCTO' => $nombreProducto,
        'DESCRIPCION' => $descripcion,
        'PRECIO' => $precio,
        'STOCK' => $stock,
        'ID_CATEGORIA' => $idCategoria,
        'RUTA_IMAGEN' => $rutaImagen
    ];
}

?>

- recuperar_model.php
<?php
include_once 'baseDatosModel.php';

function ConsultarUsuarioXEmail($email)
{
    $conexion = AbrirBaseDatos();
    $sentencia = "CALL ConsultarUsuarioXEmail('$email')";
    $respuesta = $conexion->query($sentencia);
    CerrarBaseDatos($conexion);
    return $respuesta;
}

function ActualizarContrasennaTemporal($id, $password)
{
    $conexion = AbrirBaseDatos();
    $sentencia = "CALL ActualizarContrasennaTemporal('$id', '$password')";
    $respuesta = $conexion->query($sentencia);
    CerrarBaseDatos($conexion);
    return $respuesta;
}
?>

Controller
- carrito_controller.php
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../View/login.php");
    exit();
}

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $idProducto = $_POST['idProducto'] ?? '';
        $nombreProducto = $_POST['nombreProducto'] ?? '';
        $precioProducto = $_POST['precioProducto'] ?? 0;

        if ($idProducto && $nombreProducto && $precioProducto) {
            // Si el producto ya está en el carrito, solo actualiza la cantidad
            if (isset($_SESSION['carrito'][$idProducto])) {
                $_SESSION['carrito'][$idProducto]['cantidad'] += 1; // Agrega 1 o la cantidad deseada
            } else {
                // Si no está en el carrito, agrégalo con cantidad 1
                $_SESSION['carrito'][$idProducto] = [
                    'nombre' => $nombreProducto,
                    'precio' => $precioProducto,
                    'cantidad' => 1
                ];
            }
        }

        header("Location: ../View/productos.php");
        exit();
    } elseif ($accion === 'actualizar') {
        foreach ($_POST['cantidad'] as $idProducto => $cantidad) {
            $cantidad = intval($cantidad); // Convertir a entero para evitar valores no válidos
            if ($cantidad > 0) {
                // Actualiza la cantidad del producto en el carrito
                if (isset($_SESSION['carrito'][$idProducto])) {
                    $_SESSION['carrito'][$idProducto]['cantidad'] = $cantidad;
                }
            } else {
                // Si la cantidad es 0 o menos, elimina el producto del carrito
                unset($_SESSION['carrito'][$idProducto]);
            }
        }

        header("Location: ../View/pago.php");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['action'] ?? '';

    if ($accion === 'remove' && isset($_GET['id'])) {
        $idProducto = $_GET['id'];

        // Elimina el producto del carrito
        if (isset($_SESSION['carrito'][$idProducto])) {
            unset($_SESSION['carrito'][$idProducto]);
        }

        header("Location: ../View/pago.php");
        exit();
    }
}
?>

- categorias_controller.php
<?php
include_once '../Model/categorias_model.php';

$categorias = obtenerCategorias();

if (isset($categorias['error_message'])) {
    $error_message = $categorias['error_message'];
} else {
    include_once '../View/categorias.php';
}
?>

- login_controller.php
<?php
session_start();
include_once '../Model/procesar_login.php';

function verificarRol($rol_permiso) {
    if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != $rol_permiso) {
        header("Location: ../View/login.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login_result = procesarLogin($email, $password);

    if (isset($login_result['error_message'])) {
        $_SESSION['error_message'] = $login_result['error_message'];
        header("Location: ../View/login.php");
        exit();
    } else {
        $_SESSION['id'] = $login_result['id'];
        $_SESSION['email'] = $login_result['email'];
        $_SESSION['rol_id'] = $login_result['rol_id'];
        $_SESSION['nombre'] = $login_result['nombre'];

        switch ($_SESSION['rol_id']) {
            case 1:
                header("Location: ../View/homeMedicos.php");
                exit();
            case 2:
                header("Location: ../View/homeMedicos.php");
                exit();
            case 3:
                header("Location: ../View/dashboard_admin.php");
                exit();
            default:
                $_SESSION['error_message'] = "ERROR: Rol desconocido.";
                header("Location: ../View/login.php");
                exit();
        }
    }
}
?>

- pago_controller.php
<?php
include_once '../Model/baseDatosModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id']) || !isset($_SESSION['carrito'])) {
    echo "ID del usuario o del carrito no está definido.";
    exit();
}

$id_usuario = $_SESSION['id'];
$productosCarrito = $_SESSION['carrito'];

$conexion = AbrirOrcl();

if (!$conexion) {
    echo "Error de conexión a la base de datos.";
    exit();
}

try {
    // Iniciar transacción
    $stmt = oci_parse($conexion, 'BEGIN NULL; END;');
    oci_execute($stmt);

    // Insertar en FACTURAS
    $totalFactura = 0;
    foreach ($productosCarrito as $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];
        $totalFactura += $subtotal;
    }

    $query = 'INSERT INTO FACTURAS (ID_FACTURA, ID_USUARIO, FECHA_EMISION, TOTAL)
              VALUES (FACTURAS_SEQ.NEXTVAL, :id_usuario, SYSDATE, :total)';
    $stmt = oci_parse($conexion, $query);
    oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
    oci_bind_by_name($stmt, ':total', $totalFactura);
    if (!oci_execute($stmt)) {
        throw new Exception("Error al insertar en FACTURAS: " . oci_error($stmt)['message']);
    }

    // Obtener el último ID_FACTURA generado
    $query = 'SELECT FACTURAS_SEQ.CURRVAL AS ID_FACTURA FROM DUAL';
    $stmt = oci_parse($conexion, $query);
    if (!oci_execute($stmt)) {
        throw new Exception("Error al obtener ID_FACTURA: " . oci_error($stmt)['message']);
    }
    $row = oci_fetch_assoc($stmt);
    $id_factura = $row['ID_FACTURA'];

    // Insertar en PEDIDOS y actualizar INVENTARIO
    foreach ($productosCarrito as $idProducto => $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];

        // Insertar en PEDIDOS
        $query = 'INSERT INTO PEDIDOS (ID_PEDIDO, FECHA, ID_USUARIO, PRECIO_UNITARIO, ID_PRODUCTO, CANTIDAD, TOTAL)
                  VALUES (PEDIDOS_SEQ.NEXTVAL, SYSDATE, :id_usuario, :precio, :id_producto, :cantidad, :total)';
        $stmt = oci_parse($conexion, $query);
        oci_bind_by_name($stmt, ':id_usuario', $id_usuario);
        oci_bind_by_name($stmt, ':precio', $producto['precio']);
        oci_bind_by_name($stmt, ':id_producto', $idProducto);
        oci_bind_by_name($stmt, ':cantidad', $producto['cantidad']);
        oci_bind_by_name($stmt, ':total', $subtotal);
        if (!oci_execute($stmt)) {
            throw new Exception("Error al insertar en PEDIDOS: " . oci_error($stmt)['message']);
        }

        // Actualizar INVENTARIO
        $query = 'UPDATE INVENTARIO SET CANTIDAD_DISPONIBLE = CANTIDAD_DISPONIBLE - :cantidad
                  WHERE ID_PRODUCTO = :id_producto';
        $stmt = oci_parse($conexion, $query);
        oci_bind_by_name($stmt, ':cantidad', $producto['cantidad']);
        oci_bind_by_name($stmt, ':id_producto', $idProducto);
        if (!oci_execute($stmt)) {
            throw new Exception("Error al actualizar INVENTARIO: " . oci_error($stmt)['message']);
        }
    }

    // Confirmar transacción
    $stmt = oci_parse($conexion, 'COMMIT');
    oci_execute($stmt);

    // Limpiar el carrito
    $_SESSION['carrito'] = [];
    
    header("Location: ../View/compra_exitosa.php");
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $stmt = oci_parse($conexion, 'ROLLBACK');
    oci_execute($stmt);
    echo $e->getMessage();
} finally {
    CerrarOrcl($conexion);
}
?>

- productos_controller.php
<?php
include_once '../Model/productos_model.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'agregar':
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                $idCategoria = $_POST['idCategoria'];
                $rutaImagen = $_POST['rutaImagen'];
                
                $resultado = agregarProducto($nombre, $descripcion, $precio, $stock, $rutaImagen, $idCategoria);
                if (isset($resultado['success'])) {
                    header("Location: ../View/productos_crud.php?mensaje=Producto agregado exitosamente.");
                    exit();
                } else {
                    $error_message = $resultado['error_message'];
                }
                break;

                case (preg_match('/^actualizar_producto_(\d+)$/', $_POST['accion'], $matches) ? true : false):
                    $idProducto = $matches[1];
                    
                    $nombre = $_POST['nombre_' . $idProducto] ?? '';
                    $descripcion = $_POST['descripcion_' . $idProducto] ?? '';
                    $precio = $_POST['precio_' . $idProducto] ?? 0;
                    $stock = $_POST['stock_' . $idProducto] ?? 0;
                    $idCategoria = $_POST['idCategoria_' . $idProducto] ?? 0;
                    $rutaImagen = $_POST['rutaImagen_' . $idProducto] ?? '';
    
                    $resultado = actualizarProducto($idProducto, $nombre, $descripcion, $precio, $stock, $idCategoria, $rutaImagen);
    
                    if (!isset($resultado['success'])) {
                        $error_message = $resultado['error_message'];
                    } else {
                        header("Location: ../View/productos_actualizar.php?mensaje=Producto actualizado exitosamente.");
                        exit();
                    }
                    break;

            case 'eliminar':
                $idProducto = $_POST['idProducto'];

                $resultado = eliminarProducto($idProducto);
                if (isset($resultado['success'])) {
                    header("Location: ../View/productos_crud.php?mensaje=Producto eliminado exitosamente.");
                    exit();
                } else {
                    $error_message = $resultado['error_message'];
                }
                break;
        }
    }
} elseif (isset($_GET['categoria'])) {
    $idCategoria = $_GET['categoria'];
    $productos = obtenerProductosPorCategoria($idCategoria);

    if (isset($productos['error_message'])) {
        $error_message = $productos['error_message'];
    } else {
        include_once '../View/productos.php';
    }
} else {
    $categorias = obtenerTodasLasCategorias();
    if (isset($categorias['error_message'])) {
        $error_message = $categorias['error_message'];
    } else {
        $productos = obtenerTodosLosProductos();
        if (isset($productos['error_message'])) {
            $error_message = $productos['error_message'];
        } else {
            include_once '../View/productos_crud.php';
        }
    }
}

if (isset($error_message)) {
    include_once '../View/error.php';
}
?>
- recuperar_controller.php
<?php
include_once '../Model/recuperar_model.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function enviarCorreo($asunto, $contenido, $destinatario, $correoSalida, $contrasennaSalida) {
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';

    $mail->isSMTP();
    $mail->isHTML(true); 
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;                      
    $mail->SMTPAuth = true;
    $mail->Username = $correoSalida;               
    $mail->Password = $contrasennaSalida;                                
    
    $mail->setFrom($correoSalida);
    $mail->Subject = $asunto;
    $mail->MsgHTML($contenido);   
    $mail->addAddress($destinatario);

    if(!$mail->send()) {
        return "Error al enviar el correo: " . $mail->ErrorInfo;
    } else {
        return "Correo enviado exitosamente.";
    }
}

function GenerarCodigo() {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 6; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

if(isset($_POST["BotonRecAcceso"]))
{
    $email = $_POST['email'];
    $respuesta = ConsultarUsuarioXEmail($email);

    if($respuesta->num_rows > 0)
    {
        $datos = mysqli_fetch_array($respuesta);
        $codigo = GenerarCodigo();
        $resp = ActualizarContrasennaTemporal($datos["id"], $codigo);

        if($resp == true)
        {
            $contenido = "<html><body>
            Estimado(a) " . $datos["Nombre"] . "<br/><br/>
            Se ha generado el siguiente código de seguridad: <b>" . $codigo . "</b><br/>
            Recuerde realizar el cambio de contraseña una vez que ingrese a nuestro sistema.<br/><br/>
            Muchas gracias.
            </body></html>";

            $correoSalida = "tuCorreo@ejemplo.com";
            $contrasennaSalida = "tuContrasenna";
            $resultadoEnvio = enviarCorreo('Acceso al Sistema', $contenido, $datos["email"], $correoSalida, $contrasennaSalida);

            if ($resultadoEnvio === "Correo enviado exitosamente.") {
                header("location: ../View/login.php");
                exit();
            } else {
                $_POST["msj"] = $resultadoEnvio;
            }
        }
        else
        {
            $_POST["msj"] = "No se ha podido enviar su código de seguridad correctamente.";
        }
    }
    else
    {
        $_POST["msj"] = "Su información no se ha validado correctamente.";
    }
}
?>

- registro_controller.php
<?php
include_once '../Model/procesar_registro.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $identificacion = $_POST['identificacion'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];
    $rol = $_POST['rol'];

    $resultado = procesarRegistro($nombre, $identificacion, $email, $password, $telefono, $rol);

    if ($resultado === 'Usuario registrado con éxito.') {
        header("Location: login.php");
        exit();
    } else {
        $error_registro = $resultado;
    }
}
?>

View
- categorias.php
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

- compra_exitosa.php
<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Compra</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Gracias por su compra</h2>
        <p>Su pedido ha sido procesado con éxito.</p>
        <a href="productos.php" class="btn btn-primary">Volver a la tienda</a>
    </div>
</body>
</html>

- error.php
<?php
$error_message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Ocurrió un error desconocido.';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
    <h1>Se produjo un error</h1>
    <p><?php echo $error_message; ?></p>
</body>
</html>

- layout.php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function MostrarMenu()
{
    echo '<div class="main-content" id="panel">
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav align-items-center ml-md-auto">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="assets/img/theme/usuario.png">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm font-weight-bold">' . htmlspecialchars($_SESSION['nombre']) . '</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Bienvenido</h6>
                </div>
                <div class="dropdown-divider"></div>
                <a href="login.php" class="dropdown-item">
                  <i class="ni ni-user-run"></i>
                  <span>Salir</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>';
}

function MostrarNav()
{
    echo '
    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="scrollbar-inner">
            <div class="sidenav-header align-items-center">
                <a class="navbar-brand" href="categorias.php">
                    <img src="assets/img/brand/imagen3.png" style="max-width: 20%; height: auto;">
                </a>
            </div>
            <div class="navbar-inner">
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <hr class="my-3">
                    <h6 class="navbar-heading p-0 text-muted">
                        <span class="docs-normal">Visualizaciones</span>
                    </h6>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="categorias.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Visualizar categorías</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos.php">
                                <i class="ni ni-box-2 text-primary"></i>
                                <span class="nav-link-text">Visualizar productos</span>
                            </a>
                        </li>
                    </ul>
                    <h6 class="navbar-heading p-0 text-muted">
                        <span class="docs-normal">Modificaciones</span>
                    </h6>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="productos_crud.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Agregar productos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos_actualizar.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Actualizar productos</span>
                            </a>
                        </li>
                    </ul>
                    <h6 class="navbar-heading p-0 text-muted">
                        <span class="docs-normal">Gestiones</span>
                    </h6>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="pago.php">
                                <i class="ni ni-bullet-list-67 text-primary"></i>
                                <span class="nav-link-text">Ver detalles del carrito</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>';
}


function HeadCSS()
{
    echo '
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
      <meta name="author" content="Creative Tim">
      <title>Argon - Proyecto Ambiente Web</title>
      <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
      <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
      <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
      <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
      <style>
        .custom-img {
            height: 400px;
            object-fit: cover;
        }
      </style>
    </head>';
}

function HeadJS()
{
    echo '
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>';
}

function ValidarRolAdministrador()
{
    if ($_SESSION["RolUsuario"] != 2) {
        header("location: login.php");
        exit();
    }
}

function MostrarFooter()
{
    echo '
    <footer class="py-5 mt-auto" id="footer-main">
        <div class="container">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; 2024 <a class="font-weight-bold ml-1" target="_blank">Todos los derechos reservados</a>
                    </div>
                </div>
                <div class="col-xl-6">
                    <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                        <li class="nav-item">
                            <a class="font-weight-bold ml-1" target="_blank">Proyecto Lenguajes de Base de Datos SC-504</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>';
}

?>

- login.php
<?php
session_start();
include_once '../Model/procesar_login.php';
include_once 'layout.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
</head>

<body class="bg-default">
    <nav id="navbar-main"
        class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="login.php">
                <img src="assets/img/brand/imagen4.png" style="max-width: 20%; height: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse"
                aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="login.php" class="nav-link">
                            <span class="nav-link-inner--text">Iniciar Sesion</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="registro.php" class="nav-link">
                            <span class="nav-link-inner--text">Registrarse</span>
                        </a>
                    </li>
                </ul>
                <hr class="d-lg-none" />
            </div>
        </div>
    </nav>

    <div class="main-content">

        <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <h1 class="text-white">Bienvenido</h1>
                        <p class="text-lead text-white">Ingresa en la pagina de compras de Chillyouknow para realizar tus compras.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        
        <div class="container mt--8 pb-5">
            
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card bg-secondary border-0">
                        <div class="card-header bg-transparent pb-5">
                            <div class="text-muted text-center mt-2 mb-3">
                                <h1 class="text-black">Iniciar Sesión</h1>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="POST" action="../Controller/login_controller.php">
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Email" type="email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Contraseña" type="password" name="password" required>
                                    </div>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck" name="remember_me">
                                    <label class="custom-control-label" for="customCheck">Recuérdame</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">Entrar</button>
                                </div>
                                <?php
                                if (isset($_SESSION['error_message'])) {
                                    echo '<div class="alert alert-danger mt-4">' . $_SESSION['error_message'] . '</div>';
                                    unset($_SESSION['error_message']);
                                }
                                
                                ?>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-6">
                        <a href="recuperar.php" class="text-light"><small>Recuperar contraseña</small></a>
                      </div>
                      <div class="col-6 text-right">
                        <a href="registro.php" class="text-light"><small>Crear una cuenta</small></a>
                      </div>
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
- pago_final.php
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
    <!-- Icono para regresar a la página de pago -->
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
                <!-- Actualización del formulario de pago -->
                <form action="../Controller/pago_controller.php" method="post">
                    <!-- No es necesario pasar el total en un campo oculto -->
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

- pago.php
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
                                <!-- Aquí está el botón de actualizar -->
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

- productos_actualizar.php
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

- productos_crud.php
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

- productos.php
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

<!-- Resumen del carrito en la parte superior -->
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
                                            <!-- Agregar cantidad disponible -->
                                            <p class="card-text">Cantidad Disponible: <?php echo htmlspecialchars($producto['STOCK']); ?></p>
                                            <p class="card-text mt-auto">Precio: $<?php echo htmlspecialchars(number_format($producto['PRECIO'], 2)); ?></p>
                                            <!-- Formulario para añadir al carrito -->
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

- recuperar.php
<?php include_once '../Controller/recuperar_controller.php';
include_once 'layout.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
</head>

<body class="bg-default">
  <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="login.php">
          <img src="assets/img/brand/imagen4.png" style="max-width: 20%; height: auto;">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a href="login.php" class="nav-link">
              <span class="nav-link-inner--text">Iniciar sesión</span>
            </a>
          </li>
        </ul>
        <hr class="d-lg-none" />
        <ul class="navbar-nav align-items-lg-center ml-lg-auto">
          <li class="nav-item">
            
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <h1 class="text-white">Recuperar contraseña</h1>
              <p class="text-lead text-white">En esta seccion puedes realizar la recuperación de tu contraseña</p>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent pb-5">
              <div class="text-muted text-center mt-2 mb-3"><h1 class="text-black">Recuperar contraseña</h1></div>
              <form role="form">
                <div class="form-group mb-3">
                  <div class="input-group input-group-merge input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input class="form-control" placeholder="Email" type="email">
                  </div>
                </div>
                <div class="form-group"> 
                </div>
                <div class="text-center">
                  <button type="submit" id="BotonRecAcceso" class="btn btn-primary mt-4">Enviar codigo</button>
                </div>
              </form>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="login.php" class="text-light"><small>Iniciar Sesion</small></a>
            </div>
            <div class="col-6 text-right">
              <a href="registro.php" class="text-light"><small>Crear una cuenta</small></a>
            </div>
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

- registro.php
<?php
include_once '../Controller/registro_controller.php';
include_once 'layout.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
</head>

<body class="bg-default">
    <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="login.php">
                <img src="assets/img/brand/imagen4.png" style="max-width: 20%; height: auto;">
            </a>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="login.php" class="nav-link">
                        <span class="nav-link-inner--text">Iniciar Sesion</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="registro.php" class="nav-link">
                        <span class="nav-link-inner--text">Registrarse</span>
                    </a>
                </li>
            </ul>
            <hr class="d-lg-none" />
        </div>
    </nav>

    <div class="main-content">
        <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                            <h1 class="text-white">Crear cuenta</h1>
                            <p class="text-lead text-white">Registrate para realizar tus gestiones médicas</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>

        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card bg-secondary border-0">
                        <div class="card-header bg-transparent pb-5">
                            <div class="text-muted text-center mt-2 mb-3">
                                <h1 class="text-black">Registrate</h1>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="POST" action="registro.php">
                                <?php if (isset($error_registro)) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $error_registro; ?>
                                </div>
                                <?php endif; ?>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Nombre" type="text" name="nombre" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Identificación sin guiones" type="text" name="identificacion" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Email" type="email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Teléfono" type="text" name="telefono" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Contraseña" type="password" name="password" required>
                                    </div>
                                </div>
                                <input type="hidden" name="rol" value="1">
                                <div class="row my-4">
                                    <div class="col-12">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" id="customCheckRegister" type="checkbox" required>
                                            <label class="custom-control-label" for="customCheckRegister">
                                                <span class="text-muted">Estoy de acuerdo con la <a href="#!">Privacy Policy</a></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">Crear cuenta</button>
                                </div>
                            </form>
                        </div>
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

- index.php
<?php 
    header("location: View/login.php");
?>
