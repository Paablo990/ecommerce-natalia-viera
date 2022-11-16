<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

define("RUTA_IMAGEN", __DIR__ . "/../../img");

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "DELETE")
    throw new Exception("Solo se permiten peticiones DELETE", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("La id es obligatoria", 400);

  // TODO: VALIDACIONES CAMPOS

  $id_producto = $_GET["id"];

  if (!existeProducto($database, $id_producto))
    throw new Exception("Producto no encontrado", 404);

  $query = "SELECT `ruta_imagen` FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

  $imagen = $database->queryWithParams(
    $query,
    [$id_producto],
    "i"
  )[0]["ruta_imagen"];

  unlink(RUTA_IMAGEN . "/" . $imagen);

  $query = "DELETE FROM PRODUCTOS WHERE `id_producto`=?";

  $database->updateOrDeleteRow(
    $query,
    [$id_producto],
    "i"
  );

  $database->close();
  echo json_encode(["resultado" => "Se borro correctamente el producto con id " . $id_producto]);
  return http_response_code(200);

  // errores inesperados
} catch (mysqli_sql_exception $th) {
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
