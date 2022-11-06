<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

define("RUTA_IMAGEN", __DIR__ . "/../../img");

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("La id es obligatoria", 400);

  // TODO: VALIDACIONES CAMPOS

  $id_proveedor = $_GET["id"];

  if (!existeProveedor($database, $id_proveedor))
    throw new Exception("Proveedor no encontrado", 404);

  $query = "SELECT * FROM VIEW_PROVEEDORES_CON_TELEFONO WHERE `id`=?";

  $actual = $database->queryWithParams(
    $query,
    [
      $id_proveedor
    ],
    "i"
  )[0];

  $nombre = $_POST["nombre"];
  $correo = $_POST["correo"];

  if (existeCorreoProveedor($database, $correo) && $correo != $actual["correo"])
    throw new Exception("Correo ya registrado", 409);

  $calle = $_POST["calle"];
  $puerta = $_POST["puerta"];

  $telefono = $_POST["telefono"];

  if (existeTelefono($database, $correo) && $telefono != $actual["telefono"])
    throw new Exception("Telefono ya registrado", 409);

  $query = "UPDATE PROVEEDORES SET `nombre`=?, `correo`=?, `calle`=?, `nro_puerta`=? WHERE `id_proveedor`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $nombre,
      $correo,
      $calle,
      $puerta,
      $id_proveedor
    ],
    "ssssi"
  );

  $query = "UPDATE TELEFONOS_PROVEEDORES SET `telefono`=? WHERE `id_proveedor`=?";

  $database->insertRow(
    $query,
    [
      $telefono,
      $id_proveedor
    ],
    "si"
  );

  echo json_encode(["resultado" => "Se modifico correctamente el proveedor con id " . $id_proveedor]);
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
