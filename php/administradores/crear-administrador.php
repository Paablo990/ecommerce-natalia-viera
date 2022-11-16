<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $rol = $_POST["rol"];
  $ci = $_POST["ci"];

  if (existeCI($database, $ci))
    throw new Exception("Cedula ya registrada", 409);

  $nombre = $_POST["nombre"];
  $apellido = $_POST["apellido"];

  $correo = $_POST["correo"];
  $contra = $_POST["contra"];
  $hash = password_hash($contra, PASSWORD_BCRYPT);

  if (existeCorreo($database, $correo))
    throw new Exception("Correo ya registrado", 409);

  $celular = $_POST["celular"];

  if (existeCelular($database, $celular))
    throw new Exception("Celular ya registrado", 409);

  $query = "INSERT INTO USUARIOS (`ci`, `nombre`, `apellido`, `correo`, `contra`) VALUES (?,?,?,?,?)";

  $id_nuevo_administrador = $database->insertRow(
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

  $query = "INSERT INTO CELULARES_USUARIOS (`id_usuario`, `celular`) VALUES (?,?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_administrador,
      $celular
    ],
    "is"
  );

  $querys = [
    "comprador" => "INSERT INTO COMPRADORES VALUES (?)",
    "vendedor" => "INSERT INTO VENDEDORES VALUES (?)",
    "jefe" => "INSERT INTO JEFES VALUES (?)"
  ];

  $database->queryWithParams(
    $querys[$rol],
    [
      $id_nuevo_administrador,
    ],
    "i"
  );

  $database->close();
  echo json_encode(["resultado" => "Se creo correctamente el administrador con CI " . $ci]);
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
