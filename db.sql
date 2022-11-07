DROP DATABASE IF EXISTS SISdb;
CREATE DATABASE IF NOT EXISTS SISdb;
USE SISdb;

CREATE TABLE IF NOT EXISTS USUARIOS_NA(
  id_usuario INT NOT NULL AUTO_INCREMENT,
  ci CHAR(8) NOT NULL UNIQUE,
  nombre VARCHAR(32) NOT NULL,
  apellido VARCHAR(32) NOT NULL,
  correo VARCHAR(128) NOT NULL,
  contra CHAR(60) NOT NULL,
  PRIMARY KEY (id_usuario)
);

CREATE TABLE IF NOT EXISTS CELULARES_USUARIOS_NA(
  id_usuario INT NOT NULL,
  celular CHAR(9) NOT NULL,
  PRIMARY KEY (id_usuario, celular),
  CONSTRAINT fk_idusuario_celularesusuariosna 
  FOREIGN KEY (id_usuario) REFERENCES USUARIOS_NA(id_usuario)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS USUARIOS(
  id_usuario INT NOT NULL AUTO_INCREMENT,
  ci CHAR(8) NOT NULL UNIQUE,
  nombre VARCHAR(32) NOT NULL,
  apellido VARCHAR(32) NOT NULL,
  correo VARCHAR(128) NOT NULL,
  contra CHAR(60) NOT NULL,
  PRIMARY KEY (id_usuario)
);

CREATE TABLE IF NOT EXISTS CELULARES_USUARIOS(
  id_usuario INT NOT NULL,
  celular CHAR(9) NOT NULL,
  PRIMARY KEY (id_usuario, celular),
  CONSTRAINT fk_idusuario_celularesusuarios 
  FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS  CLIENTES(
  id_cliente INT NOT NULL,
  PRIMARY KEY (id_cliente),
  CONSTRAINT fk_idcliente_clientes
  FOREIGN KEY (id_cliente) REFERENCES USUARIOS(id_usuario)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS  VENDEDORES(
  id_vendedor INT NOT NULL,
  PRIMARY KEY (id_vendedor),
  CONSTRAINT fk_idvendedor_vendedores
  FOREIGN KEY (id_vendedor) REFERENCES USUARIOS(id_usuario)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS COMPRADORES(
  id_comprador INT NOT NULL,
  PRIMARY KEY (id_comprador),
  CONSTRAINT fk_idcomprador_compradores
  FOREIGN KEY (id_comprador) REFERENCES USUARIOS(id_usuario)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS JEFES(
  id_jefe INT NOT NULL,
  PRIMARY KEY (id_jefe),
  CONSTRAINT fk_idjefe_jefes
  FOREIGN KEY (id_jefe) REFERENCES USUARIOS(id_usuario)
  ON DELETE CASCADE
);

#quitar el default esta solo por problemas con los datos de prueba ya ingresados
CREATE TABLE IF NOT EXISTS PRODUCTOS(
  id_producto INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(128) NOT NULL,
  precio INT NOT NULL,
  descuento INT NOT NULL,
  stock INT NOT NULL,
  descripcion VARCHAR(4096),
  categoria ENUM('pierna','brazo','torso','cabeza','accesorio') default ('accesorio') NOT NULL,
  PRIMARY KEY (id_producto)
);

CREATE TABLE IF NOT EXISTS IMAGENES_PRODUCTOS(
  id_producto INT NOT NULL,
  ruta_imagen VARCHAR(255) NOT NULL,
  PRIMARY KEY (id_producto, ruta_imagen),
  CONSTRAINT fk_idproducto_imagenesproductos 
  FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto)
  ON DELETE CASCADE
);

/*
CREATE TABLE IF NOT EXISTS TAGS_PRODUCTOS(
  id_producto INT NOT NULL,
  tag VARCHAR(32) NOT NULL,
  PRIMARY KEY (id_producto, tag),
  CONSTRAINT fk_idproducto_tagsproductos 
  FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto)
  ON DELETE CASCADE
);
*/

CREATE TABLE IF NOT EXISTS PROVEEDORES(
  id_proveedor INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(32) NOT NULL,
  correo VARCHAR(128) NOT NULL,
  calle VARCHAR(64) NOT NULL,
  nro_puerta VARCHAR(8) NOT NULL,
  PRIMARY KEY (id_proveedor)
);

CREATE TABLE IF NOT EXISTS TELEFONOS_PROVEEDORES(
  id_proveedor INT NOT NULL,
  telefono CHAR(8) NOT NULL,
  PRIMARY KEY (id_proveedor, telefono),
  CONSTRAINT fk_idproveedor_telefonosproveedores
  FOREIGN KEY (id_proveedor) REFERENCES PROVEEDORES(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS PEDIDOS(
  id_pedido INT NOT NULL AUTO_INCREMENT,
  estado INT NOT NULL,
  tarjeta VARCHAR(21) NOT NULL,
  monto INT NOT NULL,
  PRIMARY KEY (id_pedido)
);

CREATE TABLE IF NOT EXISTS PAQUETES(
  id_paquete INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(128) NOT NULL,
  precio INT NOT NULL,
  descuento INT NOT NULL,
  stock INT NOT NULL,
  descripcion VARCHAR(4096),
  PRIMARY KEY (id_paquete)
);

/*
CREATE TABLE IF NOT EXISTS IMAGENES_PAQUETES(
  id_paquete INT NOT NULL,
  ruta_imagen VARCHAR(255) NOT NULL,
  PRIMARY KEY (id_paquete, ruta_imagen),
  CONSTRAINT fk_idpaquete_imagenespaquetes 
  FOREIGN KEY (id_paquete) REFERENCES PAQUETES(id_paquete)
  ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS TAGS_PAQUETES(
  id_paquete INT NOT NULL,
  tag VARCHAR(32) NOT NULL,
  PRIMARY KEY (id_paquete, tag),
  CONSTRAINT fk_idpaquete_tagspaquetes
  FOREIGN KEY (id_paquete) REFERENCES PAQUETES(id_paquete)
  ON DELETE CASCADE
);
*/

CREATE TABLE IF NOT EXISTS CARRITOS(
  id_carrito INT NOT NULL AUTO_INCREMENT,
  id_cliente INT NOT NULL,
  PRIMARY KEY (id_carrito, id_cliente),
  CONSTRAINT fk_idcliente_carritos 
  FOREIGN KEY (id_cliente) REFERENCES CLIENTES(id_cliente)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS CREA_C(
  id_jefe INT NOT NULL,
  id_comprador INT NOT NULL,
  fecha DATE NOT NULL,
  PRIMARY KEY (id_jefe, id_comprador),
  CONSTRAINT fk_idjefe_creac 
  FOREIGN KEY (id_jefe) REFERENCES JEFES(id_jefe) 
  ON DELETE CASCADE,
  CONSTRAINT fk_idcomprador_creac 
  FOREIGN KEY (id_comprador) REFERENCES COMPRADORES(id_comprador)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS CREA_V(
  id_jefe INT NOT NULL,
  id_vendedor INT NOT NULL,
  fecha DATE NOT NULL,
  PRIMARY KEY (id_jefe, id_vendedor),
  CONSTRAINT fk_idjefe_creav 
  FOREIGN KEY (id_jefe) REFERENCES JEFES(id_jefe) 
  ON DELETE CASCADE,
  CONSTRAINT fk_idvendedor_creav 
  FOREIGN KEY (id_vendedor) REFERENCES VENDEDORES(id_vendedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS AUTORIZA_V(
  id_vendedor INT NOT NULL,
  id_pedido INT NOT NULL,
  PRIMARY KEY (id_vendedor, id_pedido),
  CONSTRAINT fk_idvendedor_autorizav 
  FOREIGN KEY (id_vendedor) REFERENCES VENDEDORES(id_vendedor) 
  ON DELETE CASCADE,
  CONSTRAINT fk_idpedido_autorizav
  FOREIGN KEY (id_pedido) REFERENCES PEDIDOS(id_pedido)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS AUTORIZA_J(
  id_jefe INT NOT NULL,
  id_pedido INT NOT NULL,
  PRIMARY KEY (id_jefe, id_pedido),
  CONSTRAINT fk_idjefe_autorizaj 
  FOREIGN KEY (id_jefe) REFERENCES JEFES(id_jefe) 
  ON DELETE CASCADE,
  CONSTRAINT fk_idpedido_autorizaj
  FOREIGN KEY (id_pedido) REFERENCES PEDIDOS(id_pedido)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS REALIZA(
  id_cliente INT NOT NULL,
  id_pedido INT NOT NULL,
  fecha_solicitud DATETIME NOT NULL,
  fecha_entrega DATETIME,
  PRIMARY KEY(id_cliente, id_pedido),
  CONSTRAINT fk_idcliente_realiza 
  FOREIGN KEY (id_cliente) REFERENCES CLIENTES(id_cliente) 
  ON DELETE CASCADE,
  CONSTRAINT fk_idpedido_realiza 
  FOREIGN KEY (id_pedido) REFERENCES PEDIDOS(id_pedido)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS USA(
  id_cliente INT NOT NULL,
  id_carrito INT NOT NULL,
  CONSTRAINT fk_idcliente_usa 
  FOREIGN KEY (id_cliente) REFERENCES CLIENTES(id_cliente) 
  ON DELETE CASCADE,
  CONSTRAINT fk_idcarrito_usa 
  FOREIGN KEY (id_carrito) REFERENCES CARRITOS(id_carrito)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS TIENE_1(
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  PRIMARY KEY (id_producto, id_proveedor),
  CONSTRAINT fk_idproducto_tiene1
  FOREIGN KEY (id_producto) REFERENCES PRODUCTOS(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_tiene1
  FOREIGN KEY (id_proveedor) REFERENCES PROVEEDORES(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS TIENE_2(
  id_paquete INT NOT NULL,
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  PRIMARY KEY(id_paquete, id_producto, id_proveedor),
  CONSTRAINT fk_idpaquete_tiene2
  FOREIGN KEY (id_paquete) REFERENCES PAQUETES(id_paquete)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproducto_tiene2
  FOREIGN KEY (id_producto) REFERENCES TIENE_1(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_tiene2
  FOREIGN KEY (id_proveedor) REFERENCES TIENE_1(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS INGRESA_J_1(
  id_jefe INT NOT NULL,
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  fecha DATETIME NOT NULL,
  PRIMARY KEY (id_jefe, id_producto, id_proveedor),
  CONSTRAINT fk_idjefe_ingresaj1 
  FOREIGN KEY (id_jefe) REFERENCES JEFES(id_jefe)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproducto_ingresaj1 
  FOREIGN KEY (id_producto) REFERENCES TIENE_1(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_ingresaj1 
  FOREIGN KEY (id_proveedor) REFERENCES TIENE_1(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS INGRESA_J_2(
  id_jefe INT NOT NULL,
  id_paquete INT NOT NULL,
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  PRIMARY KEY (id_jefe, id_paquete, id_producto, id_proveedor),
  CONSTRAINT fk_idjefe_ingresaj2 
  FOREIGN KEY (id_jefe) REFERENCES JEFES(id_jefe)
  ON DELETE CASCADE,
  CONSTRAINT fk_idpaquete_ingresaj2 
  FOREIGN KEY (id_paquete) REFERENCES TIENE_2(id_paquete)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproducto_ingresaj2 
  FOREIGN KEY (id_producto) REFERENCES TIENE_2(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_ingresaj2 
  FOREIGN KEY (id_proveedor) REFERENCES TIENE_2(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS TIENE_P_1(
  id_pedido INT NOT NULL,
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  cantidad INT NOT NULl,
  PRIMARY KEY (id_pedido, id_producto, id_proveedor),
  CONSTRAINT fk_idpedido_tienep1
  FOREIGN KEY (id_pedido) REFERENCES PEDIDOS(id_pedido)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproducto_tienep1
  FOREIGN KEY (id_producto) REFERENCES TIENE_1(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_tienep1
  FOREIGN KEY (id_proveedor) REFERENCES TIENE_1(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS TIENE_P_2(
  id_pedido INT NOT NULL,
  id_paquete INT NOT NULL,
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  cantidad INT NOT NULl,
  PRIMARY KEY (id_pedido, id_paquete, id_producto, id_proveedor),
  CONSTRAINT fk_idpedido_tienep2
  FOREIGN KEY (id_pedido) REFERENCES PEDIDOS(id_pedido)
  ON DELETE CASCADE,
  CONSTRAINT fk_idpaquete_tienep2
  FOREIGN KEY (id_paquete) REFERENCES TIENE_2(id_paquete)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproducto_tienep2
  FOREIGN KEY (id_producto) REFERENCES TIENE_2(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_tienep2
  FOREIGN KEY (id_proveedor) REFERENCES TIENE_2(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS TIENE_C_1(
  id_carrito INT NOT NULL,
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  cantidad INT NOT NULl,
  PRIMARY KEY (id_carrito, id_producto, id_proveedor),
  CONSTRAINT fk_idcarrito_tienec1
  FOREIGN KEY (id_carrito) REFERENCES CARRITOS(id_carrito)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproducto_tienec1
  FOREIGN KEY (id_producto) REFERENCES TIENE_1(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_tienec1
  FOREIGN KEY (id_proveedor) REFERENCES TIENE_1(id_proveedor)
  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS TIENE_C_2(
  id_carrito INT NOT NULL,
  id_paquete INT NOT NULL,
  id_producto INT NOT NULL,
  id_proveedor INT NOT NULL,
  cantidad INT NOT NULl,
  PRIMARY KEY (id_carrito, id_paquete, id_producto, id_proveedor),
  CONSTRAINT fk_idcarrito_tienec2
  FOREIGN KEY (id_carrito) REFERENCES CARRITOS(id_carrito)
  ON DELETE CASCADE,
  CONSTRAINT fk_idpaquete_tienec2
  FOREIGN KEY (id_paquete) REFERENCES TIENE_2(id_paquete)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproducto_tienec2
  FOREIGN KEY (id_producto) REFERENCES TIENE_2(id_producto)
  ON DELETE CASCADE,
  CONSTRAINT fk_idproveedor_tienec2
  FOREIGN KEY (id_proveedor) REFERENCES TIENE_2(id_proveedor)
  ON DELETE CASCADE
);

CREATE VIEW VIEW_PRODUCTOS_CON_IMAGEN AS
SELECT `PRODUCTOS`.`id_producto` `id`, `PRODUCTOS`.`nombre`, `PRODUCTOS`.`precio`, 
`PRODUCTOS`.`descuento`, `PRODUCTOS`.`stock`, `PRODUCTOS`.`descripcion`,`PRODUCTOS`.`categoria`, 
`IMAGENES_PRODUCTOS`.`ruta_imagen` `ruta`, `PROVEEDORES`.`nombre` `nombre_proveedor`, `PROVEEDORES`.`id_proveedor`
FROM PRODUCTOS 
INNER JOIN IMAGENES_PRODUCTOS
ON `PRODUCTOS`.`id_producto`=`IMAGENES_PRODUCTOS`.`id_producto`
INNER JOIN TIENE_1 
ON `PRODUCTOS`.`id_producto`=`TIENE_1`.`id_producto`
INNER JOIN PROVEEDORES
ON `TIENE_1`.`id_proveedor`=`PROVEEDORES`.`id_proveedor`
GROUP BY `PRODUCTOS`.`id_producto`;

CREATE VIEW VIEW_PAQUETES_CON_IMAGEN AS
SELECT `PAQUETES`.`id_paquete` `id`, count(`TIENE_2`.`id_producto`) `cantidad_productos`, `PAQUETES`.`nombre`, 
`PAQUETES`.`precio`, `PAQUETES`.`descuento`, `PAQUETES`.`stock`, `PAQUETES`.`descripcion`, 
`IMAGENES_PRODUCTOS`.`ruta_imagen` `ruta`
FROM PAQUETES
INNER JOIN TIENE_2
ON `PAQUETES`.`id_paquete`=`TIENE_2`.`id_paquete`
INNER JOIN PRODUCTOS
ON `TIENE_2`.`id_producto`=`PRODUCTOS`.`id_producto`
INNER JOIN IMAGENES_PRODUCTOS
ON `PRODUCTOS`.`id_producto`=`IMAGENES_PRODUCTOS`.`id_producto`
GROUP BY `PAQUETES`.`id_paquete`;

CREATE VIEW VIEW_PRODUCTOS_INFO_PROVEEDORES AS
SELECT `PRODUCTOS`.`id_producto` `id`, `PRODUCTOS`.`nombre` `nombre`, `PRODUCTOS`.`precio`, 
`PRODUCTOS`.`descuento`, `PRODUCTOS`.`stock`, `PRODUCTOS`.`descripcion`, `PROVEEDORES`.`id_proveedor`, 
`PROVEEDORES`.`nombre` `nombre_proveedor`,`PRODUCTOS`.`categoria`, `PROVEEDORES`.`correo`, `PROVEEDORES`.`calle`,
`PROVEEDORES`.`nro_puerta` 
FROM PRODUCTOS
INNER JOIN TIENE_1
ON `PRODUCTOS`.`id_producto`=`TIENE_1`.`id_producto`
INNER JOIN PROVEEDORES
ON `TIENE_1`.`id_proveedor`=`PROVEEDORES`.`id_proveedor`;

CREATE VIEW VIEW_JEFES AS
SELECT `id_jefe` `id`,
CASE
WHEN `JEFES`.`id_jefe` > 0 THEN 'Jefe'
END AS `rol`,
`USUARIOS`.`ci`, `USUARIOS`.`nombre`, 
`USUARIOS`.`apellido`, `USUARIOS`.`correo`, 
`CELULARES_USUARIOS`.`celular`
FROM USUARIOS 
INNER JOIN CELULARES_USUARIOS
ON `USUARIOS`.`id_usuario`=`CELULARES_USUARIOS`.`id_usuario`
INNER JOIN JEFES
ON `USUARIOS`.`id_usuario`=`JEFES`.`id_jefe` GROUP BY `USUARIOS`.`id_usuario`;

CREATE VIEW VIEW_COMPRADORES AS
SELECT `id_comprador` `id`,
CASE
WHEN `COMPRADORES`.`id_comprador` > 0 THEN 'Comprador'
END AS `rol`,
`USUARIOS`.`ci`, `USUARIOS`.`nombre`, 
`USUARIOS`.`apellido`,  `USUARIOS`.`correo`,
`CELULARES_USUARIOS`.`celular`
FROM USUARIOS 
INNER JOIN CELULARES_USUARIOS
ON `USUARIOS`.`id_usuario`=`CELULARES_USUARIOS`.`id_usuario`
INNER JOIN COMPRADORES
ON `USUARIOS`.`id_usuario`=`COMPRADORES`.`id_comprador` GROUP BY `USUARIOS`.`id_usuario`;

CREATE VIEW VIEW_VENDEDORES AS
SELECT `id_vendedor` `id`,
CASE
WHEN `VENDEDORES`.`id_vendedor` > 0 THEN 'Vendedor'
END AS `rol`,
`USUARIOS`.`ci`, `USUARIOS`.`nombre`, 
`USUARIOS`.`apellido`, `USUARIOS`.`correo`, 
`CELULARES_USUARIOS`.`celular`
FROM USUARIOS 
INNER JOIN CELULARES_USUARIOS
ON `USUARIOS`.`id_usuario`=`CELULARES_USUARIOS`.`id_usuario`
INNER JOIN VENDEDORES
ON `USUARIOS`.`id_usuario`=`VENDEDORES`.`id_vendedor` GROUP BY `USUARIOS`.`id_usuario`;

CREATE VIEW VIEW_CLIENTES AS
SELECT `id_cliente` `id`,
CASE
WHEN `CLIENTES`.`id_cliente` > 0 THEN 'Cliente'
END AS `rol`,
`USUARIOS`.`ci`, `USUARIOS`.`nombre`, 
`USUARIOS`.`apellido`, `USUARIOS`.`correo`, 
`CELULARES_USUARIOS`.`celular`
FROM USUARIOS 
INNER JOIN CELULARES_USUARIOS
ON `USUARIOS`.`id_usuario`=`CELULARES_USUARIOS`.`id_usuario`
INNER JOIN CLIENTES
ON `USUARIOS`.`id_usuario`=`CLIENTES`.`id_cliente` GROUP BY `USUARIOS`.`id_usuario`;

CREATE VIEW VIEW_USUARIOS_NA_CON_CELULAR AS
SELECT `USUARIOS_NA`.`id_usuario` `id`, `USUARIOS_NA`.`ci`, `USUARIOS_NA`.`nombre`,
`USUARIOS_NA`.`apellido`,`USUARIOS_NA`.`correo`, `USUARIOS_NA`.`contra`,
`CELULARES_USUARIOS_NA`.`celular`
FROM USUARIOS_NA
INNER JOIN CELULARES_USUARIOS_NA
ON `USUARIOS_NA`.`id_usuario`=`CELULARES_USUARIOS_NA`.`id_usuario` 
GROUP BY `USUARIOS_NA`.`id_usuario`;

CREATE VIEW VIEW_USUARIOS_CON_CELULAR AS
SELECT `USUARIOS`.`id_usuario` `id`, `USUARIOS`.`ci`, `USUARIOS`.`nombre`,
`USUARIOS`.`apellido`, `USUARIOS`.`correo`, `USUARIOS`.`contra`,
`CELULARES_USUARIOS`.`celular`
FROM USUARIOS
INNER JOIN CELULARES_USUARIOS
ON `USUARIOS`.`id_usuario`=`CELULARES_USUARIOS`.`id_usuario` 
GROUP BY `USUARIOS`.`id_usuario`;

CREATE VIEW VIEW_PROVEEDORES_CON_TELEFONO AS
SELECT `PROVEEDORES`.`id_proveedor` `id`, `PROVEEDORES`.`nombre`, `PROVEEDORES`.`correo`,
`PROVEEDORES`.`calle`, `PROVEEDORES`.`nro_puerta`, `TELEFONOS_PROVEEDORES`.`telefono`
FROM PROVEEDORES
INNER JOIN TELEFONOS_PROVEEDORES
ON `PROVEEDORES`.`id_proveedor`=`TELEFONOS_PROVEEDORES`.`id_proveedor`
GROUP BY `PROVEEDORES`.`id_proveedor`;

CREATE VIEW VIEW_CARRITO_DE_PRODUCTOS AS
SELECT `CARRITOS`.`id_cliente`, `CARRITOS`.`id_carrito`, `VIEW_PRODUCTOS_CON_IMAGEN`.`id` `id_producto`,
CASE
WHEN `VIEW_PRODUCTOS_CON_IMAGEN`.`id` > 0 THEN 'Producto'
END AS `tipo`,
`VIEW_PRODUCTOS_CON_IMAGEN`.`nombre`, `VIEW_PRODUCTOS_CON_IMAGEN`.`precio`, `VIEW_PRODUCTOS_CON_IMAGEN`.`descuento`,
`VIEW_PRODUCTOS_CON_IMAGEN`.`stock`, `VIEW_PRODUCTOS_CON_IMAGEN`.`descripcion`, `VIEW_PRODUCTOS_CON_IMAGEN`.`categoria`,
`VIEW_PRODUCTOS_CON_IMAGEN`.`ruta`, `VIEW_PRODUCTOS_CON_IMAGEN`.`nombre_proveedor`, `VIEW_PRODUCTOS_CON_IMAGEN`.`id_proveedor`,
`TIENE_C_1`.`cantidad`
FROM CARRITOS
INNER JOIN TIENE_C_1
ON `CARRITOS`.`id_carrito`=`TIENE_C_1`.`id_carrito`
INNER JOIN VIEW_PRODUCTOS_CON_IMAGEN
ON `TIENE_C_1`.`id_producto`=`VIEW_PRODUCTOS_CON_IMAGEN`.`id`;

CREATE VIEW VIEW_CARRITO_DE_PAQUETES AS
SELECT `CARRITOS`.`id_cliente`, `CARRITOS`.`id_carrito`, `VIEW_PAQUETES_CON_IMAGEN`.`id` `id_paquete`,
CASE
WHEN `VIEW_PAQUETES_CON_IMAGEN`.`id` > 0 THEN 'Paquete'
END AS `tipo`,
`VIEW_PAQUETES_CON_IMAGEN`.`cantidad_productos`, `VIEW_PAQUETES_CON_IMAGEN`.`nombre`, `VIEW_PAQUETES_CON_IMAGEN`.`precio`,
`VIEW_PAQUETES_CON_IMAGEN`.`descuento`, `VIEW_PAQUETES_CON_IMAGEN`.`stock`, `VIEW_PAQUETES_CON_IMAGEN`.`descripcion`,
`VIEW_PAQUETES_CON_IMAGEN`.`ruta`, `TIENE_C_2`.`cantidad`
FROM CARRITOS
INNER JOIN TIENE_C_2
ON `CARRITOS`.`id_carrito`=`TIENE_C_2`.`id_carrito`
INNER JOIN VIEW_PAQUETES_CON_IMAGEN
ON `TIENE_C_2`.`id_paquete`=`VIEW_PAQUETES_CON_IMAGEN`.`id`
GROUP BY `VIEW_PAQUETES_CON_IMAGEN`.`id`;

CREATE VIEW VIEW_PEDIDOS_CON_FECHAS AS
SELECT `CLIENTES`.`id_cliente`, `PEDIDOS`.`id_pedido`, `PEDIDOS`.`estado`, `PEDIDOS`.`tarjeta`,
`PEDIDOS`.`monto`, `REALIZA`.`fecha_solicitud`, `REALIZA`.`fecha_entrega`
FROM PEDIDOS
INNER JOIN REALIZA
ON `PEDIDOS`.`id_pedido`=`REALIZA`.`id_pedido`
INNER JOIN CLIENTES
ON `REALIZA`.`id_cliente`=`CLIENTES`.`id_cliente`;

CREATE VIEW VIEW_PEDIDOS_DE_PRODUCTOS AS
SELECT `CLIENTES`.`id_cliente`, `PEDIDOS`.`id_pedido`, `PEDIDOS`.`estado`, `PEDIDOS`.`tarjeta`, `PEDIDOS`.`monto`, 
sum(`TIENE_P_1`.`cantidad`) `cantidad`, `REALIZA`.`fecha_solicitud`, `REALIZA`.`fecha_entrega`
FROM PEDIDOS
INNER JOIN REALIZA
ON `PEDIDOS`.`id_pedido`=`REALIZA`.`id_pedido`
INNER JOIN CLIENTES
ON `REALIZA`.`id_cliente`=`CLIENTES`.`id_cliente`
INNER JOIN TIENE_P_1
ON `PEDIDOS`.`id_pedido`=`TIENE_P_1`.`id_pedido`
GROUP BY `PEDIDOS`.`id_pedido`;

CREATE VIEW VIEW_PEDIDOS_DE_PAQUETES AS
SELECT `CLIENTES`.`id_cliente`, `PEDIDOS`.`id_pedido`, `PEDIDOS`.`estado`, `PEDIDOS`.`tarjeta`, `PEDIDOS`.`monto`, 
sum(`TIENE_P_2`.`cantidad`) `cantidad`, `REALIZA`.`fecha_solicitud`, `REALIZA`.`fecha_entrega`
FROM PEDIDOS
INNER JOIN REALIZA
ON `PEDIDOS`.`id_pedido`=`REALIZA`.`id_pedido`
INNER JOIN CLIENTES
ON `REALIZA`.`id_cliente`=`CLIENTES`.`id_cliente`
INNER JOIN TIENE_P_2
ON `PEDIDOS`.`id_pedido`=`TIENE_P_2`.`id_pedido`
GROUP BY `PEDIDOS`.`id_pedido`;

CREATE VIEW VIEW_DETALLES_PEDIDOS_DE_PRODUCTOS AS
SELECT `CLIENTES`.`id_cliente`, `PEDIDOS`.`id_pedido`, `VIEW_PRODUCTOS_CON_IMAGEN`.`id` `id_producto`,
CASE
WHEN `VIEW_PRODUCTOS_CON_IMAGEN`.`id` > 0 THEN 'Producto'
END AS `tipo`,
`VIEW_PRODUCTOS_CON_IMAGEN`.`nombre`, `VIEW_PRODUCTOS_CON_IMAGEN`.`precio`, `VIEW_PRODUCTOS_CON_IMAGEN`.`descuento`,
`VIEW_PRODUCTOS_CON_IMAGEN`.`stock`, `VIEW_PRODUCTOS_CON_IMAGEN`.`descripcion`, `VIEW_PRODUCTOS_CON_IMAGEN`.`categoria`,
`VIEW_PRODUCTOS_CON_IMAGEN`.`ruta`, `VIEW_PRODUCTOS_CON_IMAGEN`.`nombre_proveedor`, `VIEW_PRODUCTOS_CON_IMAGEN`.`id_proveedor`,
`TIENE_P_1`.`cantidad`
FROM PEDIDOS
INNER JOIN REALIZA
ON `PEDIDOS`.`id_pedido`=`REALIZA`.`id_pedido`
INNER JOIN CLIENTES
ON `REALIZA`.`id_cliente`=`CLIENTES`.`id_cliente`
INNER JOIN TIENE_P_1
ON `PEDIDOS`.`id_pedido`=`TIENE_P_1`.`id_pedido`
INNER JOIN VIEW_PRODUCTOS_CON_IMAGEN
ON `TIENE_P_1`.`id_producto`=`VIEW_PRODUCTOS_CON_IMAGEN`.`id`;

CREATE VIEW VIEW_DETALLES_PEDIDOS_DE_PAQUETES AS
SELECT `CLIENTES`.`id_cliente`, `PEDIDOS`.`id_pedido`, `VIEW_PAQUETES_CON_IMAGEN`.`id` `id_paquete`,
CASE
WHEN `VIEW_PAQUETES_CON_IMAGEN`.`id` > 0 THEN 'Paquete'
END AS `tipo`,
`VIEW_PAQUETES_CON_IMAGEN`.`nombre`, `VIEW_PAQUETES_CON_IMAGEN`.`precio`, `VIEW_PAQUETES_CON_IMAGEN`.`descuento`,
`VIEW_PAQUETES_CON_IMAGEN`.`stock`, `VIEW_PAQUETES_CON_IMAGEN`.`descripcion`, `VIEW_PAQUETES_CON_IMAGEN`.`ruta`, 
`TIENE_P_2`.`cantidad`
FROM PEDIDOS
INNER JOIN REALIZA
ON `PEDIDOS`.`id_pedido`=`REALIZA`.`id_pedido`
INNER JOIN CLIENTES
ON `REALIZA`.`id_cliente`=`CLIENTES`.`id_cliente`
INNER JOIN TIENE_P_2
ON `PEDIDOS`.`id_pedido`=`TIENE_P_2`.`id_pedido`
INNER JOIN VIEW_PAQUETES_CON_IMAGEN
ON `TIENE_P_2`.`id_paquete`=`VIEW_PAQUETES_CON_IMAGEN`.`id`
GROUP BY `TIENE_P_2`.`id_paquete`;

delimiter //
CREATE TRIGGER AFTER_NUEVOS_CLIENTES
	AFTER INSERT
    ON CLIENTES
    FOR EACH ROW
BEGIN
	INSERT INTO CARRITOS (`id_cliente`) VALUES (new.`id_cliente`);
END
// delimiter ;

INSERT INTO PRODUCTOS (nombre, descripcion, precio, stock, descuento) VALUES
('Guantes Nitrilo Puño Elastizado Steelpro', 'Recubierto: Nitrilo, Color: Azul, Buen agarre en seco, Puño tejido, Buena barrera contra la grasa y aceites, Posee excelente resistencia a la abrasión, cortes y rasgaduras.', 99, 26,0),
('Guantes De Trabajo Para Temperatura Chard Guard', 'El CharGuard con su forro interior aislante está diseñado para brindar protección al calor en seco al mismo tiempo que brinda flexibilidad y ajuste. El color negro del CharGuard extiende su uso. Su recubrimiento patentado le brinda resistencia a la abrasión y agarre. Esta diseñado para resistir la exposición al calor, múltiples lavados y mantener su flexibilidad', 1523, 24,0),
('Guantes De Trabajo Térmico Hasta -26° RefrigiWear', '', 3397, 27,0),
('Guante Anticorte Malla De Acero', 'Guante confeccionado 100% en malla de acero inoxidable. Venta por unidad Posee ajuste en muñeca por Banda, Homologado para uso Alimentario, Guante Ambidiestro de fácil retiro, Usos: Protección Anti-Corte y anti-pinchazo, guante especialmente diseñado para trabajos con Cuchillo en mano como Carnicerías, restaurantes, operaciones de deshuesado, trabajos manuales en la Industria de plásticos, cuero, textiles y papel, Certificado Bajo Norma: EN-1082 y FDA.', 6700, 0,0),
('Bota Moto Lluvia Impermeable Motosafe Suela Negra', 'Bota para lluvia impermeable Motosafe, orígen Brasil. Bota Negra con suela Negra. Diseño moderno y funcional. Suela antideslizante. Plantilla incorporada. Colores de suela: Negro, Rojo y Amarillo.', 1090, 15,0),
('Bota Azul De Lluvia Náutica Camping Caza Y Pesca', 'Bota Náutica para Camping, Pesca y caza de PVC azul con cuello color Amarillo. Producto liviano y totalmente forrado. Suela antideslizante, de gran agarre y estabilidad.', 890, 34,15),
('Bota para lluvia impermeable Motosafe', 'Bota para lluvia impermeable Motosafe, orígen Brasil. Bota Negra con suela Roja. Diseño moderno y funcional. Colores de suela: Roja, Negra y Amarilla. Suela antideslizante. Plantilla incorporada.', 1090, 24,0),
('Bota Cámara Frío', 'Bota para frigorífico de microfibra. Interior forrado con material de abrigo sintético tipo corderito. Suela de poliuretano bidensidad antideslizante. Ideal para uso en espacios de bajas temperaturas. Posee plantilla térmica.', 3190, 37,0),
('Chaleco Reflectivo Amarillo Velcro Talle XL', 'Chaleco fluor con cinta reflex. Colores: Amarillo Y Naranja Talle: XL. Confeccionado en tela Citylux tejida con hilo 100 % poliéster de alta tenacidad. Certificado SGS. Aplicaciones de cinta reflectiva PVC de 2″. Van de acuerdo a norma MOP para ser usados en carreteras de alta velocidad.', 160, 31,0),
('Cintas Demarcatorias De Pare Seguridad Rollo 10 Kgs', 'Cinta demarcatoria de seguridad. Banda de 20cm en nylon delimita zonas que no se pueden transitar. 50 metros aprox. en un kilo.', 2150, 26,0),
('Conos Reflectivos Retráctiles 90cm Pvc Base Dura', 'Conos reflectivos flexibles de pvc y base dura. Incorpora pigmento fluorescente en toda su masa, lo que hace que no decolore prematuramente. Posee un collar reflectante de 4″ para que su uso sea tanto de día como de noche. Cono de 90cm. Opciones: 90cm, 70cm, 45cm, 30cm.', 2140, 19,0),
('Lentes Seguridad MIG Oscuros', 'Diseño ojo de gato. Lentes de policarbonato. Filtro UV. Marco de nylon. Patillas flexibles de nylon, delgadas y ergonómicas. Patilla telescópica ajustable a 4 posiciones de largo. Tornillo de acero inoxidable', 199, 26,20),
('Lentes De Protección Seguridad Anti Empaño', 'Diseñado para proteger el ojo contra golpes, impacto de partículas, polvo y chispas. Protección frontal y lateral. Diseño ojo de gato clásico, elegante y económico. Su radio de curvatura, y las patillas siguiendo la línea, logran un calce perfecto a la cara y visión panorámica. Lente y patillas de policarbonato. Filtro UV. Tornillo de acero inoxidable.Tratamiento anti-empaño (AF)', 189, 4,0),
('Arnés Con 3 Argollas De Fijación Chilesin', 'Arnés de 3 argollas cuerpo completo, tres argollas metálicas con diseño ergonómico desarollada para disminuir adecuadamente los efectos de la fuerza de detención y minimizar sus consecuencias sobre el usuario ante una caída de altura. Cuenta con cierre y regulación de piernas mediante hebilla de dos piezas de aleación metálica. Las argollas se ubican en pecho y ambos costados.', 2790, 36,0),
('Casco De Seguridad Marca EvoIII Arnés Naranja', 'Casco Naranja fabricado en PHAD polietileno de alta intensidad, con mucha resistencia al impacto y de baja degradación. Ajuste tipo roller. Peso del casco completo 275grs. Liviano y cómodo. Posee certificación ANSI Y NCH.', 219, 5,0),
('Equipo Conjunto De Lluvia Con Reflectivo', 'EQUIPO DE LLUVIA UNISEX EN NYLON/PVC CON DETALLES REFLECTIVOS. Campera con cierre diente de perro plástico largo con carretón. Manga raglán con doble costura de seguridad, dos bolsillos. Broches de presión en los puños de las mangas para mayor ajuste. Capucha incorporada con cordón, se guarda escondida en el cuello con cierre. Respiración interior. Todas las costuras termo selladas como en las carpas, para garantizar la impermeabilidad. Pantalón: con abertura de acceso directo al bolsillo, reforzado en la entrepierna y con broches de ajuste en los bajos. Talles del S al XXXL, color azul. Sobre de la misma tela con cierre. Detalles reflectivos en cuerpo, mangas y pantalón.', 990, 0,0),
('Pantalón Básico De Trabajo Reflectivo Varios Colores', 'Pantalón básico de trabajo. Colores: Azul, Gris, Negro y Naranja. Tardamos 24hs aprox. en colocarle reflectivo Talles: Del S al 3XL. Opción efectiva y resistente, apta para todo tipo de tareas que requieran un uniforme que conserve su color, textura y forma. Cuenta con dos bolsillos frontales profundos y un bolsillo aplicado en dorso con tapa y cierres en velcro. Para su ajuste, cuenta con una cartera con cierre y cinco pasa cinto en el perímetro de su pretina, además de contar con un medio elástico en su parte trasera. Compuesto por 35 % algodón y 65 % poliéster. Gramaje de 190g / M2.', 640, 22,0),
('Pantalón De Trabajo Cargo Industrial Azul', 'Pantalón Cargo Industrial Colores: azul y naranja Talles: 0 al 6 equivalente del XS al XXXL Talle XS = Talle 0 (36) Talle S = Talle 1 (38-40) Talle M = Talle 2 (42-44) Talle L = Talle 3 (46-48) Talle XL = Talle 4 (50-52) Talle XXL = Talle 5 (54-56) Talle XXXL = Talle 6 (58-60)', 669, 24,0),
('Pantalón Anti Corte Motosierristas 10 Capas Azul', 'Pantalón anticorte 10 capas de 360 grados destinado para el uso del operador de motosierra. Fabricado en 90 % poliéster y 10 % poliamida con telas anticorte de fibras de alta tenacidad. Costuras reforzadas. Diseño con bolsillo trasero y lateral portaherramientas.', 4900, 45,0),
('Pantalón Felpa Hombre Negro', 'Pantalones de felpa para adultos. Colores: Azul, Negro, Gris, Verde. Talles: Del XS al XXXL. Confeccionados con un algodón sumamente abrigado y confortable, combinado con el polyester necesario para una mayor resistencia, no solo para contrarestar el encogimiento sino también provee una mayor durabilidad de color. Bolsillos laterales. Cordón y elástico en cintura. Bolsillo trasero. 35% Aldodón / 65% Polyester. Gramaje: 280g.', 519, 48,0),
('Campera Impermeable Reflectivo Capucha Desmontable', 'Campera Alta Visibilidad Con Reflectivo Combinada Amarillo-Azul, Amarillo-Verde, Amarillo-Negro, Naranja-Azul. Campera ideal para motorizados o para personas que se encuentran en temperaturas muy bajas. Super abrigada. Moderno diseño. Capucha desmontable con forro capitoneado Doble reflectivo de 5cm horizontal y reflectivo vertical de 1mm a los costados Cierre al medio común y solapa con botones Espacio para guardar tarjeta de identificación Bolsillos externos a los costados Bolsillo interno Mangas con elástico Parte inferior de la campera con elástico Talles: XS al XXXL', 1490, 43,0),
('Campera De Nylon Forro Micropolar Hombre Negra', 'Campera de nylon con forro micropolar. Colores: Negra, Rojo, Gris, Verde, Azul Marino. Talles: Del S al 6XL Se caracterizan por ser una opción económica y eficaz ante la necesidad de abrigo e impermeabilidad al 100 %. Apertura con cierre, dos bolsillos frontales con cierre y dos bolsillos ojal, ajuste de cintura con tancas, puños exteriores ajustables. 100 % Poliéster. Capucha desmontable. Bolsillos con cierre. Puños elastizados y ajustables. Micropolar en el interior. Elástico en la cintura.', 1490, 44,0),
('Campera Neopreno Con Reflectivo Amarillo', 'Apertura con cierre, un bolsillo frontal con cierre (lado del corazon) y bolsilo porta identificacion (lado derecho) puños exteriores con elástico. Color: Combinada Azul / Negro (con reflectivo). Talles: S, XXL, XXXL.', 990, 42,5),
('Campera Polar Naranja Con Reflectivo', 'Campera Polar con Reflectivo Chaqueta polar unisex de alta visibilidad. Cuello alto y cremallera central. Tres bolsillos con cremallera. Cintas reflectantes en pecho y mangas. Composición: 100% poliéster.', 830, 1,10),
('Remera Polo Manga Corta Reflectivo Azul Marino', 'Remera estilo polo manga corta, doble reflectivo, 100% Polyester Incluye bolsillo del lado del corazón Polo bicolor manga corta alta visibilidad Cierre central con botones Cintas reflectantes en torso Cuello y bajo de la manga de canalé Botones en color contraste Un bolsillo: 1 bolsillo de parche en el pecho Tallas S / M / L / XL / XXL / 3XL / 4XL', 440, 48,0),
('Remera Polo Dry Manga Corta Reflectivo Naranja', 'Remera estilo polo manga corta, doble reflectivo, 100% Polyester Incluye bolsillo del lado del corazón Polo bicolor manga corta alta visibilidad Cierre central con botones Cintas reflectantes en torso Cuello y bajo de la manga de canalé Botones en color contraste Un bolsillo: 1 bolsillo de parche en el pecho Tallas S / M / L / XL / XXL / 3XL /', 440, 46,0),
('Remera Polo Manga Corta Pack X3', 'Remera Polo Manga Corta Colores: blanco, azul, negro, gris.', 1450, 12,0),
('Remera Polo Manga Larga Reflectivo Verde', 'Remera estilo polo manga larga, doble reflectivo, 100% Polyester. Incluye bolsillo del lado del corazón Color: verde manzana', 570, 49,0),
('Buzo Polar Unisex Gris', 'Buzos de micropolar unisex. Abrigo cómodo con cierre pero no llega a ser campera. Colores: Blanco, Gris, Negro y Azul Marino. Talles: Del S al XXL. Apertura de cuello con medio cierre, ajuste de cintura con tancas, puños elastizados. Confeccionadas en micropolar de 260g / M2, puño elastizado, bolsillos laterales, proceso antipeeling, cordón elastizado, ajustadores plásticos en cintura. Tapa de costura interior de cuello con vista interior, afirmado con pespunte. Composición: 100 % Poliéster. MEDIDAS Talle XS: Ancho 55cm Largo 56cm. Talle S; Ancho: 58cm Largo 61cm Talle M; Ancho: 61cm Largo 65cm Talle L; Ancho: 64cm Largo 69cm Talle XL; Ancho: 67cm Largo 74cm Talle XXL; Ancho: 70cm Largo 76cm. Talle XXXL: Ancho 73cm Largo 78cm', 869, 32,0),
('Buzo Felpa Unisex Verde Inglés', 'Buzo de felpa. Colores varios: Gris, Negro, Verde inglés y Azul marino. Talles: del XS al XXXL.', 779, 8,7),
('Buzo Polar Con Reflectivo Color Azul', 'Buzo polar con reflectivo. Colores: Azul, Naranja. Talles: Del S al XXXL. Medidas: Talle S: Contorno 61cm - Largo 64cm. Talle M: Contorno 64cm - Largo 68cm. Talle L: Contorno 67cm - Largo 72cm. Talle XL: Contorno 70cm - Largo 77cm. Talle XXL: Contorno 73cm - Largo 79cm. Talle XXXL: Contorno 76cm - Largo 82cm.', 790, 16,35),
('Buzo Micropolar Unisex Negro', 'Buzos de micropolar unisex. Abrigo cómodo con cierre pero no llega a ser campera. Colores: Blanco, Gris, Negro y Azul Marino. Talles: Del S al XXXL. Apertura de cuello con medio cierre, ajuste de cintura con tancas, puños elastizados. Confeccionadas en micropolar de 260g / M2, puño elastizado, bolsillos laterales, proceso antipeeling, cordón elastizado, ajustadores plásticos en cintura. Tapa de costura interior de cuello con vista interior, afirmado con pespunte. Composición: 100 % Poliéster. MEDIDAS Talle XS: Ancho 55cm Largo 56cm. Talle S; Ancho: 58cm Largo 61cm Talle M; Ancho: 61cm Largo 65cm Talle L; Ancho: 64cm Largo 69cm Talle XL; Ancho: 67cm Largo 74cm Talle XXL; Ancho: 70cm Largo 76cm . Talle XXXL: Ancho 73cm Largo 78cm', 840, 36,0),
('Zapato De Trabajo Con Puntera Acero Negro', 'Marca: Bracol Características: Zapato acordonado en excelente cuero hidrofugado. Plantilla: Antimicótica y micro perforada Forrada en tela poliéster y nylon. Suela: En poliuretano (PU) doble densidad inyectado directamente en la capellada. Refuerzo de costuras en zonas críticas. Puntera de acero para mayor protección, resistente hasta 200J .', 890, 10,0),
('Zapato Seguridad BRACOL Puntera Acero Amarillo', 'Zapato acordonado en excelente cuero hidrofugado. Plantilla: Antimicótica y micro perforada Forrada en tela poliéster y nylon. Suela: En poliuretano (PU) doble densidad inyectado directamente en la capellada. Refuerzo de costuras en zonas críticas. Puntera de acero para mayor protección, resistente hasta 200J .', 890, 23,5),
('Zapato De Trabajo Puntera Acero Talle Grande Especial', 'Color: Negro Marca Strom ', 999, 22,0),
('Zapato De Trabajo Nobuck BRACOL Puntera Plástica Marrón', 'Calzado acordonado de cuero nobuck con cuello acolchonado. Plantilla: antitraspirante. Forrada en tela poliéster y nylon. Suela: En poliuretano (PU) bi densidad inyectado directamente en la capellada. Puntera termoplástica', 1850, 44,0);

INSERT INTO IMAGENES_PRODUCTOS (id_producto, ruta_imagen) VALUES
(1, 'guantes.jpg'),
(2, 'guantes_trabajo.jpg'),
(3, 'guantes_termicos.jpg'),
(4, 'guante_malla.jpg'),
(5, 'botas_lluvia.jpg'),
(6, 'botas_pesca.jpg'),
(7, 'botas_moto.jpg'),
(8, 'botas_camara_frio.jpg'),
(9, 'chalecos.jpg'),
(10, 'cinta_pare.jpg'),
(11, 'conos_reflectivos.jpg'),
(12, 'lentes_mig.jpg'),
(13, 'lentes_anti_empeño.jpg'),
(14, 'arnes.jpg'),
(15, 'casco_seguridad.jpg'),
(16, 'equipo_lluvia.jpg'),
(17, 'pantalon-reflectivo.jpg'),
(18, 'pantalon_industrial.jpg'),
(19, 'pantalon_anti.jpg'),
(20, 'pantalon_felpa.jpg'),
(21, 'campera_impermeable.jpg'),
(22, 'campera_nylon.jpg'),
(23, 'campera_neopreno.jpg'),
(24, 'campera_polar.jpg'),
(25, 'remera_polar.jpg'),
(26, 'remera_reflectivo.jpg'),
(27, 'remera_3.jpg'),
(28, 'remera_larga.jpg'),
(29, 'buzo_polar.jpg'),
(30, 'buzo_felpa.jpg'),
(31, 'buzo_reflectivo.jpg'),
(32, 'buzo_micropolar.jpg'),
(33, 'zapato1.jpg'),
(34, 'zapato_seguridad.jpg'),
(35, 'zapato_trabajo.jpg'),
(36, 'zapato_trabajo_1.jpg');

INSERT INTO PROVEEDORES (nombre, correo, calle, nro_puerta) VALUES
('Proveedor 1', 'proveedor1@gmail.com','Calle 1','NRO1'),
('Proveedor 2', 'proveedor2@gmail.com','Calle 2','NRO2'),
('Proveedor 3', 'proveedor3@gmail.com','Calle 3','NRO3');

INSERT INTO TELEFONOS_PROVEEDORES (id_proveedor, telefono) VALUES 
(1, '25115349'),
(2, '23132323'),
(3, '24544456');

INSERT INTO TIENE_1 (id_proveedor, id_producto) VALUES
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(2, 6),
(1, 7),
(2, 8),
(1, 9),
(2, 10),
(2, 11),
(2, 12),
(1, 13),
(3, 14),
(3, 15),
(3, 16),
(2, 17),
(2, 18),
(3, 19),
(2, 20),
(2, 21),
(3, 22),
(1, 23),
(2, 24),
(3, 25),
(3, 26),
(2, 27),
(1, 28),
(3, 29),
(2, 30),
(1, 31),
(2, 32),
(2, 33),
(3, 34),
(1, 35),
(3, 36);

INSERT INTO USUARIOS (ci, nombre, apellido, correo, contra) VALUES
('12121212','Cliente 2','Cliente 2 Ape','cliente2@gmail.com','$2a$10$cqvKYt3hNdfx6VlGlYJpM.J57hcf.8nyKhFp4cw0tPAjL35CJ.iFG'),
('13131313','Cliente 1','Cliente 1 Ape','cliente1@gmail.com','$2a$10$n60KATx.rfu8z/5YWpsoIuIiqo1eQqQen6WgPbvq.OzYEUb3ZHWR6'),
('55556666','Vendedor 2','Vendedor 2 Ape','vendedor2@gmail.com','$2a$10$qcEq/DuXKlQY4OMRjKhyKudldfzIJ67RIZ0iXamTGwndbLgtI6toK'),
('12345678','Vendedor 1','Vendedor 1 Ape','vendedor1@gmail.com','$2a$10$uSv3Y2QvnfF1s8J97YhiUuDt2daDG50qwYhivVHtctPwIvf8Fd3Dm'),
('87654321','Comprador 2','Comprador 2 Ape','comprador2@gmail.com','$2a$10$YQH6BoCDIJ4oMPNqDDlxVOTIpEnhdIHm9.MBgeTpghUupiq6aQg92'),
('11112222','Comprador 1','Comprador 1 Ape','comprador1@gmail.com','$2a$10$JERcMiV8nW3VIa6VDzKG0.Y52D5CihIeuF5rR.AegAbOx5ut6UhY.'),
('22221111','Jefe 2','Jefe 1 Ape','jefe2@gmail.com','$2a$10$0KlE2f6I3yJ8LwIILCEsfeLxFr.9coHuHvmfSF3bs5Uk6Y2NSMqlW'),
('33334444','Jefe 1','Jefe 2 Ape','jefe1@gmail.com','$2a$10$ijSNZPzTfOQjhYupIRCBEORg5KEi4RCgFwrUoSOMD49xzvPuMw5mu'),
('12345567','santiago','garcia','santiago@gmail.com','$2y$10$lOAxHbJTn00nYiZSbRVue.6pMKc77uo8psvyJ35opPGIGuUPFxBR2'),
('12445567','santiago','garcia','santiagoc@gmail.com','$2y$10$lOAxHbJTn00nYiZSbRVue.6pMKc77uo8psvyJ35opPGIGuUPFxBR2');

INSERT INTO CELULARES_USUARIOS (id_usuario, celular) VALUES
(1,'093963343'),
(2,'094596861'),
(3,'093999999'),
(4,'094555555'),
(5,'092890765'),
(6,'095849303'),
(7,'093588392'),
(8,'093493291'),
(9,'123456789'),
(10,'987654321');

INSERT INTO USUARIOS_NA (ci, nombre, apellido, correo, contra) VALUES
('55672830','Cliente 3','Cliente 3 Ape','cliente3@gmail.com','$2a$10$cqvKYt3hNdfx6VlGlYJpM.J57hcf.8nyKhFp4cw0tPAjL35CJ.iFG'),
('55672831','Cliente 4','Cliente 4 Ape','cliente4@gmail.com','$2a$10$cqvKYt3hNdfx6VlGlYJpM.J57hcf.8nyKhFp4cw0tPAjL35CJ.iFG');

INSERT INTO CELULARES_USUARIOS_NA (id_usuario, celular) VALUES
(1,'093963343'),
(2,'093963345');

INSERT INTO CLIENTES VALUES(1),(2),(10);
INSERT INTO VENDEDORES VALUES(3),(4);
INSERT INTO COMPRADORES VALUES(5),(6);
INSERT INTO JEFES VALUES(7),(8),(9);

INSERT INTO PAQUETES (nombre, precio, descuento, stock, descripcion) VALUES 
('Paquete 1', 700, 0, 3, 'Descripcion paquete 1'),
('Paquete 2', 700, 0, 3, 'Descripcion paquete 2');

INSERT INTO TIENE_2 (id_paquete, id_producto, id_proveedor) VALUES
(1,2,3),
(1,5,3),
(1,4,3),
(2,4,3),
(2,16,3),
(2,14,3),
(2,24,3);