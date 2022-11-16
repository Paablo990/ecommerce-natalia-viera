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

  $query = "SELECT * FROM VIEW_USUARIOS_NA_CON_CELULAR WHERE `ci`=?";

  $usuario_na = $database->queryWithParams(
    $query,
    [$ci],
    "s"
  )[0];

  $query = "INSERT INTO USUARIOS (`ci`, `nombre`, `apellido`, `correo`, `contra`) VALUES (?,?,?,?,?)";

  $id_nuevo_usuario = $database->insertRow(
    $query,
    [
      $usuario_na["ci"],
      $usuario_na["nombre"],
      $usuario_na["apellido"],
      $usuario_na["correo"],
      $usuario_na["contra"]
    ],
    "sssss"
  );

  $query = "INSERT INTO CELULARES_USUARIOS (`id_usuario`, `celular`) VALUES (?,?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_usuario,
      $usuario_na["celular"]
    ],
    "is"
  );

  $query = "INSERT INTO CLIENTES (`id_cliente`) VALUES (?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_usuario,
    ],
    "i"
  );

  $query = "INSERT INTO CARRITOS (`id_cliente`) VALUES (?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_usuario,
    ],
    "i"
  );

  $query = "DELETE FROM USUARIOS_NA WHERE `ci`=?";

  $database->updateOrDeleteRow(
    $query,
    [$usuario_na["ci"]],
    "s"
  );

  $database->close();
  echo json_encode(["resultado" => "Se aprobo correctamente el usuario con CI " . $usuario_na["ci"]]);
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
