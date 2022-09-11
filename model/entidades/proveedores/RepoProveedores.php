<?php

require_once __DIR__ . "/../database/Database.php";

class RepoProveedores
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
    int $id_proveedor
  ): array {
    if (!$this->existeProveedorConId($id_proveedor)) {
      $this->database->close();
      throw new Exception("No hay proveedores con esa id", 404);
    }

    // TODO: mostrar todos los telefonos de la BD.
    $query = "SELECT * FROM VIEW_PROVEEDORES_CON_TELEFONO WHERE `id`=?";

    $proveedor = $this->database->queryWithParams(
      $query,
      [$id_proveedor],
      "i"
    )[0];

    return $proveedor;
  }

  public function getAll(): array
  {
    $query = "SELECT * FROM VIEW_PROVEEDORES_CON_TELEFONO";

    $proveedores = $this->database->queryWithoutParams(
      $query
    ) ?? [];

    return $proveedores;
  }

  public function create(
    array $nuevo_proveedor
  ): int {
    $query = "INSERT INTO PROVEEDORES (`nombre`, `correo`, `calle`, `nro_puerta`) VALUES(?,?,?,?)";

    $id_proveedor = $this->database->insertRow(
      $query,
      [
        $nuevo_proveedor["nombre"],
        $nuevo_proveedor["correo"],
        $nuevo_proveedor["calle"],
        $nuevo_proveedor["nro_puerta"]
      ],
      "ssss"
    );

    $query = "INSERT INTO TELEFONOS_PROVEEDORES (`id_proveedor`, `telefono`) VALUES (?,?)";

    foreach ($nuevo_proveedor["telefonos"] as $telefono_nuevo_proveedor) {
      $this->database->insertRow(
        $query,
        [
          $id_proveedor,
          $telefono_nuevo_proveedor
        ],
        "ss"
      );
    }

    return $id_proveedor;
  }

  public function update(
    array $nuevo_proveedor,
    int $id_provedor
  ) {
    if (!$this->existeProveedorConId($id_provedor)) {
      $this->database->close();
      throw new Exception("No hay proveedores con esa id", 404);
    }

    $query = "UPDATE PROVEEDORES SET `nombre`=?, `correo`=?, `calle`=?, `nro_puerta`=? WHERE `id_proveedor`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [
        $nuevo_proveedor["nombre"],
        $nuevo_proveedor["correo"],
        $nuevo_proveedor["calle"],
        $nuevo_proveedor["nro_puerta"],
        $id_provedor
      ],
      "ssssi"
    );
  }

  public function delete(
    int $id_proveedor
  ): void {
    if (!$this->existeProveedorConId($id_proveedor)) {
      throw new Exception("No hay proveedores con esa id", 404);
    }

    $query = "DELETE FROM PROVEEDORES WHERE `id_proveedor`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_proveedor],
      "i"
    );
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // OPERACIONES CRUD
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function existeProveedorConId(
    int $id_proveedor
  ): bool {
    $query = "SELECT count(*) `existe` FROM PROVEEDORES WHERE `id_proveedor`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$id_proveedor],
      "i"
    )[0]["existe"];

    return $existe > 0;
  }

  public function cerrarConexion(): void
  {
    $this->database->close();
  }
}
