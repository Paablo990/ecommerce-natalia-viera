<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "POST")
    throw new Exception("Solo se permiten peticiones POST", 404);

  // TODO: VALIDACIONES CAMPOS

  $id_paquete = $_POST["paquete"];
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

  if (!existePaquete($database, $id_paquete))
    throw new Exception("Paquete no encontrado", 404);

  if (!stockDisponiblePaquete($database, $id_paquete, $cantidad))
    throw new Exception("No hay suficiente stock", 400);

  // TODO: hacer que se sumen las cantidades en vez de updatear
  if (existePaqueteEnCarrito($database, $id_carrito, $id_paquete)) {
    $query = "UPDATE TIENE_C_2 SET `cantidad`=? WHERE `id_carrito`=? AND `id_paquete`=?";

    $database->insertRow(
      $query,
      [
        $cantidad,
        $id_carrito,
        $id_paquete
      ],
      "iii"
    );

    echo json_encode(["resultado" => "Se agrego correctamente al carrito"]);
    return http_response_code(201);
  }

  $query = "SELECT `id_proveedor`, `id_producto` FROM TIENE_2 WHERE `id_paquete`=?";

  $aux = $database->queryWithParams(
    $query,
    [
      $id_paquete
    ],
    "i"
  );

  foreach ($aux as $paquete_aux) {
    $query = "INSERT INTO TIENE_C_2 (`id_carrito`, `id_paquete`, `id_producto`, `id_proveedor`, `cantidad`) VALUES (?,?,?,?,?)";

    $database->insertRow(
      $query,
      [
        $id_carrito,
        $id_paquete,
        $paquete_aux["id_producto"],
        $paquete_aux["id_proveedor"],
        $cantidad
      ],
      "iiiii"
    );
  }

  echo json_encode(["resultado" => "Se agrego correctamente al carrito"]);
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
