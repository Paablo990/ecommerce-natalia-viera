<?php

require_once __DIR__ . "/RepoClientesNoAuth.php";

class ApiClientesNoAuth
{
  private RepoClientesNoAuth $repo_clientes_no_auth;

  public function __construct()
  {
    $this->repo_clientes_no_auth = new RepoClientesNoAuth();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function get(
    ?int $id_cliente_no_auth = null
  ): string {
    if ($id_cliente_no_auth != null) {
      $cliente_no_auth = $this->repo_clientes_no_auth->getById(
        $id_cliente_no_auth
      );

      $this->repo_clientes_no_auth->cerrarConexion();
      return json_encode([
        "mensaje" => "Se encontraron clientes.",
        "cliente" => $cliente_no_auth
      ]);
    }

    $clientes_no_auth = $this->repo_clientes_no_auth->getAll();

    return json_encode([
      "mensaje" => "Se encontraron clientes.",
      "clientes" => $clientes_no_auth
    ]);
  }

  public function post(
    array $cliente_no_auth
  ): string {
    $id_nuevo_cliente_no_auth = $this->repo_clientes_no_auth->create(
      $cliente_no_auth
    );

    return json_encode([
      "mensaje" => "Se agrego un cliente correctamente.",
      "id" => $id_nuevo_cliente_no_auth
    ]);
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // VERBOS
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
}
