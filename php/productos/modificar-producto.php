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

  $id_producto = $_GET["id"];

  if (!existeProducto($database, $id_producto))
    throw new Exception("Producto no encontrado", 404);

  $nombre = $_POST["nombre"];

  $precio = $_POST["precio"];
  $descuento = $_POST["descuento"];
  $stock = $_POST["stock"];

  $descripcion = $_POST["descripcion"];
  $categoria = $_POST["categoria"];

  $id_proveedor = $_POST["proveedor"];

  if (!existeProveedor($database, $id_proveedor))
    throw new Exception("Proveedor no valido", 400);

  // TODO: arreglar el tema de las fotos

  // $is_updating_image = $_FILES["imagen"]["size"] == 0;

  $query = "UPDATE PRODUCTOS SET `nombre`=?, `precio`=?, `descuento`=?, `stock`=?, `descripcion`=?, `categoria`=? WHERE `id_producto`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $nombre,
      $precio,
      $descuento,
      $stock,
      $descripcion,
      $categoria,
      $id_producto,
    ],
    "siiissi"
  );

  // if ($is_updating_image) {
  //   $query = "SELECT `ruta_imagen` FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

  //   $imagen = $database->queryWithParams(
  //     $query,
  //     [$id_producto],
  //     "i"
  //   )[0]["ruta_imagen"];

  //   unlink(RUTA_IMAGEN . "/" . $imagen);

  //   $query = "DELETE FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

  //   $database->updateOrDeleteRow(
  //     $query,
  //     [$id_producto],
  //     "i"
  //   );

  //   $query = "INSERT INTO IMAGENES_PRODUCTOS (`id_producto`, `ruta_imagen`) VALUES (?,?)";

  //   $imagen = md5(date("d-m-Y H:m:s")) . ".jpg";
  //   $aux = $_FILES["imagen"]["tmp_name"];
  //   $ruta = RUTA_IMAGEN . "/" . $imagen;

  //   move_uploaded_file($aux, $ruta);

  //   $database->insertRow(
  //     $query,
  //     [
  //       $id_producto,
  //       $imagen
  //     ],
  //     "is"
  //   );
  // } else {
  // }

  // $query = "SELECT `ruta_imagen` FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

  // $imagen = $database->queryWithParams(
  //   $query,
  //   [$id_producto],
  //   "i"
  // )[0]["ruta_imagen"];

  // $query = "DELETE FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

  // $database->updateOrDeleteRow(
  //   $query,
  //   [$id_producto],
  //   "i"
  // );

  // $query = "INSERT INTO IMAGENES_PRODUCTOS (`id_producto`, `ruta_imagen`) VALUES (?,?)";

  // $database->insertRow(
  //   $query,
  //   [
  //     $id_producto,
  //     $imagen
  //   ],
  //   "is"
  // );

  $query = "UPDATE TIENE_1 SET `id_proveedor`=? WHERE `id_producto`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $id_proveedor,
      $id_producto
    ],
    "ii"
  );

  echo json_encode(["resultado" => "Se modifico correctamente el producto con id " . $id_producto]);
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
