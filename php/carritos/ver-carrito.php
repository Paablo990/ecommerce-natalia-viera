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

  $id_usuario = $_GET["id"];

  if (!existeCliente($database, $id_usuario))
    throw new Exception("Cliente no encontrado", 404);

  $carrito = [];

  $query = "SELECT * FROM VIEW_CARRITO_DE_PRODUCTOS WHERE `id_cliente`=?";

  $productos = $database->queryWithParams(
    $query,
    [$id_usuario],
    "i"
  );

  $carrito = array_merge($carrito, $productos);

  $query = "SELECT * FROM VIEW_CARRITO_DE_PAQUETES WHERE `id_cliente`=?";

  $paquetes = $database->queryWithParams(
    $query,
    [$id_usuario],
    "i"
  );

  $carrito = array_merge($carrito, $paquetes);

  echo json_encode(["resultado" => $carrito]);
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
