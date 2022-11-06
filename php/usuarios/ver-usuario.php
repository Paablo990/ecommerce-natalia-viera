<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("El id es obligatorio", 400);

  // TODO: validar campos
  $id = $_GET["id"];

  if (!existeUsuario($database, $id))
    throw new Exception("Usuario no encontrado", 404);

  $query = "SELECT * FROM VIEW_USUARIOS_CON_CELULAR WHERE `id`=?";

  $usuario = $database->queryWithParams(
    $query,
    [$id],
    "i"
  )[0];

  echo json_encode(["resultado" => $usuario]);
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
