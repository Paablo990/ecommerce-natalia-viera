<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $correo = $_POST["correo"];
  $contra = $_POST["contra"];

  if (!existeCorreo($database, $correo))
    throw new Exception("Datos de inicio de sesion incorrectos", 403);

  if (usuarioAprobadoConCorreo($database, $correo)) {
    $query = "SELECT * FROM VIEW_USUARIOS_CON_CELULAR WHERE `correo`=?";

    $posible_usuario = $database->queryWithParams(
      $query,
      [$correo],
      "s"
    )[0];

    if (!password_verify($contra, $posible_usuario["contra"]))
      throw new Exception("Datos de inicio de sesion incorrectos", 403);

    $id = $posible_usuario["id"];

    $rol = obtenerRol($database, $id);

    echo json_encode([
      "resultado" => "Se inicio sesion correctamente",
      "datos" => [
        "id" => $id,
        "rol" => $rol
      ]
    ]);
    return http_response_code(200);
  } else {
    $query = "SELECT * FROM VIEW_USUARIOS_NA_CON_CELULAR WHERE `correo`=?";

    $posible_usuario = $database->queryWithParams(
      $query,
      [$correo],
      "s"
    )[0];

    if (!password_verify($contra, $posible_usuario["contra"]))
      throw new Exception("Datos de inicio de sesion incorrectos", 403);

    throw new Exception("El usuario no esta aprobado", 403);
  }

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
