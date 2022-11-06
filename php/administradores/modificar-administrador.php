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

  $id_administrador = $_GET["id"];

  if (!existeAdministrador($database, $id_administrador))
    throw new Exception("Administrador no encontrado", 404);

  $query = "SELECT * FROM USUARIOS WHERE `id_usuario`=?";

  $actual = $database->queryWithParams(
    $query,
    [
      $id_administrador
    ],
    "i"
  )[0];

  $ci = $_POST["ci"];

  if (existeCI($database, $ci) && $ci != $actual["ci"])
    throw new Exception("Cedula ya registrada", 409);

  $nombre = $_POST["nombre"];
  $apellido = $_POST["apellido"];

  $correo = $_POST["correo"];

  $contra = $_POST["contra"];

  $contra = $contra == "" ?
    $actual["contra"] : password_hash($contra, PASSWORD_BCRYPT);

  if (existeCorreo($database, $correo) && $correo != $actual["correo"])
    throw new Exception("Correo ya registrado", 409);

  $celular = $_POST["celular"];

  $query = "SELECT * FROM CELULARES_USUARIOS WHERE `id_usuario`=?";

  $actual = $database->queryWithParams(
    $query,
    [
      $id_administrador
    ],
    "i"
  )[0];

  if (existeCelular($database, $celular) && $celular != $actual["celular"])
    throw new Exception("Celular ya registrado", 409);

  $query = "UPDATE USUARIOS SET `ci`=?, `nombre`=?, `apellido`=?, `correo`=?, `contra`=? WHERE `id_usuario`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $ci,
      $nombre,
      $apellido,
      $correo,
      $contra,
      $id_administrador
    ],
    "sssssi"
  );

  $query = "DELETE FROM CELULARES_USUARIOS WHERE `id_usuario`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $id_administrador
    ],
    "i"
  );

  $query = "INSERT INTO CELULARES_USUARIOS (`id_usuario`, `celular`) VALUES (?,?)";

  $database->insertRow(
    $query,
    [
      $id_administrador,
      $celular
    ],
    "is"
  );

  echo json_encode(["resultado" => "Se modifico correctamente el administrador con ci " . $ci]);
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
