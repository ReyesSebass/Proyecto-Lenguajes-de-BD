--1. Agregar un nuevo rol
CREATE OR REPLACE PROCEDURE AgregarRol(
    p_id_rol IN NUMBER,
    p_nombre_rol IN VARCHAR2,
    p_descripcion IN VARCHAR2
) AS
BEGIN
    INSERT INTO ROLES (ID_ROL, NOMBRE_ROL, DESCRIPCION)
    VALUES (p_id_rol, p_nombre_rol, p_descripcion);
END;

--2. Actualizar un rol
CREATE OR REPLACE PROCEDURE ActualizarRol(
    p_id_rol IN NUMBER,
    p_nombre_rol IN VARCHAR2,
    p_descripcion IN VARCHAR2
) AS
BEGIN
    UPDATE ROLES
    SET NOMBRE_ROL = p_nombre_rol,
        DESCRIPCION = p_descripcion
    WHERE ID_ROL = p_id_rol;
END;

--3. Eliminar un rol
CREATE OR REPLACE PROCEDURE EliminarRol(
    p_id_rol IN NUMBER
) AS
BEGIN
    DELETE FROM ROLES
    WHERE ID_ROL = p_id_rol;
END;

--4. Obtener todos los roles
CREATE OR REPLACE PROCEDURE ObtenerRoles
AS
BEGIN
    FOR r IN (SELECT * FROM ROLES) LOOP
        DBMS_OUTPUT.PUT_LINE('ID: ' || r.ID_ROL || ', Nombre: ' || r.NOMBRE_ROL || ', Descripción: ' || r.DESCRIPCION);
    END LOOP;
END;

--5. Agregar una nueva categoría
CREATE OR REPLACE PROCEDURE AgregarCategoria(
    p_id_categoria IN NUMBER,
    p_nombre_categoria IN VARCHAR2,
    p_descripcion IN VARCHAR2
) AS
BEGIN
    INSERT INTO CATEGORIAS (ID_CATEGORIA, NOMBRE_CATEGORIA, DESCRIPCION)
    VALUES (p_id_categoria, p_nombre_categoria, p_descripcion);
END;

--6. Actualizar una categoría
CREATE OR REPLACE PROCEDURE ActualizarCategoria(
    p_id_categoria IN NUMBER,
    p_nombre_categoria IN VARCHAR2,
    p_descripcion IN VARCHAR2
) AS
BEGIN
    UPDATE CATEGORIAS
    SET NOMBRE_CATEGORIA = p_nombre_categoria,
        DESCRIPCION = p_descripcion
    WHERE ID_CATEGORIA = p_id_categoria;
END;

--7. Eliminar una categoría
CREATE OR REPLACE PROCEDURE EliminarCategoria(
    p_id_categoria IN NUMBER
) AS
BEGIN
    DELETE FROM CATEGORIAS
    WHERE ID_CATEGORIA = p_id_categoria;
END;

--8. Obtener todas las categorías
CREATE OR REPLACE PROCEDURE ObtenerCategorias
AS
BEGIN
    FOR r IN (SELECT * FROM CATEGORIAS) LOOP
        DBMS_OUTPUT.PUT_LINE('ID: ' || r.ID_CATEGORIA || ', Nombre: ' || r.NOMBRE_CATEGORIA || ', Descripción: ' || r.DESCRIPCION);
    END LOOP;
END;

--9. Agregar un nuevo usuario
CREATE OR REPLACE PROCEDURE AgregarUsuario(
    p_id_usuario IN NUMBER,
    p_nombre IN VARCHAR2,
    p_apellido IN VARCHAR2,
    p_correo IN VARCHAR2,
    p_telefono IN VARCHAR2,
    p_id_rol IN NUMBER
) AS
BEGIN
    INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, APELLIDO, CORREO, TELEFONO, ID_ROL)
    VALUES (p_id_usuario, p_nombre, p_apellido, p_correo, p_telefono, p_id_rol);
END;

--10. Actualizar un usuario
CREATE OR REPLACE PROCEDURE ActualizarUsuario(
    p_id_usuario IN NUMBER,
    p_nombre IN VARCHAR2,
    p_apellido IN VARCHAR2,
    p_correo IN VARCHAR2,
    p_telefono IN VARCHAR2,
    p_id_rol IN NUMBER
) AS
BEGIN
    UPDATE USUARIOS
    SET NOMBRE = p_nombre,
        APELLIDO = p_apellido,
        CORREO = p_correo,
        TELEFONO = p_telefono,
        ID_ROL = p_id_rol
    WHERE ID_USUARIO = p_id_usuario;
END;

--11. Eliminar un usuario
CREATE OR REPLACE PROCEDURE EliminarUsuario(
    p_id_usuario IN NUMBER
) AS
BEGIN
    DELETE FROM USUARIOS
    WHERE ID_USUARIO = p_id_usuario;
END;

--12. Obtener todos los usuarios
CREATE OR REPLACE PROCEDURE ObtenerUsuarios
AS
BEGIN
    FOR r IN (SELECT * FROM USUARIOS) LOOP
        DBMS_OUTPUT.PUT_LINE('ID: ' || r.ID_USUARIO || ', Nombre: ' || r.NOMBRE || ', Apellido: ' || r.APELLIDO || ', Correo: ' || r.CORREO || ', Telefono: ' || r.TELEFONO || ', Rol ID: ' || r.ID_ROL);
    END LOOP;
END;

--13. Agregar un nuevo producto
CREATE OR REPLACE PROCEDURE AgregarProducto(
    p_id_producto IN NUMBER,
    p_nombre_producto IN VARCHAR2,
    p_descripcion IN VARCHAR2,
    p_precio IN NUMBER,
    p_stock IN NUMBER,
    p_id_categoria IN NUMBER,
    p_ruta_imagen IN VARCHAR2
) AS
BEGIN
    INSERT INTO PRODUCTOS (ID_PRODUCTO, NOMBRE_PRODUCTO, DESCRIPCION, PRECIO, STOCK, ID_CATEGORIA, RUTA_IMAGEN)
    VALUES (p_id_producto, p_nombre_producto, p_descripcion, p_precio, p_stock, p_id_categoria, p_ruta_imagen);
END;

--14. Actualizar un producto
CREATE OR REPLACE PROCEDURE ActualizarProducto(
    p_id_producto IN NUMBER,
    p_nombre_producto IN VARCHAR2,
    p_descripcion IN VARCHAR2,
    p_precio IN NUMBER,
    p_stock IN NUMBER,
    p_id_categoria IN NUMBER,
    p_ruta_imagen IN VARCHAR2
) AS
BEGIN
    UPDATE PRODUCTOS
    SET NOMBRE_PRODUCTO = p_nombre_producto,
        DESCRIPCION = p_descripcion,
        PRECIO = p_precio,
        STOCK = p_stock,
        ID_CATEGORIA = p_id_categoria,
        RUTA_IMAGEN = p_ruta_imagen
    WHERE ID_PRODUCTO = p_id_producto;
END;

--15. Eliminar un producto
CREATE OR REPLACE PROCEDURE EliminarProducto(
    p_id_producto IN NUMBER
) AS
BEGIN
    DELETE FROM PRODUCTOS
    WHERE ID_PRODUCTO = p_id_producto;
END;

--16. Obtener todos los productos
CREATE OR REPLACE PROCEDURE ObtenerProductos
AS
BEGIN
    FOR r IN (SELECT * FROM PRODUCTOS) LOOP
        DBMS_OUTPUT.PUT_LINE('ID: ' || r.ID_PRODUCTO || ', Nombre: ' || r.NOMBRE_PRODUCTO || ', Precio: ' || r.PRECIO || ', Stock: ' || r.STOCK || ', Categoria ID: ' || r.ID_CATEGORIA);
    END LOOP;
END;

--17. Agregar un nuevo pedido
CREATE OR REPLACE PROCEDURE AgregarPedido(
    p_id_pedido IN NUMBER,
    p_fecha IN DATE,
    p_id_usuario IN NUMBER,
    p_precio_unitario IN NUMBER,
    p_id_producto IN NUMBER,
    p_cantidad IN NUMBER,
    p_total IN NUMBER
) AS
BEGIN
    INSERT INTO PEDIDOS (ID_PEDIDO, FECHA, ID_USUARIO, PRECIO_UNITARIO, ID_PRODUCTO, CANTIDAD, TOTAL)
    VALUES (p_id_pedido, p_fecha, p_id_usuario, p_precio_unitario, p_id_producto, p_cantidad, p_total);
END;

--18. Actualizar un pedido
CREATE OR REPLACE PROCEDURE ActualizarPedido(
    p_id_pedido IN NUMBER,
    p_fecha IN DATE,
    p_id_usuario IN NUMBER,
    p_precio_unitario IN NUMBER,
    p_id_producto IN NUMBER,
    p_cantidad IN NUMBER,
    p_total IN NUMBER
) AS
BEGIN
    UPDATE PEDIDOS
    SET FECHA = p_fecha,
        ID_USUARIO = p_id_usuario,
        PRECIO_UNITARIO = p_precio_unitario,
        ID_PRODUCTO = p_id_producto,
        CANTIDAD = p_cantidad,
        TOTAL = p_total
    WHERE ID_PEDIDO = p_id_pedido;
END;

--19. Eliminar un pedido
CREATE OR REPLACE PROCEDURE EliminarPedido(
    p_id_pedido IN NUMBER
) AS
BEGIN
    DELETE FROM PEDIDOS
    WHERE ID_PEDIDO = p_id_pedido;
END;

--20. Obtener todos los pedidos
CREATE OR REPLACE PROCEDURE ObtenerPedidos
AS
BEGIN
    FOR r IN (SELECT * FROM PEDIDOS) LOOP
        DBMS_OUTPUT.PUT_LINE('ID: ' || r.ID_PEDIDO || ', Fecha: ' || r.FECHA || ', Usuario ID: ' || r.ID_USUARIO || ', Producto ID: ' || r.ID_PRODUCTO || ', Cantidad: ' || r.CANTIDAD || ', Total: ' || r.TOTAL);
    END LOOP;
END;

--21. Agregar una nueva factura
CREATE OR REPLACE PROCEDURE AgregarFactura(
    p_id_factura IN NUMBER,
    p_id_pedido IN NUMBER,
    p_id_usuario IN NUMBER,
    p_fecha_emision IN DATE,
    p_total IN NUMBER
) AS
BEGIN
    INSERT INTO FACTURAS (ID_FACTURA, ID_PEDIDO, ID_USUARIO, FECHA_EMISION, TOTAL)
    VALUES (p_id_factura, p_id_pedido, p_id_usuario, p_fecha_emision, p_total);
END;

--22. Actualizar una factura
CREATE OR REPLACE PROCEDURE ActualizarFactura(
    p_id_factura IN NUMBER,
    p_id_pedido IN NUMBER,
    p_id_usuario IN NUMBER,
    p_fecha_emision IN DATE,
    p_total IN NUMBER
) AS
BEGIN
    UPDATE FACTURAS
    SET ID_PEDIDO = p_id_pedido,
        ID_USUARIO = p_id_usuario,
        FECHA_EMISION = p_fecha_emision,
        TOTAL = p_total
    WHERE ID_FACTURA = p_id_factura;
END;

--23. Eliminar una factura
CREATE OR REPLACE PROCEDURE EliminarFactura(
    p_id_factura IN NUMBER
) AS
BEGIN
    DELETE FROM FACTURAS
    WHERE ID_FACTURA = p_id_factura;
END;

--24. Obtener todas las facturas
CREATE OR REPLACE PROCEDURE ObtenerFacturas
AS
BEGIN
    FOR r IN (SELECT * FROM FACTURAS) LOOP
        DBMS_OUTPUT.PUT_LINE('ID: ' || r.ID_FACTURA || ', Pedido ID: ' || r.ID_PEDIDO || ', Usuario ID: ' || r.ID_USUARIO || ', Fecha Emisión: ' || r.FECHA_EMISION || ', Total: ' || r.TOTAL);
    END LOOP;
END;

--25. Actualizar la cantidad disponible en inventario
CREATE OR REPLACE PROCEDURE ActualizarInventario(
    p_id_producto IN NUMBER,
    p_cantidad_disponible IN NUMBER
) AS
BEGIN
    UPDATE INVENTARIO
    SET CANTIDAD_DISPONIBLE = p_cantidad_disponible
    WHERE ID_PRODUCTO = p_id_producto;
END;



/**************************************************************************/
/*A PARTIR DE ACA PARA ABAJO SON LAS VISTAS IMPLEMENTADAS PARA EL PROYECTO*/
/**************************************************************************/


--1. Vista de Productos con Categoría
CREATE OR REPLACE VIEW Vista_Productos_Categoria AS
SELECT 
    p.ID_PRODUCTO,
    p.NOMBRE_PRODUCTO,
    p.DESCRIPCION AS DESCRIPCION_PRODUCTO,
    p.PRECIO,
    p.STOCK,
    c.NOMBRE_CATEGORIA
FROM 
    PRODUCTOS p
JOIN 
    CATEGORIAS c ON p.ID_CATEGORIA = c.ID_CATEGORIA;

--2. Vista de Usuarios con Rol
CREATE OR REPLACE VIEW Vista_Usuarios_Rol AS
SELECT 
    u.ID_USUARIO,
    u.NOMBRE,
    u.APELLIDO,
    u.CORREO,
    u.TELEFONO,
    r.NOMBRE_ROL
FROM 
    USUARIOS u
JOIN 
    ROLES r ON u.ID_ROL = r.ID_ROL;

--3. Vista de Pedidos con Usuario y Producto
CREATE OR REPLACE VIEW Vista_Pedidos_Usuario_Producto AS
SELECT 
    p.ID_PEDIDO,
    p.FECHA,
    u.NOMBRE AS NOMBRE_USUARIO,
    u.APELLIDO AS APELLIDO_USUARIO,
    pr.NOMBRE_PRODUCTO,
    p.CANTIDAD,
    p.PRECIO_UNITARIO,
    p.TOTAL
FROM 
    PEDIDOS p
JOIN 
    USUARIOS u ON p.ID_USUARIO = u.ID_USUARIO
JOIN 
    PRODUCTOS pr ON p.ID_PRODUCTO = pr.ID_PRODUCTO;

--4. Vista de Facturas con Pedido y Usuario
CREATE OR REPLACE VIEW Vista_Facturas_Pedido_Usuario AS
SELECT 
    f.ID_FACTURA,
    f.FECHA_EMISION,
    p.ID_PEDIDO,
    u.NOMBRE AS NOMBRE_USUARIO,
    u.APELLIDO AS APELLIDO_USUARIO,
    f.TOTAL
FROM 
    FACTURAS f
JOIN 
    PEDIDOS p ON f.ID_PEDIDO = p.ID_PEDIDO
JOIN 
    USUARIOS u ON f.ID_USUARIO = u.ID_USUARIO;

--5. Vista de Inventario con Producto
CREATE OR REPLACE VIEW Vista_Inventario_Producto AS
SELECT 
    i.ID_INVENTARIO,
    p.NOMBRE_PRODUCTO,
    i.CANTIDAD_DISPONIBLE
FROM 
    INVENTARIO i
JOIN 
    PRODUCTOS p ON i.ID_PRODUCTO = p.ID_PRODUCTO;

--6. Vista de Ropa con Producto, Talla y Color
CREATE OR REPLACE VIEW Vista_Ropa_Producto_Talla_Color AS
SELECT 
    r.ID_ROPA,
    p.NOMBRE_PRODUCTO,
    t.TALLA,
    c.COLOR,
    r.TIPO,
    r.RUTA_IMAGEN
FROM 
    ROPA r
JOIN 
    PRODUCTOS p ON r.ID_PRODUCTO = p.ID_PRODUCTO
JOIN 
    TALLAS t ON r.ID_TALLA = t.ID_TALLA
JOIN 
    COLOR c ON r.ID_COLOR = c.ID_COLOR;

--7. Vista de Ventas Totales por Usuario
CREATE OR REPLACE VIEW Vista_Ventas_Totales_Usuario AS
SELECT 
    u.ID_USUARIO,
    u.NOMBRE,
    u.APELLIDO,
    SUM(p.TOTAL) AS TOTAL_VENTAS
FROM 
    PEDIDOS p
JOIN 
    USUARIOS u ON p.ID_USUARIO = u.ID_USUARIO
GROUP BY 
    u.ID_USUARIO, u.NOMBRE, u.APELLIDO;

--8. Vista de Productos con Bajo Stock
CREATE OR REPLACE VIEW Vista_Productos_Bajo_Stock AS
SELECT 
    p.ID_PRODUCTO,
    p.NOMBRE_PRODUCTO,
    p.STOCK
FROM 
    PRODUCTOS p
WHERE 
    p.STOCK < 25;

--9. Vista de Roles y Descripción
CREATE OR REPLACE VIEW Vista_Roles_Descripcion AS
SELECT 
    r.ID_ROL,
    r.NOMBRE_ROL,
    r.DESCRIPCION
FROM 
    ROLES r;

--10. Vista de Pedidos por Fecha
CREATE OR REPLACE VIEW Vista_Pedidos_Por_Fecha AS
SELECT 
    p.FECHA,
    COUNT(p.ID_PEDIDO) AS NUM_PEDIDOS,
    SUM(p.TOTAL) AS TOTAL_VENTAS
FROM 
    PEDIDOS p
GROUP BY 
    p.FECHA;


/*******************************************************************************/
/*A PARTIR DE ACA PARA ABAJO ESTAN LAS FUNCIONES IMPLEMENTADAS PARA EL PROYECTO*/
/*******************************************************************************/

--1. Función para Obtener el Total de Ventas por Usuario
CREATE OR REPLACE FUNCTION Total_Ventas_Por_Usuario(p_id_usuario NUMBER)
RETURN NUMBER
IS
    v_total NUMBER(10, 2);
BEGIN
    SELECT SUM(p.TOTAL)
    INTO v_total
    FROM PEDIDOS p
    WHERE p.ID_USUARIO = p_id_usuario;
    
    RETURN NVL(v_total, 0);
END;

--2. Función para Obtener el Stock de un Producto
CREATE OR REPLACE FUNCTION Stock_Producto(p_id_producto NUMBER)
RETURN NUMBER
IS
    v_stock NUMBER;
BEGIN
    SELECT i.CANTIDAD_DISPONIBLE
    INTO v_stock
    FROM INVENTARIO i
    WHERE i.ID_PRODUCTO = p_id_producto;
    
    RETURN NVL(v_stock, 0);
END;

--3. Función para Calcular el Descuento Aplicado a un Producto
CREATE OR REPLACE FUNCTION Calcular_Descuento(p_precio NUMBER, p_descuento NUMBER)
RETURN NUMBER
IS
    v_precio_final NUMBER(10, 2);
BEGIN
    v_precio_final := p_precio - (p_precio * p_descuento / 100);
    RETURN v_precio_final;
END;

--4. Función para Obtener el Nombre del Rol por ID
CREATE OR REPLACE FUNCTION Nombre_Rol_By_ID(p_id_rol NUMBER)
RETURN VARCHAR2
IS
    v_nombre_rol VARCHAR2(50);
BEGIN
    SELECT r.NOMBRE_ROL
    INTO v_nombre_rol
    FROM ROLES r
    WHERE r.ID_ROL = p_id_rol;
    
    RETURN v_nombre_rol;
END;

--5. Función para Obtener el Precio Promedio de los Productos por Categoría
CREATE OR REPLACE FUNCTION Precio_Promedio_Categoria(p_id_categoria NUMBER)
RETURN NUMBER
IS
    v_precio_promedio NUMBER(10, 2);
BEGIN
    SELECT AVG(p.PRECIO)
    INTO v_precio_promedio
    FROM PRODUCTOS p
    WHERE p.ID_CATEGORIA = p_id_categoria;
    
    RETURN v_precio_promedio;
END;

--6. Función para Contar el Número de Pedidos por Usuario
CREATE OR REPLACE FUNCTION Contar_Pedidos_Usuario(p_id_usuario NUMBER)
RETURN NUMBER
IS
    v_num_pedidos NUMBER;
BEGIN
    SELECT COUNT(p.ID_PEDIDO)
    INTO v_num_pedidos
    FROM PEDIDOS p
    WHERE p.ID_USUARIO = p_id_usuario;
    
    RETURN v_num_pedidos;
END;

--7. Función para Obtener el Total de Facturas Emitidas
CREATE OR REPLACE FUNCTION Total_Facturas_Emitidas(p_id_usuario NUMBER)
RETURN NUMBER
IS
    v_total_facturas NUMBER(10, 2);
BEGIN
    SELECT SUM(f.TOTAL)
    INTO v_total_facturas
    FROM FACTURAS f
    WHERE f.ID_USUARIO = p_id_usuario;
    
    RETURN NVL(v_total_facturas, 0);
END;

--8. Función para Obtener el Nombre del Producto por ID
CREATE OR REPLACE FUNCTION Nombre_Producto_By_ID(p_id_producto NUMBER)
RETURN VARCHAR2
IS
    v_nombre_producto VARCHAR2(100);
BEGIN
    SELECT p.NOMBRE_PRODUCTO
    INTO v_nombre_producto
    FROM PRODUCTOS p
    WHERE p.ID_PRODUCTO = p_id_producto;
    
    RETURN v_nombre_producto;
END;

--9. Función para Verificar si un Producto Está en Stock
CREATE OR REPLACE FUNCTION Verificar_Stock_Producto(p_id_producto NUMBER)
RETURN VARCHAR2
IS
    v_stock NUMBER;
BEGIN
    SELECT i.CANTIDAD_DISPONIBLE
    INTO v_stock
    FROM INVENTARIO i
    WHERE i.ID_PRODUCTO = p_id_producto;
    
    IF v_stock > 0 THEN
        RETURN 'En Stock';
    ELSE
        RETURN 'Agotado';
    END IF;
END;

--10. Función para Obtener el Tipo de Ropa por ID
CREATE OR REPLACE FUNCTION Tipo_Ropa_By_ID(p_id_ropa NUMBER)
RETURN VARCHAR2
IS
    v_tipo_ropa VARCHAR2(50);
BEGIN
    SELECT r.TIPO
    INTO v_tipo_ropa
    FROM ROPA r
    WHERE r.ID_ROPA = p_id_ropa;
    
    RETURN v_tipo_ropa;
END;

--11. Función para Obtener el Precio Total de un Pedido
CREATE OR REPLACE FUNCTION Precio_Total_Pedido(p_id_pedido NUMBER)
RETURN NUMBER
IS
    v_total NUMBER(10, 2);
BEGIN
    SELECT p.TOTAL
    INTO v_total
    FROM PEDIDOS p
    WHERE p.ID_PEDIDO = p_id_pedido;
    
    RETURN NVL(v_total, 0);
END;

--12. Función para Obtener la Descripción del Producto por ID
CREATE OR REPLACE FUNCTION Descripcion_Producto_By_ID(p_id_producto NUMBER)
RETURN VARCHAR2
IS
    v_descripcion VARCHAR2(200);
BEGIN
    SELECT p.DESCRIPCION
    INTO v_descripcion
    FROM PRODUCTOS p
    WHERE p.ID_PRODUCTO = p_id_producto;
    
    RETURN v_descripcion;
END;

--13. Función para Obtener la Fecha de Inicio del Salario de un Usuario
CREATE OR REPLACE FUNCTION Fecha_Inicio_Salario(p_id_usuario NUMBER)
RETURN DATE
IS
    v_fecha_inicio DATE;
BEGIN
    SELECT s.FECHA_INICIO
    INTO v_fecha_inicio
    FROM SALARIO s
    WHERE s.ID_USUARIO = p_id_usuario
    ORDER BY s.FECHA_INICIO DESC
    FETCH FIRST ROW ONLY;
    
    RETURN v_fecha_inicio;
END;

--14. Función para Obtener el Stock Total de Todos los Productos
CREATE OR REPLACE FUNCTION Stock_Total_Productos
RETURN NUMBER
IS
    v_stock_total NUMBER;
BEGIN
    SELECT SUM(i.CANTIDAD_DISPONIBLE)
    INTO v_stock_total
    FROM INVENTARIO i;
    
    RETURN NVL(v_stock_total, 0);
END;

--15. Función para Calcular el Total de Ventas por Fecha
CREATE OR REPLACE FUNCTION Total_Ventas_Por_Fecha(p_fecha DATE)
RETURN NUMBER
IS
    v_total_ventas NUMBER(10, 2);
BEGIN
    SELECT SUM(p.TOTAL)
    INTO v_total_ventas
    FROM PEDIDOS p
    WHERE p.FECHA = p_fecha;
    
    RETURN NVL(v_total_ventas, 0);
END;



/******************************************************************************/
/*A PARTIR DE ACA PARA ABAJO ESTAN LOS PAQUETES IMPLEMENTADOS PARA EL PROYECTO*/
/******************************************************************************/

CREATE SEQUENCE PRODUCTOS_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

--1. Paquete de Gestión de Productos
CREATE OR REPLACE PACKAGE BODY Productos_Package AS

    PROCEDURE Insertar_Producto(p_nombre_producto VARCHAR2, p_descripcion VARCHAR2, p_precio NUMBER, p_stock NUMBER, p_id_categoria NUMBER, p_ruta_imagen VARCHAR2) IS
    BEGIN
        INSERT INTO PRODUCTOS (ID_PRODUCTO, NOMBRE_PRODUCTO, DESCRIPCION, PRECIO, STOCK, ID_CATEGORIA, RUTA_IMAGEN)
        VALUES (PRODUCTOS_SEQ.NEXTVAL, p_nombre_producto, p_descripcion, p_precio, p_stock, p_id_categoria, p_ruta_imagen);
    END Insertar_Producto;

    PROCEDURE Actualizar_Producto(p_id_producto NUMBER, p_nombre_producto VARCHAR2, p_descripcion VARCHAR2, p_precio NUMBER, p_stock NUMBER, p_id_categoria NUMBER, p_ruta_imagen VARCHAR2) IS
    BEGIN
        UPDATE PRODUCTOS
        SET NOMBRE_PRODUCTO = p_nombre_producto, DESCRIPCION = p_descripcion, PRECIO = p_precio, STOCK = p_stock, ID_CATEGORIA = p_id_categoria, RUTA_IMAGEN = p_ruta_imagen
        WHERE ID_PRODUCTO = p_id_producto;
    END Actualizar_Producto;

    FUNCTION Obtener_Precio(p_id_producto NUMBER) RETURN NUMBER IS
        v_precio NUMBER(10, 2);
    BEGIN
        SELECT PRECIO
        INTO v_precio
        FROM PRODUCTOS
        WHERE ID_PRODUCTO = p_id_producto;
        
        RETURN v_precio;
    END Obtener_Precio;

END Productos_Package;

--2. Paquete de Gestión de Usuarios

CREATE SEQUENCE USUARIOS_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE PACKAGE Usuarios_Package AS
    PROCEDURE Crear_Usuario(p_nombre VARCHAR2, p_apellido VARCHAR2, p_correo VARCHAR2, p_telefono VARCHAR2, p_id_rol NUMBER);
    PROCEDURE Actualizar_Usuario(p_id_usuario NUMBER, p_nombre VARCHAR2, p_apellido VARCHAR2, p_correo VARCHAR2, p_telefono VARCHAR2, p_id_rol NUMBER);
    FUNCTION Obtener_Nombre_Completo(p_id_usuario NUMBER) RETURN VARCHAR2;
END Usuarios_Package;

CREATE OR REPLACE PACKAGE BODY Usuarios_Package AS

    PROCEDURE Crear_Usuario(p_nombre VARCHAR2, p_apellido VARCHAR2, p_correo VARCHAR2, p_telefono VARCHAR2, p_id_rol NUMBER) IS
    BEGIN
        INSERT INTO USUARIOS (ID_USUARIO, NOMBRE, APELLIDO, CORREO, TELEFONO, ID_ROL)
        VALUES (USUARIOS_SEQ.NEXTVAL, p_nombre, p_apellido, p_correo, p_telefono, p_id_rol);
    END Crear_Usuario;

    PROCEDURE Actualizar_Usuario(p_id_usuario NUMBER, p_nombre VARCHAR2, p_apellido VARCHAR2, p_correo VARCHAR2, p_telefono VARCHAR2, p_id_rol NUMBER) IS
    BEGIN
        UPDATE USUARIOS
        SET NOMBRE = p_nombre, APELLIDO = p_apellido, CORREO = p_correo, TELEFONO = p_telefono, ID_ROL = p_id_rol
        WHERE ID_USUARIO = p_id_usuario;
    END Actualizar_Usuario;

    FUNCTION Obtener_Nombre_Completo(p_id_usuario NUMBER) RETURN VARCHAR2 IS
        v_nombre_completo VARCHAR2(100);
    BEGIN
        SELECT NOMBRE || ' ' || APELLIDO
        INTO v_nombre_completo
        FROM USUARIOS
        WHERE ID_USUARIO = p_id_usuario;
        
        RETURN v_nombre_completo;
    END Obtener_Nombre_Completo;

END Usuarios_Package;

--3. Paquete de Gestión de Pedidos

CREATE SEQUENCE PEDIDOS_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE PACKAGE Pedidos_Package AS
    PROCEDURE Crear_Pedido(p_id_usuario NUMBER, p_id_producto NUMBER, p_precio_unitario NUMBER, p_cantidad NUMBER, p_total NUMBER);
    PROCEDURE Actualizar_Pedido(p_id_pedido NUMBER, p_id_usuario NUMBER, p_id_producto NUMBER, p_precio_unitario NUMBER, p_cantidad NUMBER, p_total NUMBER);
    FUNCTION Obtener_Total_Pedido(p_id_pedido NUMBER) RETURN NUMBER;
END Pedidos_Package;

CREATE OR REPLACE PACKAGE BODY Pedidos_Package AS

    PROCEDURE Crear_Pedido(p_id_usuario NUMBER, p_id_producto NUMBER, p_precio_unitario NUMBER, p_cantidad NUMBER, p_total NUMBER) IS
    BEGIN
        INSERT INTO PEDIDOS (ID_PEDIDO, FECHA, ID_USUARIO, PRECIO_UNITARIO, ID_PRODUCTO, CANTIDAD, TOTAL)
        VALUES (PEDIDOS_SEQ.NEXTVAL, SYSDATE, p_id_usuario, p_precio_unitario, p_id_producto, p_cantidad, p_total);
    END Crear_Pedido;

    PROCEDURE Actualizar_Pedido(p_id_pedido NUMBER, p_id_usuario NUMBER, p_id_producto NUMBER, p_precio_unitario NUMBER, p_cantidad NUMBER, p_total NUMBER) IS
    BEGIN
        UPDATE PEDIDOS
        SET ID_USUARIO = p_id_usuario, ID_PRODUCTO = p_id_producto, PRECIO_UNITARIO = p_precio_unitario, CANTIDAD = p_cantidad, TOTAL = p_total
        WHERE ID_PEDIDO = p_id_pedido;
    END Actualizar_Pedido;

    FUNCTION Obtener_Total_Pedido(p_id_pedido NUMBER) RETURN NUMBER IS
        v_total NUMBER(10, 2);
    BEGIN
        SELECT TOTAL
        INTO v_total
        FROM PEDIDOS
        WHERE ID_PEDIDO = p_id_pedido;
        
        RETURN v_total;
    END Obtener_Total_Pedido;

END Pedidos_Package;

--5. Paquete de Gestión de Salarios

CREATE SEQUENCE SALARIO_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE PACKAGE Salarios_Package AS
    PROCEDURE Crear_Salario(p_id_usuario NUMBER, p_monto NUMBER, p_fecha_inicio DATE, p_fecha_fin DATE);
    PROCEDURE Actualizar_Salario(p_id_salario NUMBER, p_id_usuario NUMBER, p_monto NUMBER, p_fecha_inicio DATE, p_fecha_fin DATE);
    FUNCTION Obtener_Monto_Salario(p_id_usuario NUMBER) RETURN NUMBER;
END Salarios_Package;

CREATE OR REPLACE PACKAGE BODY Salarios_Package AS

    PROCEDURE Crear_Salario(p_id_usuario NUMBER, p_monto NUMBER, p_fecha_inicio DATE, p_fecha_fin DATE) IS
    BEGIN
        INSERT INTO SALARIO (ID_SALARIO, ID_USUARIO, MONTO, FECHA_INICIO, FECHA_FIN)
        VALUES (SALARIO_SEQ.NEXTVAL, p_id_usuario, p_monto, p_fecha_inicio, p_fecha_fin);
    END Crear_Salario;

    PROCEDURE Actualizar_Salario(p_id_salario NUMBER, p_id_usuario NUMBER, p_monto NUMBER, p_fecha_inicio DATE, p_fecha_fin DATE) IS
    BEGIN
        UPDATE SALARIO
        SET ID_USUARIO = p_id_usuario, MONTO = p_monto, FECHA_INICIO = p_fecha_inicio, FECHA_FIN = p_fecha_fin
        WHERE ID_SALARIO = p_id_salario;
    END Actualizar_Salario;

    FUNCTION Obtener_Monto_Salario(p_id_usuario NUMBER) RETURN NUMBER IS
        v_monto NUMBER(10, 2);
    BEGIN
        SELECT MONTO
        INTO v_monto
        FROM SALARIO
        WHERE ID_USUARIO = p_id_usuario
        ORDER BY FECHA_INICIO DESC
        FETCH FIRST ROW ONLY;
        
        RETURN v_monto;
    END Obtener_Monto_Salario;

END Salarios_Package;

--6. Paquete de Gestión de Inventario

CREATE OR REPLACE PACKAGE Inventario_Package AS
    PROCEDURE Actualizar_Stock(p_id_producto NUMBER, p_cantidad NUMBER);
    FUNCTION Obtener_Stock(p_id_producto NUMBER) RETURN NUMBER;
END Inventario_Package;

CREATE OR REPLACE PACKAGE BODY Inventario_Package AS

    PROCEDURE Actualizar_Stock(p_id_producto NUMBER, p_cantidad NUMBER) IS
    BEGIN
        UPDATE INVENTARIO
        SET CANTIDAD_DISPONIBLE = p_cantidad
        WHERE ID_PRODUCTO = p_id_producto;
    END Actualizar_Stock;

    FUNCTION Obtener_Stock(p_id_producto NUMBER) RETURN NUMBER IS
        v_stock NUMBER;
    BEGIN
        SELECT CANTIDAD_DISPONIBLE
        INTO v_stock
        FROM INVENTARIO
        WHERE ID_PRODUCTO = p_id_producto;
        
        RETURN NVL(v_stock, 0);
    END Obtener_Stock;

END Inventario_Package;

--7. Paquete de Gestión de Roles

CREATE SEQUENCE ROLES_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE PACKAGE Roles_Package AS
    PROCEDURE Crear_Rol(p_nombre_rol VARCHAR2, p_descripcion VARCHAR2);
    PROCEDURE Actualizar_Rol(p_id_rol NUMBER, p_nombre_rol VARCHAR2, p_descripcion VARCHAR2);
    FUNCTION Obtener_Descripcion_Rol(p_id_rol NUMBER) RETURN VARCHAR2;
END Roles_Package;

CREATE OR REPLACE PACKAGE BODY Roles_Package AS

    PROCEDURE Crear_Rol(p_nombre_rol VARCHAR2, p_descripcion VARCHAR2) IS
    BEGIN
        INSERT INTO ROLES (ID_ROL, NOMBRE_ROL, DESCRIPCION)
        VALUES (ROLES_SEQ.NEXTVAL, p_nombre_rol, p_descripcion);
    END Crear_Rol;

    PROCEDURE Actualizar_Rol(p_id_rol NUMBER, p_nombre_rol VARCHAR2, p_descripcion VARCHAR2) IS
    BEGIN
        UPDATE ROLES
        SET NOMBRE_ROL = p_nombre_rol, DESCRIPCION = p_descripcion
        WHERE ID_ROL = p_id_rol;
    END Actualizar_Rol;

    FUNCTION Obtener_Descripcion_Rol(p_id_rol NUMBER) RETURN VARCHAR2 IS
        v_descripcion VARCHAR2(200);
    BEGIN
        SELECT DESCRIPCION
        INTO v_descripcion
        FROM ROLES
        WHERE ID_ROL = p_id_rol;
        
        RETURN v_descripcion;
    END Obtener_Descripcion_Rol;

END Roles_Package;

--8. Paquete de Gestión de Categorías

CREATE SEQUENCE CATEGORIAS_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE PACKAGE Categorias_Package AS
    PROCEDURE Crear_Categoria(p_nombre_categoria VARCHAR2, p_descripcion VARCHAR2);
    PROCEDURE Actualizar_Categoria(p_id_categoria NUMBER, p_nombre_categoria VARCHAR2, p_descripcion VARCHAR2);
    FUNCTION Obtener_Descripcion_Categoria(p_id_categoria NUMBER) RETURN VARCHAR2;
END Categorias_Package;

CREATE OR REPLACE PACKAGE BODY Categorias_Package AS

    PROCEDURE Crear_Categoria(p_nombre_categoria VARCHAR2, p_descripcion VARCHAR2) IS
    BEGIN
        INSERT INTO CATEGORIAS (ID_CATEGORIA, NOMBRE_CATEGORIA, DESCRIPCION)
        VALUES (CATEGORIAS_SEQ.NEXTVAL, p_nombre_categoria, p_descripcion);
    END Crear_Categoria;

    PROCEDURE Actualizar_Categoria(p_id_categoria NUMBER, p_nombre_categoria VARCHAR2, p_descripcion VARCHAR2) IS
    BEGIN
        UPDATE CATEGORIAS
        SET NOMBRE_CATEGORIA = p_nombre_categoria, DESCRIPCION = p_descripcion
        WHERE ID_CATEGORIA = p_id_categoria;
    END Actualizar_Categoria;

    FUNCTION Obtener_Descripcion_Categoria(p_id_categoria NUMBER) RETURN VARCHAR2 IS
        v_descripcion VARCHAR2(200);
    BEGIN
        SELECT DESCRIPCION
        INTO v_descripcion
        FROM CATEGORIAS
        WHERE ID_CATEGORIA = p_id_categoria;
        
        RETURN v_descripcion;
    END Obtener_Descripcion_Categoria;

END Categorias_Package;

--9. Paquete de Gestión de Ropa

CREATE SEQUENCE ROPA_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE PACKAGE Ropa_Package AS
    PROCEDURE Crear_Ropa(p_id_producto NUMBER, p_id_talla NUMBER, p_id_color NUMBER, p_tipo VARCHAR2, p_ruta_imagen VARCHAR2);
    PROCEDURE Actualizar_Ropa(p_id_ropa NUMBER, p_id_producto NUMBER, p_id_talla NUMBER, p_id_color NUMBER, p_tipo VARCHAR2, p_ruta_imagen VARCHAR2);
    FUNCTION Obtener_Tipo_Ropa(p_id_ropa NUMBER) RETURN VARCHAR2;
END Ropa_Package;

CREATE OR REPLACE PACKAGE BODY Ropa_Package AS

    PROCEDURE Crear_Ropa(p_id_producto NUMBER, p_id_talla NUMBER, p_id_color NUMBER, p_tipo VARCHAR2, p_ruta_imagen VARCHAR2) IS
    BEGIN
        INSERT INTO ROPA (ID_ROPA, ID_PRODUCTO, ID_TALLA, ID_COLOR, TIPO, RUTA_IMAGEN)
        VALUES (ROPA_SEQ.NEXTVAL, p_id_producto, p_id_talla, p_id_color, p_tipo, p_ruta_imagen);
    END Crear_Ropa;

    PROCEDURE Actualizar_Ropa(p_id_ropa NUMBER, p_id_producto NUMBER, p_id_talla NUMBER, p_id_color NUMBER, p_tipo VARCHAR2, p_ruta_imagen VARCHAR2) IS
    BEGIN
        UPDATE ROPA
        SET ID_PRODUCTO = p_id_producto, ID_TALLA = p_id_talla, ID_COLOR = p_id_color, TIPO = p_tipo, RUTA_IMAGEN = p_ruta_imagen
        WHERE ID_ROPA = p_id_ropa;
    END Actualizar_Ropa;

    FUNCTION Obtener_Tipo_Ropa(p_id_ropa NUMBER) RETURN VARCHAR2 IS
        v_tipo VARCHAR2(50);
    BEGIN
        SELECT TIPO
        INTO v_tipo
        FROM ROPA
        WHERE ID_ROPA = p_id_ropa;
        
        RETURN v_tipo;
    END Obtener_Tipo_Ropa;

END Ropa_Package;

--10. Paquete de Gestión de Tallas

CREATE SEQUENCE TALLAS_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE PACKAGE Tallas_Package AS
    PROCEDURE Crear_Talla(p_talla VARCHAR2);
    PROCEDURE Actualizar_Talla(p_id_talla NUMBER, p_talla VARCHAR2);
    FUNCTION Obtener_Talla(p_id_talla NUMBER) RETURN VARCHAR2;
END Tallas_Package;

CREATE OR REPLACE PACKAGE BODY Tallas_Package AS

    PROCEDURE Crear_Talla(p_talla VARCHAR2) IS
    BEGIN
        INSERT INTO TALLAS (ID_TALLA, TALLA)
        VALUES (TALLAS_SEQ.NEXTVAL, p_talla);
    END Crear_Talla;

    PROCEDURE Actualizar_Talla(p_id_talla NUMBER, p_talla VARCHAR2) IS
    BEGIN
        UPDATE TALLAS
        SET TALLA = p_talla
        WHERE ID_TALLA = p_id_talla;
    END Actualizar_Talla;

    FUNCTION Obtener_Talla(p_id_talla NUMBER) RETURN VARCHAR2 IS
        v_talla VARCHAR2(10);
    BEGIN
        SELECT TALLA
        INTO v_talla
        FROM TALLAS
        WHERE ID_TALLA = p_id_talla;
        
        RETURN v_talla;
    END Obtener_Talla;

END Tallas_Package;


/******************************************************************************/
/*A PARTIR DE ACA PARA ABAJO ESTAN LOS PAQUETES IMPLEMENTADOS PARA EL PROYECTO*/
/******************************************************************************/

--1. Trigger para Actualizar el Stock en INVENTARIO Cuando se Inserta un Producto

CREATE SEQUENCE INVENTARIO_SEQ
START WITH 1
INCREMENT BY 1
NOCACHE
NOCYCLE;

CREATE OR REPLACE TRIGGER trg_Update_Stock_On_Product_Insert
AFTER INSERT ON PRODUCTOS
FOR EACH ROW
BEGIN
    INSERT INTO INVENTARIO (ID_INVENTARIO, ID_PRODUCTO, CANTIDAD_DISPONIBLE)
    VALUES (INVENTARIO_SEQ.NEXTVAL, :NEW.ID_PRODUCTO, 0);  -- Inicializa el stock en 0
END;

--2. Trigger para Actualizar el Stock en INVENTARIO Cuando se Actualiza el Producto

CREATE OR REPLACE TRIGGER trg_Update_Stock_On_Product_Update
AFTER UPDATE ON PRODUCTOS
FOR EACH ROW
BEGIN
    IF :OLD.ID_PRODUCTO != :NEW.ID_PRODUCTO THEN
        UPDATE INVENTARIO
        SET ID_PRODUCTO = :NEW.ID_PRODUCTO
        WHERE ID_PRODUCTO = :OLD.ID_PRODUCTO;
    END IF;
END;

--3. Trigger para Actualizar el Total en PEDIDOS Cuando se Actualiza el Precio del Producto

CREATE OR REPLACE TRIGGER trg_Update_Total_On_Product_Price_Update
AFTER UPDATE OF PRECIO ON PRODUCTOS
FOR EACH ROW
BEGIN
    UPDATE PEDIDOS
    SET TOTAL = :NEW.PRECIO * CANTIDAD
    WHERE ID_PRODUCTO = :OLD.ID_PRODUCTO;
END;

--4. Trigger para Verificar la Existencia del Usuario en SALARIO

CREATE OR REPLACE TRIGGER trg_Check_User_Exists_On_Salary_Insert
BEFORE INSERT ON SALARIO
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO v_count
    FROM USUARIOS
    WHERE ID_USUARIO = :NEW.ID_USUARIO;

    IF v_count = 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'El usuario no existe.');
    END IF;
END;

--5. Trigger para Verificar la Existencia del Producto en ROPA

CREATE OR REPLACE TRIGGER trg_Check_Product_Exists_On_Ropa_Insert
BEFORE INSERT ON ROPA
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO v_count
    FROM PRODUCTOS
    WHERE ID_PRODUCTO = :NEW.ID_PRODUCTO;

    IF v_count = 0 THEN
        RAISE_APPLICATION_ERROR(-20002, 'El producto no existe.');
    END IF;
END;



/******************************************************************************/
/*A PARTIR DE ACA PARA ABAJO ESTAN LOS CURSORES IMPLEMENTADOS PARA EL PROYECTO*/
/******************************************************************************/


--1. Cursor para Listar Todos los Productos
DECLARE
    CURSOR cur_Productos IS
        SELECT * FROM PRODUCTOS;
    v_Producto PRODUCTOS%ROWTYPE;
BEGIN
    OPEN cur_Productos;
    LOOP
        FETCH cur_Productos INTO v_Producto;
        EXIT WHEN cur_Productos%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID: ' || v_Producto.ID_PRODUCTO || ' Nombre: ' || v_Producto.NOMBRE_PRODUCTO);
    END LOOP;
    CLOSE cur_Productos;
END;

--2. Cursor para Listar Usuarios con un Rol Específico
DECLARE
    CURSOR cur_Usuarios IS
        SELECT * FROM USUARIOS WHERE ID_ROL = 1;
    v_Usuario USUARIOS%ROWTYPE;
BEGIN
    OPEN cur_Usuarios;
    LOOP
        FETCH cur_Usuarios INTO v_Usuario;
        EXIT WHEN cur_Usuarios%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID: ' || v_Usuario.ID_USUARIO || ' Nombre: ' || v_Usuario.NOMBRE || ' ' || v_Usuario.APELLIDO);
    END LOOP;
    CLOSE cur_Usuarios;
END;

--3. Cursor para Listar Todos los Pedidos
DECLARE
    CURSOR cur_Pedidos IS
        SELECT * FROM PEDIDOS;
    v_Pedido PEDIDOS%ROWTYPE;
BEGIN
    OPEN cur_Pedidos;
    LOOP
        FETCH cur_Pedidos INTO v_Pedido;
        EXIT WHEN cur_Pedidos%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Pedido: ' || v_Pedido.ID_PEDIDO || ' Fecha: ' || v_Pedido.FECHA || ' Total: ' || v_Pedido.TOTAL);
    END LOOP;
    CLOSE cur_Pedidos;
END;

--4. Cursor para Listar Productos en Inventario
DECLARE
    CURSOR cur_Inventario IS
        SELECT p.ID_PRODUCTO, p.NOMBRE_PRODUCTO, i.CANTIDAD_DISPONIBLE
        FROM INVENTARIO i
        JOIN PRODUCTOS p ON i.ID_PRODUCTO = p.ID_PRODUCTO;
    v_Inventario cur_Inventario%ROWTYPE;
BEGIN
    OPEN cur_Inventario;
    LOOP
        FETCH cur_Inventario INTO v_Inventario;
        EXIT WHEN cur_Inventario%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Producto: ' || v_Inventario.ID_PRODUCTO || ' Nombre: ' || v_Inventario.NOMBRE_PRODUCTO || ' Cantidad Disponible: ' || v_Inventario.CANTIDAD_DISPONIBLE);
    END LOOP;
    CLOSE cur_Inventario;
END;

--5. Cursor para Listar Facturas de un Usuario Específico
DECLARE
    CURSOR cur_Facturas IS
        SELECT * FROM FACTURAS WHERE ID_USUARIO = 1;
    v_Factura FACTURAS%ROWTYPE;
BEGIN
    OPEN cur_Facturas;
    LOOP
        FETCH cur_Facturas INTO v_Factura;
        EXIT WHEN cur_Facturas%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Factura: ' || v_Factura.ID_FACTURA || ' Fecha Emisión: ' || v_Factura.FECHA_EMISION || ' Total: ' || v_Factura.TOTAL);
    END LOOP;
    CLOSE cur_Facturas;
END;

--6. Cursor para Listar Todos los Roles
DECLARE
    CURSOR cur_Roles IS
        SELECT * FROM ROLES;
    v_Rol ROLES%ROWTYPE;
BEGIN
    OPEN cur_Roles;
    LOOP
        FETCH cur_Roles INTO v_Rol;
        EXIT WHEN cur_Roles%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Rol: ' || v_Rol.ID_ROL || ' Nombre Rol: ' || v_Rol.NOMBRE_ROL);
    END LOOP;
    CLOSE cur_Roles;
END;

--7. Cursor para Listar Todos los Colores
DECLARE
    CURSOR cur_Colores IS
        SELECT * FROM COLOR;
    v_Color COLOR%ROWTYPE;
BEGIN
    OPEN cur_Colores;
    LOOP
        FETCH cur_Colores INTO v_Color;
        EXIT WHEN cur_Colores%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Color: ' || v_Color.ID_COLOR || ' Color: ' || v_Color.COLOR);
    END LOOP;
    CLOSE cur_Colores;
END;

--8. Cursor para Listar Todos los Tamaños
DECLARE
    CURSOR cur_Tallas IS
        SELECT * FROM TALLAS;
    v_Talla TALLAS%ROWTYPE;
BEGIN
    OPEN cur_Tallas;
    LOOP
        FETCH cur_Tallas INTO v_Talla;
        EXIT WHEN cur_Tallas%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Talla: ' || v_Talla.ID_TALLA || ' Talla: ' || v_Talla.TALLA);
    END LOOP;
    CLOSE cur_Tallas;
END;

--9. Cursor para Listar Todos los Productos por Categoría
DECLARE
    CURSOR cur_ProductosPorCategoria IS
        SELECT p.ID_PRODUCTO, p.NOMBRE_PRODUCTO, c.NOMBRE_CATEGORIA
        FROM PRODUCTOS p
        JOIN CATEGORIAS c ON p.ID_CATEGORIA = c.ID_CATEGORIA;
    v_ProductoPorCategoria cur_ProductosPorCategoria%ROWTYPE;
BEGIN
    OPEN cur_ProductosPorCategoria;
    LOOP
        FETCH cur_ProductosPorCategoria INTO v_ProductoPorCategoria;
        EXIT WHEN cur_ProductosPorCategoria%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Producto: ' || v_ProductoPorCategoria.ID_PRODUCTO || ' Nombre: ' || v_ProductoPorCategoria.NOMBRE_PRODUCTO || ' Categoría: ' || v_ProductoPorCategoria.NOMBRE_CATEGORIA);
    END LOOP;
    CLOSE cur_ProductosPorCategoria;
END;

--10. Cursor para Listar Todos los Productos con Stock Bajo
DECLARE
    CURSOR cur_ProductosStockBajo IS
        SELECT p.ID_PRODUCTO, p.NOMBRE_PRODUCTO, i.CANTIDAD_DISPONIBLE
        FROM PRODUCTOS p
        JOIN INVENTARIO i ON p.ID_PRODUCTO = i.ID_PRODUCTO
        WHERE i.CANTIDAD_DISPONIBLE < 100;
    v_ProductoStockBajo cur_ProductosStockBajo%ROWTYPE;
BEGIN
    OPEN cur_ProductosStockBajo;
    LOOP
        FETCH cur_ProductosStockBajo INTO v_ProductoStockBajo;
        EXIT WHEN cur_ProductosStockBajo%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Producto: ' || v_ProductoStockBajo.ID_PRODUCTO || ' Nombre: ' || v_ProductoStockBajo.NOMBRE_PRODUCTO || ' Cantidad Disponible: ' || v_ProductoStockBajo.CANTIDAD_DISPONIBLE);
    END LOOP;
    CLOSE cur_ProductosStockBajo;
END;

--11. Cursor para Listar Usuarios con Salarios Pendientes
DECLARE
    CURSOR cur_UsuariosSalarioPendiente IS
        SELECT u.ID_USUARIO, u.NOMBRE, u.APELLIDO, s.MONTO
        FROM USUARIOS u
        JOIN SALARIO s ON u.ID_USUARIO = s.ID_USUARIO
        WHERE s.FECHA_FIN IS NULL;
    v_UsuarioSalarioPendiente cur_UsuariosSalarioPendiente%ROWTYPE;
BEGIN
    OPEN cur_UsuariosSalarioPendiente;
    LOOP
        FETCH cur_UsuariosSalarioPendiente INTO v_UsuarioSalarioPendiente;
        EXIT WHEN cur_UsuariosSalarioPendiente%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Usuario: ' || v_UsuarioSalarioPendiente.ID_USUARIO || ' Nombre: ' || v_UsuarioSalarioPendiente.NOMBRE || ' Apellido: ' || v_UsuarioSalarioPendiente.APELLIDO || ' Monto: ' || v_UsuarioSalarioPendiente.MONTO);
    END LOOP;
    CLOSE cur_UsuariosSalarioPendiente;
END;

--12. Cursor para Listar Facturas por Fecha de Emisión
DECLARE
    CURSOR cur_FacturasPorFecha IS
        SELECT * FROM FACTURAS WHERE FECHA_EMISION BETWEEN DATE '2024-01-01' AND DATE '2024-12-31';
    v_Factura FACTURAS%ROWTYPE;
BEGIN
    OPEN cur_FacturasPorFecha;
    LOOP
        FETCH cur_FacturasPorFecha INTO v_Factura;
        EXIT WHEN cur_FacturasPorFecha%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Factura: ' || v_Factura.ID_FACTURA || ' Fecha Emisión: ' || v_Factura.FECHA_EMISION || ' Total: ' || v_Factura.TOTAL);
    END LOOP;
    CLOSE cur_FacturasPorFecha;
END;

--13. Cursor para Listar Productos Vendidos en un Pedido
DECLARE
    CURSOR cur_ProductosPorPedido IS
        SELECT p.ID_PRODUCTO, p.NOMBRE_PRODUCTO, pd.CANTIDAD
        FROM PEDIDOS pd
        JOIN PRODUCTOS p ON pd.ID_PRODUCTO = p.ID_PRODUCTO
        WHERE pd.ID_PEDIDO = 1;
    v_ProductoPorPedido cur_ProductosPorPedido%ROWTYPE;
BEGIN
    OPEN cur_ProductosPorPedido;
    LOOP
        FETCH cur_ProductosPorPedido INTO v_ProductoPorPedido;
        EXIT WHEN cur_ProductosPorPedido%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Producto: ' || v_ProductoPorPedido.ID_PRODUCTO || ' Nombre: ' || v_ProductoPorPedido.NOMBRE_PRODUCTO || ' Cantidad: ' || v_ProductoPorPedido.CANTIDAD);
    END LOOP;
    CLOSE cur_ProductosPorPedido;
END;


--14. Cursor para Listar Productos de una Categoría Específica
DECLARE
    CURSOR cur_ProductosPorCategoria IS
        SELECT * FROM PRODUCTOS WHERE ID_CATEGORIA = 1;
    v_Producto PRODUCTOS%ROWTYPE;
BEGIN
    OPEN cur_ProductosPorCategoria;
    LOOP
        FETCH cur_ProductosPorCategoria INTO v_Producto;
        EXIT WHEN cur_ProductosPorCategoria%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Producto: ' || v_Producto.ID_PRODUCTO || ' Nombre: ' || v_Producto.NOMBRE_PRODUCTO || ' Precio: ' || v_Producto.PRECIO);
    END LOOP;
    CLOSE cur_ProductosPorCategoria;
END;

--15. Cursor para Listar Ropa Disponibles por Talla
DECLARE
    CURSOR cur_RopaPorTalla IS
        SELECT r.ID_ROPA, r.TIPO, t.TALLA
        FROM ROPA r
        JOIN TALLAS t ON r.ID_TALLA = t.ID_TALLA;
    v_RopaPorTalla cur_RopaPorTalla%ROWTYPE;
BEGIN
    OPEN cur_RopaPorTalla;
    LOOP
        FETCH cur_RopaPorTalla INTO v_RopaPorTalla;
        EXIT WHEN cur_RopaPorTalla%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE('ID Ropa: ' || v_RopaPorTalla.ID_ROPA || ' Tipo: ' || v_RopaPorTalla.TIPO || ' Talla: ' || v_RopaPorTalla.TALLA);
    END LOOP;
    CLOSE cur_RopaPorTalla;
END;

COMMIT;
