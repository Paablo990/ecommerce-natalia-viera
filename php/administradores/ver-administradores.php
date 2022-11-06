<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  $querys = [
    "SELECT * FROM VIEW_JEFES",
    "SELECT * FROM VIEW_COMPRADORES",
    "SELECT * FROM VIEW_VENDEDORES"
  ];

  $administradores = [];

  foreach ($querys as $query) {
    $administradores  = array_merge($administradores, $database->queryWithoutParams(
      $query
    ));
  }

  echo json_encode(["resultado" => $administradores]);
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
