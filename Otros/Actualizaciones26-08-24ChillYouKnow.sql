select * from usuarios;
select * from productos;
select * from roles;


ALTER TABLE "ADMINISTRADOR"."USUARIOS"
ADD "PASSWORD" VARCHAR2(100 BYTE);

UPDATE "ADMINISTRADOR"."USUARIOS"
SET "PASSWORD" = '12345';

commit;

CREATE SEQUENCE seq_usuarios_id_usuario
START WITH 1
INCREMENT BY 1
NOCACHE;

SELECT MAX(ID_USUARIO) FROM usuarios;

ALTER SEQUENCE seq_usuarios_id_usuario RESTART START WITH 22;

CREATE OR REPLACE TRIGGER trg_usuarios_before_insert
BEFORE INSERT ON usuarios
FOR EACH ROW
BEGIN
  IF :new.id_usuario IS NULL THEN
    SELECT seq_usuarios_id_usuario.NEXTVAL INTO :new.id_usuario FROM dual;
  END IF;
END;


DELETE FROM "ADMINISTRADOR"."USUARIOS"
WHERE "ID_USUARIO" = 22;

CREATE OR REPLACE PROCEDURE RegistrarUsuario(
    p_nombre        IN VARCHAR2,
    p_identificacion IN VARCHAR2,
    p_email         IN VARCHAR2,
    p_password      IN VARCHAR2,
    p_telefono      IN VARCHAR2,
    p_rol           IN NUMBER,
    p_resultado     OUT VARCHAR2
) AS
BEGIN
    -- Verificar si el email ya está registrado
    DECLARE
        v_count NUMBER;
    BEGIN
        SELECT COUNT(*) INTO v_count FROM USUARIOS WHERE CORREO = p_email;

        IF v_count > 0 THEN
            p_resultado := 'El email ya está registrado.';
            RETURN;
        END IF;
    END;

    -- Insertar nuevo usuario
    INSERT INTO USUARIOS (NOMBRE, APELLIDO, CORREO, PASSWORD, TELEFONO, ID_ROL)
    VALUES (p_nombre, p_identificacion, p_email, p_password, p_telefono, p_rol);

    -- Confirmar éxito
    p_resultado := 'Usuario registrado con éxito.';
EXCEPTION
    WHEN OTHERS THEN
        p_resultado := 'Error al registrar el usuario: ' || SQLERRM;
END RegistrarUsuario;

CREATE OR REPLACE PROCEDURE SP_PROCESAR_LOGIN (
    p_email IN VARCHAR2,
    p_id_usuario OUT NUMBER,
    p_correo OUT VARCHAR2,
    p_password OUT VARCHAR2,
    p_id_rol OUT NUMBER,
    p_nombre OUT VARCHAR2
) AS
BEGIN
    SELECT id_usuario, correo, password, id_rol, nombre
    INTO p_id_usuario, p_correo, p_password, p_id_rol, p_nombre
    FROM USUARIOS
    WHERE correo = p_email;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        p_id_usuario := NULL;
        p_correo := NULL;
        p_password := NULL;
        p_id_rol := NULL;
        p_nombre := NULL;
    WHEN OTHERS THEN
        p_id_usuario := NULL;
        p_correo := NULL;
        p_password := NULL;
        p_id_rol := NULL;
        p_nombre := NULL;
END SP_PROCESAR_LOGIN;

CREATE OR REPLACE PROCEDURE OBTENER_CATEGORIAS (
    p_resultado OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN p_resultado FOR 
    SELECT ID_CATEGORIA, NOMBRE_CATEGORIA, DESCRIPCION 
    FROM CATEGORIAS;
END OBTENER_CATEGORIAS;


CREATE OR REPLACE PROCEDURE OBTENER_PRODUCTOS_POR_CATEGORIA (
    p_id_categoria IN NUMBER,
    p_resultado OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN p_resultado FOR 
    SELECT ID_PRODUCTO, NOMBRE_PRODUCTO, DESCRIPCION, PRECIO, STOCK, RUTA_IMAGEN 
    FROM PRODUCTOS 
    WHERE ID_CATEGORIA = p_id_categoria;
END OBTENER_PRODUCTOS_POR_CATEGORIA;


CREATE OR REPLACE PROCEDURE OBTENER_TODOS_LOS_PRODUCTOS (p_resultado OUT SYS_REFCURSOR) AS
BEGIN
    OPEN p_resultado FOR
    SELECT * FROM PRODUCTOS;
END;

ALTER TABLE "ADMINISTRADOR"."PRODUCTOS" MODIFY ("RUTA_IMAGEN" VARCHAR2(500));

CREATE OR REPLACE PROCEDURE AGREGAR_PRODUCTO (
    p_nombre_producto IN VARCHAR2,
    p_descripcion IN VARCHAR2,
    p_precio IN NUMBER,
    p_stock IN NUMBER,
    p_id_categoria IN NUMBER,
    p_ruta_imagen IN VARCHAR2
) AS
BEGIN
    INSERT INTO productos (id_producto, nombre_producto, descripcion, precio, stock, id_categoria, ruta_imagen)
	VALUES (producto_seq.NEXTVAL, p_nombre_producto, p_descripcion, p_precio, p_stock, p_id_categoria, p_ruta_imagen);
END;


CREATE OR REPLACE PROCEDURE ACTUALIZAR_PRODUCTO (
    p_id_producto IN NUMBER,
    p_nombre IN VARCHAR2,
    p_descripcion IN VARCHAR2,
    p_precio IN NUMBER,
    p_stock IN NUMBER,
    p_id_categoria IN NUMBER,
    p_ruta_imagen IN VARCHAR2
) IS
BEGIN
    UPDATE PRODUCTOS
    SET
        NOMBRE_PRODUCTO = p_nombre,
        DESCRIPCION = p_descripcion,
        PRECIO = p_precio,
        STOCK = p_stock,
        ID_CATEGORIA = p_id_categoria,
        RUTA_IMAGEN = p_ruta_imagen
    WHERE ID_PRODUCTO = p_id_producto;
END;


CREATE OR REPLACE PROCEDURE ELIMINAR_PRODUCTO (
    p_id_producto IN NUMBER
) AS
BEGIN
    DELETE FROM PRODUCTOS WHERE ID_PRODUCTO = p_id_producto;
END ELIMINAR_PRODUCTO;


CREATE OR REPLACE PROCEDURE OBTENER_TODAS_LAS_CATEGORIAS (resultado OUT SYS_REFCURSOR) AS
BEGIN
    OPEN resultado FOR
    SELECT * FROM CATEGORIAS;
END;

CREATE OR REPLACE TRIGGER productos_before_insert
BEFORE INSERT ON productos
FOR EACH ROW
BEGIN
    IF :new.id_producto IS NULL THEN
        :new.id_producto := productos_seq.NEXTVAL;
    END IF;
END;


CREATE SEQUENCE producto_seq START WITH 1 INCREMENT BY 1;
ALTER SEQUENCE producto_seq RESTART START WITH 21;
SELECT producto_seq.NEXTVAL FROM dual;

SELECT * FROM INVENTARIO WHERE ID_PRODUCTO = (SELECT producto_seq.CURRVAL FROM dual);


BEGIN
   AGREGAR_PRODUCTO(
       'Camisa para niño polo', 
       'Camisa para niño tipo polo', 
       7000, 
       50, 
       3, 
       'https://www.madiacreaciones.com/wp-content/uploads/2017/12/polo-ni%C3%B1o-2.png'
   );
END;

SELECT producto_seq.NEXTVAL FROM dual;


SELECT INVENTARIO_SEQ.NEXTVAL FROM dual;

CREATE OR REPLACE TRIGGER TRG_UPDATE_STOCK_ON_PRODUCT_INSERT
BEFORE INSERT ON productos
FOR EACH ROW
DECLARE
    v_count INTEGER;
BEGIN
    -- Asignar un valor a ID_PRODUCTO si es NULL
    IF :NEW.id_producto IS NULL THEN
        :NEW.id_producto := producto_seq.NEXTVAL;
    END IF;

    -- Verificar si el ID_PRODUCTO ya existe en INVENTARIO
    SELECT COUNT(*) INTO v_count
    FROM INVENTARIO
    WHERE ID_PRODUCTO = :NEW.ID_PRODUCTO;

    IF v_count = 0 THEN
        INSERT INTO INVENTARIO (ID_INVENTARIO, ID_PRODUCTO, CANTIDAD_DISPONIBLE)
        VALUES (INVENTARIO_SEQ.NEXTVAL, :NEW.ID_PRODUCTO, 0);  -- Inicializa el stock en 0
    END IF;
EXCEPTION
    WHEN DUP_VAL_ON_INDEX THEN
        NULL;  -- Manejar el error si ya existe
END;

SELECT INCREMENT_BY, LAST_NUMBER FROM USER_SEQUENCES WHERE SEQUENCE_NAME = 'INVENTARIO_SEQ';
ALTER SEQUENCE INVENTARIO_SEQ RESTART START WITH 21;  -- Usa el valor correcto basado en tus datos

-- Verificar si hay duplicados en INVENTARIO
SELECT ID_PRODUCTO, COUNT(*)
FROM INVENTARIO
GROUP BY ID_PRODUCTO
HAVING COUNT(*) > 1;




SELECT * FROM INVENTARIO WHERE ID_PRODUCTO = 21;  -- Verifica el ID_PRODUCTO que se generará
SELECT INVENTARIO_SEQ.NEXTVAL FROM dual;

SELECT INCREMENT_BY, LAST_NUMBER FROM USER_SEQUENCES WHERE SEQUENCE_NAME = 'INVENTARIO_SEQ';
SELECT * FROM PRODUCTOS ORDER BY ID_PRODUCTO;




CREATE OR REPLACE PROCEDURE ObtenerProductoPorId(
    p_id_producto IN NUMBER,
    p_nombre_producto OUT VARCHAR2,
    p_descripcion OUT VARCHAR2,
    p_precio OUT NUMBER,
    p_stock OUT NUMBER,
    p_id_categoria OUT NUMBER,
    p_ruta_imagen OUT VARCHAR2
) AS
BEGIN
    SELECT
        NOMBRE_PRODUCTO,
        DESCRIPCION,
        PRECIO,
        STOCK,
        ID_CATEGORIA,
        RUTA_IMAGEN
    INTO
        p_nombre_producto,
        p_descripcion,
        p_precio,
        p_stock,
        p_id_categoria,
        p_ruta_imagen
    FROM
        PRODUCTOS
    WHERE
        ID_PRODUCTO = p_id_producto;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        p_nombre_producto := NULL;
        p_descripcion := NULL;
        p_precio := NULL;
        p_stock := NULL;
        p_id_categoria := NULL;
        p_ruta_imagen := NULL;
    WHEN OTHERS THEN
        RAISE;
END;


commit;

BEGIN
   ACTUALIZAR_PRODUCTO(
       28,  -- ID del producto
       'Camiseta manga corta para hombre',  -- Nombre del producto
       'Camiseta manga corta para hombre color verde/musgo',  -- Descripción actualizada, esta parte lo actualice y todo bien
       10000,  -- Precio
       20,  -- Stock
       1,  -- ID de la categoría
       'https://leonisa.cr/cdn/shop/products/M2843S_052_1200X1500_1.jpg?v=1664840650'  -- URL de la imagen
   );
END;
SELECT * FROM PRODUCTOS WHERE ID_PRODUCTO = 28;


/*********************************************************/
/*********************************************************/

-- Asegúrate de definir los tipos de tabla
CREATE OR REPLACE TYPE carrito_item AS OBJECT (
    id_producto NUMBER,
    cantidad NUMBER,
    precio_total NUMBER
);

CREATE OR REPLACE TYPE carrito_table AS TABLE OF carrito_item;
/



-- Define el procedimiento almacenado
CREATE SEQUENCE facturas_seq
    START WITH 1
    INCREMENT BY 1
    CACHE 20;


CREATE OR REPLACE PROCEDURE PROCESAR_PAGO (
    p_id_usuario IN NUMBER,
    p_fecha_emision IN DATE,
    p_total IN NUMBER,
    p_productos IN SYS_REFCURSOR
)
AS
    v_id_factura NUMBER;
    v_id_producto NUMBER;
    v_cantidad NUMBER;
    v_precio NUMBER;
BEGIN
    -- Insertar en FACTURAS
    INSERT INTO FACTURAS (ID_FACTURA, ID_PEDIDO, ID_USUARIO, FECHA_EMISION, TOTAL)
    VALUES (FACTURAS_SEQ.NEXTVAL, NULL, p_id_usuario, p_fecha_emision, p_total)
    RETURNING ID_FACTURA INTO v_id_factura;

    -- Procesar cada producto
    LOOP
        FETCH p_productos INTO v_id_producto, v_cantidad, v_precio;
        EXIT WHEN p_productos%NOTFOUND;

        -- Insertar en DETALLE_FACTURA
        INSERT INTO DETALLE_FACTURA (ID_DETALLE_FACTURA, ID_FACTURA, ID_PRODUCTO, CANTIDAD, PRECIO)
        VALUES (DETALLE_FACTURA_SEQ.NEXTVAL, v_id_factura, v_id_producto, v_cantidad, v_precio);

        -- Actualizar STOCK
        UPDATE PRODUCTOS
        SET STOCK = STOCK - v_cantidad
        WHERE ID_PRODUCTO = v_id_producto;
    END LOOP;

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE;
END;
/
SELECT * FROM PEDIDOS WHERE ID_PEDIDO = :id_pedido;

SELECT * FROM PEDIDOS WHERE ID_PEDIDO = 21;

SELECT PEDIDOS_SEQ.NEXTVAL FROM DUAL;
CREATE TABLE DETALLE_FACTURA (
    ID_DETALLE_FACTURA NUMBER PRIMARY KEY,
    ID_FACTURA NUMBER,
    ID_PRODUCTO NUMBER,
    CANTIDAD NUMBER,
    PRECIO_UNITARIO NUMBER(10,2),
    SUBTOTAL NUMBER(10,2)
);

DROP SEQUENCE PEDIDOS_SEQ;
CREATE SEQUENCE PEDIDOS_SEQ START WITH 21 INCREMENT BY 1;

CREATE SEQUENCE PEDIDOS_SEQ START WITH 1 INCREMENT BY 1;
CREATE SEQUENCE FACTURAS_SEQ START WITH 1 INCREMENT BY 1;


SHOW ERRORS PROCEDURE procesar_pago;


SELECT * FROM user_sequences WHERE sequence_name = 'FACTURAS_SEQ';
ALTER PROCEDURE procesar_pago COMPILE;

CREATE OR REPLACE PROCEDURE REGISTRAR_FACTURA(
    p_id_usuario IN NUMBER,
    p_total IN NUMBER,
    p_id_factura OUT NUMBER
) AS
BEGIN
    INSERT INTO FACTURAS (ID_FACTURA, ID_USUARIO, FECHA_EMISION, TOTAL)
    VALUES (FACTURAS_SEQ.NEXTVAL, p_id_usuario, SYSDATE, p_total)
    RETURNING ID_FACTURA INTO p_id_factura;
END REGISTRAR_FACTURA;

CREATE OR REPLACE PROCEDURE REGISTRAR_PEDIDO(
    p_id_usuario IN NUMBER,
    p_precio_unitario IN NUMBER,
    p_id_producto IN NUMBER,
    p_cantidad IN NUMBER,
    p_total IN NUMBER
) AS
BEGIN
    INSERT INTO PEDIDOS (ID_PEDIDO, FECHA, ID_USUARIO, PRECIO_UNITARIO, ID_PRODUCTO, CANTIDAD, TOTAL)
    VALUES (PEDIDOS_SEQ.NEXTVAL, SYSDATE, p_id_usuario, p_precio_unitario, p_id_producto, p_cantidad, p_total);
END REGISTRAR_PEDIDO;

CREATE OR REPLACE PROCEDURE ACTUALIZAR_INVENTARIO(
    p_id_producto IN NUMBER,
    p_cantidad_comprada IN NUMBER
) AS
BEGIN
    UPDATE PRODUCTOS
    SET STOCK = STOCK - p_cantidad_comprada
    WHERE ID_PRODUCTO = p_id_producto;
END ACTUALIZAR_INVENTARIO;
commit;





CREATE OR REPLACE PROCEDURE OBTENER_COMPRAS_USUARIO(
    p_id_usuario IN NUMBER,
    p_resultado OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN p_resultado FOR
    SELECT F.ID_FACTURA, F.FECHA_EMISION, F.TOTAL
    FROM FACTURAS F
    WHERE F.ID_USUARIO = p_id_usuario
    ORDER BY F.FECHA_EMISION DESC;
END OBTENER_COMPRAS_USUARIO;








UPDATE FACTURAS F
SET F.ID_PEDIDO = (SELECT P.ID_PEDIDO FROM PEDIDOS P WHERE P.ID_USUARIO = F.ID_USUARIO AND P.FECHA = F.FECHA_EMISION)
WHERE F.ID_PEDIDO IS NULL;






VARIABLE rc REFCURSOR;
EXEC OBTENER_COMPRAS_USUARIO(23, :rc);
PRINT rc;

VARIABLE rc REFCURSOR;
EXEC OBTENER_PRODUCTOS_FACTURA(82, :rc);
PRINT rc;

CREATE OR REPLACE TRIGGER actualizar_id_pedido
FOR INSERT ON FACTURAS
COMPOUND TRIGGER

    TYPE t_facturas IS TABLE OF FACTURAS%ROWTYPE;
    v_facturas t_facturas := t_facturas();

    AFTER EACH ROW IS
    BEGIN
        v_facturas.EXTEND;
        v_facturas(v_facturas.COUNT).ID_FACTURA := :NEW.ID_FACTURA;
        v_facturas(v_facturas.COUNT).ID_USUARIO := :NEW.ID_USUARIO;
        v_facturas(v_facturas.COUNT).FECHA_EMISION := :NEW.FECHA_EMISION;
    END AFTER EACH ROW;

    AFTER STATEMENT IS
    BEGIN
        FOR i IN 1 .. v_facturas.COUNT LOOP
            UPDATE FACTURAS F
            SET F.ID_PEDIDO = (
                SELECT P.ID_PEDIDO
                FROM PEDIDOS P
                WHERE P.ID_USUARIO = v_facturas(i).ID_USUARIO
                AND P.FECHA = v_facturas(i).FECHA_EMISION
                AND ROWNUM = 1
            )
            WHERE F.ID_FACTURA = v_facturas(i).ID_FACTURA;
        END LOOP;
    END AFTER STATEMENT;

END actualizar_id_pedido;

CREATE OR REPLACE TRIGGER actualizar_id_pedido
AFTER INSERT ON PEDIDOS
FOR EACH ROW
BEGIN
    UPDATE FACTURAS F
    SET F.ID_PEDIDO = :NEW.ID_PEDIDO
    WHERE F.ID_USUARIO = :NEW.ID_USUARIO
    AND F.FECHA_EMISION = :NEW.FECHA;
END;



UPDATE FACTURAS F
SET F.ID_PEDIDO = (
    SELECT P.ID_PEDIDO
    FROM PEDIDOS P
    WHERE P.ID_USUARIO = F.ID_USUARIO
    AND P.FECHA = F.FECHA_EMISION
    AND ROWNUM = 1
)
WHERE F.ID_PEDIDO IS NULL;


CREATE OR REPLACE PROCEDURE OBTENER_PRODUCTOS_FACTURA(
    p_id_factura IN NUMBER,
    p_resultado OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN p_resultado FOR
    SELECT P.NOMBRE_PRODUCTO, PE.CANTIDAD, PE.PRECIO_UNITARIO, PE.TOTAL
    FROM PEDIDOS PE
    JOIN PRODUCTOS P ON PE.ID_PRODUCTO = P.ID_PRODUCTO
    JOIN FACTURAS F ON F.ID_PEDIDO = PE.ID_PEDIDO
    WHERE F.ID_FACTURA = p_id_factura;
END OBTENER_PRODUCTOS_FACTURA;

commit;
