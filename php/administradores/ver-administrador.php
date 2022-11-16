<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (!isset($_GET["ci"]) || empty($_GET["ci"]))
    throw new Exception("La CI es obligatoria", 400);

  // TODO: VALIDACIONES CAMPOS

  $ci = $_GET["ci"];

  if (!existeCI($database, $ci))
    throw new Exception("Administrador no encontrado", 404);

  $query = "SELECT * FROM USUARIOS WHERE `ci`=?";

  $administrador = $database->queryWithParams(
    $query,
    [$ci],
    "s"
  )[0];

  $query = "SELECT * FROM CELULARES_USUARIOS WHERE `id_usuario`=?";

  $celular = $database->queryWithParams(
    $query,
    [$administrador['id_usuario']],
    "s"
  )[0]['celular'];

  $administrador = [
    "id" => $administrador['id_usuario'],
    "ci" => $administrador['ci'],
    "nombre" => $administrador["nombre"],
    "apellido" => $administrador["apellido"],
    "correo" => $administrador["correo"],
    "contra" => $administrador["contra"],
    "celular" => $celular,
  ];

  $database->close();
  echo json_encode(["resultado" => $administrador]);
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
