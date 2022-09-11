<?php

require_once __DIR__ . "/../../database/Database.php";

class RepoClientesNoAuth
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
    int $id_cliente_no_auth
  ): array {
    if (!$this->existeClienteNaConId($id_cliente_no_auth)) {
      $this->database->close();
      throw new Exception("No hay clientes con esa id.", 404);
    }

    $query = "SELECT * FROM USUARIOS_NA WHERE `id_usuario`=?";

    $cliente_no_auth = $this->database->queryWithParams(
      $query,
      [$id_cliente_no_auth],
      "i"
    )[0];

    $query = "SELECT `celular` FROM CELULARES_USUARIOS_NA WHERE `id_usuario`=?";

    $aux_celulares_cliente_no_auth = $this->database->queryWithParams(
      $query,
      [$id_cliente_no_auth],
      "i"
    );

    $celulares_cliente_no_auth = [];

    foreach ($aux_celulares_cliente_no_auth as $celular_cliente_no_auth) {
      $celulares_cliente_no_auth[] = $celular_cliente_no_auth["celular"];
    }

    return [
      "id" => $cliente_no_auth["id_usuario"],
      "ci" => $cliente_no_auth["ci"],
      "nombre_1" => $cliente_no_auth["nombre_1"],
      "nombre_2" => $cliente_no_auth["nombre_2"],
      "apellido_1" => $cliente_no_auth["apellido_1"],
      "apellido_2" => $cliente_no_auth["apellido_2"],
      "correo" => $cliente_no_auth["correo"],
      "contra" => $cliente_no_auth["contra"],
      "celulares" => $celulares_cliente_no_auth
    ];
  }

  public function getAll(): array
  {
    $query = "SELECT * FROM VIEW_USUARIOS_NA_CON_CELULAR";

    $clientes_no_auth = $this->database->queryWithoutParams(
      $query
    ) ?? [];

    return $clientes_no_auth;
  }

  public function create(
    array $nuevo_cliente_no_auth
  ): int {
    if ($this->existeClienteNaConCi($nuevo_cliente_no_auth["ci"])) {
      $this->database->close();
      throw new Exception("Ya hay un cliente con esa cedula.", 409);
    }

    $query = "INSERT INTO USUARIOS_NA (`ci`, `nombre_1`, `nombre_2`, `apellido_1`, `apellido_2`, `correo`, `contra`) VALUES (?,?,?,?,?,?,?)";

    $id_cliente_no_auth = $this->database->insertRow(
      $query,
      [
        $nuevo_cliente_no_auth["ci"],
        $nuevo_cliente_no_auth["nombre_1"],
        $nuevo_cliente_no_auth["nombre_2"],
        $nuevo_cliente_no_auth["apellido_1"],
        $nuevo_cliente_no_auth["apellido_2"],
        $nuevo_cliente_no_auth["correo"],
        $nuevo_cliente_no_auth["contra"]
      ],
      "sssssss"
    );

    foreach ($nuevo_cliente_no_auth["celulares"] as $celular_cliente_no_auth) {
      $query = "INSERT INTO CELULARES_USUARIOS_NA (`id_usuario`, `celular`) VALUES (?,?)";

      $this->database->insertRow(
        $query,
        [
          $id_cliente_no_auth,
          $celular_cliente_no_auth
        ],
        "is"
      );
    }

    return $id_cliente_no_auth;
  }

  public function delete(
    int $id_cliente_no_auth
  ): void {
    if (!$this->existeClienteNaConId($id_cliente_no_auth)) {
      $this->database->close();
      throw new Exception("No hay clientes con esa id.", 404);
    }

    $query = "DELETE FROM USUARIOS_NA WHERE `id_usuario`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_cliente_no_auth],
      "i"
    );
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // OPERACIONES CRUD
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function existeClienteNaConId(
    int $id_cliente_no_auth
  ): bool {
    $query = "SELECT count(*) `existe` FROM USUARIOS_NA WHERE `id_usuario`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$id_cliente_no_auth],
      "i"
    )[0]["existe"];

    return $existe > 0;
  }

  public function existeClienteNaConCi(
    string $ci_cliente_no_auth
  ): bool {
    $query = "SELECT count(*) `existe` FROM USUARIOS_NA WHERE `ci`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$ci_cliente_no_auth],
      "s"
    )[0]["existe"];

    return $existe > 0;
  }

  public function cerrarConexion(): void
  {
    $this->database->close();
  }
}
