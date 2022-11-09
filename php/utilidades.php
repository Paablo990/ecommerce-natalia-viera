<?php

function existeProveedor(
  Database $db,
  int $id_proveedor
): bool {
  $query = "SELECT count(*) `existe` FROM PROVEEDORES WHERE `id_proveedor`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_proveedor],
    "i"
  )[0]["existe"];

  return $existe > 0;
}

function existeAdministrador(
  Database $db,
  string $id_usuario
): bool {
  $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `id_usuario`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_usuario],
    "i"
  )[0]["existe"];

  return $existe > 0;
}

function existeTelefono(
  Database $db,
  string $telefono
): bool {
  $query = "SELECT count(*) `existe` FROM TELEFONOS_PROVEEDORES WHERE `telefono`=?";

  $existe = $db->queryWithParams(
    $query,
    [$telefono],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function proveedorTieneProductos(
  Database $db,
  int $id_proveedor
): bool {
  $query = "SELECT count(*) `existe` FROM TIENE_1 WHERE `id_proveedor`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_proveedor],
    "i"
  )[0]["existe"];

  return $existe > 0;
}

function existeCorreoProveedor(
  Database $db,
  string $correo
): bool {
  $query = "SELECT count(*) `existe` FROM PROVEEDORES WHERE `correo`=?";

  $existe = $db->queryWithParams(
    $query,
    [$correo],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function existeCI(
  Database $db,
  string $ci
): bool {
  $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `ci`=?";

  $existe = $db->queryWithParams(
    $query,
    [$ci],
    "s"
  )[0]["existe"];

  if ($existe > 0) return true;

  $query = "SELECT count(*) `existe` FROM USUARIOS_NA WHERE `ci`=?";

  $existe = $db->queryWithParams(
    $query,
    [$ci],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function existeCorreo(
  Database $db,
  string $correo
): bool {
  $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `correo`=?";

  $existe = $db->queryWithParams(
    $query,
    [$correo],
    "s"
  )[0]["existe"];

  if ($existe > 0) return true;

  $query = "SELECT count(*) `existe` FROM USUARIOS_NA WHERE `correo`=?";

  $existe = $db->queryWithParams(
    $query,
    [$correo],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function existeCelular(
  Database $db,
  string $celular
): bool {
  $query = "SELECT count(*) `existe` FROM CELULARES_USUARIOS WHERE `celular`=?";

  $existe = $db->queryWithParams(
    $query,
    [$celular],
    "s"
  )[0]["existe"];

  if ($existe > 0) return true;

  $query = "SELECT count(*) `existe` FROM CELULARES_USUARIOS_NA WHERE `celular`=?";

  $existe = $db->queryWithParams(
    $query,
    [$celular],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function usuarioAprobado(
  Database $db,
  string $ci
): bool {
  $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `ci`=?";

  $existe = $db->queryWithParams(
    $query,
    [$ci],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function usuarioAprobadoConCorreo(
  Database $db,
  string $correo
): bool {
  $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `correo`=?";

  $existe = $db->queryWithParams(
    $query,
    [$correo],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function esCliente(
  Database $db,
  string $ci
): bool {
  $query = "SELECT count(*) `existe` FROM VIEW_CLIENTES WHERE `ci`=?";

  $existe = $db->queryWithParams(
    $query,
    [$ci],
    "s"
  )[0]["existe"];

  return $existe > 0;
}

function existeProducto(
  Database $db,
  int $id_producto
): bool {
  $query = "SELECT count(*) `existe` FROM PRODUCTOS WHERE `id_producto`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_producto],
    "i"
  )[0]["existe"];

  return $existe > 0;
}

function obtenerRol(
  Database $db,
  int $id_usuario
): string {
  $querys = [
    "SELECT count(*) `existe` FROM CLIENTES WHERE `id_cliente`=?" => "cliente",
    "SELECT count(*) `existe` FROM COMPRADORES WHERE `id_comprador`=?" => "comprador",
    "SELECT count(*) `existe` FROM VENDEDORES WHERE `id_vendedor`=?" => "vendedor",
    "SELECT count(*) `existe` FROM JEFES WHERE `id_jefe`=?" => "jefe"
  ];

  foreach ($querys as $query => $rol) {
    $existe = $db->queryWithParams(
      $query,
      [$id_usuario],
      "i"
    )[0]["existe"];

    if ($existe > 0) return $rol;
  }

  return "error";
}



function existeCliente(
  Database $db,
  int $id_cliente
): bool {
  $query = "SELECT count(*) `existe` FROM CLIENTES WHERE `id_cliente`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_cliente],
    "i"
  )[0]["existe"];

  return $existe > 0;
}

function existeProductoEnCarrito(
  Database $db,
  int $id_carrito,
  int $id_producto
): bool {
  $query = "SELECT count(*) `existe` FROM TIENE_C_1 WHERE `id_carrito`=? AND `id_producto`=?";

  $existe = $db->queryWithParams(
    $query,
    [
      $id_carrito,
      $id_producto
    ],
    "ii"
  )[0]["existe"];

  return $existe > 0;
}

function existePaqueteEnCarrito(
  Database $db,
  int $id_carrito,
  int $id_paquete
): bool {
  $query = "SELECT count(*) `existe` FROM TIENE_C_2 WHERE `id_carrito`=? AND `id_paquete`=?";

  $existe = $db->queryWithParams(
    $query,
    [
      $id_carrito,
      $id_paquete
    ],
    "ii"
  )[0]["existe"];

  return $existe > 0;
}

function existeUsuario(
  Database $db,
  int $id_usuario
): bool {
  $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `id_usuario`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_usuario],
    "i"
  )[0]["existe"];

  return $existe > 0;
}

function stockDisponibleProducto(
  Database $db,
  int $id_producto,
  int $cantidad
): bool {
  $query = "SELECT `stock` FROM PRODUCTOS WHERE `id_producto`=?";

  $stock = $db->queryWithParams(
    $query,
    [$id_producto],
    "i"
  )[0]["stock"];

  return $stock >= $cantidad;
}

function stockDisponiblePaquete(
  Database $db,
  int $id_paquete,
  int $cantidad
): bool {
  $query = "SELECT `stock` FROM PAQUETES WHERE `id_paquete`=?";

  $stock = $db->queryWithParams(
    $query,
    [$id_paquete],
    "i"
  )[0]["stock"];

  return $stock >= $cantidad;
}

function existePaquete(
  Database $db,
  int $id_paquete
): bool {
  $query = "SELECT count(*) `existe` FROM PAQUETES WHERE `id_paquete`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_paquete],
    "i"
  )[0]["existe"];

  return $existe > 0;
}

function existePedido(
  Database $db,
  int $id_pedido
): bool {
  $query = "SELECT count(*) `existe` FROM PEDIDOS WHERE `id_pedido`=?";

  $existe = $db->queryWithParams(
    $query,
    [$id_pedido],
    "i"
  )[0]["existe"];

  return $existe > 0;
}
