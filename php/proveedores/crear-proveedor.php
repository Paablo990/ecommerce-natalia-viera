<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $nombre = $_POST["nombre"];
  $calle = $_POST["calle"];
  $puerta = $_POST["puerta"];

  $correo = $_POST["correo"];

  if (existeCorreoProveedor($database, $correo))
    throw new Exception("Correo ya registrado", 409);

  $telefono = $_POST["telefono"];

  if (existeTelefono($database, $telefono))
    throw new Exception("Telefono ya registrado", 409);

  $query = "INSERT INTO PROVEEDORES (`nombre`, `calle`, `nro_puerta`, `correo`) VALUES (?,?,?,?)";

  $id_nuevo_proveedor = $database->insertRow(
    $query,
    [
      $nombre,
      $calle,
      $puerta,
      $correo
    ],
    "ssss"
  );

  $query = "INSERT INTO TELEFONOS_PROVEEDORES (`id_proveedor`, `telefono`) VALUES (?,?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_proveedor,
      $telefono
    ],
    "is"
  );

  echo json_encode(["resultado" => "Se creo correctamente el proveedor con id " . $id_nuevo_proveedor]);
  return http_response_code(201);

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
