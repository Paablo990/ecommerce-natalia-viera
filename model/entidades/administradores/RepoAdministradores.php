<?php

require_once __DIR__ . "/../database/Database.php";

class RepoAdministradores
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
    int $id_administrador
  ): array {
    if (!$this->existeAdministradorConId($id_administrador)) {
      $this->database->close();
      throw new Exception("No hay administradores con esa id.", 404);
    }

    $rol = $this->rolAdministradorConId($id_administrador);

    if ($rol == "Cliente") {
      $this->database->close();
      throw new Exception("No hay administradores con esa id.", 404);
    }

    $query = "SELECT * FROM VIEW_USUARIOS_CON_CELULAR WHERE `id`=?";

    $usuario = $this->database->queryWithParams(
      $query,
      [$id_administrador],
      "i"
    )[0];

    $usuario = array_merge(["rol" => $rol], $usuario);
    $usuario["celulares"] = [$usuario["celulares"]];

    return $usuario;
  }

  public function getAll(): array
  {
    $querys = [
      "SELECT * FROM VIEW_JEFES",
      "SELECT * FROM VIEW_COMPRADORES",
      "SELECT * FROM VIEW_VENDEDORES"
    ];

    $administradores = [];

    foreach ($querys as $query) {
      $aux_administradores[] = $this->database->queryWithoutParams(
        $query
      )[0];

      $administradores = array_merge($administradores, $aux_administradores);
    }

    return $administradores;
  }

  private function createUsuario(
    array $nuevo_usuario
  ): int {
    if ($this->existeAdministradorConCi($nuevo_usuario["ci"])) {
      $this->database->close();
      throw new Exception("Ya hay un administrador con esa cedula.", 409);
    }

    $query = "INSERT INTO USUARIOS (`ci`, `nombre_1`, `nombre_2`, `apellido_1`, `apellido_2`, `correo`, `contra`) VALUES (?,?,?,?,?,?,?)";

    $id_nuevo_usuario = $this->database->insertRow(
      $query,
      [
        $nuevo_usuario["ci"],
        $nuevo_usuario["nombre_1"],
        $nuevo_usuario["nombre_2"],
        $nuevo_usuario["apellido_1"],
        $nuevo_usuario["apellido_2"],
        $nuevo_usuario["correo"],
        $nuevo_usuario["contra"]
      ],
      "sssssss"
    );

    $query = "INSERT INTO CELULARES_USUARIOS (`id_usuario`, `celular`) VALUES (?,?)";

    foreach ($nuevo_usuario["celulares"] as $celular_nuevo_usuario) {
      $this->database->insertRow(
        $query,
        [
          $id_nuevo_usuario,
          $celular_nuevo_usuario
        ],
        "is"
      );
    }

    return $id_nuevo_usuario;
  }

  public function createJefe(
    array $nuevo_jefe
  ): int {
    $id_nuevo_jefe = $this->createUsuario($nuevo_jefe);

    $query = "INSERT INTO JEFES (`id_jefe`) VALUES (?)";

    $this->database->insertRow(
      $query,
      [$id_nuevo_jefe],
      "i"
    );

    return $id_nuevo_jefe;
  }

  public function createComprador(
    array $nuevo_comprador
  ): int {
    $id_nuevo_comprador = $this->createUsuario($nuevo_comprador);

    $query = "INSERT INTO COMPRADORES (`id_comprador`) VALUES (?)";

    $this->database->insertRow(
      $query,
      [$id_nuevo_comprador],
      "i"
    );

    return $id_nuevo_comprador;
  }

  public function createVendedor(
    array $nuevo_vendedor
  ): int {
    $id_nuevo_vendedor = $this->createUsuario($nuevo_vendedor);

    $query = "INSERT INTO VENDEDORES (`id_vendedor`) VALUES (?)";

    $this->database->insertRow(
      $query,
      [$id_nuevo_vendedor],
      "i"
    );

    return $id_nuevo_vendedor;
  }

  public function update(
    array $nuevo_administrador,
    int $id_administrador
  ): void {
    if (!$this->existeAdministradorConId($id_administrador)) {
      $this->database->close();
      throw new Exception("No hay administradores con ese id.", 404);
    }

    if (!$this->existeAdministradorConCi($nuevo_administrador["ci"])) {
      $this->database->close();
      throw new Exception("Ya hay administradores con esa ci", 409);
    }

    $query = "UPDATE USUARIOS SET `ci`=?, `nombre_1`=?, `nombre_2`=?, `apellido_1`=?, `apellido_2`=?, `correo`=?, `contra`=? WHERE `id_usuario`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [
        $nuevo_administrador["ci"],
        $nuevo_administrador["nombre_1"],
        $nuevo_administrador["nombre_2"],
        $nuevo_administrador["apellido_1"],
        $nuevo_administrador["apellido_2"],
        $nuevo_administrador["correo"],
        $nuevo_administrador["contra"],
        $id_administrador
      ],
      "sssssssi"
    );

    $query = "DELETE FROM CELULARES_USUARIOS WHERE `id_usuario`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_administrador],
      "i"
    );

    $query = "INSERT INTO CELULARES_USUARIOS (`id_usuario`, `celular`) VALUES (?,?)";

    foreach ($nuevo_administrador["celulares"] as $celular_nuevo_administrador) {
      $this->database->insertRow(
        $query,
        [
          $id_administrador,
          $celular_nuevo_administrador
        ],
        "is"
      );
    }
  }

  public function delete(
    int $id_administrador
  ): void {
    if (!$this->existeAdministradorConId($id_administrador)) {
      $this->database->close();
      throw new Exception("No hay administradores con ese id.", 404);
    }

    $query = "DELETE FROM USUARIOS WHERE `id_usuario`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_administrador],
      "i"
    );
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // OPERACIONES CRUD
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@  

  public function rolAdministradorConId(
    $id_administrador
  ): string {
    if (!$this->existeAdministradorConId($id_administrador)) {
      $this->database->close();
      throw new Exception("No hay administradores con esa id.", 404);
    }

    $querys = [
      "SELECT count(*) `existe` FROM JEFES WHERE `id_jefe`=?"
      => "Jefe",
      "SELECT count(*) `existe` FROM COMPRADORES WHERE `id_comprador`=?"
      => "Comprador",
      "SELECT count(*) `existe` FROM VENDEDORES WHERE `id_vendedor`=?"
      => "Vendedor"
    ];

    foreach ($querys as $query => $rol) {
      $existe = $this->database->queryWithParams(
        $query,
        [$id_administrador],
        "i"
      )[0]["existe"];

      if ($existe > 0) return $rol;
    }

    return "Cliente";
  }

  public function existeAdministradorConCi(
    string $ci_administrador
  ): bool {
    $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `ci`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$ci_administrador],
      "i"
    )[0]["existe"];

    return $existe > 0;
  }

  public function existeAdministradorConId(
    int $id_administrador
  ): bool {
    $query = "SELECT count(*) `existe` FROM USUARIOS WHERE `id_usuario`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$id_administrador],
      "i"
    )[0]["existe"];

    return $existe > 0;
  }

  public function cerrarConexion(): void
  {
    $this->database->close();
  }
}
