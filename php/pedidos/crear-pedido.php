<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $id_usuario = $_POST["usuario"];
  $total = $_POST["total"];

  $nombre = $_POST["nombre"];
  $apellido = $_POST["apellido"];
  $tarjeta = $_POST["tarjeta"];
  $codigo = $_POST["codigo"];

  if (!existeCliente($database, $id_usuario))
    throw new Exception("Cliente no encontrado", 404);

  $query = "SELECT `id_carrito` FROM CARRITOS WHERE `id_cliente`=?";

  $id_carrito = $database->queryWithParams(
    $query,
    [$id_usuario],
    "s"
  )[0]['id_carrito'];

  $query = "SELECT * FROM VIEW_CARRITO_DE_PRODUCTOS WHERE `id_carrito`=?";

  $productos = $database->queryWithParams(
    $query,
    [$id_carrito],
    "i"
  );

  foreach ($productos as $producto) {
    if ($producto["stock"] < $producto["cantidad"]) {
      throw new Exception("No hay stock suficiente stock de " . $producto["nombre"], 400);
    }
  }

  $query = "SELECT * FROM VIEW_CARRITO_DE_PAQUETES WHERE `id_carrito`=?";

  $paquetes = $database->queryWithParams(
    $query,
    [$id_carrito],
    "i"
  );

  foreach ($paquetes as $paquete) {
    if ($paquete["stock"] < $paquete["cantidad"]) {
      throw new Exception("No hay stock suficiente stock de " . $paquete["nombre"], 400);
    }
  }

  $query = "INSERT INTO PEDIDOS (`estado`,`tarjeta`,`monto`) VALUES (?,?,?)";

  $id_pedido = $database->insertRow(
    $query,
    [
      "0",
      $tarjeta,
      $total
    ],
    "isi"
  );

  date_default_timezone_set("America/Montevideo");

  $query = "INSERT INTO REALIZA (`id_cliente`,`id_pedido`,`fecha_solicitud`) VALUES (?,?,?)";

  $database->insertRow(
    $query,
    [
      $id_usuario,
      $id_pedido,
      date('d-m-Y h:i:s')
    ],
    "iis"
  );

  $query = "SELECT * FROM VIEW_CARRITO_DE_PRODUCTOS WHERE `id_carrito`=?";

  $productos = $database->queryWithParams(
    $query,
    [$id_carrito],
    "i"
  );

  foreach ($productos as $producto) {
    $query = "SELECT `stock` FROM PRODUCTOS WHERE `id_producto`=?";

    $stock_actual = $database->queryWithParams(
      $query,
      [$producto["id_producto"]],
      "i"
    )[0]["stock"];

    $query = "UPDATE PRODUCTOS SET `stock`=? WHERE `id_producto`=?";

    $stock = $stock_actual - $producto["cantidad"];

    $database->updateOrDeleteRow(
      $query,
      [
        $stock,
        $producto["id_producto"]
      ],
      "ii"
    );

    $query = "INSERT INTO TIENE_P_1 (`id_pedido`,`id_producto`,`id_proveedor`,`cantidad`) VALUES (?,?,?,?)";

    $database->insertRow(
      $query,
      [
        $id_pedido,
        $producto["id_producto"],
        $producto["id_proveedor"],
        $producto["cantidad"]
      ],
      "iiii"
    );
  }

  $query = "SELECT * FROM VIEW_CARRITO_DE_PAQUETES WHERE `id_carrito`=?";

  $paquetes = $database->queryWithParams(
    $query,
    [$id_carrito],
    "i"
  );

  foreach ($paquetes as $paquete) {
    $query = "SELECT `stock` FROM PAQUETES WHERE `id_paquete`=?";

    $stock_actual = $database->queryWithParams(
      $query,
      [$paquete["id_paquete"]],
      "i"
    )[0]["stock"];

    $query = "UPDATE PAQUETES SET `stock`=? WHERE `id_paquete`=?";

    $stock = $stock_actual - $paquete["cantidad"];

    $database->updateOrDeleteRow(
      $query,
      [
        $stock,
        $paquete["id_paquete"]
      ],
      "ii"
    );

    $query = "SELECT * FROM TIENE_2 WHERE `id_paquete`=?";

    $productos = $database->queryWithParams(
      $query,
      [$paquete["id_paquete"]],
      "i"
    );

    foreach ($productos as $producto) {
      $query = "INSERT INTO TIENE_P_2 (`id_pedido`,`id_paquete`,`id_producto`,`id_proveedor`,`cantidad`) VALUES (?,?,?,?,?)";

      $database->insertRow(
        $query,
        [
          $id_pedido,
          $paquete["id_paquete"],
          $producto["id_producto"],
          $producto["id_proveedor"],
          $paquete["cantidad"]
        ],
        "iiiii"
      );
    }
  }

  $query = "DELETE FROM TIENE_C_1 WHERE `id_carrito`=?";

  $database->updateOrDeleteRow(
    $query,
    [$id_carrito],
    "i"
  );

  $query = "DELETE FROM TIENE_C_2 WHERE `id_carrito`=?";

  $database->updateOrDeleteRow(
    $query,
    [$id_carrito],
    "i"
  );

  echo json_encode(["resultado" => "Se realizo el pedido correctamente"]);
  return http_response_code(200);

  // errores inesperados
} catch (Throwable | mysqli_sql_exception $th) {
  echo json_encode([
    "resultado" => $th->getMessage(),
    "codigo" => $th->getCode()
  ]);
  return http_response_code(500);

  // errores esperados
} catch (Exception $ex) {
  echo json_encode([
    "resultado" => $ex->getMessage(),
    "codigo" => $ex->getCode()
  ]);
  return http_response_code($ex->getCode());
}
