<?php

require_once __DIR__ . "/../Database.php";
require_once __DIR__ . "/../utilidades.php";

try {
  $database = new Database();

  if ($_SERVER["REQUEST_METHOD"] !== "GET")
    throw new Exception("Solo se permiten peticiones GET", 404);

  if (!isset($_GET["id"]) || empty($_GET["id"]))
    throw new Exception("La ID es obligatoria", 400);

  // TODO: VALIDACIONES CAMPOS

  $id_pedido = $_GET["id"];

  if (!existePedido($database, $id_pedido))
    throw new Exception("Pedido no encontrado", 404);

  $query = "SELECT `estado` FROM PEDIDOS WHERE `id_pedido`=?";

  $estado = $database->queryWithParams(
    $query,
    [$id_pedido],
    "i"
  )[0]["estado"];

  if ($estado + 1 > 4)
    throw new Exception("El pedido ya finalizo", 404);

  $query = "UPDATE PEDIDOS SET `estado`=? WHERE `id_pedido`=?";

  $database->updateOrDeleteRow(
    $query,
    [
      $estado + 1,
      $id_pedido
    ],
    "ii"
  );

  if ($estado + 1 == 4) {
    $query = "UPDATE REALIZA SET `fecha_entrega`=? WHERE `id_pedido`=?";

    $database->updateOrDeleteRow(
      $query,
      [
        date('d-m-y h:i:s'),
        $id_pedido
      ],
      "si"
    );
  }

  echo json_encode(["resultado" => "Se actualizo el estado correctamente"]);
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
