<?php

require_once __DIR__ . "/../database/Database.php";
require_once __DIR__ . "/../proveedores/RepoProveedores.php";

class RepoProductos
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
    int $id_producto
  ): array {
    if (!$this->existeProductoConId($id_producto)) {
      $this->database->close();
      throw new Exception("No hay productos con esa id.", 404);
    }

    $query = "SELECT * FROM VIEW_PRODUCTOS_INFO_PROVEEDORES WHERE `id`=?";

    $producto = $this->database->queryWithParams(
      $query,
      [$id_producto],
      "i"
    )[0];

    $query = "SELECT * FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

    $aux_imagenes_producto = $this->database->queryWithParams(
      $query,
      [$id_producto],
      "i"
    );

    $imagenes_producto = [];

    foreach ($aux_imagenes_producto as $imagen_producto) {
      $imagenes_producto[] = $imagen_producto["ruta_imagen"];
    }

    $producto["imagenes"] = $imagenes_producto;

    return [
      "id" => $id_producto,
      "nombre" => $producto["nombre"],
      "precio" => $producto["precio"],
      "descuento" => $producto["descuento"],
      "stock" => $producto["stock"],
      "descripcion" => $producto["descripcion"],
      "imagenes" => $imagenes_producto,
      "proveedor" => [
        "id_proveedor" => $producto["id_proveedor"],
        "nombre_proveedor" => $producto["nombre_proveedor"],
        "correo" => $producto["correo"],
        "calle" => $producto["calle"],
        "nro_puerta" => $producto["nro_puerta"],
      ]
    ];
  }

  public function getAll(): array
  {
    $query = "SELECT * FROM VIEW_PRODUCTOS_CON_IMAGEN";

    $productos = $this->database->queryWithoutParams(
      $query
    ) ?? [];

    return $productos;
  }

  public function create(
    array $nuevo_producto
  ): int {
    $repo_proveedores = new RepoProveedores();

    if (!$repo_proveedores->existeProveedorConId(
      $nuevo_producto["id_proveedor"]
    )) {
      $repo_proveedores->cerrarConexion();
      $this->database->close();
      throw new Exception("No hay proveedores con esa id", 404);
    }

    $repo_proveedores->cerrarConexion();

    $query = "INSERT INTO PRODUCTOS (`nombre`, `precio`, `descuento`, `stock`, `descripcion`) VALUES (?,?,?,?,?)";

    $id_nuevo_producto = $this->database->insertRow(
      $query,
      [
        $nuevo_producto["nombre"],
        $nuevo_producto["precio"],
        $nuevo_producto["descuento"],
        $nuevo_producto["stock"],
        $nuevo_producto["descripcion"]
      ],
      "siiis"
    );

    $query = "INSERT INTO IMAGENES_PRODUCTOS (`id_producto`, `ruta_imagen`) VALUES (?,?)";

    foreach ($nuevo_producto["imagenes"] as $imagen_nuevo_producto) {
      $this->database->insertRow(
        $query,
        [
          $id_nuevo_producto,
          $imagen_nuevo_producto
        ],
        "is"
      );
    }

    $query = "INSERT INTO TIENE_1 (`id_producto`, `id_proveedor`) VALUES (?,?)";

    $this->database->queryWithParams(
      $query,
      [
        $id_nuevo_producto,
        $nuevo_producto["id_proveedor"]
      ],
      "ii"
    );

    return $id_nuevo_producto;
  }

  public function update(
    array $nuevo_producto,
    int $id_producto
  ): void {
    if (!$this->existeProductoConId($id_producto)) {
      $this->database->close();
      throw new Exception("No hay productos con esa id", 404);
    }

    $repo_proveedores = new RepoProveedores();

    if (!$repo_proveedores->existeProveedorConId(
      $nuevo_producto["id_proveedor"]
    )) {
      $repo_proveedores->cerrarConexion();
      $this->database->close();
      throw new Exception("No hay proveedores con esa id", 404);
    }

    $repo_proveedores->cerrarConexion();

    $query = "UPDATE PRODUCTOS SET `nombre`=?, `precio`=?, `descuento`=?, `stock`=?, `descripcion`=? WHERE `id_producto`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [
        $nuevo_producto["nombre"],
        $nuevo_producto["precio"],
        $nuevo_producto["descuento"],
        $nuevo_producto["stock"],
        $nuevo_producto["descripcion"],
        $id_producto
      ],
      "siiisi"
    );

    $query = "DELETE FROM IMAGENES_PRODUCTOS WHERE `id_producto`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_producto],
      "i"
    );

    $query = "INSERT INTO IMAGENES_PRODUCTOS (`id_producto`, `ruta_imagen`) VALUES (?,?)";

    foreach ($nuevo_producto["imagenes"] as $imagen_nuevo_producto) {
      $this->database->insertRow(
        $query,
        [
          $id_producto,
          $imagen_nuevo_producto
        ],
        "is"
      );
    }

    $query = "UPDATE TIENE_1 SET `id_proveedor`=? WHERE `id_producto`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [
        $nuevo_producto["id_proveedor"],
        $id_producto
      ],
      "ii"
    );
  }

  public function delete(
    int $id_producto
  ): void {
    if (!$this->existeProductoConId($id_producto)) {
      $this->database->close();
      throw new Exception("No hay productos con esa id.", 404);
    }

    $query = "DELETE FROM PRODUCTOS WHERE `id_producto`=?";

    $this->database->updateOrDeleteRow(
      $query,
      [$id_producto],
      "i"
    );
  }

  // @@@@@@@@@@@@@@@@@@@@@@@@@@@
  // OPERACIONES CRUD
  // @@@@@@@@@@@@@@@@@@@@@@@@@@@

  public function existeProductoConId(
    int $id_producto
  ): bool {
    $query = "SELECT count(*) `existe` FROM PRODUCTOS WHERE `id_producto`=?";

    $existe = $this->database->queryWithParams(
      $query,
      [$id_producto],
      "i"
    )[0]["existe"];

    return $existe;
  }

  public function cerrarConexion(): void
  {
    $this->database->close();
  }
}
