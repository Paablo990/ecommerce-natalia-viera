<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $id_producto = $_POST["producto"];
  $id_usuario = $_POST["usuario"];
  $cantidad = $_POST["cantidad"];

  if (!existeCliente($database, $id_usuario))
    throw new Exception("Cliente no encontrado", 404);

  $query = "SELECT `id_carrito` FROM CARRITOS WHERE `id_cliente`=?";

  $id_carrito = $database->queryWithParams(
    $query,
    [$id_usuario],
    "s"
  )[0]['id_carrito'];

  if (!existeProducto($database, $id_producto))
    throw new Exception("Producto no encontrado", 404);

  if (!stockDisponibleProducto($database, $id_producto, $cantidad))
    throw new Exception("No hay suficiente stock", 400);

  // TODO: hacer que se sumen las cantidades en vez de updatear
  if (existeProductoEnCarrito($database, $id_carrito, $id_producto)) {
    $query = "UPDATE TIENE_C_1 SET `cantidad`=? WHERE `id_carrito`=? AND `id_producto`=?";

    $database->insertRow(
      $query,
      [
        $cantidad,
        $id_carrito,
        $id_producto
      ],
      "iii"
    );

    echo json_encode(["resultado" => "Se agrego correctamente al carrito"]);
    return http_response_code(201);
  }

  $query = "SELECT `id_proveedor` FROM TIENE_1 WHERE `id_producto`=?";

  $id_proveedor = $database->queryWithParams(
    $query,
    [
      $id_producto
    ],
    "i"
  )[0]['id_proveedor'];

  $query = "INSERT INTO TIENE_C_1 (`id_carrito`, `id_producto`,`id_proveedor`, `cantidad`) VALUES (?,?,?,?)";

  $database->insertRow(
    $query,
    [
      $id_carrito,
      $id_producto,
      $id_proveedor,
      $cantidad
    ],
    "iiii"
  );

  $database->close();
  echo json_encode(["resultado" => "Se agrego correctamente al carrito"]);
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
