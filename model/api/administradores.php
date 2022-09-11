<?php

require_once __DIR__ .
  "/../entidades/administradores/ApiAdministradores.php";

$_POST = json_decode(file_get_contents('php://input'), true);

// TODO: validar formatos
// TODO: validar permisos

try {

  $api_administradores = new ApiAdministradores();

  if (
    $_SERVER["REQUEST_METHOD"] == "GET" &&
    !empty($_GET["id"])
  ) {
    $id_administrador = $_GET["id"] ?? -1;

    $respuesta = $api_administradores->get(
      $id_administrador
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "GET"
  ) {
    $respuesta = $api_administradores->get();

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_GET["jefe"])
  ) {
    $nuevo_jefe = $_POST["jefe"];

    $respuesta = $api_administradores->post(
      $nuevo_jefe,
      "Jefe"
    );

    echo $respuesta;
    return http_response_code(201);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_GET["comprador"])
  ) {
    $nuevo_comprador = $_POST["comprador"];

    $respuesta = $api_administradores->post(
      $nuevo_comprador,
      "Comprador"
    );

    echo $respuesta;
    return http_response_code(201);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_GET["vendedor"])
  ) {
    $nuevo_vendedor = $_POST["vendedor"];

    $respuesta = $api_administradores->post(
      $nuevo_vendedor,
      "Vendedor"
    );

    echo $respuesta;
    return http_response_code(201);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "PUT" &&
    isset($_GET["id"])
  ) {
    $id_administrador = $_GET["id"];
    $nuevo_administrador = $_POST["administrador"];

    $respuesta = $api_administradores->put(
      $nuevo_administrador,
      $id_administrador
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "DELETE" &&
    isset($_GET["id"])
  ) {
    $id_administrador = $_GET["id"];

    $respuesta = $api_administradores->delete(
      $nuevo_administrador
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
