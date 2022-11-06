<?php

require_once __DIR__ . "/../Database.php";

// TODO: limit y offset con parametros

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (
    isset($_GET["categoria"]) && !empty($_GET["categoria"]) &&
    isset($_GET["query"]) && !empty($_GET["query"])
  ) {
    // TODO: validar formato
    $categoria = $_GET["categoria"];
    $query_filter = $_GET["query"];

    $query = "SELECT * FROM VIEW_PRODUCTOS_CON_IMAGEN WHERE `categoria`=? AND `nombre` LIKE ?";

    $productos = $database->queryWithParams(
      $query,
      [
        $categoria,
        "%" . $query_filter . "%"
      ],
      "ss"
    ) ?? [];

    echo json_encode(["resultado" => $productos]);
    return http_response_code(200);
  }

  if (isset($_GET["categoria"]) && !empty($_GET["categoria"])) {
    // TODO: validar formato
    $categoria = $_GET["categoria"];

    $query = "SELECT * FROM VIEW_PRODUCTOS_CON_IMAGEN WHERE `categoria`=?";

    $productos = $database->queryWithParams(
      $query,
      [$categoria],
      "s"
    ) ?? [];

    echo json_encode(["resultado" => $productos]);
    return http_response_code(200);
  }

  if (isset($_GET["query"]) && !empty($_GET["query"])) {
    // TODO: validar formato
    $query_filter = $_GET["query"];

    $query = "SELECT * FROM VIEW_PRODUCTOS_CON_IMAGEN WHERE `nombre` LIKE ?";

    $productos = $database->queryWithParams(
      $query,
      ["%" . $query_filter . "%"],
      "s"
    ) ?? [];

    echo json_encode(["resultado" => $productos]);
    return http_response_code(200);
  }

  if (isset($_GET["oferta"])) {
    $query = "SELECT * FROM VIEW_PRODUCTOS_CON_IMAGEN WHERE `descuento`>0";

    $productos = $database->queryWithoutParams(
      $query
    ) ?? [];

    echo json_encode(["resultado" => $productos]);
    return http_response_code(200);
  }

  $query = "SELECT * FROM VIEW_PRODUCTOS_CON_IMAGEN";

  $productos = $database->queryWithoutParams(
    $query
  ) ?? [];

  echo json_encode(["resultado" => $productos]);
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
