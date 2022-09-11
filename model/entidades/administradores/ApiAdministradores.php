<?php

require_once __DIR__ . "/RepoAdministradores.php";

class ApiAdministradores
{
  private RepoAdministradores $repo_administradores;

  public function __construct()
  {
    $this->repo_administradores = new RepoAdministradores();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function get(
    ?int $id_administrador = null
  ): string {
    if ($id_administrador != null) {
      $administrador = $this->repo_administradores->getById(
        $id_administrador
      );

      $this->repo_administradores->cerrarConexion();
      return json_encode([
        "mensaje" => "Se encontraron administradores.",
        "administrador" => $administrador
      ]);
    }

    $administradores = $this->repo_administradores->getAll();

    $this->repo_administradores->cerrarConexion();
    return json_encode([
      "mensaje" => "Se encontraron administradores.",
      "administradores" => $administradores
    ]);
  }

  public function post(
    array $nuevo_administrador,
    string $rol
  ): string {
    if (
      $rol == "Jefe"
    ) {
      $id_nuevo_jefe = $this->repo_administradores->createJefe(
        $nuevo_administrador
      );

      $this->repo_administradores->cerrarConexion();
      return json_encode([
        "mensaje" => "Se agrego un jefe correctamente.",
        "id" => $id_nuevo_jefe
      ]);
    }

    if (
      $rol == "Comprador"
    ) {
      $id_nuevo_comprador = $this->repo_administradores->createComprador(
        $nuevo_administrador
      );

      $this->repo_administradores->cerrarConexion();
      return json_encode([
        "mensaje" => "Se agrego un comprador correctamente.",
        "id" => $id_nuevo_comprador
      ]);
    }

    if (
      $rol == "Vendedor"
    ) {
      $id_nuevo_vendedor = $this->repo_administradores->createVendedor(
        $nuevo_administrador
      );

      $this->repo_administradores->cerrarConexion();
      return json_encode([
        "mensaje" => "Se agrego un vendedor correctamente.",
        "id" => $id_nuevo_vendedor
      ]);
    }
  }

  public function put(
    array $nuevo_administrador,
    int $id_administrador
  ): string {
    $this->repo_administradores->update(
      $nuevo_administrador,
      $id_administrador
    );

    $this->repo_administradores->cerrarConexion();
    return json_encode([
      "mensaje" => "Se modifico el administrador correctamente."
    ]);
  }

  public function delete(
    int $id_administrador
  ): string {
    $this->repo_administradores->delete(
      $id_administrador
    );

    $this->repo_administradores->cerrarConexion();
    return json_encode([
      "mensaje" => "Se borro el administrador #$id_administrador correctamente."
    ]);
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
}
