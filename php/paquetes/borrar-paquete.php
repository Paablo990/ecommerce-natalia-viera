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

  $id_paquete = $_GET["id"];

  if (!existePaquete($database, $id_paquete))
    throw new Exception("Paquete no encontrado", 404);

  $query = "DELETE FROM PAQUETES WHERE `id_paquete`=?";

  $database->updateOrDeleteRow(
    $query,
    [$id_paquete],
    "i"
  );

  echo json_encode(["resultado" => "Se borro correctamente el paquete con id " . $id_paquete]);
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
