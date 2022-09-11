<?php

require_once __DIR__ .
  "/../../entidades/clientes/auth/ApiClientesAuth.php";

$_POST = json_decode(file_get_contents('php://input'), true);

// TODO: validar formatos
// TODO: validar permisos

try {

  $api_clientes_auth = new ApiClientesAuth();

  if (
    $_SERVER["REQUEST_METHOD"] == "GET" &&
    !empty($_GET["id"])
  ) {
    $id_cliente_auth = $_GET["id"] ?? -1;

    $respuesta = $api_clientes_auth->get(
      $id_cliente_auth
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "GET"
  ) {
    $respuesta = $api_clientes_auth->get();

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    !empty($_GET["id"])
  ) {
    $id_cliente_no_auth = $_GET["id"];

    $respuesta = $api_clientes_auth->post(
      $id_cliente_no_auth
    );

    echo $respuesta;
    return http_response_code(201);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "DELETE" &&
    !empty($_GET["id"])
  ) {
    $id_cliente_auth = $_GET["id"];

    $respuesta = $api_clientes_auth->delete(
      $id_cliente_auth
    );

    echo $respuesta;
    return http_response_code(200);
  }

  throw new Exception("Parece que estas perdido, mira nuestra documentacion!", 404);

  // error hanlders
} catch (Exception $ex) {
  echo json_encode([
    "mensaje_error" => $ex->getMessage()
  ]);
  return http_response_code($ex->getCode());
} catch (mysqli_sql_exception | Throwable $ex) {
  echo json_encode([
    "mensaje_error" => $ex->getMessage()
  ]);
  return http_response_code(500);
}
