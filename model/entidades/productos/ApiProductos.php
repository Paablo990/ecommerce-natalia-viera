<?php

require_once __DIR__ . "/RepoProductos.php";

class ApiProductos
{
  private RepoProductos $repo_productos;

  public function __construct()
  {
    $this->repo_productos = new RepoProductos();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function get(
    ?int $id_producto = null
  ): string {
    if ($id_producto != null) {
      $producto = $this->repo_productos->getById(
        $id_producto
      );

      $this->repo_productos->cerrarConexion();
      return json_encode([
        "mensaje" => "Se encontraron productos.",
        "producto" => $producto
      ]);
    }

    $productos = $this->repo_productos->getAll();

    $this->repo_productos->cerrarConexion();
    return json_encode([
      "mensaje" => "Se encontraron productos.",
      "productos" => $productos
    ]);
  }

  public function post(
    array $producto
  ): string {
    $id_nuevo_producto = $this->repo_productos->create(
      $producto
    );

    $this->repo_productos->cerrarConexion();
    return json_encode([
      "mensaje" => "Se agrego un producto correctamente.",
      "id" => $id_nuevo_producto
    ]);
  }

  public function put(
    array $producto,
    int $id_producto
  ): string {
    $this->repo_productos->update(
      $producto,
      $id_producto
    );

    $this->repo_productos->cerrarConexion();
    return json_encode([
      "mensaje" => "Se modifico el producto correctamente."
    ]);
  }

  public function delete(
    int $id_producto
  ): string {
    $this->repo_productos->delete(
      $id_producto
    );

    $this->repo_productos->cerrarConexion();
    return json_encode([
      "mensaje" => "Se borro el producto #$id_producto correctamente."
    ]);
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

}
