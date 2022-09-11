<?php

require_once __DIR__ . "/../entidades/proveedores/ApiProveedores.php";

$_POST = json_decode(file_get_contents('php://input'), true);

// TODO: validar formatos
// TODO: validar permisos

try {

  $api_proveedores = new ApiProveedores();

  if (
    $_SERVER["REQUEST_METHOD"] == "GET" &&
    !empty($_GET["id"])
  ) {
    $id_proveedor = $_GET["id"] ?? -1;

    $respuesta = $api_proveedores->get(
      $id_proveedor
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "GET"
  ) {
    $respuesta = $api_proveedores->get();

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "POST"
  ) {
    $nuevo_proveedor = $_POST["proveedor"];

    $respuesta = $api_proveedores->post(
      $nuevo_proveedor
    );

    echo $respuesta;
    return http_response_code(201);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "DELETE" &&
    !empty($_GET["id"])
  ) {
    $id_proveedor = $_GET["id"];

    $respuesta = $api_proveedores->delete(
      $id_proveedor
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "PUT" &&
    !empty($_GET["id"])
  ) {
    $id_proveedor = $_GET["id"];
    $nuevo_proveedor = $_POST["proveedor"];

    $respuesta = $api_proveedores->put(
      $nuevo_proveedor,
      $id_proveedor
    );

    echo $respuesta;
    return http_response_code(201);
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
