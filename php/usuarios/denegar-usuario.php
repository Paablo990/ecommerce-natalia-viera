<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  if (!isset($_GET["ci"]) || empty($_GET["ci"]))
    throw new Exception("La CI es obligatoria", 400);

  // TODO: validar formato

  $ci = $_GET["ci"];

  if (!existeCI($database, $ci))
    throw new Exception("Usuario no encontrado", 404);

  if (usuarioAprobado($database, $ci))
    throw new Exception("Usuario ya aprobado", 404);

  $query = "DELETE FROM USUARIOS_NA WHERE `ci`=?";

  $database->updateOrDeleteRow(
    $query,
    [$ci],
    "s"
  );

  echo json_encode(["resultado" => "Se denego correctamente el usuario con CI " . $ci]);
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
