<?php

require_once __DIR__ . "/RepoPaquetes.php";

class ApiPaquetes
{
  private RepoPaquetes $repo_paquetes;

  public function __construct()
  {
    $this->repo_paquetes = new RepoPaquetes();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@  

  public function get(
    ?int $id_paquete = null
  ): string {
    if ($id_paquete != null) {
      $paquete = $this->repo_paquetes->getById(
        $id_paquete
      );

      $this->repo_paquetes->cerrarConexion();
      return json_encode([
        "mensaje" => "Se encontraron paquetes.",
        "paquete" => $paquete
      ]);
    }

    $paquetes = $this->repo_paquetes->getAll();

    $this->repo_paquetes->cerrarConexion();
    return json_encode([
      "mensaje" => "Se encontraron paquetes.",
      "paquetes" => $paquetes
    ]);
  }

  public function post(
    array $nuevo_paquete
  ): string {
    $id_nuevo_paquete = $this->repo_paquetes->create(
      $nuevo_paquete
    );

    $this->repo_paquetes->cerrarConexion();
    return json_encode([
      "mensaje" => "Se agrego un paquete correctamente.",
      "id" => $id_nuevo_paquete
    ]);
  }

  public function put(
    array $nuevo_paquete,
    int $id_paquete
  ): string {
    $this->repo_paquetes->update(
      $nuevo_paquete,
      $id_paquete
    );

    $this->repo_paquetes->cerrarConexion();
    return json_encode([
      "mensaje" => "Se modifico un paquete correctamente.",
    ]);
  }

  public function delete(
    int $id_paquete
  ): string {
    $this->repo_paquetes->delete(
      $id_paquete
    );

    $this->repo_paquetes->cerrarConexion();
    return json_encode([
      "mensaje" => "Se borro el paquete #$id_paquete correctamente.",
    ]);
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
}
