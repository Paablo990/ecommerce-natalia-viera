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

  $id_paquete = $_GET["id"];

  if (!existePaquete($database, $id_paquete))
    throw new Exception("Paquete no encontrado", 404);

  $nombre = $_POST["nombre"];

  $precio = $_POST["precio"];
  $descuento = $_POST["descuento"];
  $stock = $_POST["stock"];

  $descripcion = $_POST["descripcion"];
  $productos = json_decode($_POST["productos"]);

  foreach ($productos as $datos_producto) {
    $id_producto = $datos_producto[0];
    $id_proveedor = $datos_producto[1];

    if (!existeProveedor($database, $id_proveedor))
      throw new Exception("Proveedor no valido", 400);

    if (!existeProducto($database, $id_producto))
      throw new Exception("Producto no encontrado", 404);
  }

  $query = "UPDATE PAQUETES SET `nombre`=?, `precio`=?, `descuento`=?, `stock`=?, `descripcion`=? WHERE `id_paquete`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $nombre,
      $precio,
      $descuento,
      $stock,
      $descripcion,
      $id_paquete,
    ],
    "siiisi"
  );

  $query = "DELETE FROM TIENE_2 WHERE `id_paquete`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $id_paquete
    ],
    "i"
  );

  $query = "INSERT INTO TIENE_2 (`id_paquete`, `id_producto`, `id_proveedor`) VALUES (?,?,?)";

  foreach ($productos as $datos_producto) {
    $id_producto = $datos_producto[0];
    $id_proveedor = $datos_producto[1];

    $database->insertRow(
      $query,
      [
        $id_paquete,
        $id_producto,
        $id_proveedor
      ],
      "iii"
    );
  }


  echo json_encode(["resultado" => "Se modifico correctamente el paquete con id " . $id_paquete]);
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
