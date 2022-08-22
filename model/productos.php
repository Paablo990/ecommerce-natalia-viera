<?php
// TODO: verificar el formato de TODOS los datos

require_once "productosdb.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
  try {
    $id = $_GET["id"];

    $productosDb = new ProductosDB();
    $result = $productosDb->getByID($id);

    echo json_encode($result);
    return http_response_code(200);

    // exception handlers
  } catch (mysqli_sql_exception $ex) {
    echo json_encode(["msgError" => "Error en la DB"]);
    return http_response_code(500);
  } catch (Exception $ex) {
    echo json_encode(["msgError" => $ex->getMessage()]);
    return http_response_code(404);
  }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  try {
    $limit = $_GET["limit"] ?? 25;
    $offset = $_GET["offset"] ?? 0;

    $productosDb = new ProductosDB();
    $result = $productosDb->getAll($limit, $offset);

    echo json_encode($result);
    return http_response_code(200);

    // exception handlers
  } catch (mysqli_sql_exception $ex) {
    echo json_encode(["msgError" => "Error en la DB"]);
    return http_response_code(500);
  } catch (Exception $ex) {
    echo json_encode(["msgError" => $ex->getMessage()]);
    return http_response_code(404);
  }
}

$_BODY = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    validateEmptyProductFields($_BODY);

    $product = [
      "nombre" => $_BODY["nombre"],
      "precio" => $_BODY["precio"],
      "descuento" => $_BODY["descuento"],
      "descripcion" => $_BODY["descripcion"]
    ];

    $images = $_BODY["imagenes"];

    $productosDb = new ProductosDB();
    $productosDb->insert($product, $images);

    return http_response_code(200);

    // exception handlers
  } catch (mysqli_sql_exception $ex) {
    echo json_encode(["msgError" => "Error en la DB"]);
    return http_response_code(500);
  } catch (Exception $ex) {
    echo json_encode(["msgError" => $ex->getMessage()]);
    return http_response_code(404);
  }
}
