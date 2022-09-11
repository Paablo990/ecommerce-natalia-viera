<?php

require_once __DIR__ . "/../entidades/productos/ApiProductos.php";

$_POST = json_decode(file_get_contents('php://input'), true);

// TODO: validar formatos
// TODO: validar permisos
// TODO: agregar tags

try {

  $api_productos = new ApiProductos();

  if (
    $_SERVER["REQUEST_METHOD"] == "GET" &&
    !empty($_GET["id"])
  ) {
    $id_producto = $_GET["id"] ?? -1;

    $respuesta = $api_productos->get(
      $id_producto
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "GET"
  ) {
    $respuesta = $api_productos->get();

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "POST"
  ) {
    $nuevo_producto = $_POST["producto"];

    $respuesta = $api_productos->post(
      $nuevo_producto
    );

    echo $respuesta;
    return http_response_code(201);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "PUT" &&
    !empty($_GET["id"])
  ) {
    $id_producto = intval($_GET["id"]);
    $nuevo_producto = $_POST["producto"];

    $respuesta = $api_productos->put(
      $nuevo_producto,
      $id_producto
    );

    echo $respuesta;
    return http_response_code(200);
  }

  if (
    $_SERVER["REQUEST_METHOD"] == "DELETE" &&
    !empty($_GET["id"])
  ) {
    $id_producto = $_GET["id"];

    $respuesta = $api_productos->delete(
      $id_producto
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
