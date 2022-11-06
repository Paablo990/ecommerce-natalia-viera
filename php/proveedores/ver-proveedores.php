<?php

require_once __DIR__ . "/../Database.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  $query = "SELECT * FROM VIEW_PROVEEDORES_CON_TELEFONO";

  $proveedores = $database->queryWithoutParams(
    $query
  ) ?? [];

  echo json_encode(["resultado" => $proveedores]);
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
