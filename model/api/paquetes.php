<?php

require_once __DIR__ . "/../entidades/paquetes/ApiPaquetes.php";

$_POST = json_decode(file_get_contents('php://input'), true);

// TODO: validar formatos
// TODO: validar permisos
// TODO: agregar tags

try {

  $api_paquetes = new ApiPaquetes();

  if (
    $_SERVER["REQUEST_METHOD"] == "GET" &&
    !empty($_GET["id"])
  ) {
    $id_paquete = $_GET["id"] ?? -1;

    $respuesta = $api_paquetes->get(
      $id_paquete
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "GET"
  ) {
    $respuesta = $api_paquetes->get();

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "POST"
  ) {
    $nuevo_paquete = $_POST["paquete"];

    $respuesta = $api_paquetes->post(
      $nuevo_paquete
    );

    echo $respuesta;
    return http_response_code(201);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "PUT" &&
    !empty($_GET["id"])
  ) {
    $id_paquete = $_GET["id"];
    $nuevo_paquete = $_POST["paquete"];

    $respuesta = $api_paquetes->put(
      $nuevo_paquete,
      $id_paquete
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "DELETE" &&
    !empty($_GET["id"])
  ) {
    $id_paquete = $_GET["id"];

    $respuesta = $api_paquetes->delete(
      $id_paquete
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
