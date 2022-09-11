<?php

require_once __DIR__ . "/../database/Database.php";
require_once __DIR__ . "/../productos/RepoProductos.php";

class RepoPaquetes
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
    int $id_paquete
  ): array {
    if (!$this->existePaqueteConId($id_paquete)) {
      $this->database->close();
      throw new Exception("No hay paquetes con esa id.", 404);
    }

    $query = "SELECT `id_paquete` `id`, `nombre`, `precio`, `descuento`, `stock`, `descripcion` FROM PAQUETES WHERE `id_paquete`=?";

    $paquete = $this->database->queryWithParams(
      $query,
      [$id_paquete],
      "i"
    )[0];

    $query = "SELECT * FROM TIENE_2 WHERE `id_paquete`=?";

    $aux_productos_paquete = $this->database->queryWithParams(
      $query,
      [$id_paquete],
      "i"
    );

    $productos_paquete = [];

    $repo_productos = new RepoProductos();
    foreach ($aux_productos_paquete as $aux_producto_paquete) {
      $producto_paquete = $repo_productos->getById(
        $aux_producto_paquete["id_producto"]
      );
      $productos_paquete[] = $producto_paquete;
    }
    $repo_productos->cerrarConexion();

    $paquete["productos"] = $productos_paquete;

    return $paquete;
  }

  public function getAll(): array
  {
    $query = "SELECT * FROM VIEW_PAQUETES_CON_IMAGEN";

    $paquetes = $this->database->queryWithoutParams(
      $query
    ) ?? [];

    return $paquetes;
  }

  public function create(
    array $nuevo_paquete
  ): int {
    $repo_productos = new RepoProductos();
    foreach ($nuevo_paquete["productos"] as $id_producto_nuevo_paquete) {
      if (!$repo_productos->existeProductoConId(
        $id_producto_nuevo_paquete
      )) {
        $repo_productos->cerrarConexion();
        $this->database->close();
        throw new Exception("No hay productos con esa id.", 404);
      }
    }
    $repo_productos->cerrarConexion();

    $query = "INSERT INTO PAQUETES (`nombre`, `precio`, `descuento`, `stock`, `descripcion`) VALUES (?,?,?,?,?)";

    $id_nuevo_paquete = $this->database->insertRow(
      $query,
      [
        $nuevo_paquete["nombre"],
        $nuevo_paquete["precio"],
        $nuevo_paquete["descuento"],
        $nuevo_paquete["stock"],
        $nuevo_paquete["descripcion"],
      ],
      "siiis"
    );

    $query = "SELECT `id_proveedor` FROM VIEW_PRODUCTOS_INFO_PROVEEDORES WHERE `id`=?";

    $id_proveedores_nuevo_paquete = [];

    foreach ($nuevo_paquete["productos"] as $id_producto_nuevo_paquete) {
      $id_proveedores_nuevo_paquete[] = $this->database->queryWithParams(
        $query,
        [$id_producto_nuevo_paquete],
        "i"
      )[0]["id_proveedor"];
    }

    $query = "INSERT INTO TIENE_2 (id_paquete, id_producto, id_proveedor) VALUES (?,?,?)";

    for ($i = 0; $i < count($nuevo_paquete["productos"]); $i++) {
      $id_producto_nuevo_paquete = $nuevo_paquete["productos"][$i];
      $id_proveedor_nuevo_paquete = $id_proveedores_nuevo_paquete[$i];

      $this->database->insertRow(
        $query,
        [
          $id_nuevo_paquete,
          $id_producto_nuevo_paquete,
          $id_proveedor_nuevo_paquete
        ],
        "iii"
      );
    }

    return $id_nuevo_paquete;
  }

  public function update(
    array $nuevo_paquete,
    int $id_paquete
  ): void {
    if (!$this->existePaqueteConId($id_paquete)) {
      $this->database->close();
      throw new Exception("No hay paquetes con esa id.", 404);
    }

    $repo_productos = new RepoProductos();
    foreach ($nuevo_paquete["productos"] as $id_producto_nuevo_paquete) {
      if (!$repo_productos->existeProductoConId(
        $id_producto_nuevo_paquete
      )) {
        $repo_productos->cerrarConexion();
        $this->database->close();
        throw new Exception("No hay productos con esa id.", 404);
      }
    }
    $repo_productos->cerrarConexion();

    $query = "UPDATE PAQUETES SET `nombre`=?, `precio`=?, `descuento`=?, `stock`=?, `descripcion`=? WHERE `id_paquete`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [
        $nuevo_paquete["nombre"],
        $nuevo_paquete["precio"],
        $nuevo_paquete["descuento"],
        $nuevo_paquete["stock"],
        $nuevo_paquete["descripcion"],
        $id_paquete,
      ],
      "siiisi"
    );

    $query = "SELECT `id_proveedor` FROM VIEW_PRODUCTOS_INFO_PROVEEDORES WHERE `id`=?";

    $id_proveedores_nuevo_paquete = [];

    foreach ($nuevo_paquete["productos"] as $id_producto_nuevo_paquete) {
      $id_proveedores_nuevo_paquete[] = $this->database->queryWithParams(
        $query,
        [$id_producto_nuevo_paquete],
        "i"
      )[0]["id_proveedor"];
    }

    $query = "DELETE FROM PAQUETES WHERE `id_paquete`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_paquete],
      "i"
    );

    $query = "INSERT INTO TIENE_2 (id_paquete, id_producto, id_proveedor) VALUES (?,?,?)";

    for ($i = 0; $i < count($nuevo_paquete["productos"]); $i++) {
      $id_producto_nuevo_paquete = $nuevo_paquete["productos"][$i];
      $id_proveedor_nuevo_paquete = $id_proveedores_nuevo_paquete[$i];

      $this->database->insertRow(
        $query,
        [
          $id_paquete,
          $id_producto_nuevo_paquete,
          $id_proveedor_nuevo_paquete
        ],
        "iii"
      );
    }
  }

  public function delete(
    int $id_paquete
  ): void {
    if (!$this->existePaqueteConId($id_paquete)) {
      $this->database->close();
      throw new Exception("No hay paquetes con esa id.", 404);
    }

    $query = "DELETE FROM PAQUETES WHERE `id_paquete`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_paquete],
      "i"
    );
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // OPERACIONES CRUD
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function existePaqueteConId(
    int $id_paquete
  ): bool {
    $query = "SELECT count(*) `existe` FROM PAQUETES WHERE `id_paquete`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$id_paquete],
      "i"
    )[0]["existe"];

    return $existe;
  }

  public function cerrarConexion(): void
  {
    $this->database->close();
  }
}
