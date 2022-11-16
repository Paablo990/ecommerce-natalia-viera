<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

define("RUTA_IMAGEN", __DIR__ . "/../../img");

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $imagen = md5(date("d-m-Y H:m:s")) . ".jpg";
  $aux = $_FILES["imagen"]["tmp_name"];
  $ruta = RUTA_IMAGEN . "/" . $imagen;

  move_uploaded_file($aux, $ruta);

  $nombre = $_POST["nombre"];

  $precio = $_POST["precio"];
  $descuento = $_POST["descuento"];
  $stock = $_POST["stock"];

  $descripcion = $_POST["descripcion"];
  $categoria = $_POST["categoria"];

  $id_proveedor = $_POST["proveedor"];

  if (!existeProveedor($database, $id_proveedor))
    throw new Exception("Proveedor no valido", 400);

  $query = "INSERT INTO PRODUCTOS (`nombre`, `precio`, `descuento`, `stock`, `descripcion`, `categoria`) VALUES (?,?,?,?,?,?)";

  $id_nuevo_producto = $database->insertRow(
    $query,
    [
      $nombre,
      $precio,
      $descuento,
      $stock,
      $descripcion,
      $categoria,
    ],
    "siiiss"
  );

  $query = "INSERT INTO IMAGENES_PRODUCTOS (`id_producto`, `ruta_imagen`) VALUES (?,?)";

  $database->insertRow(
    $query,
    [
      $id_nuevo_producto,
      $imagen
    ],
    "is"
  );

  $query = "INSERT INTO TIENE_1 (`id_producto`, `id_proveedor`) VALUES (?,?)";

  $database->queryWithParams(
    $query,
    [
      $id_nuevo_producto,
      $id_proveedor
    ],
    "ii"
  );

  $database->close();
  echo json_encode(["resultado" => "Se creo el producto correctamente con id " . $id_nuevo_producto]);
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
