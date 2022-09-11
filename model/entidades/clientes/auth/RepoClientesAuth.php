<?php

require_once __DIR__ . "/../../database/Database.php";
require_once __DIR__ . "/../no-auth/RepoClientesNoAuth.php";

class RepoClientesAuth
{
  private Database $database;

  public function __construct()
  {
    $this->database = new Database();
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // OPERACIONES CRUD
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function getById(
    int $id_cliente_auth
  ): array {
    if (!$this->existeClienteAConId($id_cliente_auth)) {
      $this->database->close();
      throw new Exception("No hay clientes con esa id.", 404);
    }

    $query = "SELECT * FROM VIEW_CLIENTES WHERE `id`=?";

    $cliente_auth = $this->database->queryWithParams(
      $query,
      [$id_cliente_auth],
      "i"
    )[0];

    $query = "SELECT `celular` FROM CELULARES_USUARIOS WHERE `id_usuario`=?";

    $aux_celulares_cliente_auth = $this->database->queryWithParams(
      $query,
      [$id_cliente_auth],
      "i"
    );

    $celulares_cliente_auth = [];

    foreach ($aux_celulares_cliente_auth as $celular_cliente_auth) {
      $celulares_cliente_auth[] = $celular_cliente_auth["celular"];
    }

    return [
      "id" => $cliente_auth["id"],
      "ci" => $cliente_auth["ci"],
      "nombre_1" => $cliente_auth["nombre_1"],
      "nombre_2" => $cliente_auth["nombre_2"],
      "apellido_1" => $cliente_auth["apellido_1"],
      "apellido_2" => $cliente_auth["apellido_2"],
      "correo" => $cliente_auth["correo"],
      "celulares" => $celulares_cliente_auth
    ];
  }

  public function getAll(): array
  {
    $query = "SELECT * FROM VIEW_CLIENTES";

    $aux_clientes_auth = $this->database->queryWithoutParams(
      $query
    ) ?? [];

    $clientes_auth = [];

    // TODO: cambiar la consulta en vez de hacer esto
    foreach ($aux_clientes_auth as $cliente_auth) {
      unset($cliente_auth["rol"]);
      $clientes_auth[] = $cliente_auth;
    }

    return $clientes_auth;
  }

  public function create(
    int $id_cliente_no_auth
  ): int {
    $repo_clientes_no_auth = new RepoClientesNoAuth();
    $cliente_no_auth = $repo_clientes_no_auth->getById(
      $id_cliente_no_auth
    );

    // TODO: borrar el usuario al ingresarlo en los autenticados
    $repo_clientes_no_auth->cerrarConexion();

    $query = "INSERT INTO USUARIOS (`ci`, `nombre_1`, `nombre_2`, `apellido_1`, `apellido_2`, `correo`, `contra`) VALUES (?,?,?,?,?,?,?)";

    $id_cliente_auth = $this->database->insertRow(
      $query,
      [
        $cliente_no_auth["ci"],
        $cliente_no_auth["nombre_1"],
        $cliente_no_auth["nombre_2"],
        $cliente_no_auth["apellido_1"],
        $cliente_no_auth["apellido_2"],
        $cliente_no_auth["correo"],
        $cliente_no_auth["contra"]
      ],
      "sssssss"
    );

    $query = "INSERT INTO CELULARES_USUARIOS (`id_usuario`, `celular`) VALUES (?,?)";

    foreach ($cliente_no_auth["celulares"] as $celular_cliente_no_auth) {
      $this->database->insertRow(
        $query,
        [
          $id_cliente_auth,
          $celular_cliente_no_auth
        ],
        "is"
      );
    }

    $query = "INSERT INTO CLIENTES (id_cliente) VALUES (?)";

    $this->database->insertRow(
      $query,
      [$id_cliente_auth],
      "i"
    );

    return $id_cliente_auth;
  }

  public function delete(
    int $id_cliente_auth
  ): void {
    if (!$this->existeClienteAConId($id_cliente_auth)) {
      $this->database->close();
      throw new Exception("No hay clientes con esa id.", 404);
    }

    $query = "DELETE FROM USUARIOS WHERE `id_usuario`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_cliente_auth],
      "i"
    );
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // OPERACIONES CRUD
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function existeClienteAConId(
    int $id_cliente_auth
  ): bool {
    $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `id_usuario`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$id_cliente_auth],
      "i"
    )[0]["existe"];

    return $existe > 0;
  }

  public function existeClienteAConCi(
    string $ci_cliente_auth
  ): bool {
    $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `ci`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$ci_cliente_auth],
      "s"
    )[0]["existe"];

    return $existe > 0;
  }

  public function cerrarConexion(): void
  {
    $this->database->close();
  }
}
