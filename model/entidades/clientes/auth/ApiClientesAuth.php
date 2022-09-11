<?php

require_once __DIR__ . "/RepoClientesAuth.php";

class ApiClientesAuth
{
  private RepoClientesAuth $repo_clientes_auth;

  public function __construct()
  {
    $this->repo_clientes_auth = new RepoClientesAuth();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function get(
    ?int $id_cliente_auth = null
  ): string {
    if ($id_cliente_auth != null) {
      $cliente_auth = $this->repo_clientes_auth->getById(
        $id_cliente_auth
      );

      $this->repo_clientes_auth->cerrarConexion();
      return json_encode([
        "mensaje" => "Se encontraron clientes.",
        "cliente" => $cliente_auth
      ]);
    }

    $clientes_auth = $this->repo_clientes_auth->getAll();

    $this->repo_clientes_auth->cerrarConexion();
    return json_encode([
      "mensaje" => "Se encontraron clientes.",
      "clientes" => $clientes_auth
    ]);
  }

  public function post(
    int $id_cliente_no_auth
  ): string {
    $id_nuevo_cliente_auth = $this->repo_clientes_auth->create(
      $id_cliente_no_auth
    );

    $this->repo_clientes_auth->cerrarConexion();
    return json_encode([
      "mensaje" => "Se agrego un cliente correctamente.",
      "id" => $id_nuevo_cliente_auth
    ]);
  }

  public function delete(
    int $id_cliente_auth
  ): string {
    $this->repo_clientes_auth->delete(
      $id_cliente_auth
    );

    $this->repo_clientes_auth->cerrarConexion();
    return json_encode([
      "mensaje" => "Se borro el cliente #$id_cliente_auth correctamente.",
    ]);
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
}
