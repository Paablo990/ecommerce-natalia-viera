<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("La id es obligatoria", 400);

  // TODO: VALIDACIONES CAMPOS

  $id_producto = $_GET["id"];

  if (!existeProducto($database, $id_producto))
    throw new Exception("Producto no encontrado", 404);

  $query = "SELECT * FROM VIEW_PRODUCTOS_INFO_PROVEEDORES WHERE `id`=?";

  $producto = $database->queryWithParams(
    $query,
    [$id_producto],
    "i"
  )[0];

  $query = "SELECT * FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

  $imagen = $database->queryWithParams(
    $query,
    [$id_producto],
    "i"
  )[0]['ruta_imagen'];

  $producto = [
    "id" => $id_producto,
    "nombre" => $producto["nombre"],
    "precio" => $producto["precio"],
    "descuento" => $producto["descuento"],
    "stock" => $producto["stock"],
    "descripcion" => $producto["descripcion"],
    "categoria" => $producto["categoria"],
    "imagen" => $imagen,
    "proveedor" => [
      "id_proveedor" => $producto["id_proveedor"],
      "nombre_proveedor" => $producto["nombre_proveedor"],
      "correo" => $producto["correo"],
      "calle" => $producto["calle"],
      "nro_puerta" => $producto["nro_puerta"],
    ]
  ];

  echo json_encode(["resultado" => $producto]);
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
