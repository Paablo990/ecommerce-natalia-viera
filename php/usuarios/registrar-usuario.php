<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $ci = $_POST["ci"];

  if (existeCI($database, $ci))
    throw new Exception("Cedula ya registrada", 409);

  $nombre = $_POST["nombre"];
  $apellido = $_POST["apellido"];

  $correo = $_POST["correo"];

  if (existeCorreo($database, $correo))
    throw new Exception("Correo ya registrado", 409);

  $contra = $_POST["contra"];
  $hash = password_hash($contra, PASSWORD_BCRYPT);

  $celular = $_POST["celular"];

  if (existeCelular($database, $celular))
    throw new Exception("Celular ya registrado", 409);

  $query = "INSERT INTO USUARIOS_NA (`ci`, `nombre`, `apellido`, `correo`, `contra`) VALUES (?,?,?,?,?)";

  $id_nuevo_usuario_na = $database->insertRow(
    $query,
    [
      $ci,
      $nombre,
      $apellido,
      $correo,
      $hash
    ],
    "sssss"
  );

  $query = "INSERT INTO CELULARES_USUARIOS_NA (`id_usuario`, `celular`) VALUES (?,?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_usuario_na,
      $celular
    ],
    "is"
  );

  echo json_encode(["resultado" => "Se registro correctamente el usuario con CI " . $ci]);
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
