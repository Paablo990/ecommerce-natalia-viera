<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "DELETE")
    throw new Exception("Solo se permiten peticiones DELETE", 404);

  // TODO: VALIDACIONES CAMPOS

  $id_producto = $_GET["id_p"];
  $id_usuario = $_GET["id_u"];

  if (!existeCliente($database, $id_usuario))
    throw new Exception("Cliente no encontrado", 404);

  if (!existeProducto($database, $id_producto))
    throw new Exception("Producto no encontrado", 404);

  $query = "SELECT `id_carrito` FROM CARRITOS WHERE `id_cliente`=?";

  $id_carrito = $database->queryWithParams(
    $query,
    [$id_usuario],
    "s"
  )[0]['id_carrito'];

  if (!existeProductoEnCarrito($database, $id_carrito, $id_producto))
    throw new Exception("Producto no encontrado en carrito", 404);

  $query = "DELETE FROM TIENE_C_1 WHERE `id_carrito`=? AND `id_producto`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $id_carrito,
      $id_producto
    ],
    "ii"
  );

  $database->close();
  echo json_encode(["resultado" => "Se quito correctamente del carrito"]);
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
