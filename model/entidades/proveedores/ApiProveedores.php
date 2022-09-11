<?php

require_once __DIR__ . "/RepoProveedores.php";

class ApiProveedores
{
  private RepoProveedores $repo_proveedores;

  public function __construct()
  {
    $this->repo_proveedores = new RepoProveedores();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function get(
    ?int $id_proveedor = null
  ): string {
    if ($id_proveedor != null) {
      $proveedor = $this->repo_proveedores->getById(
        $id_proveedor
      );

      $this->repo_proveedores->cerrarConexion();
      return json_encode([
        "mensaje" => "Se encontraron proveedores.",
        "proveedor" => $proveedor
      ]);
    }

    $proveedores = $this->repo_proveedores->getAll();

    $this->repo_proveedores->cerrarConexion();
    return json_encode([
      "mensaje" => "Se encontraron proveedores.",
      "proveedores" => $proveedores
    ]);
  }

  public function post(
    array $proveedor
  ): string {
    $id_nuevo_proveedor = $this->repo_proveedores->create(
      $proveedor
    );

    $this->repo_proveedores->cerrarConexion();
    return json_encode([
      "mensaje" => "Se agrego un proveedor correctamente.",
      "id" => $id_nuevo_proveedor
    ]);
  }

  public function put(
    array $nuevo_proveedor,
    int $id_proveedor
  ): string {
    $this->repo_proveedores->update(
      $nuevo_proveedor,
      $id_proveedor
    );

    $this->repo_proveedores->cerrarConexion();
    return json_encode([
      "mensaje" => "Se modifico el proveedor correctamente.",
    ]);
  }

  public function delete(
    int $id_proveedor
  ): string {
    $this->repo_proveedores->delete(
      $id_proveedor
    );

    return json_encode([
      "mensaje" => "Se borro el proveedor #$id_proveedor correctamente.",
    ]);
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
}
