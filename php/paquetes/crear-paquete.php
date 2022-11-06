<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS
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

  $query = "INSERT INTO PAQUETES (`nombre`, `precio`, `descuento`, `stock`, `descripcion`) VALUES (?,?,?,?,?)";

  $id_nuevo_paquete = $database->insertRow(
    $query,
    [
      $nombre,
      $precio,
      $descuento,
      $stock,
      $descripcion,
    ],
    "siiis"
  );

  $query = "INSERT INTO TIENE_2 (`id_paquete`, `id_producto`, `id_proveedor`) VALUES (?,?,?)";

  foreach ($productos as $datos_producto) {
    $id_producto = $datos_producto[0];
    $id_proveedor = $datos_producto[1];

    $database->insertRow(
      $query,
      [
        $id_nuevo_paquete,
        $id_producto,
        $id_proveedor
      ],
      "iii"
    );
  }

  echo json_encode(["resultado" => "Se creo el paquete correctamente con id " . $id_nuevo_paquete]);
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
