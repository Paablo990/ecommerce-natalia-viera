<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("La id es obligatoria", 400);

  // TODO: VALIDACIONES CAMPOS

  $id_paquete = $_GET["id"];

  if (!existePaquete($database, $id_paquete))
    throw new Exception("Paquete no encontrado", 404);

  $query = "SELECT * FROM PAQUETES WHERE `id_paquete`=?";

  $paquete = $database->queryWithParams(
    $query,
    [$id_paquete],
    "i"
  )[0];

  $query = "SELECT `id_producto` FROM TIENE_2 WHERE `id_paquete`=?";

  $id_productos = $database->queryWithParams(
    $query,
    [$id_paquete],
    "i"
  );

  $productos = [];

  $imagen = null;

  foreach ($id_productos as $id_producto) {
    $query = "SELECT * FROM VIEW_PRODUCTOS_CON_IMAGEN WHERE `id`=?";

    $producto = $database->queryWithParams(
      $query,
      [$id_producto["id_producto"]],
      "i"
    )[0];

    if ($imagen == null) $imagen = $producto["ruta"];

    $productos[] = $producto;
  }

  $paquete = array_merge($paquete, ["productos" =>  $productos]);
  $paquete = array_merge($paquete, ["imagen" =>  $imagen]);

  echo json_encode(["resultado" => $paquete]);
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
