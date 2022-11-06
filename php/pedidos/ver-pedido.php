<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("La ID es obligatoria", 400);

  // TODO: VALIDACIONES CAMPOS

  $id_pedido = $_GET["id"];

  if (!existePedido($database, $id_pedido))
    throw new Exception("Pedido no encontrado", 404);

  $pedido = [];

  $query = "SELECT * FROM VIEW_DETALLES_PEDIDOS_DE_PRODUCTOS WHERE `id_pedido`=?";

  $productos = $database->queryWithParams(
    $query,
    [$id_pedido],
    "i"
  ) ?? [];

  $pedido = array_merge($pedido, $productos);

  $query = "SELECT * FROM VIEW_DETALLES_PEDIDOS_DE_PAQUETES WHERE `id_pedido`=?";

  $paquetes = $database->queryWithParams(
    $query,
    [$id_pedido],
    "i"
  ) ?? [];

  $pedido = array_merge($pedido, $paquetes);

  echo json_encode(["resultado" => $pedido]);
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
