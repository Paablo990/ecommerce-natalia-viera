<?php

require_once __DIR__ . "/../Database.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id_usuario = $_GET["id"];

    $query = "SELECT * FROM VIEW_PEDIDOS_CON_FECHAS WHERE `id_cliente`=?";

    $pedidos = $database->queryWithParams(
      $query,
      [
        $id_usuario
      ],
      "i"
    );

    foreach ($pedidos as $key => $value) {
      $id_pedido = $pedidos[$key]["id_pedido"];

      $query = "SELECT `cantidad` FROM VIEW_PEDIDOS_DE_PRODUCTOS WHERE `id_pedido`=?";

      $cantidad_productos = $database->queryWithParams(
        $query,
        [$id_pedido],
        "i"
      )[0]["cantidad"] ?? 0;

      $query = "SELECT `cantidad` FROM VIEW_PEDIDOS_DE_PAQUETES WHERE `id_pedido`=?";

      $cantidad_paquetes = $database->queryWithParams(
        $query,
        [$id_pedido],
        "i"
      )[0]["cantidad"] ?? 0;

      $cantidad_total = $cantidad_productos + $cantidad_paquetes;
      $pedidos[$key]["cantidad"] = $cantidad_total;
    }

    echo json_encode(["resultado" => $pedidos]);
    return http_response_code(200);
  }

  $query = "SELECT * FROM VIEW_PEDIDOS_CON_FECHAS";

  $pedidos = $database->queryWithoutParams(
    $query
  );

  foreach ($pedidos as $key => $value) {
    $id_pedido = $pedidos[$key]["id_pedido"];

    $query = "SELECT `cantidad` FROM VIEW_PEDIDOS_DE_PRODUCTOS WHERE `id_pedido`=?";

    $cantidad_productos = $database->queryWithParams(
      $query,
      [$id_pedido],
      "i"
    )[0]["cantidad"] ?? 0;

    $query = "SELECT `cantidad` FROM VIEW_PEDIDOS_DE_PAQUETES WHERE `id_pedido`=?";

    $cantidad_paquetes = $database->queryWithParams(
      $query,
      [$id_pedido],
      "i"
    )[0]["cantidad"] ?? 0;

    $cantidad_total = $cantidad_productos + $cantidad_paquetes;
    $pedidos[$key]["cantidad"] = $cantidad_total;
  }

  echo json_encode(["resultado" => $pedidos]);
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
