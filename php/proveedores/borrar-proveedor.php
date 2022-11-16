<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "DELETE")
    throw new Exception("Solo se permiten peticiones DELETE", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("El id es obligatorio", 400);

  // TODO: VALIDACIONES CAMPOS

  $id_proveedor = $_GET["id"];

  if (!existeProveedor($database, $id_proveedor))
    throw new Exception("Proveedor no encontrado", 404);

  if (proveedorTieneProductos($database, $id_proveedor))
    throw new Exception("El proveedor tiene productos vinculados", 409);

  $query = "DELETE FROM PROVEEDORES WHERE `id_proveedor`=?";

  $database->updateOrDeleteRow(
    $query,
    [$id_proveedor],
    "i"
  );

  $database->close();
  echo json_encode(["resultado" => "Se borro correctamente el proveedor con Id " . $id_proveedor]);
  return http_response_code(201);

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
