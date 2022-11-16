<?php

require_once __DIR__ . "/../Database.php";

// TODO: limit y offset con parametros

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (isset($_GET["oferta"])) {
    $query = "SELECT * FROM VIEW_PAQUETES_CON_IMAGEN WHERE `descuento`>0";

    $paquetes = $database->queryWithoutParams(
      $query
    ) ?? [];

    $database->close();
    echo json_encode(["resultado" => $paquetes]);
    return http_response_code(200);
  }

  if (isset($_GET["query"]) && !empty($_GET["query"])) {
    // TODO: validar formato
    $query_filter = $_GET["query"];

    $query = "SELECT * FROM VIEW_PAQUETES_CON_IMAGEN WHERE `nombre` LIKE ?";

    $paquetes = $database->queryWithParams(
      $query,
      ["%" . $query_filter . "%"],
      "s"
    ) ?? [];

    $database->close();
    echo json_encode(["resultado" => $paquetes]);
    return http_response_code(200);
  }

  $query = "SELECT * FROM VIEW_PAQUETES_CON_IMAGEN";

  $paquetes = $database->queryWithoutParams(
    $query
  ) ?? [];

  $database->close();
  echo json_encode(["resultado" => $paquetes]);
  return http_response_code(200);

  // errores inesperados
} catch (Throwable | mysqli_sql_exception $th) {
  $database->close();
  echo json_encode([
    "resultado" => $th->getMessage(),
    "codigo" => $th->getCode()
  ]);
  return http_response_code(500);

  // errores esperados
} catch (Exception $ex) {
  $database->close();
  echo json_encode([
    "resultado" => $ex->getMessage(),
    "codigo" => $ex->getCode()
  ]);
  return http_response_code($ex->getCode());
}
