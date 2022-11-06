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

  if (!usuarioAprobado($database, $ci))
    throw new Exception("Usuario no esta aprobado", 404);

  $query = "SELECT * FROM VIEW_USUARIOS_CON_CELULAR WHERE `ci`=?";

  $usuario = $database->queryWithParams(
    $query,
    [$ci],
    "s"
  )[0];

  $query = "INSERT INTO USUARIOS_NA (`ci`, `nombre`, `apellido`, `correo`, `contra`) VALUES (?,?,?,?,?)";

  $id_nuevo_usuario_na = $database->insertRow(
    $query,
    [
      $usuario["ci"],
      $usuario["nombre"],
      $usuario["apellido"],
      $usuario["correo"],
      $usuario["contra"]
    ],
    "sssss"
  );

  $query = "INSERT INTO CELULARES_USUARIOS_NA (`id_usuario`, `celular`) VALUES (?,?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_usuario_na,
      $usuario["celular"]
    ],
    "is"
  );

  $query = "DELETE FROM USUARIOS WHERE `ci`=?";

  $database->updateOrDeleteRow(
    $query,
    [$usuario["ci"]],
    "s"
  );

  echo json_encode(["resultado" => "Se suspendio correctamente el usuario con CI " . $usuario["ci"]]);
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
